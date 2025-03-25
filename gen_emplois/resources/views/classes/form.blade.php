<!-- resources/views/salles/form.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 mt-4">
        <h1 class="text-2xl font-bold mb-4">{{ $classe->exists ? 'Modifier une Classe' : 'Ajouter une Classe' }}</h1>
        <form action="{{ route($classe->exists ? 'classes.update' : 'classes.store', $classe) }}" method="POST"
            class="space-y-6">
            @csrf
            @method($classe->exists ? 'PUT' : 'POST')

            <!-- Nom de la Classe -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la Classe</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $classe->nom) }}"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Effectif de la Classe -->
            <div>
                <label for="effectif" class="block text-sm font-medium text-gray-700">Effectif de la Classe</label>
                <input type="number" name="effectif" id="effectif" value="{{ old('effectif', $classe->effectif) }}"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('effectif')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bouton de Soumission -->
            <div>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $classe->exists ? 'Modifier' : 'Ajouter' }}
                </button>
            </div>
        </form>
    </div>
@endsection
