{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Application de gestion de colocation - Gérez vos dépenses, invitations et soldes facilement">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Coloc'Manager - Gérez votre colocation simplement</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">

    {{-- Navigation --}}
    <nav class="bg-white shadow-lg fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-800">Coloc'Manager</span>
                    </div>
                </div>

                <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                    <a href="#features" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Fonctionnalités</a>
                    <a href="#how-it-works" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Comment ça marche</a>
                    <a href="#pricing" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Tarifs</a>

                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/dashboard') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Connexion</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium">Inscription</a>
                    @endif
                    @endauth
                    @endif
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="mobile-menu hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <a href="#features" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Fonctionnalités</a>
                <a href="#how-it-works" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Comment ça marche</a>
                <a href="#pricing" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Tarifs</a>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <div class="gradient-bg text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center">
                <h1 class="text-4xl tracking-tight font-extrabold sm:text-5xl md:text-6xl">
                    <span class="block">Gérez votre colocation</span>
                    <span class="block text-indigo-200">simplement et efficacement</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-indigo-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Dépenses partagées, calcul automatique des soldes, invitations et bien plus encore.
                    La solution complète pour une colocation sans stress.
                </p>

                @guest
                <div class="mt-10 flex justify-center gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 md:text-lg">
                        Commencer gratuitement
                        <svg class="ml-2 -mr-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <a href="#features" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-800 bg-opacity-60 hover:bg-opacity-70 md:text-lg">
                        En savoir plus
                    </a>
                </div>
                @endguest
            </div>
        </div>
    </div>

    {{-- Features Section --}}
    <div id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Fonctionnalités</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Tout ce dont vous avez besoin
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Une application complète pour gérer votre colocation au quotidien
                </p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                {{-- Feature 1 --}}
                <div class="feature-card transition-all duration-300 bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Gestion des membres</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Invitez vos colocataires par email, gérez les rôles (Owner/Member) et suivez leur réputation.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="feature-card transition-all duration-300 bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Suivi des dépenses</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Ajoutez des dépenses par catégorie, visualisez l'historique et filtrez par mois.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="feature-card transition-all duration-300 bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Calcul automatique</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Soldes individuels, vue "qui doit à qui" et système de paiement simplifié.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="feature-card transition-all duration-300 bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Système de réputation</h3>
                    <p class="mt-2 text-base text-gray-500">
                        +1 pour les départs sans dette, -1 pour les dettes. Encourage les bons comportements.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="feature-card transition-all duration-300 bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Statistiques détaillées</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Visualisez vos dépenses par catégorie et par mois. Dashboard admin global.
                    </p>
                </div>

                {{-- Feature 6 --}}
                <div class="feature-card transition-all duration-300 bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Invitations par email</h3>
                    <p class="mt-2 text-base text-gray-500">
                        Envoyez des invitations avec token unique. Acceptation ou refus facile.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- How It Works --}}
    <div id="how-it-works" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Comment ça marche</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Simple en 3 étapes
                </p>
            </div>

            <div class="mt-16">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-between">
                        <div class="text-center">
                            <span class="h-12 w-12 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xl font-bold mx-auto">1</span>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Inscrivez-vous</h3>
                            <p class="mt-2 text-sm text-gray-500">Créez votre compte gratuitement</p>
                        </div>
                        <div class="text-center">
                            <span class="h-12 w-12 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xl font-bold mx-auto">2</span>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Créez ou rejoignez</h3>
                            <p class="mt-2 text-sm text-gray-500">Créez une colocation ou acceptez une invitation</p>
                        </div>
                        <div class="text-center">
                            <span class="h-12 w-12 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xl font-bold mx-auto">3</span>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Gérez sereinement</h3>
                            <p class="mt-2 text-sm text-gray-500">Ajoutez vos dépenses et suivez les soldes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pricing Section --}}
    <div id="pricing" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Tarifs</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Gratuit, tout simplement
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Pas de frais cachés. Toutes les fonctionnalités sont gratuites.
                </p>
            </div>

            <div class="mt-16 max-w-lg mx-auto">
                <div class="bg-gray-50 rounded-lg shadow-lg overflow-hidden">
                    <div class="px-6 py-8">
                        <h3 class="text-2xl font-bold text-center text-gray-900">Gratuit</h3>
                        <p class="mt-4 text-center text-gray-500">Pour toutes les colocations</p>
                        <p class="mt-8 text-center">
                            <span class="text-5xl font-extrabold text-gray-900">0€</span>
                            <span class="text-xl text-gray-500">/mois</span>
                        </p>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-3 text-gray-700">Gestion des membres illimitée</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-3 text-gray-700">Dépenses illimitées</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-3 text-gray-700">Calcul automatique des soldes</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-3 text-gray-700">Support prioritaire</span>
                            </li>
                        </ul>
                    </div>
                    @guest
                    <div class="px-6 py-4 bg-gray-100">
                        <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Commencer maintenant
                        </a>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    {{-- CTA Section --}}
    @guest
    <div class="gradient-bg">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Prêt à simplifier votre colocation ?</span>
                <span class="block text-indigo-200">Inscrivez-vous gratuitement.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                        S'inscrire
                    </a>
                </div>
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-800 bg-opacity-60 hover:bg-opacity-70">
                        Se connecter
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endguest

    {{-- Footer --}}
    <footer class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Produit</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#features" class="text-base text-gray-500 hover:text-gray-900">Fonctionnalités</a></li>
                        <li><a href="#pricing" class="text-base text-gray-500 hover:text-gray-900">Tarifs</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Support</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-500 hover:text-gray-900">Documentation</a></li>
                        <li><a href="#" class="text-base text-gray-500 hover:text-gray-900">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Légal</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-500 hover:text-gray-900">Confidentialité</a></li>
                        <li><a href="#" class="text-base text-gray-500 hover:text-gray-900">CGU</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Social</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-500 hover:text-gray-900">Twitter</a></li>
                        <li><a href="#" class="text-base text-gray-500 hover:text-gray-900">GitHub</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <p class="text-base text-gray-400 text-center">
                    &copy; {{ date('Y') }} Coloc'Manager. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>

    {{-- Mobile menu script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.querySelector('.mobile-menu-button');
            const menu = document.querySelector('.mobile-menu');

            if (btn && menu) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>

</html>