<!-- resources/views/professeurs/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 mt-8">
        @include('shared.flush')
        <h1 class="text-2xl font-bold mb-4">Liste des Professeurs</h1>

        <!-- Bouton pour ajouter un nouveau professeur -->
        <a href="{{ route('professeurs.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded-md mb-8 inline-block hover:bg-blue-600">
            Ajouter un Professeur
        </a>

        <div id="warning-message"
            class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-2 rounded relative mb-8" role="alert">
            <strong class="font-bold">Note:</strong>
            <span class="block sm:inline">tu peux voir les disponibilités d'un professeur vacataire en cliquant sur son
                nom.</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-yellow-500 cursor-pointer" role="button"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="closeMessage('warning-message')">
                </svg>
            </span>
        </div>

        <!-- Tableau des professeurs -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full">
                <!-- En-tête du tableau -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heures
                            Max
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>

                <!-- Corps du tableau -->
                <tbody class="divide-y divide-gray-200">
                    @foreach ($professeurs as $professeur)
                        <tr>
                            <!-- Nom du professeur -->
                            <td class="px-6 py-4">
                                @if ($professeur->type_prof == 'vacataire')
                                    <a href="{{ route('professeurs.show', $professeur) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        {{ $professeur->nom }}
                                    </a>
                                @else
                                    {{ $professeur->nom }}
                                @endif
                            </td>

                            <!-- Type de professeur -->
                            <td class="px-6 py-4">
                                @if ($professeur->type_prof === 'permanent')
                                    <span
                                        class="px-2 py-1 text-sm bg-green-100 text-green-800 rounded-full">permanent</span>
                                @elseif ($professeur->type_prof === 'vacataire')
                                    <span
                                        class="px-2 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full">vacataire</span>
                                @else
                                    <span class="px-2 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">doctorant</span>
                                @endif
                            </td>

                            <!-- Heures Max -->
                            <td class="px-6 py-4">
                                {{ $professeur->max_heures ?? 'N/A' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <!-- Bouton Modifier -->
                                <a href="{{ route('professeurs.edit', $professeur) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-2">
                                    Modifier
                                </a>

                                <!-- Bouton Supprimer -->
                                <form action="{{ route('professeurs.destroy', $professeur) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce professeur ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $professeurs->links() }}
        </div>
    </div>
@endsection
