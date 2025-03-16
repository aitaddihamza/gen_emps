<!-- resources/views/salles/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 mt-8">
        @include('shared.flush')
        <h1 class="text-2xl font-bold mb-4">Liste des Salles</h1>

        <!-- Bouton pour ajouter une nouvelle salle -->
        <a href="{{ route('salles.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded-md mb-8 inline-block hover:bg-blue-600">
            Ajouter une Salle
        </a>

        <!-- Tableau des salles -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full">
                <!-- En-tête du tableau -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacité
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de
                            Salle
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>

                <!-- Corps du tableau -->
                <tbody class="divide-y divide-gray-200">
                    @foreach ($salles as $salle)
                        <tr>
                            <!-- Nom de la salle -->
                            <td class="px-6 py-4">
                                <a href="{{ route('salles.show', $salle) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $salle->nom }}
                                </a>
                            </td>

                            <!-- Capacité de la salle -->
                            <td class="px-6 py-4">
                                {{ $salle->capacite }} places
                            </td>

                            <!-- Type de salle -->
                            <td class="px-6 py-4">
                                {{ $salle->type_salle }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <!-- Bouton Modifier -->
                                <a href="{{ route('salles.edit', $salle) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-2">
                                    Modifier
                                </a>

                                <!-- Bouton Supprimer -->
                                <form action="{{ route('salles.destroy', $salle) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')">
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
            {{ $salles->links() }}
        </div>
    </div>
@endsection
