<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <meta name="description" content="Système de gestion de paroisse catholique" />

    <title>@yield('title', config('app.name', 'Paroisse'))</title>

    @PwaHead

    @php
        $paroisseId = auth()->check() ? (auth()->user()->paroisse_id ?? null) : null;
        $favicon = \App\Helpers\ParoisseConfig::get($paroisseId, 'logo_path', '/images/logo-paroisse.svg');
    @endphp
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(ltrim($favicon, '/')) }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        :root {
            {!! \App\Helpers\ParoisseConfig::getCssVariables() !!}
        }
    </style>

    @stack('head-scripts')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @stack('styles')
</head>
<body class="font-sans antialiased min-h-screen bg-slate-100 dark:bg-slate-950">
    <div id="main-wrapper" style="display: flex; flex-direction: column; min-height: 100vh;">
        @include('partials.preload')
        @include('partials.nav-header')
        @include('partials.header')
        @include('partials.sidebar')

        <div id="sidebar-overlay" class="sidebar-overlay" aria-hidden="true"></div>

        <div id="mainContent" class="main-content flex-1 flex flex-col transition-all duration-300 adventiste-content-canvas" style="margin-top: 80px;">
            @include('partials.offline-banners')

            @isset($header)
                <header class="adventiste-page-header-shell relative">
                    <div class="adventiste-page-header-accent" aria-hidden="true"></div>
                    <div class="relative max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @else
                @hasSection('page-title')
                <header class="@yield('page-header-class', 'adventiste-page-header-shell relative')">
                    <div class="adventiste-page-header-accent" aria-hidden="true"></div>
                    <div class="relative @yield('content-container-class', 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8') py-6 sm:py-8 flex flex-wrap items-start sm:items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h1 class="@yield('page-title-class', 'text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white')">@yield('page-title')</h1>
                            @hasSection('page-title-info')<div class="@yield('page-title-info-class', 'mt-2 text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed max-w-3xl')">@yield('page-title-info')</div>@endif
                            @hasSection('breadcrumb')
                            <nav aria-label="breadcrumb" class="mt-3">
                                <ol class="breadcrumb flex flex-wrap gap-1 text-sm text-slate-600 dark:text-slate-400 mb-0 list-none p-0">@yield('breadcrumb')</ol>
                            </nav>
                            @endif
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            @yield('btn-create')
                        </div>
                    </div>
                </header>
                @endif
            @endisset

            <main class="py-6 sm:py-8">
                <div class="@yield('content-container-class', 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8')">
                    @include('partials.flash-messages')
                    @hasSection('page-aide')
                        @yield('page-aide')
                    @endif
                    @hasSection('content')
                        @yield('content')
                    @endif
                </div>
            </main>
        </div>

        @include('partials.footer')
    </div>

    @stack('body-modals')
    @include('partials.flash-alert-modal')
    @stack('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @if(session('flash_alert'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const flash = @json(session('flash_alert'));
                const typeMap = { success: 'success', error: 'error', info: 'info', warning: 'warning' };
                if (typeof toastr !== 'undefined') {
                    toastr[typeMap[flash.type] || 'info'](flash.message, flash.title, {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 5000,
                        extendedTimeOut: 1000
                    });
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[data-transform], textarea[data-transform]').forEach(function (el) {
                var type = el.getAttribute('data-transform');
                var applyTransform = function () {
                    var value = el.value;
                    if (!value) return;
                    if (type === 'upper') {
                        el.value = value.toLocaleUpperCase('fr-FR');
                    } else if (type === 'lower') {
                        el.value = value.toLocaleLowerCase('fr-FR');
                    } else if (type === 'title') {
                        var lower = value.toLocaleLowerCase('fr-FR');
                        el.value = lower.replace(/([\p{L}\p{N}]+(?:'[\p{L}\p{N}]+)?)/gu, function (word) {
                            return word.charAt(0).toLocaleUpperCase('fr-FR') + word.slice(1);
                        });
                    }
                };
                el.addEventListener('blur', applyTransform);
            });
            document.querySelectorAll('input[data-input="phone"]').forEach(function (el) {
                el.addEventListener('input', function () {
                    var value = el.value;
                    if (!value) return;
                    var hasPlus = value.trim().charAt(0) === '+';
                    value = value.replace(/[^\d]/g, '');
                    if (hasPlus) value = '+' + value;
                    el.value = value;
                });
            });
            document.querySelectorAll('[data-check-toggle]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var targetSelector = btn.getAttribute('data-check-toggle-target');
                    if (!targetSelector) return;
                    var container = document.querySelector(targetSelector);
                    if (!container) return;
                    container.querySelectorAll('input[type="checkbox"]').forEach(function (cb) { cb.checked = true; });
                });
            });
            document.querySelectorAll('[data-uncheck-toggle]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var targetSelector = btn.getAttribute('data-check-toggle-target');
                    if (!targetSelector) return;
                    var container = document.querySelector(targetSelector);
                    if (!container) return;
                    container.querySelectorAll('input[type="checkbox"]').forEach(function (cb) { cb.checked = false; });
                });
            });
        });
    </script>

    @if (file_exists(public_path('sw.js')))
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js', { scope: '/' }).then(function () {}).catch(function () {});
            });
        }
    </script>
    @endif

    @auth
    @if(file_exists(public_path('js/offline-sync.js')))
    <script src="{{ asset('js/offline-sync.js') }}"></script>
    <script>
        (function() {
            function updateOfflineBanner() {
                var banner = document.getElementById('offline-banner');
                if (banner) banner.style.display = navigator.onLine ? 'none' : 'block';
            }
            window.addEventListener('online', updateOfflineBanner);
            window.addEventListener('offline', updateOfflineBanner);
            updateOfflineBanner();
        })();
    </script>
    @endif
    @endauth

    <style>
        .form-submit-spinner {
            display: inline-block;
            width: 1em;
            height: 1em;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 9999px;
            animation: form-submit-spin 0.6s linear infinite;
            vertical-align: -0.2em;
            margin-right: 0.35rem;
        }
        @keyframes form-submit-spin { to { transform: rotate(360deg); } }
        .form-submit-loading { opacity: 0.75; cursor: not-allowed; }
    </style>
    <script>
        (function () {
            document.addEventListener('submit', function (event) {
                var form = event.target;
                if (!(form instanceof HTMLFormElement)) return;
                if (form.dataset.skipSubmitLoading === '1') return;
                var submitter = event.submitter;
                var btn = submitter instanceof HTMLButtonElement || submitter instanceof HTMLInputElement
                    ? submitter
                    : form.querySelector('button[type="submit"]:not([disabled]), input[type="submit"]:not([disabled])');
                if (!btn) return;
                if (btn.dataset.loading === '1') return;
                btn.dataset.loading = '1';
                if (!btn.dataset.originalHtml && btn instanceof HTMLButtonElement) {
                    btn.dataset.originalHtml = btn.innerHTML;
                }
                var loadingText = btn.dataset.loadingText || form.dataset.loadingText || 'Chargement...';
                if (btn instanceof HTMLButtonElement) {
                    btn.innerHTML = '<span class="form-submit-spinner"></span> ' + loadingText;
                } else {
                    btn.value = loadingText;
                }
                btn.disabled = true;
                btn.setAttribute('aria-busy', 'true');
                btn.classList.add('form-submit-loading');
            }, true);
        })();
    </script>
</body>
</html>
