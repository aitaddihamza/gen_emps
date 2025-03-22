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
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-indigo-600">
                Gestion des Emplois du Temps
            </a>

            <!-- Navigation -->
            <nav class="space-x-4">
                <a href="{{ route('professeurs.index') }}" class="text-gray-700 hover:text-indigo-600">Professeurs</a>
                <a href="{{ route('salles.index') }}" class="text-gray-700 hover:text-indigo-600">Salles</a>
                <a href="{{ route('classes.index') }}" class="text-gray-700 hover:text-indigo-600">Classes</a>
                <a href="{{ route('modules.index') }}" class="text-gray-700 hover:text-indigo-600">Modules</a>
            </nav>
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
