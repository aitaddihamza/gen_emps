# la partie données

# les créneaux
CRENEAUX = ["08:30-10:30", "10:40-12:30", "13:30-15:30", "15:40-17:30"]

# les jours
JOURS = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"]

# les classes
CLASSES = {
    "2A_GD": {
        "Bases de Traitement d'images médicales": {"volume": 28, "tp_seances": 5},
        "Mini projet de Traitement d'image": {"volume": 28, "tp_seances": 0},
        "Robotiques médicales": {"volume": 28, "tp_seances": 2},
        "Gestion de projet": {"volume": 28, "tp_seances": 0},
        "Réalisation": {"volume": 28, "tp_seances": 0},
        "Organisation hospitalière": {"volume": 28, "tp_seances": 0},
        "Bioinformatique": {"volume": 28, "tp_seances": 0},
        "Bases de données Avancées": {"volume": 56, "tp_seances": 0},
        "Machine learning": {"volume": 56, "tp_seances": 5},
        "Français": {"volume": 24, "tp_seances": 0},
        "Anglais": {"volume": 24, "tp_seances": 0},
        "Economie de santé": {"volume": 28, "tp_seances": 0},
        "ESP": {"volume": 48, "tp_seances": 0},
    },
    "2A_GB": {
        "Imagerie Médicale": {"volume": 24, "tp_seances": 0},
        "Bases de Traitement d'images médicales": {"volume": 28, "tp_seances": 5},
        "Mini projet de Traitement d'image": {"volume": 28, "tp_seances": 0},
        "Biostatistiques": {"volume": 24, "tp_seances": 0},
        "Bioinformatique": {"volume": 28, "tp_seances": 0},
        "Gestion de projet": {"volume": 28, "tp_seances": 0},
        "Réalisation": {"volume": 28, "tp_seances": 0},
        "Organisation hospitalière": {"volume": 24, "tp_seances": 0},
        "Bases de données Avancées": {"volume": 56, "tp_seances": 0},
        "Physiologie": {"volume": 24, "tp_seances": 0},
        "Comptabilité": {"volume": 24, "tp_seances": 0},
        "ESP": {"volume": 48, "tp_seances": 0},
    },
    "1A_GD": {
        "Mathématiques": {"volume": 30, "tp_seances": 0},
        "Programmation": {"volume": 58, "tp_seances": 5},
        "Comptabilité": {"volume": 30, "tp_seances": 0},
        "Français": {"volume": 30, "tp_seances": 0},
        "Anglais": {"volume": 30, "tp_seances": 0}
    },
    "1A_GB": {
        "Mathématiques": {"volume": 30, "tp_seances": 0},
        "Biologie": {"volume": 30, "tp_seances": 0},
        "Comptabilité": {"volume": 30, "tp_seances": 0},
        "Français": {"volume": 30, "tp_seances": 0},
        "Anglais": {"volume": 30, "tp_seances": 0}
    }
}

# les profs
# max_heures: c'est le volume horaire maximal qu'un prof peut enseigner par semaine
PROFESSEURS = {
    "Pr.MERZOUQI": {"modules": ["Bases de données Avancées", "Réalisation", "Gestion de projet", "Bases de Traitement d'images médicales", "Mini projet de Traitement d'image"], "max_heures": 40, "type": "permanent"},
    "Pr.BETTASS": {"modules": ["Robotiques médicales", "Biostatistiques"], "max_heures": 40, "type": "permanent"},
    "Pr.BOUHALI": {"modules": ["Français"], "max_heures": 40, "type": "permanent"},
    "Pr.ADDI": {"modules": ["ESP"], "max_heures": 40, "type": "permanent"},
    "Pr.HAMZA": {"modules": ["ESP"], "max_heures": 40, "type": "permanent"},
    "Pr.DEBBAGH": {"modules": ["Physiologie"], "max_heures": 40, "type": "permanent"},
    "Pr.BEHJA": {"modules": ["Economie de santé", "Comptabilité"], "max_heures": 40, "type": "permanent"},
    "Pr.SAWALMEH": {"modules": ["Anglais"],"type": "vacataire", "disponibilites": ["Mercredi"]},
    "Pr.KHAY": {"modules": ["Organisation hospitalière"], "type": "vacataire", "disponibilites": ["Mercredi", "Vendredi"]},
    "Pr.HOUSBANE": {"modules": ["Bioinformatique"], "type": "vacataire", "disponibilites": ["Vendredi", "Mercredi"]},
    "Pr.MOUAD": {"modules": ["Imagerie Médicale"], "type": "vacataire", "disponibilites": ["Jeudi", "Vendredi", "Mercredi"]},
    "Pr.EL YANDOUZI": {"modules": ["Machine learning"], "type": "vacataire", "disponibilites": ["Jeudi", "Mardi", "Lundi"]},
    "P7": {"modules": ["Sécurité"], "max_heures": 20, "type": "permanent"},
    "P8": {"modules": ["Biologie"], "max_heures": 20, "type": "permanent"},
    "Pr.ACHIR": {"modules": ["TP Bases de Traitement d'images médicales"], "type": "doctorant"},
    "Pr.EL MOUFID": {"modules": ["TP Robotiques médicales"], "type": "doctorant"},
    "Pr.AKKAOUI": {"modules": ["TP Machine learning"], "type": "doctorant"},
    "D4": {"modules": ["TP Bioinformatique", "TP Biologie"], "type": "doctorant"}
}
