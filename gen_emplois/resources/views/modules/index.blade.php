<!-- resources/views/modules/index.blade.php -->
<!-- resources/views/modules/index.blade.php -->

@extends('layouts.app')

@section('content')
    <style>
        .action-button {
            background-color: var(--primary-color);
            color: white;
        }
        .action-button:hover {
            background-color: #00a5bb;
        }
        .action-link {
            color: var(--primary-color);
        }
        .action-link:hover {
            color: #00a5bb;
        }
    </style>

    <div class="container mx-auto px-4 mt-8">
        @include('shared.flush')
        <h1 class="text-2xl font-bold mb-4">Liste des Modules</h1>

        <!-- Bouton pour ajouter un nouveau module -->
        <a href="{{ route('modules.create') }}"
            class="action-button px-4 py-2 rounded-md mb-8 inline-block">
            Ajouter un Module
        </a>

        <!-- Tableau des modules -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full">
                <!-- En-tête du tableau -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volume
                            Horaire
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Séances de
                            TP
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Professeurs Affectés
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            classes Affectés
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>

                <!-- Corps du tableau -->
                <tbody class="divide-y divide-gray-200">
                    @foreach ($modules as $module)
                        <tr>
                            <!-- Nom du module -->
                            <td class="px-6 py-4 text-indigo-600 hover:text-indigo-900">
                                {{ $module->nom }}
                            </td>

                            <!-- Volume Horaire -->
                            <td class="px-6 py-4">
                                {{ $module->volume_horaire }} heures
                            </td>

                            <!-- Séances de TP -->
                            <td class="px-6 py-4">
                                {{ $module->tp_seances }} séances
                            </td>

                            <!-- Professeurs Affectés -->
                            <td class="px-6 py-4">
                                @if ($module->professeurs->count() > 0)
                                    <ul class="list-disc list-inside">
                                        @foreach ($module->professeurs as $professeur)
                                            <li>{{ $professeur->nom }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-500">Aucun professeur affecté</span>
                                @endif
                            </td>
                            <!-- Classes Affectés -->
                            <td class="px-6 py-4">
                                @if ($module->classes->count() > 0)
                                    <ul class="list-disc list-inside">
                                        @foreach ($module->classes as $classe)
                                            <li>{{ $classe->nom }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-500">Aucun classe affecté</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <!-- Bouton Modifier -->
                                <a href="{{ route('modules.edit', $module) }}"
                                    class="action-link mr-2">
                                    Modifier
                                </a>

                                <!-- Bouton Supprimer -->
                                <form action="{{ route('modules.destroy', $module) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce module ?')">
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
            {{ $modules->links() }}
        </div>
    </div>
@endsection
