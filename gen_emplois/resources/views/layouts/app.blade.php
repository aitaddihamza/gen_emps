<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion des Emplois du Temps')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <!-- Logo ou lien vers le Dashboard -->
            <a href="{{ route(auth()->user()->role == 'admin' ? 'admin.dashboard' : 'prof.index') }}" class="text-xl font-bold text-indigo-600">
                Gestion des Emplois du Temps
            </a>

            <!-- Navigation -->
            <!-- check the user role   -->
            @if (Auth::user()->role == 'admin')
                <nav class="space-x-4">
                    <a href="{{ route('professeurs.index') }}"
                        class="text-gray-700 hover:text-indigo-600">Professeurs</a>
                    <a href="{{ route('salles.index') }}" class="text-gray-700 hover:text-indigo-600">Salles</a>
                    <a href="{{ route('classes.index') }}" class="text-gray-700 hover:text-indigo-600">Classes</a>
                    <a href="{{ route('modules.index') }}" class="text-gray-700 hover:text-indigo-600">Modules</a>
                </nav>
            @endif

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500   hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            @php 
                            $userName = Auth::user()->name;
                            $userName = strtoupper($userName[0]) . substr($userName, 1);
                            $prefix =  Auth::user()->role == 'prof' ? 'Pr.' : 'M.El ';
                            @endphp 
                            <div>{{ $prefix . $userName }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Déconnecter') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

        </div>
    </header>

    <!-- Contenu principal -->
    <main class="container mx-auto px-4 py-8 flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-md mt-8">
        <div class="container mx-auto px-4 py-4 text-center text-gray-600">
            &copy; {{ date('Y') }} Gestion des Emplois du Temps. Tous droits réservés.
        </div>
    </footer>
</body>

</html>
