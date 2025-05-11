<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion des Emplois du Temps')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #00bcd4;
        }

        body {
            background-color: #f2f4f7;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            min-height: 100vh;
        }

        .ellipse {
            position: absolute;
            border-radius: 50%;
            background-color: #A7DBE8;
            opacity: 0.6;
            z-index: 0;
        }

        .ellipse1 {
            width: 300px;
            height: 300px;
            top: -200px;
            left: -200px;
        }

        .ellipse2 {
            width: 250px;
            height: 250px;
            top: -80px;
            right: -60px;
        }

        .ellipse3 {
            width: 250px;
            height: 250px;
            top: 720px;
            left: -210px;
        }

        .ellipse4 {
            width: 300px;
            height: 300px;
            bottom: -200px;
            right: -95px;
        }

        .sidebar {
            width: 250px;
            background: white;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 20;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            padding-top: 2rem;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.collapsed {
            transform: translateX(-200px);
        }

        .sidebar.collapsed .sidebar-logo {
            display: none;
        }

        .sidebar.collapsed .nav-item span {
            display: none;
        }

        .sidebar.collapsed .nav-item {
            padding: 0.75rem 0.5rem;
            justify-content: center;
        }

        .sidebar.collapsed .nav-item i {
            margin-right: 0;
        }

        .toggle-sidebar {
            position: absolute;
            right: -15px;
            top: 20px;
            background: var(--primary-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 30;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: margin-left 0.3s ease-in-out;
            width: calc(100% - 250px);
            overflow-y: auto;
        }

        .main-content.expanded {
            margin-left: 50px;
            width: calc(100% - 50px);
        }

        .top-header {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .main-container {
            padding: 2rem;
            width: 100%;
            min-height: calc(100vh - 64px); /* Hauteur de l'en-tête */
            overflow-y: auto;
        }

        .content-wrapper {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            height: 100%;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #4a5568;
            text-decoration: none;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .nav-item:hover,
        .nav-item.active {
            background-color: var(--primary-color);
            color: white;
        }

        .nav-item i {
            width: 24px;
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        .sidebar-nav {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* Personnalisation de la barre de défilement */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #00a5bb;
        }
    </style>
</head>

<body class="bg-gray-100 flex">
    <!-- Decorative ellipses -->
    <div class="ellipse ellipse1"></div>
    <div class="ellipse ellipse2"></div>
    <div class="ellipse ellipse3"></div>
    <div class="ellipse ellipse4"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="toggle-sidebar" onclick="toggleSidebar()">
            <i class="fas fa-chevron-left" id="toggle-icon"></i>
        </div>
        <div class="sidebar-logo">
            <a href="{{ route(auth()->user()->role == 'admin' ? 'admin.dashboard' : 'prof.index') }}" class="text-xl font-bold" style="color: var(--primary-color)">
                <img src="{{ asset('images/suptech.png') }}" alt="" width="200" class="block mx-auto">
            </a>
        </div>

        @if (Auth::user()->role == 'admin')
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.timetables') }}" class="nav-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Emplois du temps</span>
                </a>
                <a href="{{ route('professeurs.index') }}" class="nav-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Professeurs</span>
                </a>
                <a href="{{ route('salles.index') }}" class="nav-item">
                    <i class="fas fa-door-open"></i>
                    <span>Salles</span>
                </a>
                <a href="{{ route('classes.index') }}" class="nav-item">
                    <i class="fas fa-users"></i>
                    <span>Classes</span>
                </a>
                <a href="{{ route('modules.index') }}" class="nav-item">
                    <i class="fas fa-book"></i>
                    <span>Modules</span>
                </a>
            </nav>
        @endif
    </aside>

    <div class="main-content" id="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            @php 
                            $userName = Auth::user()->name;
                            $userName = strtoupper($userName[0]) . substr($userName, 1);
                            $prefix = Auth::user()->role == 'prof' ? 'Pr.' : 'M.El ';
                            @endphp 
                            <i class="fas fa-user-circle mr-2"></i>
                            <div>{{ $prefix . $userName }}</div>
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                {{ __('Déconnecter') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </header>

        <div class="main-container">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleIcon = document.getElementById('toggle-icon');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            toggleIcon.classList.toggle('fa-chevron-right');
            toggleIcon.classList.toggle('fa-chevron-left');
        }
    </script>
</body>

</html>
