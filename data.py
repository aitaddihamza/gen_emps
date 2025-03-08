# la partie données

# les créneaux
CRENEAUX = ["08:30-10:30", "10:40-12:30", "13:30-15:30", "15:40-17:30"]

# les jours
JOURS = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"]

# les classes
CLASSES = {
    "2A_GD": {
        "Bases de Traitement d'images médicales": {"volume": 28, "tp_seances": 4},
        "Mini projet de Traitement d'image": {"volume": 28, "tp_seances": 2},
        "Robotiques médicales": {"volume": 24, "tp_seances": 2},
        "Gestion de projet": {"volume": 28, "tp_seances": 0},
        "Réalisation": {"volume": 28, "tp_seances": 0},
        "Organisation hospitalière": {"volume": 56, "tp_seances": 0},
        "Bioinformatique": {"volume": 56, "tp_seances": 0},
        "Bases de données Avancées": {"volume": 56, "tp_seances": 2},
        "Machine learning": {"volume": 48, "tp_seances": 2},
        "Economie de santé": {"volume": 28, "tp_seances": 0},
        "ESP": {"volume": 48, "tp_seances": 0},
        "Français": {"volume": 24, "tp_seances": 0},
        "Anglais": {"volume": 24, "tp_seances": 0}
    },
    "2A_GB": {
        "Imagerie Médicale": {"volume": 28, "tp_seances": 5},
        "Bases de Traitement d'images médicales": {"volume": 28, "tp_seances": 4},
        "Mini projet de Traitement d'image": {"volume": 28, "tp_seances": 0},
        "Biostatistiques": {"volume": 28, "tp_seances": 0},
        "Bioinformatique": {"volume": 56, "tp_seances": 0},
        "Gestion de projet": {"volume": 28, "tp_seances": 0},
        "Réalisation": {"volume": 28, "tp_seances": 0},
        "Organisation hospitalière": {"volume": 56, "tp_seances": 0},
        "Bases de données Avancées": {"volume": 56, "tp_seances": 2},
        "Physiologie": {"volume": 28, "tp_seances": 0},
        "Comptabilité": {"volume": 28, "tp_seances": 0},
        "ESP": {"volume": 48, "tp_seances": 0},
        "Français": {"volume": 24, "tp_seances": 0},
        "Anglais": {"volume": 24, "tp_seances": 0}
    },
    "1A_GB": {
        "Radioactivité": {"volume": 24, "tp_seances": 0},
        "Traitement de signal": {"volume": 24, "tp_seances": 5},
        "Recherche opérationnelle": {"volume": 48, "tp_seances": 4},
        "Bases de données": {"volume": 24, "tp_seances": 0},
        "Droit": {"volume": 24, "tp_seances": 0},
        "TOEIC": {"volume": 24, "tp_seances": 0},
        "CAO-DAO": {"volume": 12, "tp_seances": 0},
        "Programmation Orienté Objet": {"volume": 24, "tp_seances": 0},
        "Statistique et probabilité": {"volume": 24, "tp_seances": 0},
        "Physiologie": {"volume": 56, "tp_seances": 4},
        "Gestion de l'entreprise et Marché public": {"volume": 24, "tp_seances": 0},
        "Compétences culturelles et Artistiques": {"volume": 24, "tp_seances": 0},
        "ESP": {"volume": 48, "tp_seances": 0},
        "Français": {"volume": 24, "tp_seances": 0},
        "Anglais": {"volume": 24, "tp_seances": 0}
    },
    "1A_GD": {
        "Radioactivité": {"volume": 24, "tp_seances": 0},
        "Traitement de signal": {"volume": 24, "tp_seances": 5},
        "Recherche opérationnelle": {"volume": 48, "tp_seances": 4},
        "Bases de données": {"volume": 24, "tp_seances": 0},
        "Droit": {"volume": 24, "tp_seances": 0},
        "TOEIC": {"volume": 24, "tp_seances": 0},
        "Programmation Orienté Objet": {"volume": 56, "tp_seances": 0},
        "Statistique et probabilité": {"volume": 24, "tp_seances": 0},
        "Physiologie": {"volume": 56, "tp_seances": 4},
        "Gestion de l'entreprise et Marché public": {"volume": 24, "tp_seances": 0},
        "Compétences culturelles et Artistiques": {"volume": 24, "tp_seances": 0},
        "ESP": {"volume": 48, "tp_seances": 0},
        "Français": {"volume": 24, "tp_seances": 0},
        "Anglais": {"volume": 24, "tp_seances": 0}

    }
}

