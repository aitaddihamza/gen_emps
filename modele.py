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

# Variables globales
CONTRAINTES = {
    "salles_reserves": [],
    "salles_tps": {}, # "Lundi": {"8:30-10:30": {"TP1"}}
    "jour_de_sport": defaultdict(int),
    "profs_max_seances": {},
    "non_disponibilites_profs": {}
}

for p, d in PROFESSEURS.items():
    if d["type"] == "permanent":
        CONTRAINTES["profs_max_seances"][p] = math.floor(d["max_heures"] / 2) 
    else:
        CONTRAINTES["profs_max_seances"][p] = 1

# Fonctions

def get_cours_infos(cours):
    volume = cours["volume"]
    tp_seances = cours["tp_seances"]
    seances = math.ceil(volume / 2)
    seance_par_semaine = math.ceil(seances / TOTAL_SEMAINES)
    semaines = math.ceil(seances / seance_par_semaine)
    return seances, semaines, seance_par_semaine, tp_seances

def get_tp_infos(cours): 
    _, semaines, seance_par_semaine, tp_seances = get_cours_infos(cours)
    semaine_debut = math.floor(semaines / 2) + 1
    seance_par_semaine = math.ceil(tp_seances / semaine_debut)
    semaines = math.ceil(tp_seances / seance_par_semaine)
    return semaine_debut, semaines, seance_par_semaine

def prepare_modules(classe_info):
    modules = {}
    for module, details in classe_info.items():
        _, semaines, seance_par_semaine, tp_seances = get_cours_infos(details)
        modules[module] = seance_par_semaine
        if tp_seances > 0:
            nbr_semaines_tp = math.ceil(semaines / 2)
            modules[f"TP {module}"] = math.ceil(tp_seances / nbr_semaines_tp)
    return modules

def prepare_profs(classe_modules):
    profs = {}
    for p, details in PROFESSEURS.items():
        prof_type = details["type"]
        prof_info = {"modules": [], "type": prof_type}
        for m in details["modules"]:
            tp = "TP " + m
            if m in classe_modules:
                prof_info["modules"].append(m)
            if details["type"] == "doctorant" and tp in classe_modules:
                prof_info["modules"].append(tp)
        if prof_info["modules"]:
            if prof_type in ["doctorant", "permanent"]:
                prof_info["disponibilites"] = JOURS
            if prof_type in ["doctorant", "vacataire"]:
                prof_info["count"] = 1
                if prof_type == "vacataire":
                    prof_info["disponibilites"] = details["disponibilites"]
            if prof_type == "permanent":
                prof_info["count"] = math.floor(details["max_heures"] / 2)
            profs[p] = prof_info
    return profs

def trouver_jour_et_prof_de_sport(profs):
    jour_de_sport = profs_vacataires = None
    while jour_de_sport is None or profs_vacataires is None or len(profs_vacataires) > 1 or CONTRAINTES['jour_de_sport'][jour_de_sport] > 1:
        jour_de_sport = random.choice(JOURS)
        profs_vacataires = [p for p in profs.values() if jour_de_sport in p["disponibilites"] and p["type"] == "vacataire"]
    profs_de_sport = [p for p, d in profs.items() if "ESP" in d["modules"]]
    prof_de_sport = random.choice(profs_de_sport)
    return jour_de_sport, prof_de_sport

def eliminer_sport(profs, classe_modules):
    del classe_modules["ESP"]
    profs_de_sport = [p for p, d in profs.items() if "ESP" in d["modules"]]
    for prof in profs_de_sport:
        del profs[prof]

def reserver_salle():
    salles_disponibles = [salle for salle in range(NBR_SALLES) if salle not in CONTRAINTES["salles_reserves"]]
    if not salles_disponibles:
        raise Exception("Il n'y a pas assez de salles disponibles")
    salle = random.choice(salles_disponibles)
    return salle

def reserver_salle_tp(jour, c):
    salles_disponibles = [salle for salle in SALLES_TPS if salle not in CONTRAINTES["salles_tps"].get(jour, {}).get(c, set())]
    if not salles_disponibles:
        raise Exception("Il n'y a pas assez de salles disponibles")
    salle = random.choice(salles_disponibles)
    return salle

