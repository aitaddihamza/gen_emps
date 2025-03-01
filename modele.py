import math
import random
import time
from constants import *
from data import *
from collections import defaultdict
import copy


"""
Description: 
    Modèle pour la génération des emplois de temps pour les étudiants.
""" 


# les variables globales:
# on a besoin d'un variable globale qui va contenir les contraintes pour éviter les conflits entre les emplois de temps des classes 

# Exemple:
CONTRAINTES = {
    "salles_reserves": [],
    "jour_de_sport": defaultdict(int),
    "profs_max_seances": {},
    "non_disponibilites_profs": {}
}

for p, d in PROFESSEURS.items():
    if d["type"] == "permanent":
        CONTRAINTES["profs_max_seances"][p] = math.floor(d["max_heures"] / 2) 
    else:
        CONTRAINTES["profs_max_seances"][p] = 1


# la structure des emplois pour l'instant
emplois = {classe: { jour: {creneau: None for creneau in CRENEAUX } for jour in JOURS } for classe in CLASSES.keys() }


# FONCTIONNES 

def get_cours_infos(cours):
    volume = cours["volume"]
    tp_seances = cours["tp_seances"]
    # Calcul du nombre total de séances pour le module
    seances = math.ceil(volume / 2)
    seance_par_semaine = math.ceil(seances / TOTAL_SEMAINES)
    semaines = math.ceil(seances / seance_par_semaine)
    
    return seances, semaines, seance_par_semaine, tp_seances


def get_tp_infos(cours): 
    seances, semaines, seance_par_semaine, tp_seances = get_cours_infos(cours)
    semaine_debut = math.floor(semaines / 2)
    seance_par_semaine = math.ceil( tp_seances / semaine_debut)
    semaines = tp_seances / seance_par_semaine 
    return semaine_debut, math.ceil(semaines), seance_par_semaine 

###################### Fonctionne pour intialiser les modules/matières ###############################
# comme paramètre il prend un dict qui représente les infos d'une classe
def prepare_modules(classe_info):
    # Initialisation des modules et des séances de TP
    modules = {}
    
    for module, details in classe_info.items():
        _, semaines, seance_par_semaine, tp_seances = get_cours_infos(details)
        modules[module] = seance_par_semaine
        
        # Gestion des séances de TP si elles existent
        if tp_seances > 0:
            nbr_semaines_tp = math.ceil(semaines / 2)
            modules[f"TP {module}"] = math.ceil(tp_seances / nbr_semaines_tp)
    return modules

###################### Fonctionne pour intialiser les profs ###############################
# comme paramètre il prend les modules d'une classe et qui retourne tous les profs avec les modules qui correspond de celles de la classe.
# Note: prof est un prof permanent ou vacataire ou même un doctorant!
def prepare_profs(classe_modules):
    profs = {}
    for p, details in PROFESSEURS.items():
        prof_type = details["type"]
        prof_info = dict()
        prof_info["modules"] = []
        prof_info["type"] = prof_type
        for m in details["modules"]:
            tp = "TP " + m
            if m in classe_modules:
                prof_info["modules"].append(m)
            if details["type"] == "doctorant":
                if tp in classe_modules:
                    prof_info["modules"].append(tp)

        if len(prof_info["modules"]) > 0:
            if prof_type == "doctorant" or prof_type == "permanent":
                prof_info["disponibilites"] = JOURS
            
            if prof_type == "doctorant" or prof_type == "vacataire":
                prof_info["count"] = 1
                if prof_type == "vacataire":
                    prof_info["disponibilites"] = details["disponibilites"]

            if prof_type == "permanent":
                prof_info["count"] = math.floor(details["max_heures"] / 2) 
            profs[p] =  prof_info
    return profs 


###################### Fonctionne planifier le jour de sport ###############################
def trouver_jour_et_prof_de_sport(profs):
    jour_de_sport = profs_vacataires = None

    # le choix du jour de sport
    while jour_de_sport is None or profs_vacataires is None or len(profs_vacataires) > 1 or CONTRAINTES['jour_de_sport'][jour_de_sport] > 1:
        jour_de_sport = random.choice(JOURS)
        profs_vacataires = [p for p in profs.values() if jour_de_sport in p["disponibilites"] and p["type"] == "vacataire"]

    # le choit du prof du sport
    # Note: on suppose que les profs du sports sont des profs permanents, sinon il faut changer le comportement de ceette fonctionne.
    profs_de_sport = [p for p, d in profs.items() if "ESP" in d["modules"]]
    prof_de_sport = random.choice(profs_de_sport)
    return jour_de_sport, prof_de_sport 

