<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="paroisse, gestion, église catholique" />
    <meta name="author" content="Paroisse" />
    <meta name="robots" content="index, follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Système de gestion de paroisse catholique" />
    <meta name="format-detection" content="telephone=no">
    <title>@yield('title', 'Gestion de Paroisse') - {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('tpl/images/favicon.png') }}">

    <!-- Styles du template -->
    <link rel="stylesheet" href="{{ asset('tpl/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('tpl/css/style.css') }}">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Styles personnalisés de l'application -->
    <style>
        :root {
            {!! \App\Helpers\ParoisseConfig::getCssVariables() !!}
        }
    </style>
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body>
    @php
        $paroisseId = auth()->check() ? (auth()->user()->paroisse_id ?? null) : null;
        $loaderActif = \App\Helpers\ParoisseConfig::get($paroisseId, 'loader_actif', true);
        $loaderDureeMin = (int) \App\Helpers\ParoisseConfig::get($paroisseId, 'loader_duree_min', 10);
        $loaderAfficherLogo = \App\Helpers\ParoisseConfig::get($paroisseId, 'loader_afficher_logo', true);
        $loaderStyle = \App\Helpers\ParoisseConfig::get($paroisseId, 'loader_style', 'logo_spinner');
        $loaderLogoPath = \App\Helpers\ParoisseConfig::get($paroisseId, 'logo_path', '/images/logo-paroisse.svg');
    @endphp
    <!--*******************
        Preloader start (paramétrable : Configuration > Loader)
    ********************-->
    <div id="preloader"
         class="page-loader loader-style-{{ $loaderStyle }} {{ $loaderActif ? '' : 'loader-disabled' }}"
         data-loader-actif="{{ $loaderActif ? '1' : '0' }}"
         data-loader-duree-min="{{ $loaderDureeMin }}"
         style="background: var(--loader-bg, #003366); color: var(--loader-text, #fff);">
        <div class="page-loader-inner">
            @if($loaderAfficherLogo && $loaderLogoPath)
                <img src="{{ asset(ltrim($loaderLogoPath, '/')) }}" alt="Logo" class="page-loader-logo" width="120" height="120">
            @endif
            @if($loaderStyle !== 'logo_centre')
                <div class="sk-three-bounce">
                    <div class="sk-child sk-bounce1"></div>
                    <div class="sk-child sk-bounce2"></div>
                    <div class="sk-child sk-bounce3"></div>
                </div>
            @endif
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{ route('dashboard') }}" class="brand-logo">
                <img src="{{ asset(\App\Helpers\ParoisseConfig::get(null, 'logo_path', '/images/logo-paroisse.svg')) }}" alt="Logo" class="logo-abbr" style="width: 48px; height: 48px;">
                <span class="brand-title" style="color: white; font-size: 18px; font-weight: 600; margin-left: 10px;">
                    {{ \App\Helpers\ParoisseConfig::get(null, 'titre_paroisse', 'Paroisse') }}
                </span>
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                <span style="color: var(--primary); font-weight: 600;">
                                    {{ \App\Helpers\ParoisseConfig::get(null, 'nom_paroisse', 'Tableau de bord') }}
                                </span>
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown notification_dropdown">
                                {{-- Recherche globale : à réactiver quand une vraie recherche sera implémentée --}}
                            </li>
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell bell-link primary" href="#">
                                    <svg width="22" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20.4604 0.848846H3.31682C2.64742 0.849582 2.00565 1.11583 1.53231 1.58916C1.05897 2.0625 0.792727 2.70427 0.791992 3.37367V15.1562C0.792727 15.8256 1.05897 16.4674 1.53231 16.9407C2.00565 17.414 2.64742 17.6803 3.31682 17.681C3.53999 17.6812 3.75398 17.7699 3.91178 17.9277C4.06958 18.0855 4.15829 18.2995 4.15843 18.5226V20.3168C4.15843 20.6214 4.24112 20.9204 4.39768 21.1817C4.55423 21.4431 4.77879 21.6571 5.04741 21.8008C5.31602 21.9446 5.61861 22.0127 5.92292 21.998C6.22723 21.9833 6.52183 21.8863 6.77533 21.7173L12.6173 17.8224C12.7554 17.7299 12.9179 17.6807 13.0841 17.681H17.187C17.7383 17.68 18.2742 17.4993 18.7136 17.1664C19.1531 16.8334 19.472 16.3664 19.6222 15.8359L22.8965 4.05007C22.9998 3.67478 23.0152 3.28071 22.9413 2.89853C22.8674 2.51634 22.7064 2.15636 22.4707 1.8466C22.2349 1.53684 21.9309 1.28565 21.5822 1.1126C21.2336 0.93954 20.8497 0.849282 20.4604 0.848846ZM21.2732 3.60301L18.0005 15.3847C17.9499 15.5614 17.8432 15.7168 17.6964 15.8274C17.5496 15.938 17.3708 15.9979 17.187 15.9978H13.0841C12.5855 15.9972 12.098 16.1448 11.6836 16.4219L5.84165 20.3168V18.5226C5.84091 17.8532 5.57467 17.2115 5.10133 16.7381C4.62799 16.2648 3.98622 15.9985 3.31682 15.9978C3.09365 15.9977 2.87966 15.909 2.72186 15.7512C2.56406 15.5934 2.47534 15.3794 2.47521 15.1562V3.37367C2.47534 3.15051 2.56406 2.93652 2.72186 2.77871C2.87966 2.62091 3.09365 2.5322 3.31682 2.53206H20.4604C20.5905 2.53239 20.7187 2.56274 20.8352 2.62073C20.9516 2.67872 21.0531 2.7628 21.1318 2.86643C21.2104 2.97005 21.2641 3.09042 21.2886 3.21818C21.3132 3.34594 21.3079 3.47763 21.2732 3.60301Z" fill="#000"></path>
                                    </svg>
                                    <div class="pulse-css"></div>
                                </a>
                            </li>
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">
                                    @php
                                        $u = auth()->user();
                                        $initials = collect(explode(' ', trim($u->name ?? 'U')))->filter()->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="me-2 d-none d-md-block text-end">
                                            <div style="font-weight: 600; line-height: 1;">{{ $u->name }}</div>
                                            <small class="text-muted">
                                                {{ $u->email ?? '—' }}
                                                @if($u->paroisse?->nom)
                                                    · {{ $u->paroisse->nom }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: var(--rgba-primary-1); color: var(--primary); font-weight: 700;">
                                            {{ $initials ?: 'U' }}
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('profile.edit') }}" class="dropdown-item ai-icon">
                                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        <span class="ms-2">Mon profil</span>
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item ai-icon border-0 bg-transparent w-100 text-start">
                                            <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                            <span class="ms-2">Déconnexion</span>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="deznav">
            <div class="deznav-scroll">
                @include('layouts.menu')
                <div class="copyright">
                    <p>Gestion de Paroisse<br/>© {{ date('Y') }} Tous droits réservés</p>
                    <p class="op5">Fait avec <span class="heart"></span> pour l'Église</p>
                </div>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © {{ date('Y') }} - Gestion de Paroisse</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('tpl/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('tpl/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('tpl/js/custom.min.js') }}"></script>
    <script src="{{ asset('tpl/js/deznav-init.js') }}"></script>

    <!-- Script du loader : durée min (ex. 10s) + logo, puis masquage -->
    <script>
        (function() {
            var preloader = document.getElementById('preloader');
            var mainWrapper = document.getElementById('main-wrapper');
            if (!preloader || !mainWrapper) return;

            var actif = preloader.getAttribute('data-loader-actif') === '1';
            var dureeMinSec = parseInt(preloader.getAttribute('data-loader-duree-min'), 10) || 10;
            var dureeMinMs = Math.max(1000, dureeMinSec * 1000);
            var start = Date.now();

            function hidePreloader() {
                if (typeof jQuery !== 'undefined') {
                    jQuery(preloader).fadeOut(500);
                    jQuery(mainWrapper).addClass('show');
                } else {
                    preloader.style.opacity = '0';
                    preloader.style.visibility = 'hidden';
                    preloader.style.transition = 'opacity 0.5s ease, visibility 0.5s ease';
                    mainWrapper.classList.add('show');
                    setTimeout(function() { preloader.style.display = 'none'; }, 500);
                }
            }

            if (!actif) {
                preloader.style.display = 'none';
                document.addEventListener('DOMContentLoaded', function() {
                    mainWrapper.classList.add('show');
                });
                return;
            }

            window.addEventListener('load', function() {
                var elapsed = Date.now() - start;
                var remaining = Math.max(0, dureeMinMs - elapsed);
                setTimeout(hidePreloader, remaining);
            });
        })();
    </script>

    <!-- Script pour garantir le fonctionnement du hamburger -->
    <script>
        (function() {
            function toggleMenu() {
                const mainWrapper = document.querySelector('#main-wrapper');
                const hamburger = document.querySelector('.hamburger');
                if (mainWrapper) {
                    mainWrapper.classList.toggle('menu-toggle');
                    if (hamburger) {
                        hamburger.classList.toggle('is-active');
                    }
                }
            }

            // Utiliser la délégation d'événement au niveau du document pour garantir le fonctionnement
            document.addEventListener('click', function(e) {
                if (e.target.closest('.nav-control')) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMenu();
                }
            }, true);

            // Aussi avec jQuery pour compatibilité
            if (typeof jQuery !== 'undefined') {
                jQuery(document).ready(function($) {
                    // Délégation d'événement jQuery
                    $(document).off('click', '.nav-control').on('click', '.nav-control', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleMenu();
                    });

                    // Aussi directement sur l'élément
                    setTimeout(function() {
                        $(".nav-control").off('click').on('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            toggleMenu();
                        });
                    }, 500);
                });
            }
        })();
    </script>

    <!-- Script global pour interactions des formulaires (transformations, téléphone, etc.) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Transformations de texte selon le type de champ
            document.querySelectorAll('input[data-transform], textarea[data-transform]').forEach(function (el) {
                var type = el.getAttribute('data-transform');

                var applyTransform = function () {
                    var value = el.value;
                    if (!value) {
                        return;
                    }

                    if (type === 'upper') {
                        el.value = value.toLocaleUpperCase('fr-FR');
                    } else if (type === 'lower') {
                        el.value = value.toLocaleLowerCase('fr-FR');
                    } else if (type === 'title') {
                        var lower = value.toLocaleLowerCase('fr-FR');
                        el.value = lower.replace(/([\p{L}\p{N}]+(?:'[\\p{L}\p{N}]+)?)/gu, function (word) {
                            return word.charAt(0).toLocaleUpperCase('fr-FR') + word.slice(1);
                        });
                    }
                };

                // On applique à la sortie du champ (blur) pour laisser l'utilisateur taper normalement
                el.addEventListener('blur', applyTransform);
            });

            // Normalisation simple des téléphones côté client
            document.querySelectorAll('input[data-input="phone"]').forEach(function (el) {
                el.addEventListener('input', function () {
                    var value = el.value;
                    if (!value) {
                        return;
                    }

                    var hasPlus = value.trim().charAt(0) === '+';
                    // On enlève tout sauf chiffres
                    value = value.replace(/[^\d]/g, '');
                    if (hasPlus) {
                        value = '+' + value;
                    }
                    el.value = value;
                });
            });

            // Boutons Tout cocher / Tout décocher
            document.querySelectorAll('[data-check-toggle]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var targetSelector = btn.getAttribute('data-check-toggle-target');
                    if (!targetSelector) return;
                    var container = document.querySelector(targetSelector);
                    if (!container) return;
                    container.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
                        cb.checked = true;
                    });
                });
            });

            document.querySelectorAll('[data-uncheck-toggle]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var targetSelector = btn.getAttribute('data-check-toggle-target');
                    if (!targetSelector) return;
                    var container = document.querySelector(targetSelector);
                    if (!container) return;
                    container.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
                        cb.checked = false;
                    });
                });
            });
        });
    </script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Scripts de l'application -->
    @vite(['resources/js/app.js'])
    @stack('scripts')

    <!-- FlashAlert -->
    @if(session('flash_alert'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const flash = @json(session('flash_alert'));
                const typeMap = {
                    'success': 'success',
                    'error': 'error',
                    'info': 'info',
                    'warning': 'warning'
                };
                toastr[typeMap[flash.type] || 'info'](flash.message, flash.title, {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000,
                    extendedTimeOut: 1000
                });
            });
        </script>
    @endif
</body>
</html>
