<!-- resources/views/modules/form.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 mt-4">
        <h1 class="text-2xl font-bold mb-4">{{ $module->exists ? 'Modifier un Module' : 'Ajouter un Module' }}</h1>
        <form action="{{ route($module->exists ? 'modules.update' : 'modules.store', $module) }}" method="POST"
            class="space-y-6">
            @csrf
            @method($module->exists ? 'PUT' : 'POST')

            <!-- Nom du Module -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom du Module</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $module->nom) }}"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Volume Horaire -->
            <div>
                <label for="volume_horaire" class="block text-sm font-medium text-gray-700">Volume Horaire (en
                    heures)</label>
                <input type="number" name="volume_horaire" id="volume_horaire"
                    value="{{ old('volume_horaire', $module->volume_horaire) }}"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('volume_horaire')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nombre de séances de TP -->
            <div>
                <label for="tp_seances" class="block text-sm font-medium text-gray-700">Nombre de séances de TP</label>
                <input type="number" name="tp_seances" id="tp_seances" value="{{ old('tp_seances', $module->tp_seances) }}"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('tp_seances')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sélection multiple des professeurs -->
            <div>
                <label for="professeurs" class="block text-sm font-medium text-gray-700">Professeurs</label>
                <select name="professeurs[]" id="professeurs" multiple
                    class="mt-1 block w-full rounded-md border-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @foreach ($professeurs as $professeur)
                        <option value="{{ $professeur->id }}"
                            {{ in_array($professeur->id, old('professeurs', $module->professeurs->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                            {{ $professeur->nom }}
                        </option>
                    @endforeach
                </select>
                @error('professeurs')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sélection multiple des classes -->
            <div>
                <label for="classes" class="block text-sm font-medium text-gray-700">Classes</label>
                <select name="classes[]" id="classes" multiple
                    class="mt-1 block w-full rounded-md border-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @foreach ($classes as $classe)
                        <option value="{{ $classe->id }}"
                            {{ in_array($classe->id, old('classes', $module->classes->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                            {{ $classe->nom }}
                        </option>
                    @endforeach
                </select>
                @error('classes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bouton de Soumission -->
            <div>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $module->exists ? 'Modifier' : 'Ajouter' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Script pour améliorer l'expérience utilisateur avec Select2 (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script>
        $(document).ready(function() {
            $('#professeurs').select2({
                placeholder: "Sélectionnez les professeurs",
                allowClear: true
            });

            $('#classes').select2({
                placeholder: "Sélectionnez les classes",
                allowClear: true
            });
        });
    </script>
@endsection