###################### Fonctionne pour éliminer le sport après avoir le planifier ###############################
def eliminer_sport(profs, classe_modules):
    del classe_modules["ESP"]
    profs_de_sport = [p for p, d in profs.items() if "ESP" in d["modules"]]
    for prof in profs_de_sport:
        del profs[prof]

###################### Fonctionne pour réserver une salle ###############################
def reserver_salle():
    # les salles disponibles 
    salles_disponibles = [ salle for salle in range(NBR_SALLES) if salle not in CONTRAINTES["salles_reserves"]]
    salle = random.choice(salles_disponibles)
    CONTRAINTES["salles_reserves"].append(salle)
    return salle

##################### Fonctionne pour générer un individu (emploi du temps) pour une classe ############
# il retourne une tuple (individu, salle, modules) pour l'instant.
def generer_individu(classe_info):
    # Initialisation de l'individu (emploi du temps)
    individu = {jour: {creneau: None for creneau in CRENEAUX } for jour in JOURS}
    
    # prepaer les cours et les tps
    # modules = dict => exemple: {"Robotiques médicales": 2, "Math": 1}, 2 et 1 représente le nombre de séances par semaine
    modules = prepare_modules(classe_info)

    # TOTAL SÉANCES DE MODULES (modules["Français"] => count => nombre des séancs par semaine) 
    total_seances_modules = sum(modules.values())
    # print(total_seances_modules)
        
    profs = prepare_profs(modules)

    jour_de_sport, prof_de_sport = trouver_jour_et_prof_de_sport(profs)
    CONTRAINTES["jour_de_sport"][jour_de_sport] += 1
    # réserver le sport
    individu[jour_de_sport]["13:30-15:30"] = {
        "prof": prof_de_sport,
        "module": "ESP" 
    }
    individu[jour_de_sport]["15:40-17:30"] = {
        "prof": prof_de_sport,
        "module": "ESP" 
    }
    # éliminer le sport
    eliminer_sport(profs, modules)

    for jour in JOURS:
        creeaaux_reserves = set()
        for c in CRENEAUX:
            if c in creeaaux_reserves:
                continue
            if jour == jour_de_sport and c == "13:30-15:30":
                break
            profs_disponibles = {p:d for p, d in profs.items() if p not in CONTRAINTES['non_disponibilites_profs'].get(jour, {}).get(c, [])}
            if len(profs_disponibles) > 0:
                # la priorité est de profs vacataires 
                profs_vacataires = [p for p, d in profs_disponibles.items() if jour in d["disponibilites"] and d["type"] == "vacataire"]
                # si on a des profs vacataires disponible ce jour on va les prioriser
                if len(profs_vacataires) > 0:
                    nom_prof = random.choice(profs_vacataires)
                else:
                    nom_prof = random.choice(list(profs_disponibles.keys()))
                prof = profs[nom_prof]
                attempt = 0
                while (jour not in prof["disponibilites"] or CONTRAINTES["profs_max_seances"][nom_prof] <= 0) and attempt <= 200:
                    attempt += 1
                    nom_prof = random.choice(list(profs_disponibles.keys()))
                    prof = profs[nom_prof]

                if prof["type"] == "permanent":
                    prof["count"] -= 1
                    CONTRAINTES["profs_max_seances"][nom_prof] -= 1

                CONTRAINTES["non_disponibilites_profs"].setdefault(jour, {}).setdefault(c, []).append(nom_prof)
                # choisir la matière ou bien on peut la nommer module
                nom_module = random.choice(prof["modules"])
                if modules[nom_module] >= 2:
                    if c == "08:30-10:30" or c == "13:30-15:30":
                        c_suivante = "10:40-12:30" if c == "08:30-10:30" else "15:40-17:30"
                        individu[jour][c_suivante] = {
                            "prof": nom_prof,
                            "module": nom_module
                        }
                        modules[nom_module] -= 1
                        creeaaux_reserves.add(c_suivante)

                modules[nom_module] -= 1

            else:
                nom_prof = ""
                nom_module = "Pause"
            individu[jour][c] = {
                "prof": nom_prof,
                "module": nom_module
            }
            # update profs
            deleted_profs = []
            for p in profs:
                profs[p]["modules"] = [m for m in profs[p]["modules"] if modules[m] > 0]
                if len(profs[p]["modules"]) == 0:
                    deleted_profs.append(p)

            if len(deleted_profs) > 0:
                for dp in deleted_profs:
                    del profs[dp]
                        

    return individu , modules


    # Affichage des modules et des séances de TP
    # print(modules)

