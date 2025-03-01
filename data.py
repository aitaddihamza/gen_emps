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
        "Bioinformatique": {"volume": 56, "tp_seances": 0},
        "Bases de données Avancées": {"volume": 56, "tp_seances": 0},
        "Machine learning": {"volume": 56, "tp_seances": 5},
        "Economie de santé": {"volume": 28, "tp_seances": 0},
        "ESP": {"volume": 48, "tp_seances": 0},
        "Français": {"volume": 24, "tp_seances": 0},
        "Anglais": {"volume": 24, "tp_seances": 0}
    },
    "2A_GB": {
        "Imagerie Médicale": {"volume": 28, "tp_seances": 0},
        "Bases de Traitement d'images médicales": {"volume": 28, "tp_seances": 5},
        "Mini projet de Traitement d'image": {"volume": 28, "tp_seances": 0},
        "Biostatistiques": {"volume": 28, "tp_seances": 0},
        "Bioinformatique": {"volume": 56, "tp_seances": 0},
        "Gestion de projet": {"volume": 28, "tp_seances": 0},
        "Réalisation": {"volume": 28, "tp_seances": 0},
        "Organisation hospitalière": {"volume": 56, "tp_seances": 0},
        "Bases de données Avancées": {"volume": 56, "tp_seances": 5},
        "Physiologie": {"volume": 28, "tp_seances": 0},
        "Comptabilité": {"volume": 28, "tp_seances": 0},
        "ESP": {"volume": 48, "tp_seances": 0},
        "Français": {"volume": 24, "tp_seances": 0},
        "Anglais": {"volume": 24, "tp_seances": 0}
    },
    "1A_GD": {
        "Anaylse numérique": {"volume": 56, "tp_seances": 5},
        "Programmation Orienté Objet": {"volume": 56, "tp_seances": 5},
        "Génie Logicil": {"volume": 24, "tp_seances": 0},
        "Mathématiques": {"volume": 24, "tp_seances": 0},
        "Programmation": {"volume": 28, "tp_seances": 5},
        "Comptabilité": {"volume": 30, "tp_seances": 0},
        "Physiologie": {"volume": 56, "tp_seances": 0},
        "Biologie": {"volume": 24, "tp_seances": 0},
        "Anatomie": {"volume": 24, "tp_seances": 0},
        "ESP": {"volume": 48, "tp_seances": 0},
        "Français": {"volume": 24, "tp_seances": 0},
        "Anglais": {"volume": 24, "tp_seances": 0}
    },
    "1A_GB": {
        "Anaylse numérique": {"volume": 56, "tp_seances": 5},
        "Programmation Orienté Objet": {"volume": 56, "tp_seances": 5},
        "Mathématiques": {"volume": 30, "tp_seances": 0},
        "Programmation": {"volume": 58, "tp_seances": 5},
        "Comptabilité": {"volume": 30, "tp_seances": 0},
        "Physiologie": {"volume": 56, "tp_seances": 0},
        "Biologie": {"volume": 28, "tp_seances": 2},
        "Anatomie": {"volume": 56, "tp_seances": 2},
        "ESP": {"volume": 48, "tp_seances": 0},
        "Français": {"volume": 24, "tp_seances": 0},
        "Anglais": {"volume": 24, "tp_seances": 0}
    }
}

# les profs
# max_heures: c'est le volume horaire maximal qu'un prof peut enseigner par semaine
PROFESSEURS = {
    "Pr.MERZOUQI": {"modules": ["Bases de données Avancées", "Réalisation", "Gestion de projet", "Bases de Traitement d'images médicales", "Mini projet de Traitement d'image"], "max_heures": 40, "type": "permanent"},
    "Pr.BETTASS": {"modules": ["Robotiques médicales", "Biostatistiques"], "max_heures": 40, "type": "permanent"},
    "Pr.BOUHALI": {"modules": ["Français"], "max_heures": 40, "type": "permanent"},
    "Pr.ANSARI": {"modules": ["Anatomie"], "max_heures": 40, "type": "permanent"},
    "Pr.AHMED": {"modules": ["Français"], "max_heures": 40, "type": "permanent"},
    "Pr.ERRAZI": {"modules": ["Programmation"], "max_heures": 40, "type": "permanent"},
    "Pr.ADDI": {"modules": ["ESP"], "max_heures": 40, "type": "permanent"},
    "Pr.HAMZA": {"modules": ["ESP"], "max_heures": 40, "type": "permanent"},
    "Pr.DEBBAGH": {"modules": ["Physiologie", "Biologie"], "max_heures": 40, "type": "permanent"},
    "Pr.BEHJA": {"modules": ["Economie de santé", "Comptabilité"], "max_heures": 40, "type": "permanent"},
    "Pr.SAWALMEH": {"modules": ["Anglais"],"type": "vacataire", "disponibilites": ["Mercredi"]},
    "Pr.SPAGETTI": {"modules": ["Anglais"], "max_heures": 40, "type": "permanent"},
    "Pr.KHAY": {"modules": ["Organisation hospitalière"], "type": "vacataire", "disponibilites": ["Mercredi", "Vendredi"]},
    "Pr.MOHAMMED": {"modules": ["Anaylse numérique", "Mathématiques"], "type": "vacataire", "disponibilites": ["Jeudi", "Vendredi"]},
    "Pr.HOUSBANE": {"modules": ["Bioinformatique"], "type": "vacataire", "disponibilites": ["Vendredi", "Mercredi"]},
    "Pr.MOUAD": {"modules": ["Imagerie Médicale", "Mini projet de Traitement d'image", "Bases de Traitement d'images médicales", "Gestion de projet"], "type": "vacataire", "disponibilites": ["Jeudi", "Vendredi", "Mercredi"]},
    "Pr.EL YANDOUZI": {"modules": ["Machine learning", "Programmation Orienté Objet", "Génie Logicil"], "type": "vacataire", "disponibilites": ["Jeudi", "Mardi", "Lundi"]},
    "P7": {"modules": ["Sécurité"], "max_heures": 20, "type": "permanent"},
    "P8": {"modules": ["Biologie"], "max_heures": 20, "type": "permanent"},
    "Pr.ACHIR": {"modules": ["TP Bases de Traitement d'images médicales"], "type": "doctorant"},
    "Pr.AYOUB": {"modules": ["TP Biologie"], "type": "doctorant"},
    "Pr.EL MOUFID": {"modules": ["TP Robotiques médicales"], "type": "doctorant"},
    "Pr.AKKAOUI": {"modules": ["TP Machine learning", "TP Bases de données Avancées"], "type": "doctorant"},
    "D4": {"modules": ["TP Bioinformatique", "TP Biologie"], "type": "doctorant"},
    "Pr.ZOUBIR": {"modules": ["TP Anaylse numérique", "TP Programmation"], "type": "doctorant"},
    "Pr.YANDOUZI": {"modules": ["TP Programmation Orienté Objet","Génie Logicil"], "type": "doctorant"}
}
