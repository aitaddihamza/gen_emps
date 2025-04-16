<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('title', 'Dashboard - Génération des Emplois du Temps')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-8">Génération des Emplois du Temps</h1>

        <!-- Formulaire de génération des emplois du temps -->
        <form id="generateTimetableForm" class="bg-white shadow-md rounded-lg p-6 mb-8" action="" method="POST">
            @csrf
            <div class="mb-4">
                <label for="weeks" class="block text-sm font-medium text-gray-700">Nombre de semaines dans le
                    semestre</label>
                <input type="number" name="weeks" id="weeks" min="1" required value="15"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Bouton pour lancer la génération -->
            <button type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                Générer les Emplois du Temps
            </button>

            <!-- Lien stylisé pour voir les emplois du temps -->
            <a href="{{ route('admin.timetables') }}"
                class="inline-block mt-4 px-6 py-2 text-indigo-600 bg-white border border-indigo-600 rounded-md hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                Voir les emplois du temps
            </a>
        </form>

        <!-- Loader (animation SVG) -->
        <div id="loader" class="hidden text-center">
            <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <p class="mt-2 text-gray-600">Génération des emplois du temps en cours...</p>
        </div>

        <!-- Notification -->
        <div id="notification" class="hidden border-l-4 p-4 mb-8" role="alert">
            <p id="notificationMessage"></p>
        </div>

        <!-- Rapport d'analyse -->
        <div id="analysisReport" class="hidden bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Rapport d'Analyse</h2>
            <pre id="analysisContent" class="bg-gray-100 p-4 rounded-md overflow-auto text-sm text-gray-800"></pre>
        </div>

        <!-- Tableau des emplois du temps générés -->
        <div id="timetableResults" class="hidden">
            <h2 class="text-xl font-bold mb-4">Emplois du Temps Générés</h2>
            <div id="timetableTable" class="overflow-x-auto">
                <!-- Les emplois du temps seront injectés ici dynamiquement -->
            </div>
        </div>
    </div>

    <!-- Script pour gérer la génération des emplois du temps -->
    <script>
        document.getElementById('generateTimetableForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Afficher le loader
            document.getElementById('loader').classList.remove('hidden');
            document.getElementById('notification').classList.add('hidden');
            document.getElementById('timetableResults').classList.add('hidden');
            document.getElementById('analysisReport').classList.add('hidden');

            // Récupérer le nombre de semaines
            const weeks = document.getElementById('weeks').value;

            // Envoyer les données à l'API Laravel
            fetch("{{ route('admin.generate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        weeks: weeks
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        console.log(response)
                        throw new Error('Erreur réseau ou serveur');
                    }
                    return response.json();
                })
                .then(data => {
                    // Cacher le loader
                    document.getElementById('loader').classList.add('hidden');

                    // Afficher la notification
                    const notification = document.getElementById('notification');
                    const notificationMessage = document.getElementById('notificationMessage');
                    notificationMessage.textContent = data.message;
                    notification.classList.remove('hidden');

                    if (data.success) {
                        notification.classList.add('bg-green-100', 'border-green-500', 'text-green-700');
                        notification.classList.remove('bg-red-100', 'border-red-500', 'text-red-700');
                        document.getElementById('timetableResults').classList.remove('hidden');
                        renderTimetable(data.timetables);

                        // Afficher le rapport d'analyse
                        const analysisReport = document.getElementById('analysisReport');
                        const analysisContent = document.getElementById('analysisContent');
                        analysisContent.textContent = data.analysis || 'Aucun rapport d\'analyse disponible.';
                        analysisReport.classList.remove('hidden');
                    } else {
                        notification.classList.add('bg-red-100', 'border-red-500', 'text-red-700');
                        notification.classList.remove('bg-green-100', 'border-green-500', 'text-green-700');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    document.getElementById('loader').classList.add('hidden');
                    const notification = document.getElementById('notification');
                    const notificationMessage = document.getElementById('notificationMessage');
                    notificationMessage.textContent = 'Erreur lors de la génération des emplois du temps.';
                    notification.classList.remove('hidden');
                    notification.classList.add('bg-red-100', 'border-red-500', 'text-red-700');
                    notification.classList.remove('bg-green-100', 'border-green-500', 'text-green-700');
                });
        });

        // Fonction pour afficher les emplois du temps dans un tableau
        function renderTimetable(timetables) {
            const timetableTable = document.getElementById('timetableTable');
            timetableTable.innerHTML = ''; // Vider le contenu précédent

            // Vérifier si les emplois du temps sont définis
            if (!timetables || Object.keys(timetables).length === 0) {
                timetableTable.innerHTML = '<p class="text-gray-600">Aucun emploi du temps disponible.</p>';
                return;
            }

            // Créneaux horaires fixes
            const creneaux = ["08:30-10:30", "10:40-12:30", "13:30-15:30", "15:40-17:30"];

            // Jours de la semaine
            const jours = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"];

            // Parcourir chaque classe
            for (const [classe, emploiDuTemps] of Object.entries(timetables)) {
                const classeSection = document.createElement('div');
                classeSection.className = 'mb-12';

                // Titre de la classe
                const classeTitle = document.createElement('h3');
                classeTitle.className =
                    'text-lg font-semibold mb-4 bg-indigo-100 p-3 rounded-md shadow-sm border-l-4 border-indigo-500';
                classeTitle.textContent = `Classe : ${classe}`;
                classeSection.appendChild(classeTitle);

                // Tableau pour les emplois du temps
                const table = document.createElement('table');
                table.className = 'min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden shadow-lg';

                // En-tête du tableau (créneaux)
                const thead = document.createElement('thead');
                const headerRow = document.createElement('tr');
                headerRow.className = 'bg-gradient-to-r from-indigo-600 to-indigo-800 text-white';

                // Cellule d'en-tête pour "Jour"
                const headerJour = document.createElement('th');
                headerJour.className = 'py-3 px-4 border-b font-medium text-left';
                headerJour.textContent = 'Jour';
                headerRow.appendChild(headerJour);

                // Cellules d'en-tête pour les créneaux
                for (const creneau of creneaux) {
                    const headerCreneau = document.createElement('th');
                    headerCreneau.className = 'py-3 px-4 border-b font-medium';
                    headerCreneau.textContent = creneau;
                    headerRow.appendChild(headerCreneau);
                }

                thead.appendChild(headerRow);
                table.appendChild(thead);

                // Corps du tableau
                const tbody = document.createElement('tbody');

                // Créer les lignes des jours
                jours.forEach((jour, index) => {
                    const row = document.createElement('tr');
                    row.className = index % 2 === 0 ? 'bg-gray-50' : 'bg-white';

                    // Colonne Jour
                    const jourCell = document.createElement('td');
                    jourCell.className = 'py-3 px-4 border-b font-medium bg-gray-100';
                    jourCell.textContent = jour;
                    row.appendChild(jourCell);

                    // Colonnes Créneaux
                    for (const creneau of creneaux) {
                        const creneauCell = document.createElement('td');
                        creneauCell.className = 'py-2 px-3 border-b align-top';

                        // Vérifier si des cours existent pour ce jour et ce créneau
                        if (emploiDuTemps[jour] && emploiDuTemps[jour][creneau]) {
                            const coursList = document.createElement('div');
                            coursList.className = 'space-y-3';

                            // Parcourir chaque cours dans ce créneau
                            for (const cours of emploiDuTemps[jour][creneau]) {
                                const coursDiv = document.createElement('div');
                                coursDiv.className =
                                    'p-2 rounded-md text-sm shadow-sm transition-transform hover:scale-[1.02]';
                                coursDiv.style.backgroundColor = '#e2f0fb';
                                coursDiv.style.color = '#0e4377';
                                coursDiv.style.borderLeft = '4px solid #a8d5ff';

                                // Module (en plus grand et en gras)
                                const module = document.createElement('div');
                                module.className = 'font-bold text-base mb-1';
                                module.textContent = cours.module;
                                coursDiv.appendChild(module);

                                // Professeur
                                const prof = document.createElement('div');
                                prof.className = 'flex items-center';
                                prof.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg> ${cours.prof}`;
                                coursDiv.appendChild(prof);

                                // Salle
                                const salle = document.createElement('div');
                                salle.className = 'flex items-center';
                                salle.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg> ${cours.salle}`;
                                coursDiv.appendChild(salle);

                                // Intervalle des semaines avec badge
                                const semaines = document.createElement('div');
                                semaines.className = 'mt-1';
                                semaines.innerHTML = `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-opacity-50" style="background-color: #a8d5ff; color: #0e4377">
                                    S${cours.semaine_debut}-S${cours.semaine_fin}
                                </span>`;
                                coursDiv.appendChild(semaines);

                                coursList.appendChild(coursDiv);
                            }

                            creneauCell.appendChild(coursList);
                        }

                        row.appendChild(creneauCell);
                    }

                    tbody.appendChild(row);
                });

                table.appendChild(tbody);
                classeSection.appendChild(table);
                timetableTable.appendChild(classeSection);
            }
        }
    </script>
@endsection
