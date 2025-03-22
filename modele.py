import math
import random
from collections import defaultdict
import copy
import json
from flask import Flask, request, jsonify
import time

app = Flask(__name__)

@app.route('/')
def home():
    return "The api it's working."

@app.route('/generate_timetables', methods=["POST"])
def generate_timetable():
    # Simuler la réception des données JSON envoyées par Laravel
    data = request.get_json()

    # Afficher les données reçues pour vérification (optionnel)
    print("Données reçues :", data)


    TOTAL_SEMAINES = int(data["TOTAL_SEMAINES"])
    SALLES_COURS = data["SALLES_COURS"]
    SALLES_TP = data["SALLES_TP"]
    CRENEAUX = ["08:30-10:30", "10:40-12:30", "13:30-15:30", "15:40-17:30"]
    JOURS = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"]
    CLASSES = data["CLASSES"]
    CLASSES_EFFECTIF = data["CLASSES_EFFECTIF"]
    PROFESSEURS = data["PROFESSEURS"]


    CONTRAINTES = {
        "salles_reserves": [],
        "salles_tps": {}, 
        "jour_de_sport": defaultdict(int),
        "profs_max_seances": {},
        "non_disponibilites_profs": {}
    }



# intialisation du contraintes des profs 
    for p, d in PROFESSEURS.items():
        if d["type"] == "permanent":
            CONTRAINTES["profs_max_seances"][p] = math.floor(d["max_heures"] / 2) 
        else:
            CONTRAINTES["profs_max_seances"][p] = 1


    def get_cours_infos(cours):
        """ 
            fonctionne pour récupérer les infos d'un cours comme le volume, tp_seances, etc.
            il retourne une tuple(seances, semaines, seance_par_semaine, tp_seances).
        """
        volume = cours["volume"]
        tp_seances = cours["tp_seances"]
        # Calcul du nombre total de séances pour le module
        seances = math.ceil(volume / 2)
        seance_par_semaine = math.ceil(seances / TOTAL_SEMAINES)
        semaines = math.ceil(seances / seance_par_semaine)
        
        return seances, semaines, seance_par_semaine, tp_seances

    def prepare_modules(classe_info):
        """ 
            Comme paramètre il prend un dict qui représente les infos d'une classe
            il retourne les modules (cours ou tp) : seance par semaines.
        """
        # Initialisation des modules et des séances de TP
        modules = {}
        
        for module, details in classe_info.items():
            _, semaines, seance_par_semaine, tp_seances = get_cours_infos(details)
            modules[module] = seance_par_semaine
            
            # Gestion des séances de TP si elles existent
            if tp_seances > 0:
                # nbr_semaines_tp = math.ceil(semaines / 2)
                # modules[f"TP {module}"] = math.ceil(tp_seances / nbr_semaines_tp)
                modules[f"TP {module}"] = 1
        return modules

    def prepare_profs(classe_modules):
        """ 
            comme paramètre il prend les modules d'une classe et qui retourne tous les profs avec les modules qui correspond de celles de la classe.
            Note: prof est un prof permanent ou vacataire ou même un doctorant!
        """ 
        profs = {}
        for p, details in PROFESSEURS.items():
            prof_type = details["type"]
            prof_info = dict()
            prof_info["modules"] = []
            prof_info["type"] = prof_type
            
            # Ajouter les modules appropriés
            for m in details["modules"]:
                tp = "TP " + m
                if m in classe_modules:
                    prof_info["modules"].append(m)
                if details["type"] == "doctorant":
                    if tp in classe_modules:
                        prof_info["modules"].append(tp)
            
            if len(prof_info["modules"]) > 0:
                # Initialiser les disponibilités selon le type de professeur
                if prof_type == "doctorant" or prof_type == "permanent":
                    prof_info["disponibilites"] = {}
                
                if prof_type == "doctorant" or prof_type == "vacataire":
                    prof_info["count"] = 1
                    if prof_type == "vacataire":
                        # Pour les vacataires, copier la structure de disponibilités mise à jour
                        prof_info["disponibilites"] = details["disponibilites"]
                
                if prof_type == "permanent":
                    prof_info["count"] = math.floor(details["max_heures"] / 2)
                
                profs[p] = prof_info
        
        return profs


    def trouver_jour_et_prof_de_sport(profs):
        """ 
            planifier le jour de sport, il retourn le jour et le prof du sport.
        """
        jour_de_sport = profs_vacataires = None

        # le choix du jour de sport
        while jour_de_sport is None or profs_vacataires is None or len(profs_vacataires) > 1 or CONTRAINTES['jour_de_sport'][jour_de_sport] > 4:
            jour_de_sport = random.choice(JOURS)
            # Note il faut changer ce code
            profs_vacataires = [p for p in profs.values() if p["disponibilites"].get(jour_de_sport, None) and p["type"] == "vacataire"]

        # le choit du prof du sport
        # Note: on suppose que les profs du sports sont des profs permanents, sinon il faut changer le comportement de ceette fonctionne.
        profs_de_sport = [p for p, d in profs.items() if "ESP" in d["modules"]]
        prof_de_sport = random.choice(profs_de_sport)
        return jour_de_sport, prof_de_sport 

    def eliminer_sport(profs, classe_modules):
        """ 
            i have to add some doc here for this small func.
        """ 
        del classe_modules["ESP"]
        profs_de_sport = [p for p, d in profs.items() if "ESP" in d["modules"]]
        for prof in profs_de_sport:
            del profs[prof]
            CONTRAINTES["profs_max_seances"][prof] -= 1


    def reserver_salle_tp(jour, c):
        # les salles disponibles 
        salles_disponibles = [ salle for salle in SALLES_TP if salle not in CONTRAINTES["salles_tps"].get(jour, {}).get(c, set()) ]
        if not salles_disponibles:
            raise Exception("y a pas assez des salles de tps disponibles")
        salle = random.choice(salles_disponibles)
        CONTRAINTES["salles_tps"].setdefault(jour, {}).setdefault(c, set()).add(salle)
        return salle

    def reserver_salle(classe_name):
        # les salles disponibles 
        salles_disponibles = [salle for salle, n in SALLES_COURS.items() if salle not in CONTRAINTES["salles_reserves"] and n >= CLASSES_EFFECTIF[classe_name]]
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
        if sf + sm <= TOTAL_SEMAINES:
            return True
        
        return False    

    def regrouper(classe_name, modules, tps_cours):
        tps_partages = []
        for ctg in tps_cours:
            if len(ctg) < 2:
                # raise Exception("Il n'y a pas assez de TP à regrouper !")
                continue

            # Trier les TP par leur première semaine
            ctg.sort(key=lambda m: trouver_semaines(classe_name, m, modules)[0])

            i = 0
            while i < len(ctg):
                group = [ctg[i]]  # Commencer un nouveau groupe avec le ctg actuel
                j = i + 1

                while j < len(ctg):
                    if est_ce_que_on_regrouper(classe_name, modules, group, ctg[j]):
                        group.append(ctg[j])  # Ajouter le ctg au groupe
                        ctg.pop(j)  # Retirer le ctg de la liste
                    else:
                        j += 1  # Passer au ctg suivant

                if len(group) >= 2:
                    tps_partages.append(group)  # Ajouter le groupe à la liste des ctg partageables
                    ctg.pop(i)  # Retirer le ctg de départ de la liste
                else:
                    i += 1  # Passer au ctg suivant si le groupe est trop petit
        return tps_partages

    def get_shared_modules(classe_name, modules):
        
        tps_cours = [[m for m in modules.keys() if modules[m] == 1]]
        tps_partages = regrouper(classe_name, modules, tps_cours)
        seances_optimises = sum(len(groupe) - 1 for groupe in tps_partages)  
        # print(f"nombre des seances optimisés: {seances_optimises}")
        total_seances_modules = sum(modules.values())
        rest = total_seances_modules - seances_optimises
        # print(f"The rest is: {rest}")
        if rest > 20:
            # Filtrer les TP à une seule séance
            modules = {m: 2 if c == 1 and "TP " in m and (trouver_semaines(classe_name, m, modules)[1] - trouver_semaines(classe_name, m, modules)[0] + 1) % 2 == 0 else c for m, c in modules.items()}
            tps2 = [m for m in modules.keys() if modules[m] == 2 and "TP " in m]
            # tps2 = []
            tps1 = [m for m in modules.keys() if modules[m] == 1]
            
            tps_cours = [tps1] + [tps2]
            tps_partages = regrouper(classe_name, modules, tps_cours)
            seances_optimises = sum(len(groupe) - 1 if modules[groupe[0]] == 1 else len(groupe) - 2 for groupe in tps_partages)  
            # print(f"The rest is: {rest}")
        # print(f"Les TP partageables : {tps_partages}")
        return tps_partages, modules
        


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
        if "TP " in groupe[0]:
            if not infos["salle"]:
                infos["salle"] = reserver_salle_tp(jour, c)

        seance = {
            "prof": groupe_profs[0],
            "salle": infos["salle"] if "TP " in groupe[0] else infos["salle"],
            "module": groupe[0],
            "salle": infos["salle"],
            "semaine_debut": sd,
            "semaine_fin": sf
        }
        individu[jour][c].append(seance)
        modules[groupe[0]] -= 1
        # pour garantir que tous les tps se déroule dans la même salle TP.
        once = 0
        for i in range(1, len(groupe)):
            sf += 1
            sd = sf 
            sf = sf + trouver_semaines(classe_name, groupe[i], modules_fix)[1] - trouver_semaines(classe_name, groupe[i], modules_fix)[0]
            if once == 0:
                if "TP " in groupe[i]:
                    if not infos["salle"]:
                        infos["salle"] = reserver_salle_tp(jour, c)
                once = 1
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
        profs_vacataires = []
        for p, d in names_of_profs_disponibles.items():
            if d["type"] == "vacataire":
                # Vérifier si le professeur est disponible dans ce créneau spécifique
                if jour in d["disponibilites"] and c in d["disponibilites"][jour]:
                    profs_vacataires.append(p)
                    
        # si on a des profs vacataires disponible ce jour et ce créneau on va les prioriser
        if len(profs_vacataires) > 0:
            nom_prof = random.choice(profs_vacataires)
        else:
            sorted_profs = sorted(names_of_profs_disponibles.items(), key=lambda p: CONTRAINTES["profs_max_seances"][p[0]], reverse=True)
            nom_prof = random.choice([p[0] for p in sorted_profs])
        
        prof = profs[nom_prof]
        attempt = 0
        
        # Pour les vacataires, vérifier la disponibilité spécifique au créneau
        while attempt < 200:
            if prof["type"] == "vacataire":
                if jour in prof["disponibilites"] and c in prof["disponibilites"][jour]:
                    break
            else:
                # Pour les autres types, la vérification reste simple
                if CONTRAINTES["profs_max_seances"][nom_prof] > 0:
                    break
            
            attempt += 1
            sorted_profs = sorted(names_of_profs_disponibles.items(), key=lambda p: CONTRAINTES["profs_max_seances"][p[0]], reverse=True)
            nom_prof = random.choice([p[0] for p in sorted_profs])
            prof = profs[nom_prof]
        
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
            semaine_debut = math.floor(cours_semaines / 4)
            if semaine_debut <= tp_seances:
                semaine_debut = tp_seances - semaine_debut
            tp_semaines = math.ceil(tp_seances / modules["TP " + module])
            semaine_fin = semaine_debut + tp_semaines
            semaine_debut = semaine_debut + 1
        else:
            semaine_fin = cours_semaines
            semaine_debut = 1

        return  semaine_debut , semaine_fin

    def equilibrer_charge_prof(profs_disponibles, nom_module, old_prof, jour, c):
        module_profs = [p for p, d in profs_disponibles.items() if nom_module in d["modules"]]
        sorted_profs = sorted(module_profs, key=lambda p: CONTRAINTES["profs_max_seances"][p], reverse=True)
        if nom_module =="Français":
            print(module_profs)
            print(sorted_profs)
            for p in module_profs:
                print(p, CONTRAINTES["profs_max_seances"][p])
        if len(sorted_profs) <= 1:
            return old_prof, profs_disponibles[old_prof]
        nom_prof = sorted_profs[0]
        prof =  profs_disponibles[nom_prof]

        print(nom_prof)

        return nom_prof, prof

    def generer_individu(classe_name):
        classe_info = CLASSES[classe_name]
        # Initialisation de l'individu (emploi du temps)
        individu = {jour: {creneau: [] for creneau in CRENEAUX} for jour in JOURS}

        # Préparer les cours et les tps
        modules = prepare_modules(classe_info)

        # TOTAL SÉANCES DE MODULES (modules["Français"] => count => nombre des séances par semaine)
        total_seances_modules = sum(modules.values())
        print(total_seances_modules)

        tps_partages = []
        if total_seances_modules - 20 > 0:
            tps_partages, new_modules  = get_shared_modules(classe_name, modules)
            modules = new_modules
        modules_fix = copy.deepcopy(modules)
        profs = prepare_profs(modules)

        salle = reserver_salle(classe_name)
        salle_fixe = salle

        # Planifier le sport
        jour_de_sport, prof_de_sport = trouver_jour_et_prof_de_sport(profs)
        CONTRAINTES["jour_de_sport"][jour_de_sport] += 1
        semaine_debut, semaine_fin = trouver_semaines(classe_name, "ESP", modules_fix)
        affecter_seance(classe_name, individu, jour_de_sport, "13:30-15:30",
                        {"nom_prof": prof_de_sport, "salle": salle_fixe, "nom_module": "ESP"}, modules_fix)
        affecter_seance(classe_name, individu, jour_de_sport, "15:40-17:30",
                        {"nom_prof": prof_de_sport, "salle": salle_fixe, "nom_module": "ESP"}, modules_fix)
        eliminer_sport(profs, modules)

        # Planifier les modules à deux séances en premier
        for jour in JOURS:
            creneaux_reserves = set()
            for c in CRENEAUX:
                if c in creneaux_reserves:
                    continue
                if jour == jour_de_sport and c == "13:30-15:30":
                    break

                profs_disponibles = {p: d for p, d in profs.items() if
                                    p not in CONTRAINTES['non_disponibilites_profs'].get(jour, {}).get(c, [])}
                
                if len(profs_disponibles) > 0:
                    # 1. Choisir un professeur
                    nom_prof, prof = choisir_prof(profs_disponibles, profs, jour, c)
                    # Identifier les modules avec deux séances ou plus
                    modules_deux_seances = [m for m in prof["modules"] if modules[m] >= 2]
                    # 2. Choisir un module (prioriser les modules à deux séances)
                    if modules_deux_seances:
                        nom_module = random.choice(modules_deux_seances)
                    else:
                        nom_module = random.choice(prof["modules"])

                    nom_prof, prof = equilibrer_charge_prof(profs_disponibles, nom_module, nom_prof, jour, c)

                    groupe = is_module_partage(tps_partages, nom_module)
                    iter = 0
                    while groupe and iter <= 200:
                        iter += 1
                        groupe_profs = get_profs_of_other_moduels(profs_disponibles, profs, groupe)
                        c1 = 0
                        if len(prof["modules"]) > 1:
                            while groupe and len(groupe_profs) != len(groupe) and c1 <= 50:
                                c1 += 1
                                nom_module = random.choice(prof["modules"])
                                groupe = is_module_partage(tps_partages, nom_module)
                                groupe_profs = get_profs_of_other_moduels(profs_disponibles, profs, groupe)

                        if c1 == 50:
                            Exception("something happens here !")
                        if groupe and len(groupe_profs) != len(groupe):
                            nom_prof, prof = choisir_prof(profs_disponibles, profs, jour, c)
                            nom_module = random.choice(prof["modules"])
                            nom_porf, prof = equilibrer_charge_prof(profs_disponibles, nom_module, nom_prof, jour, c)
                            groupe = is_module_partage(tps_partages, nom_module)
                            groupe_profs = get_profs_of_other_moduels(profs_disponibles, profs, groupe)
                        else:
                            break

                    update_prof_dispo(nom_prof, jour, c)
                    # Si le module a deux séances ou plus, vérifier les créneaux consécutifs
                    if modules[nom_module] >= 2:
                        if c == "08:30-10:30" or c == "13:30-15:30":
                            c_suivante = "10:40-12:30" if c == "08:30-10:30" else "15:40-17:30"

                            # Vérifier si le créneau suivant est disponible
                            if c_suivante not in creneaux_reserves:
                                # Si c'est un TP, réserver une salle de TP
                                if nom_module.startswith("TP "):
                                    salle = reserver_salle_tp(jour, c_suivante)
                                else:
                                    salle = salle_fixe

                                # Affecter les séances consécutives
                                if groupe:
                                    affecter_groupe_seances(classe_name, individu, jour, c_suivante,
                                                            {"nom_prof": nom_prof, "salle": salle}, modules, groupe,
                                                            groupe_profs, modules_fix)
                                else:
                                    affecter_seance(classe_name, individu, jour, c_suivante,
                                                    {"nom_prof": nom_prof, "nom_module": nom_module, "salle": salle},
                                                    modules_fix)
                                    modules[nom_module] -= 1

                                # Réserver le créneau suivant
                                CONTRAINTES["non_disponibilites_profs"].setdefault(jour, {}).setdefault(c_suivante, []).append(nom_prof)

                                creneaux_reserves.add(c_suivante)

                    # Mettre à jour le nombre de séances restantes pour le module
                    if not groupe:
                        modules[nom_module] -= 1

                else:
                    nom_prof = ""
                    nom_module = "Pause"

                # Affecter la séance actuelle
                if nom_module.startswith("TP "):
                    salle = reserver_salle_tp(jour, c)
                else:
                    salle = salle_fixe

                if groupe:
                    affecter_groupe_seances(classe_name, individu, jour, c, {"nom_prof": nom_prof, "salle": salle}, modules,
                                            groupe, groupe_profs, modules_fix)
                else:
                    affecter_seance(classe_name, individu, jour, c,
                                    {"nom_prof": nom_prof, "nom_module": nom_module, "salle": salle}, modules_fix)

                # Mettre à jour les professeurs
                deleted_profs = []
                for p in profs:
                    profs[p]["modules"] = [m for m in profs[p]["modules"] if modules[m] > 0]
                    if len(profs[p]["modules"]) == 0:
                        deleted_profs.append(p)

                if len(deleted_profs) > 0:
                    for dp in deleted_profs:
                        del profs[dp]

        return individu, salle_fixe, modules


    def afficher_individu(individu, classe_name, salle, modules):
        # print(modules)
        print("********************************************************")
        print("********************************************************")
        print(f"******** Emploi de temps de {classe_name} - salle: salle {salle} ********")
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

    def evaluate(modules):
        """ 
            fonctionne pour l'évaluation d'emploi de temps
        """
        score = 0
        for m, c in modules.items():
            if c > 0:
                score -= 1


        return score

    iter = 0
    result = dict()
    for classe in CLASSES:
        OLD_CONTRAINTES = copy.deepcopy(CONTRAINTES)
        score = -1
        while score < 0 and iter < 30:
            iter += 1
            CONTRAINTES = copy.deepcopy(OLD_CONTRAINTES)
            individu, salle, modules = generer_individu(classe)
            result[classe] = individu
            score = evaluate(modules)
        afficher_individu(individu, classe, salle, modules)
        print(f"score: {score}")

    print(f" this took {iter} iterations ")

    for p, r in CONTRAINTES["profs_max_seances"].items():
        print(f"{p}: {r}")



    response = {
        "success": True,
        "message": "Les emplois du temps ont été générés avec succès.",
        "timetables": result
    }

    # suspendre 2 secondes
    time.sleep(2)

    # Retourner la réponse en JSON
    return jsonify(response)

if __name__ == '__main__':
    app.run(debug=True)
