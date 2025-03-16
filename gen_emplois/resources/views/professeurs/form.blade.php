<!-- resources/views/professeurs/form.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 mt-4">
        <h1 class="text-2xl font-bold mb-4">{{ $professeur->exists ? 'Modifier un Professeur' : 'Ajouter un Professeur' }}
        </h1>
        <form action="{{ route($professeur->exists ? 'professeurs.update' : 'professeurs.store', $professeur) }}"
            method="POST" class="space-y-6">
            @csrf
            @method($professeur->exists ? 'PUT' : 'POST')

            <!-- Nom du Professeur -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $professeur->nom) }}"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type de Professeur -->
            <div>
                <label for="type_prof" class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type_prof" id="type_prof"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="PERMANENT"
                        {{ old('type_prof', $professeur->type_prof) === 'PERMANENT' ? 'selected' : '' }}>Permanent</option>
                    <option value="VACATAIRE"
                        {{ old('type_prof', $professeur->type_prof) === 'VACATAIRE' ? 'selected' : '' }}>Vacataire</option>
                    <option value="DOCTORANT"
                        {{ old('type_prof', $professeur->type_prof) === 'DOCTORANT' ? 'selected' : '' }}>Doctorant</option>
                </select>
                @error('type_prof')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Heures Max (uniquement pour les permanents et doctorants) -->
            <div id="max_heures_field"
                class="{{ old('type_prof', $professeur->type_prof) === 'VACATAIRE' ? 'hidden' : '' }}">
                <label for="max_heures" class="block text-sm font-medium text-gray-700">Heures Max</label>
                <input type="number" name="max_heures" id="max_heures"
                    value="{{ old('max_heures', $professeur->max_heures) }}"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('max_heures')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Disponibilités (uniquement pour les vacataires) -->
            <div id="disponibilites_field"
                class="{{ old('type_prof', $professeur->type_prof) === 'VACATAIRE' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700">Disponibilités</label>
                <div class="mt-2 space-y-4">
                    @foreach (['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'] as $jour)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $jour }}</label>
                            <div class="mt-1 grid grid-cols-2 gap-4">
                                @foreach (['08:30-10:30', '10:40-12:30', '13:30-15:30', '15:40-17:30'] as $creneau)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="disponibilites[{{ $jour }}][]"
                                            value="{{ $creneau }}"
                                            {{ isset($professeur->disponibilites[$jour]) && in_array($creneau, $professeur->disponibilites[$jour]) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2">{{ $creneau }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('disponibilites')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bouton de Soumission -->
            <div>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    <!-- Script pour gérer l'affichage dynamique des champs -->
    <script>
        document.getElementById('type_prof').addEventListener('change', function() {
            const type = this.value;
            const maxHeuresField = document.getElementById('max_heures_field');
            const disponibilitesField = document.getElementById('disponibilites_field');

            if (type === 'VACATAIRE') {
                maxHeuresField.classList.add('hidden');
                disponibilitesField.classList.remove('hidden');
            } else {
                maxHeuresField.classList.remove('hidden');
                disponibilitesField.classList.add('hidden');
            }
        });

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            const type = document.getElementById('type_prof').value;
            const maxHeuresField = document.getElementById('max_heures_field');
            const disponibilitesField = document.getElementById('disponibilites_field');

            if (type === 'VACATAIRE') {
                maxHeuresField.classList.add('hidden');
                disponibilitesField.classList.remove('hidden');
            } else {
                maxHeuresField.classList.remove('hidden');
                disponibilitesField.classList.add('hidden');
            }
        });
    </script>
@endsection
