import './bootstrap';

// Initialisation du template
document.addEventListener('DOMContentLoaded', function() {
    // Preloader
    const preloader = document.getElementById('preloader');
    if (preloader) {
        setTimeout(() => {
            preloader.style.display = 'none';
            document.getElementById('main-wrapper')?.classList.add('show');
        }, 500);
    }

    // Navigation toggle
    const navControl = document.querySelector('.nav-control');
    const hamburger = document.querySelector('.hamburger');
    const mainWrapper = document.getElementById('main-wrapper');

    if (navControl && hamburger && mainWrapper) {
        navControl.addEventListener('click', function() {
            mainWrapper.classList.toggle('menu-toggle');
            hamburger.classList.toggle('is-active');
        });
    }

    // MetisMenu initialization (si disponible)
    if (typeof window.metisMenu !== 'undefined' && document.getElementById('menu')) {
        new window.metisMenu('#menu');
    }
});