# les profs
# max_heures: c'est le volume horaire maximal qu'un prof peut enseigner par semaine
PROFESSEURS = {
    "Pr.JBAHI": {"modules": ["Radioactivité"], "max_heures": 20, "type": "permanent"},
    "Pr.FAIZ": {"modules": ["Anglais"], "max_heures": 20, "type": "permanent"},
    "Pr.MOUMADI": {"modules": ["Programmation Orienté Objet"], "max_heures": 20, "type": "permanent"},
    "Pr.ASRAOUI": {"modules": ["Statistique et probabilité"], "max_heures": 20, "type": "permanent"},
    "Pr.ERRABIH": {"modules": ["Droit"], "max_heures": 20, "type": "permanent"},
    "Pr.KHALFAOUI": {"modules": ["Recherche opérationnelle"], "max_heures": 20, "type": "permanent"},
    "Pr.AMEZIANE": {"modules": ["CAO-DAO"], "max_heures": 20, "type": "permanent"},
    "Pr.MERZOUQI": {"modules": ["Bases de données", "Bases de données Avancées", "Réalisation", "Gestion de projet", "Bases de Traitement d'images médicales", "Mini projet de Traitement d'image"], "max_heures": 20, "type": "permanent"},
    "Pr.BETTASS": {"modules": ["Robotiques médicales", "Biostatistiques"], "max_heures": 20, "type": "permanent"},
    "Pr.KAHKAHY": {"modules": ["Français"], "max_heures": 20, "type": "permanent"},
    "Pr.BOUHALI": {"modules": ["Français"], "max_heures": 20, "type": "permanent"},
    "Pr.ANSARI": {"modules": ["Anatomie", "Gestion de l'entreprise et Marché public"], "max_heures": 20, "type": "permanent"},
    "Pr.AHMED": {"modules": ["Français"], "max_heures": 20, "type": "permanent"},
    "Pr.ERRAZI": {"modules": ["Programmation"], "max_heures": 20, "type": "permanent"},
    "Pr.ADDI": {"modules": ["ESP"], "max_heures": 20, "type": "permanent"},
    "Pr.BANOUARAB": {"modules": ["ESP"], "max_heures": 20, "type": "permanent"},
    "Pr.DEBBAGH": {"modules": ["Physiologie", "Biologie"], "max_heures": 20, "type": "permanent"},
    "Pr.BEHJA": {"modules": ["Economie de santé", "Comptabilité"], "max_heures": 20, "type": "permanent"},
    "Pr.SAWALMEH": {"modules": ["Anglais", "Compétences culturelles et Artistiques"],"type": "vacataire", "disponibilites": ["Mercredi"]},
    "Pr.SPAGETTI": {"modules": ["Anglais"], "max_heures": 20, "type": "permanent"},
    "Pr.KHAY": {"modules": ["Organisation hospitalière"], "type": "vacataire", "disponibilites": ["Mercredi", "Vendredi"]},
    "Pr.MEFADDEL": {"modules": ["TOEIC"], "type": "vacataire", "disponibilites": ["Jeuid", "Mercredi"]},
    "Pr.MOHAMMED": {"modules": ["Anaylse numérique", "Mathématiques"], "type": "vacataire", "disponibilites": ["Jeudi", "Vendredi"]},
    "Pr.HOUSBANE": {"modules": ["Bioinformatique"], "type": "vacataire", "disponibilites": ["Vendredi", "Mercredi"]},
    "Pr.MOUZOUN": {"modules": ["Imagerie Médicale", "Mini projet de Traitement d'image", "Bases de Traitement d'images médicales", "Gestion de projet", "Traitement de signal"], "type": "vacataire", "disponibilites": ["Jeudi", "Vendredi", "Mercredi"]},
    "Pr.EL YANDOUZI": {"modules": ["Machine learning", "Génie Logicil"], "type": "vacataire", "disponibilites": ["Jeudi", "Mardi", "Lundi"]},
    "P7": {"modules": ["Sécurité"], "max_heures": 20, "type": "permanent"},
    "P8": {"modules": ["Biologie"], "max_heures": 20, "type": "permanent"},
    "Pr.ACHIR": {"modules": ["TP Bases de Traitement d'images médicales", "TP Imagerie Médicale", "TP Physiologie", "TP Mini projet de Traitement d'image"], "type": "doctorant"},
    "Pr.AYOUB": {"modules": ["TP Biologie"], "type": "doctorant"},
    "Pr.PHIRI": {"modules": ["TP Recherche opérationnelle"], "type": "doctorant"},
    "Pr.GOURAM": {"modules": ["TP Traitement de signal"], "type": "doctorant"},
    "Pr.SLALMI": {"modules": ["TP Traitement de signal"], "type": "doctorant"},
    "Pr.EL MOUFID": {"modules": ["TP Robotiques médicales"], "type": "doctorant"}, 
    "Pr.AKKAOUI": {"modules": ["TP Machine learning", "TP Bases de données Avancées"], "type": "doctorant"},
    "D4": {"modules": ["TP Bioinformatique", "TP Biologie"], "type": "doctorant"},
    "Pr.ZOUBIR": {"modules": ["TP Anaylse numérique", "TP Programmation"], "type": "doctorant"},
    "Pr.YANDOUZI": {"modules": ["TP Programmation Orienté Objet","Génie Logicil"], "type": "doctorant"}
}
