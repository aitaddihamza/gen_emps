<!-- resources/views/professeurs/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 mt-8">
        <h1 class="text-2xl font-bold mb-4">Informations du Professeur Vacataire</h1>

        <!-- Informations du professeur -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Informations Personnelles</h2>
            <div class="space-y-4">
                <!-- Nom du professeur -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                    <p class="mt-1 text-lg">{{ $professeur->nom }}</p>
                </div>

                <!-- Type de professeur -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type</label>
                    <p class="mt-1 text-lg">
                        @if ($professeur->type_prof === 'VACATAIRE')
                            <span class="px-2 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full">Vacataire</span>
                        @else
                            <span class="px-2 py-1 text-sm bg-gray-100 text-gray-800 rounded-full">Autre</span>
                        @endif
                    </p>
                </div>

                <!-- Heures Max -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heures Max</label>
                    <p class="mt-1 text-lg">{{ $professeur->max_heures ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Disponibilités du professeur -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Disponibilités</h2>
            @if ($professeur->disponibilites && count($professeur->disponibilites) > 0)
                <div class="space-y-4">
                    @foreach ($professeur->disponibilites as $jour => $creneaux)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $jour }}</label>
                            <ul class="mt-1 space-y-2">
                                @foreach ($creneaux as $creneau)
                                    <li class="text-lg">{{ $creneau }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Aucune disponibilité enregistrée.</p>
            @endif
        </div>

        <!-- Bouton de retour -->
        <div class="mt-8 flex items-center gap-2">
            <a href="{{ route('professeurs.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Retour
            </a>
            <a href="{{ route('professeurs.edit', $professeur) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Modifier
            </a>
        </div>
    </div>
@endsection
