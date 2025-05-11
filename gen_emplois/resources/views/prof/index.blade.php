@extends('layouts.app')

@section('title', 'Emploi du Temps - Professeur')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h1 class="text-2xl font-bold mb-4">Emploi du Temps - {{ $professeur->nom }}</h1>
            <p class="text-gray-600">Consultez votre emploi du temps pour toutes vos classes.</p>
            <a href="{{ route('prof.timetable.export') }}"
                class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Exporter en PDF
            </a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        @if (empty($timetable))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-8" role="alert">
                <p>Aucun cours n'est planifié pour le moment.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden shadow-lg">
                    <thead>
                        <tr class="bg-gray-100">
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
                                                <div
                                                    class="mb-2 p-2 rounded-md shadow-sm bg-blue-100 border-l-4 border-blue-500">
                                                    <div class="font-bold">{{ $cours['module'] }}</div>
                                                    <div class="text-sm text-gray-600">Classe: {{ $cours['classe'] }}</div>
                                                    <div class="text-sm text-gray-600">Salle: {{ $cours['salle'] }}</div>
                                                    <div class="text-sm text-gray-600">Semaines:
                                                        S{{ $cours['semaine_debut'] }}-S{{ $cours['semaine_fin'] }}</div>
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
            </div>
        @endif
    </div>
@endsection
