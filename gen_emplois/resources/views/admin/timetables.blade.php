@extends('layouts.app')

@section('title', 'Dashboard - Voir les Emplois du Temps')

@section('content')
    <style>
        .select-primary {
            border-color: var(--primary-color);
        }
        .select-primary:focus {
            border-color: var(--primary-color);
            ring-color: var(--primary-color);
        }
        .btn-export {
            background-color: var(--primary-color);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-export:hover {
            background-color: #00a5bb;
        }
        .table-header {
            background: var(--primary-color);
            color: white;
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-8">Voir les Emplois du Temps</h1>

        <!-- Sélection du semestre -->
        <form id="semesterForm" class="mb-8">
            <label for="semestre" class="block text-sm font-medium text-gray-700 mb-2">Choisir le Semestre</label>
            <select name="semestre" id="semestre" onchange="document.getElementById('semesterForm').submit()"
                class="select-primary block w-1/4 rounded-md h-[50px] p-2 shadow-sm focus:ring-2 focus:ring-primary sm:text-sm">
                <option value="1" {{ request('semestre') == 1 ? 'selected' : '' }}>Semestre 1</option>
                <option value="2" {{ request('semestre') == 2 ? 'selected' : '' }}>Semestre 2</option>
            </select>
        </form>

        <!-- Tableau des emplois du temps générés -->
        <div id="timetableResults">
            @if (empty($timetables) || count($timetables) === 0)
                <p class="text-gray-600">Aucun emploi du temps n'a été généré pour le semestre sélectionné.</p>
            @else
                @foreach ($timetables as $classe => $timetable)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Classe : {{ $classe }}</h3>
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden shadow-lg">
                            <thead>
                                <tr class="table-header">
                                    <th class="py-3 px-4 border-b font-medium text-left">Jour</th>
                                    <th class="py-3 px-4 border-b font-medium text-left">08:30-10:30</th>
                                    <th class="py-3 px-4 border-b font-medium text-left">10:40-12:30</th>
                                    <th class="py-3 px-4 border-b font-medium text-left">13:30-15:30</th>
                                    <th class="py-3 px-4 border-b font-medium text-left">15:40-17:30</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
                                    $creneaux = ['08:30-10:30', '10:40-12:30', '13:30-15:30', '15:40-17:30'];
                                @endphp
                                @foreach ($jours as $jour)
                                    <tr>
                                        <td class="py-3 px-4 border-b font-medium bg-gray-100">{{ $jour }}</td>
                                        @foreach ($creneaux as $creneau)
                                            <td class="py-3 px-4 border-b">
                                                @if (isset($timetable[$jour][$creneau]))
                                                    @foreach ($timetable[$jour][$creneau] as $cours)
                                                        @php
                                                            $class = '';
                                                            if (str_contains(strtolower($cours['module']), 'tp')) {
                                                                $class = 'bg-green-100 border-l-4 border-green-500'; // TP: Green
                                                            } elseif (str_contains(strtolower($cours['module']), 'eps')) {
                                                                $class = 'bg-orange-100 border-l-4 border-orange-500'; // EPS: Orange
                                                            } else {
                                                                $class = 'bg-blue-100 border-l-4 border-blue-500'; // Cours: Blue
                                                            }
                                                        @endphp
                                                        <div class="mb-2 p-2 rounded-md shadow-sm {{ $class }}">
                                                            <div class="font-bold">{{ $cours['module'] }}</div>
                                                            <div class="text-sm text-gray-600">Prof: {{ $cours['prof'] }}</div>
                                                            <div class="text-sm text-gray-600">Salle: {{ $cours['salle'] }}</div>
                                                            <div class="text-sm text-gray-600">Semaines: S{{ $cours['semaine_debut'] }}-S{{ $cours['semaine_fin'] }}</div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Bouton pour exporter en PDF -->
                        <a href="{{ route('admin.timetables.export', ['classe' => $classe]) }}"
                            class="btn-export mt-4 inline-block px-6 py-2 rounded-md shadow-md hover:shadow-lg">
                            Exporter en PDF
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
