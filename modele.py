import math
import random
import time
from constants import *
from data import *
from collections import defaultdict
import copy


CONTRAINTES = {
    "salles_reserves": [],
    "salles_tps": {}, 
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
emplois = {classe: { jour: {creneau: [] for creneau in CRENEAUX } for jour in JOURS } for classe in CLASSES.keys() }


# FONCTIONNES 

def get_cours_infos(cours):
    volume = cours["volume"]
    tp_seances = cours["tp_seances"]
    # Calcul du nombre total de séances pour le module
    seances = math.ceil(volume / 2)
    seance_par_semaine = math.ceil(seances / TOTAL_SEMAINES)
    semaines = math.ceil(seances / seance_par_semaine)
    
    return seances, semaines, seance_par_semaine, tp_seances

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

###################### Fonctionne pour intiali# "jour": {"8:30-10-30": "salle1"}ser les profs ###############################
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
def reserver_salle_tp(jour, c):
    # les salles disponibles 
    salles_disponibles = [ salle for salle in SALLES_TP if salle not in CONTRAINTES["salles_tps"].get(jour, {}).get(c, set()) ]
    if not salles_disponibles:
        raise Exception("y a pas assez des salles de tps disponibles")
    salle = random.choice(salles_disponibles)
    CONTRAINTES["salles_tps"].setdefault(jour, {}).setdefault(c, set()).add(salle)
    return salle

def reserver_salle():
    # les salles disponibles 
    salles_disponibles = [salle for salle in range(NBR_SALLES) if salle not in CONTRAINTES["salles_reserves"]]
    if len(salles_disponibles) == 0:
        raise Exception("y a pas assez des salles disponibles")
    salle = random.choice(salles_disponibles)
    CONTRAINTES["salles_reserves"].append(salle)
    return salle

def affecter_seance(classe_name, individu, jour, c, infos, modules):
    semaine_debut, semaine_fin = trouver_semaines(classe_name, infos["nom_module"], modules)
    individu[jour][c].append({
        "prof": infos["nom_prof"],
        "salle": infos["salle"],
        "module": infos["nom_module"],
        "semaine_debut": semaine_debut,
        "semaine_fin": semaine_fin
    })


def est_ce_que_on_regrouper(classe_name, modules, group, newMember):
    sd = trouver_semaines(classe_name, group[0], modules)[0]
    total_sm = 0
    for m in group:
        total_sm += trouver_semaines(classe_name, m, modules)[1] - trouver_semaines(classe_name, m, modules)[0]
    if len(group) > 1:
        total_sm += 1
    sf = sd + total_sm 
    sm = trouver_semaines(classe_name, newMember, modules)[1] - trouver_semaines(classe_name, newMember, modules)[0] + 1
    if sf + sm <= 14:
        return True
    
    return False    

def get_shared_modules(classe_name, modules):
    tps_partages = []
    
    # les tps à une seul seance:
    tps1 = sorted([m for m in modules.keys() if m.startswith("TP") and modules[m] == 1], key=lambda m: trouver_semaines(classe_name, m, modules)[0])
    # print(f"Les tps à une seul seances: {tps1}")
    if not tps1 or len(tps1) < 2:
        raise Exception("Il n'y a pas assez des tps à regrouper !")
    i, j = 0, 1
    group = list()
    group.append(tps1[i])
    while i < len(tps1) and j < len(tps1):
        if est_ce_que_on_regrouper(classe_name, modules, group, tps1[j]):
            group.append(tps1[j])
            tps1.remove(tps1[j])
        else:
            j+=1
        if j >= len(tps1):
            if len(group) >= 2:
                tps1.remove(tps1[i])
                tps_partages.append(group) 
            else:
                i += 1
            j = i + 1
            group = list()
            if len(tps1) > 1:
                group.append(tps1[i])

            
    # les tps à une deux seance:
    # tps2 = sorted([m for m in modules if m.startswith("TP ") and module[m] == 2], key=lambda m: trouver_semaines(m)[0])
    
    # print(f"Les tps restants : {tps1}")
    return tps_partages
    


def is_module_partage(modules_partages, module_name):
    for g in modules_partages:
        for m in g:
            if m == module_name:
                return g    

    return None


def update_prof_dispo(nom_prof, jour, c):
    if PROFESSEURS[nom_prof]["type"] == "permanent":
        CONTRAINTES["profs_max_seances"][nom_prof] -= 1

    CONTRAINTES["non_disponibilites_profs"].setdefault(jour, {}).setdefault(c, []).append(nom_prof)

def affecter_groupe_seances(classe_name, individu, jour, c, infos, modules, groupe, groupe_profs, modules_fix):
    sd, sf = trouver_semaines(classe_name, groupe[0], modules_fix)
    update_prof_dispo(groupe_profs[0], jour, c)
    individu[jour][c].append({
        "prof": groupe_profs[0],
        "salle": infos["salle"],
        "module": groupe[0],
        "semaine_debut": sd,
        "semaine_fin": sf
    })
    modules[groupe[0]] -= 1
    for i in range(1, len(groupe)):
        sf += 1
        sd = sf 
        sf = sf + trouver_semaines(classe_name, groupe[i], modules_fix)[1] - trouver_semaines(classe_name, groupe[i], modules_fix)[0]
        individu[jour][c].append({
            "prof": groupe_profs[i],
            "salle": infos["salle"],
            "module": groupe[i],
            "semaine_debut": sd,
            "semaine_fin": sf
        })
        update_prof_dispo(groupe_profs[i], jour, c)
        modules[groupe[i]] -= 1



def choisir_prof(names_of_profs_disponibles, profs, jour, c):
    # la priorité est de profs vacataires 
    profs_vacataires = [p for p, d in names_of_profs_disponibles.items() if jour in d["disponibilites"] and d["type"] == "vacataire"]
    # si on a des profs vacataires disponible ce jour on va les prioriser
    if len(profs_vacataires) > 0:
        nom_prof = random.choice(profs_vacataires)
    else:
        nom_prof = random.choice(list(names_of_profs_disponibles.keys()))
    prof = profs[nom_prof]
    attempt = 0
    while (jour not in prof["disponibilites"] or CONTRAINTES["profs_max_seances"][nom_prof] <= 0) and attempt < 200:
        attempt += 1
        nom_prof = random.choice(list(names_of_profs_disponibles.keys()))
        prof = profs[nom_prof]
    
    update_prof_dispo(nom_prof, jour, c)

    return nom_prof, prof

def get_profs_of_other_moduels(profs_disponibles, profs, res):
    result = []
    if not res:
        return []
    if len(res) <= 1:
        return res
    for m_partage in res:
        profs_result = []
        for p in profs_disponibles:
            p_modules = profs[p]["modules"]
            for p_m in p_modules:
                if p_m == m_partage:
                    profs_result.append(p)
                    break
        result.append(profs_result)
    profs_partages = []
    for pfs in result:
        if len(pfs) > 0:
            profs_partages.append(random.choice(pfs))
    return profs_partages


def generer_individu(classe_name):
    classe_info = CLASSES[classe_name]
    # Initialisation de l'individu (emploi du temps)
    individu = {jour: {creneau: [] for creneau in CRENEAUX } for jour in JOURS}
    
    # Préparer les cours et les tps
    modules = prepare_modules(classe_info)

    # TOTAL SÉANCES DE MODULES (modules["Français"] => count => nombre des séancs par semaine) 
    total_seances_modules = sum(modules.values())
    print(total_seances_modules)
    
    tps_partages = []
    if total_seances_modules - 20 > 0: 
        tps_partages = get_shared_modules(classe_name, modules) 

    modules_fix = copy.deepcopy(modules)
    profs = prepare_profs(modules)

    salle = reserver_salle()
    salle_fixe = salle

    jour_de_sport, prof_de_sport = trouver_jour_et_prof_de_sport(profs)
    CONTRAINTES["jour_de_sport"][jour_de_sport] += 1
    # réserver le sport
    semaine_debut, semaine_fin = trouver_semaines(classe_name, "ESP", modules_fix)
    affecter_seance(classe_name, individu, jour_de_sport, "13:30-15:30", {"nom_prof": prof_de_sport, "salle": salle_fixe,  "nom_module": "ESP"}, modules_fix)
    affecter_seance(classe_name, individu,  jour_de_sport, "15:40-17:30", {"nom_prof": prof_de_sport, "salle": salle_fixe, "nom_module": "ESP"}, modules_fix)
    # éliminer le sport
    eliminer_sport(profs, modules)

    for jour in JOURS:
        creneaux_reserves = set()
        for c in CRENEAUX:
            if c in creneaux_reserves:
                continue
            if jour == jour_de_sport and c == "13:30-15:30":
                break
            profs_disponibles = {p:d for p, d in profs.items() if p not in CONTRAINTES['non_disponibilites_profs'].get(jour, {}).get(c, [])}
            if len(profs_disponibles) > 0:
                # 1. choisir un professeur
                nom_prof, prof = choisir_prof(profs_disponibles, profs, jour, c)
                # 2. choisir le module
                nom_module = random.choice(prof["modules"])
                groupe = is_module_partage(tps_partages, nom_module)
                iter = 0
                while groupe and iter <= 200:
                    print(iter)
                    iter += 1
                    print(nom_module)
                    print(groupe)
                    groupe_profs = get_profs_of_other_moduels(profs_disponibles, profs, groupe)
                    c1 = 0
                    if len(prof["modules"]) > 1:
                        while groupe and len(groupe_profs) != len(groupe) and c1 <= 20:
                                c1 += 1
                                nom_module = random.choice(prof["modules"])
                                groupe = is_module_partage(tps_partages, nom_module)
                                groupe_profs = get_profs_of_other_moduels(profs_disponibles, profs, groupe)

                    if groupe and len(groupe_profs) != len(groupe) :
                        nom_prof, prof = choisir_prof(profs_disponibles, profs, jour, c)
                        nom_module = random.choice(prof["modules"])
                        groupe = is_module_partage(tps_partages, nom_module)
                        groupe_profs = get_profs_of_other_moduels(profs_disponibles, profs, groupe)
                    else:
                        break
                    print(groupe_profs)

                if modules[nom_module] >= 2:
                    if c == "08:30-10:30" or c == "13:30-15:30":
                        c_suivante = "10:40-12:30" if c == "08:30-10:30" else "15:40-17:30"
                        # si c'est un seance de tp on doit réserver une salle de tp.
                        if nom_module.startswith("TP "):
                            salle = reserver_salle_tp(jour, c_suivante)
                        else:
                            salle = None
                        if groupe:
                            affecter_groupe_seances(classe_name, individu, jour, c_suivante, {"nom_prof": nom_prof, "salle": salle}, modules, groupe, groupe_profs, modules_fix)
                        else:
                            affecter_seance(classe_name, individu, jour, c_suivante, {"nom_prof": nom_prof, "nom_module": nom_module, "salle": salle}, modules_fix)
                            modules[nom_module] -= 1
                        CONTRAINTES["non_disponibilites_profs"].setdefault(jour, {}).setdefault(c_suivante, []).append(nom_prof)
                        creneaux_reserves.add(c_suivante)

                modules[nom_module] -= 1
            else:
                nom_prof = ""
                nom_module = "Pause"

            if nom_module.startswith("TP "):
                salle = reserver_salle_tp(jour, c)
            else:
                salle = None

            if groupe:
                affecter_groupe_seances(classe_name, individu, jour, c, {"nom_prof": nom_prof, "salle": salle}, modules, groupe, groupe_profs, modules_fix)
            else:
                affecter_seance(classe_name, individu, jour, c, {"nom_prof": nom_prof, "nom_module": nom_module, "salle": salle}, modules_fix)
            # update profs
            deleted_profs = []
            for p in profs:
                profs[p]["modules"] = [m for m in profs[p]["modules"] if modules[m] > 0]
                if len(profs[p]["modules"]) == 0:
                    deleted_profs.append(p)

            if len(deleted_profs) > 0:
                for dp in deleted_profs:
                    del profs[dp]

    return individu, salle_fixe, modules

def trouver_semaines(classe, module, modules):
    if module.startswith("TP"):
        module = module[3:]
        cours_seances, cours_semaines, _, tp_seances = get_cours_infos(CLASSES[classe][module])
    elif module == "Pause":
        return 1, TOTAL_SEMAINES
    else:
        cours_seances, cours_semaines, _, _ = get_cours_infos(CLASSES[classe][module])
        tp_seances = 0

    if tp_seances > 0:
        semaine_debut = math.floor(cours_semaines / 2)
        tp_seance_par_semaine = modules["TP " + module]
        tp_semaines = math.ceil(tp_seances / tp_seance_par_semaine)
        semaine_fin = semaine_debut + tp_semaines
        semaine_debut = semaine_debut + 1
    else:
        semaine_fin = cours_semaines
        semaine_debut = 1

    return  semaine_debut , semaine_fin


def afficher_individu(individu, classe_name, salle, modules):
    # print(modules)
    print("********************************************************")
    print("********************************************************")
    print(f"******** Emploi de temps de {classe_name} - salle: salle {salle+1} ********")
    for jour in individu:
        print(jour + ": ")
        for c in individu[jour]:
            # Afficher le créneau
            print(end="\t")
            print(c + ": ")
            for seance in individu[jour][c]:
                print(end="\t\t")
                print(seance["module"], end=" - ") 
                if seance["module"] != "ESP":
                    if seance["salle"]:
                        print(end="salle: ")
                        print(seance["salle"], end="- ")
                print(seance["prof"], end=" semaines: ")
                print(f"S{seance['semaine_debut']} - S{seance['semaine_fin']}")
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


# Génie Digital et IA en santé
# individu, salle, modules = generer_individu("2A_GD")
# score = evaluate(modules)
# afficher_individu(individu, "2A_GD", salle, modules)
# print(f"score: {score}")
# # Génie Biomédicale
# individu, salle, modules = generer_individu("2A_GB")
# score = evaluate(modules)
# afficher_individu(individu, "2A_GB", salle, modules)
# print(f"score: {score}")
# exit()
iter = 0
for classe in CLASSES:
    OLD_CONTRAINTES = copy.deepcopy(CONTRAINTES)
    score = -1
    while score < 0:
        iter += 1
        CONTRAINTES = copy.deepcopy(OLD_CONTRAINTES)
        individu, salle, modules = generer_individu(classe)
        score = evaluate(modules)
    afficher_individu(individu, classe, salle, modules)
    print(f"score: {score}")

print(f" this took {iter} iterations ")
exit()
