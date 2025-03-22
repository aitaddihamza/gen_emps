from flask import Flask, request, jsonify
import time
import json

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

    # Simuler la génération des emplois du temps
    # Pour l'instant, on retourne simplement une réponse réussie
    with open('emplois_du_temps.json', 'r', encoding='utf-8') as f:
        fake_data = json.load(f)

    response = {
        "success": True,
        "message": "Les emplois du temps ont été générés avec succès.",
        "timetables": fake_data
    }

    # suspendre 3 secondes
    time.sleep(3)

    # Retourner la réponse en JSON
    return jsonify(response)

if __name__ == '__main__':
    app.run(debug=True)




