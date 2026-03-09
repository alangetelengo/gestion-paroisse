"use strict";

const CACHE_NAME = "paroisse-offline-v2";
const OFFLINE_URL = "/offline.html";

// Pages à mettre en cache pour consultation hors ligne (formulaires de saisie)
const PAGES_TO_CACHE = [
    "/",
    "/offline.html",
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(PAGES_TO_CACHE.map(u => new Request(u, { credentials: "same-origin" }))))
            .then(() => self.skipWaiting())
            .catch(() => {})
    );
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

self.addEventListener("fetch", (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Ignorer les requêtes non-GET et les domaines externes
    if (request.method !== "GET" || url.origin !== self.location.origin) {
        return;
    }

    // Ignorer les requêtes API (gérées par le navigateur)
    if (url.pathname.startsWith("/api/")) {
        return;
    }

    if (request.mode === "navigate") {
        event.respondWith(
            fetch(request, { credentials: "same-origin" })
                .then((response) => {
                    const clone = response.clone();
                    if (response.ok && (url.pathname.startsWith("/revenues") || url.pathname.startsWith("/expenses") || url.pathname === "/" || url.pathname === "/dashboard")) {
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, clone).catch(() => {});
                        });
                    }
                    return response;
                })
                .catch(() => {
                    return caches.match(request).then((cached) => cached || caches.match(OFFLINE_URL));
                })
        );
        return;
    }

    // Pour les autres ressources (CSS, JS, images)
    event.respondWith(
        fetch(request)
            .then((response) => response)
            .catch(() => caches.match(request))
    );
});