# exit()
def trouver_semaine_fin(classe, module):
    if module.startswith("TP"):
        module = module[3:]
        cours_seances, cours_semaines, _, tp_seances = get_cours_infos(CLASSES[classe][module])
    elif module == "Pause":
        return 1, 14
    else:
        cours_seances, cours_semaines, _, _ = get_cours_infos(CLASSES[classe][module])
        tp_seances = 0

        
    if tp_seances > 0:
        semaine_debut, tp_semaines, tp_seance_par_semaine = get_tp_infos(CLASSES[classe][module])
        semaine_fin = semaine_debut + tp_semaines
        semaine_debut = semaine_debut + 1
    else:
        semaine_fin = cours_semaines
        semaine_debut = 1

    return  semaine_debut , semaine_fin


def afficher_individu(individu, classe_name, salle, modules):
    print(modules)
    print("********************************************************")
    print("********************************************************")
    print(f"******** Emploi de temp de 1GD - salle: salle {salle} ********")
    for jour in individu:
        print(jour+": ")
        for c in individu[jour]:
            print(end="\t")
            print(c, end=" - ")
            print(individu[jour][c]["module"], end=" - ")
            print(individu[jour][c]["prof"], end = " semaines: ")
            semaine_debut, semaine_fin = trouver_semaine_fin(classe_name, individu[jour][c]["module"])
            print(f"S{semaine_debut} - S{semaine_fin}")
        print()

# fonctionne pour l'évaluation d'emploi de temps
def evaluate(modules):
    score = 0
    for m, c in modules.items():
        if c > 0:
            score -= 1
    return score
# fonctionne fintness_score pour évaluer un individu
def fitness_score(individu):
    score = 0
    for jour in individu:
        x = ["08:30-10:30", "10:40-12:30"]
        y = ["13:30-15:30", "15:40-17:30"]

        for i in x:
            for j in y:
                if individu[jour][i]["module"] == individu[jour][j]["module"]:
                    score -= 1
    return score

salle = reserver_salle()
individu, modules = generer_individu(CLASSES["2A_GD"])
afficher_individu(individu, "2A_GD", salle, modules)
print(f"score: {evaluate(modules)}")
# pour bio
OLD_CONTRAINTES = copy.deepcopy(CONTRAINTES)
score = -1
while score < 0:
    salle = reserver_salle()
    individu, modules = generer_individu(CLASSES["2A_GB"])
    score = evaluate(modules)
    CONTRAINTES = copy.deepcopy(OLD_CONTRAINTES)
    print(CONTRAINTES["salles_reserves"])
afficher_individu(individu, "2A_GB", salle, modules)
print(f"score: {score}")


OLD_CONTRAINTES = copy.deepcopy(CONTRAINTES)
# pour 1anné génie digital
score = -1
while score < 0:
    CONTRAINTES = copy.deepcopy(OLD_CONTRAINTES)
    salle = reserver_salle()
    individu, modules = generer_individu(CLASSES["1A_GD"])
    score = evaluate(modules)
afficher_individu(individu, "1A_GD", salle, modules)
print(f"score: {score}")

exit()
# afficher 10 version d'emploi de temps de 2éme année génie digital et intelligence artificiel en santé
for v in range(TAILLE_GENERATION):
    individu, salle, modules = generer_individu(CLASSES["2A_GD"])
    afficher_individu(individu, "2A_GD", salle, modules)
    print(f"Score de la verison:{v+1} = {fitness_score(individu)}")
