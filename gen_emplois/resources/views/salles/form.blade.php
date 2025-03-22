<!-- resources/views/salles/form.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 mt-4">
        <h1 class="text-2xl font-bold mb-4">{{ $salle->exists ? 'Modifier une Salle' : 'Ajouter une Salle' }}</h1>
        <form action="{{ route($salle->exists ? 'salles.update' : 'salles.store', $salle) }}" method="POST" class="space-y-6">
            @csrf
            @method($salle->exists ? 'PUT' : 'POST')

            <!-- Nom de la Salle -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la Salle</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $salle->nom) }}"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Capacité de la Salle -->
            <div>
                <label for="capacite" class="block text-sm font-medium text-gray-700">Capacité de la Salle</label>
                <input type="number" name="capacite" id="capacite" value="{{ old('capacite', $salle->capacite) }}"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('capacite')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type de Salle -->
            <div>
                <label for="type_salle" class="block text-sm font-medium text-gray-700">Type de Salle</label>
                <select name="type_salle" id="type_salle"
                    class="mt-1 block w-full rounded-md border-2 h-[50px] p-2 border-black shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="Salle de cours"
                        {{ old('type_salle', $salle->type_salle) === 'Salle de cours' ? 'selected' : '' }}>Salle de cours
                    </option>
                    <option value="Salle de tp"
                        {{ old('type_salle', $salle->type_salle) === 'Salle de tp' ? 'selected' : '' }}>Salle de tp
                    </option>
                </select>
                @error('type_salle')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bouton de Soumission -->
            <div>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $salle->exists ? 'Modifier' : 'Ajouter' }}
                </button>
            </div>
        </form>
    </div>
@endsection