def generer_individu(classe_info):
    individu = {jour: {creneau: None for creneau in CRENEAUX} for jour in JOURS}
    modules = prepare_modules(classe_info)
    total_seances_modules = sum(modules.values())
    print(f"total des seances du modules par semaine: {total_seances_modules}")
    profs = prepare_profs(modules)
    salle = reserver_salle()
    CONTRAINTES["salles_reserves"].append(salle)
    jour_de_sport, prof_de_sport = trouver_jour_et_prof_de_sport(profs)
    CONTRAINTES["jour_de_sport"][jour_de_sport] += 1
    salle_tp = None
    individu[jour_de_sport]["13:30-15:30"] = {"prof": prof_de_sport, "salle_tp": salle_tp, "module": "ESP"}
    individu[jour_de_sport]["15:40-17:30"] = {"prof": prof_de_sport, "salle_tp": salle_tp, "module": "ESP"}
    eliminer_sport(profs, modules)
    for jour in JOURS:
        creneaux_reserves = set()
        for c in CRENEAUX:
            if c in creneaux_reserves:
                continue
            if jour == jour_de_sport and c == "13:30-15:30":
                break
            profs_disponibles = {p: d for p, d in profs.items() if p not in CONTRAINTES['non_disponibilites_profs'].get(jour, {}).get(c, [])}
            if profs_disponibles:
                profs_vacataires = [p for p, d in profs_disponibles.items() if jour in d["disponibilites"] and d["type"] == "vacataire"]
                if profs_vacataires:
                    nom_prof = random.choice(profs_vacataires)
                else:
                    nom_prof = random.choice(list(profs_disponibles.keys()))
                prof = profs[nom_prof]
                attempt = 0
                while (jour not in prof["disponibilites"] or CONTRAINTES["profs_max_seances"][nom_prof] <= 0) and attempt < 200:
                    attempt += 1
                    nom_prof = random.choice(list(profs_disponibles.keys()))
                    prof = profs[nom_prof]
                if prof["type"] == "permanent":
                    prof["count"] -= 1
                    CONTRAINTES["profs_max_seances"][nom_prof] -= 1
                CONTRAINTES["non_disponibilites_profs"].setdefault(jour, {}).setdefault(c, []).append(nom_prof)
                nom_module = random.choice(prof["modules"])
                if modules[nom_module] >= 2:
                    if c == "08:30-10:30" or c == "13:30-15:30":
                        c_suivante = "10:40-12:30" if c == "08:30-10:30" else "15:40-17:30"
                        if nom_module.startswith("TP "):
                            salle_tp = reserver_salle_tp(jour, c_suivante)
                            CONTRAINTES["salles_tps"].setdefault(jour, {}).setdefault(c_suivante, set()).add(salle_tp)
                        individu[jour][c_suivante] = {"prof": nom_prof, "salle_tp": salle_tp, "module": nom_module}
                        CONTRAINTES["non_disponibilites_profs"].setdefault(jour, {}).setdefault(c_suivante, []).append(nom_prof)
                        modules[nom_module] -= 1
                        creneaux_reserves.add(c_suivante)
                modules[nom_module] -= 1
            else:
                nom_prof = ""
                nom_module = "Pause"
            if nom_module.startswith("TP "):
                salle_tp = reserver_salle_tp(jour, c)
                CONTRAINTES["salles_tps"].setdefault(jour, {}).setdefault(c, set()).add(salle_tp)
            individu[jour][c] = {"prof": nom_prof, "salle_tp": salle_tp, "module": nom_module}
            salle_tp = None
            deleted_profs = []
            for p in profs:
                profs[p]["modules"] = [m for m in profs[p]["modules"] if modules[m] > 0]
                if not profs[p]["modules"]:
                    deleted_profs.append(p)
            for dp in deleted_profs:
                del profs[dp]
    return individu, salle, modules

def trouver_semaines(classe, module):
    if module.startswith("TP"):
        module = module[3:]
        cours_seances, cours_semaines, _, tp_seances = get_cours_infos(CLASSES[classe][module])
    elif module == "Pause":
        return 1, 14
    else:
        cours_seances, cours_semaines, _, _ = get_cours_infos(CLASSES[classe][module])
        tp_seances = 0
    if tp_seances > 0:
        semaine_debut, tp_semaines, _ = get_tp_infos(CLASSES[classe][module])
        semaine_fin = semaine_debut + tp_semaines
        semaine_debut += 1
    else:
        semaine_fin = cours_semaines
        semaine_debut = 1
    return semaine_debut, semaine_fin

def afficher_individu(individu, classe_name, salle, modules):
    print("********************************************************")
    print("********************************************************")
    print(f"******** Emploi de temps de {classe_name} - salle: salle {salle + 1} ********")
    for jour in individu:
        print(jour + ": ")
        for c, seance in individu[jour].items():
            print(end="\t")
            print(c, end=" - ")
            print(seance["module"], end=" - ")
            if seance["salle_tp"]:
                print("salle: Salle", seance["salle_tp"], end=" - ")
            if seance["module"] != "Pause":
                print(seance["prof"], end=" semaines: ")
                semaine_debut, semaine_fin = trouver_semaines(classe_name, seance["module"])
                print(f"S{semaine_debut} - S{semaine_fin}")
            else:
                print()
        print()

def evaluate(modules):
    score = 0
    for m, c in modules.items():
        if c > 0:
            score -= 1
    return score

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

# Génération des emplois du temps
iter = 0
for classe in CLASSES:
    OLD_CONTRAINTES = copy.deepcopy(CONTRAINTES)
    score = -1
    while score < 0:
        iter += 1
        CONTRAINTES = copy.deepcopy(OLD_CONTRAINTES)
        individu, salle, modules = generer_individu(CLASSES[classe])
        score = evaluate(modules)
    afficher_individu(individu, classe, salle, modules)
    print(f"score: {score}")
print(f"Total iterations: {iter}")
exit()