exit()
scores = []
generation = []
for _ in range(TAILLE_GENERATION):
    individu, salle, modules = generer_individu(CLASSES["2A_GD"])
    # afficher_individu(individu, "2A_GD", salle, modules)
    score = fitness_score(individu)
    generation.append((individu, salle, modules))
    scores.append(score)

max_score = max(scores)
individu, salle, module = generation[scores.index(max_score)]
afficher_individu(individu, "2A_GD", salle, modules)
print(f"Max score: {max_score}")
exit()


def selection(generation):
    pass

# fonctionne pour générer l'emploi de temps par classe
def generer_emploi(classe):
    # algorithme génétique = générer la pop initiale => évaluation => sélection => croisement(30%) => mutation(10%)
    # =>  remplacer l'ancienne génération par la nouvelle recommencer par l'évaluation jusqu'à la génération NBR_GENERATION - 1

    # Étape 0: générer la population/génération initiale 
    generation = [] # Génération 0 de taille TAILLE_GENERATION

    for i in range(TAILLE_GENERATION):
        emploi = generer_individu(classe)
        generation.append(emploi)

    for i in range(NBR_GENERATION):
        # Étape 1: évaluation de chaque individu via la fonctionne fitness_score(individu)
        # Étape 2: sélectionner demi génération qui on le plus haut score
        meilleurs_individus = selection(generation) 
        enfants = []
        # croisment si la prob dépasse ou égale 30%
        # mutation si la prob dépasse ou égale 10%  
        # nouvelle génération = meilleurs_individus + enfants
        # remplacer l'ancienne génération par la nouvelle
        generation = meilleurs_individus + enfants
    meilleur_emploi = sorted(generation[0], reverse=True) # qu'a le plus haut score 
    # Mettre à jour CONTRAINTES


# fonctionne pour générer les emplois pour chaque classe
for classe in CLASSES.keys():
    emplois[classe] = generer_emploi(classe)


# fonctionne pour affiche les emplois 
def afficher_emplois():
    pass
    # Exemple d'affichage d'un emploi 
    """ 
    ********************************************************
    ******** Emploi de temp de 1GD - salle: salle 5 ********
    Lundi: 
        créneau1:
            Programmation - prof A - S1-S14
            TP Programmation - Doct 2 - S7-S10
        créneau2: Programmation - prof A - S1-S14
        créneau3: Base de données - prof A - S1-S14
        créneau4: Pause
    Mardi: 
        créneau1: Statistiques - prof A - S1-S14
        créneau2: Pause
        créneau3: 
            Réseaux - prof B - S1-S14
            TP Réseaux - Doct 3 - S7-S12
        créneau4: Réseaux - prof B - S1-S14
    ...
    Vendredi:
        créneau1: Français - prof C - S1-S14
        créneau2: Biologie - prof B - S1-S14
        créneau3: Base de données - prof A - S1-S14
        créneau4: Pause
    ********************************************************
    """

    

# Problèmes: 
# Comment planifier les séances de tps ?

# les tps doivent commencer après la moité de cours théorique
# Exemple: Cloud Computing - Prof A - S1-S14  => donc le tp commence après: S7 jusqu'à le reste.
# mais il faut déterminer le nombre de séances de tps par semaine, comment ? 
# floor(14 semaines / 2) = 7 semaines, et par exemple le tp a 9 séances => 9séances / 7 semaines = floor(1,..) = 2séance par semaine => 9séances / 2séancs par semaine = floor(4,5) = 5semaines
# donc le tp de coloud Computing Computing commence de la semaine S7 jusqu'à la semaine S(7+5=12).
# Mais comment la planifier dans l'emploi sachant qu'il respecte les contraintes ? 

# comment ne pas avoir le sport dans le matin ?
# comment quelque chose comme: Réseaux 10:30-12:30 et Réseaux 13:30 - 15:30 ?
# comment ne pas déapasser le nombre de séances par semaine (20 séances) ? comment bien répartir les cours et les tps dans la semaines ?
# comment ajouter les intervalles des seamines pour sépcifier la semaine de début d'un tel module et la semaine de fin, exemple: Organisation hospitalière S2-S14 
# le sport ESP

