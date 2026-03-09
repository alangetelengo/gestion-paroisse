/**
 * Module de synchronisation offline pour recettes et dépenses.
 * Stocke les données en IndexedDB et les synchronise quand la connexion revient.
 */
(function () {
    "use strict";

    const DB_NAME = "paroisse-offline";
    const DB_VERSION = 1;
    const STORE_NAME = "sync_queue";
    const SYNC_URL = "/api/sync";

    let db = null;

    function openDB() {
        return new Promise((resolve, reject) => {
            if (db) {
                resolve(db);
                return;
            }
            const req = indexedDB.open(DB_NAME, DB_VERSION);
            req.onerror = () => reject(req.error);
            req.onsuccess = () => {
                db = req.result;
                resolve(db);
            };
            req.onupgradeneeded = (e) => {
                const store = e.target.result.createObjectStore(STORE_NAME, { keyPath: "id", autoIncrement: true });
                store.createIndex("type", "type", { unique: false });
                store.createIndex("created", "createdAt", { unique: false });
            };
        });
    }

    function addToQueue(type, data) {
        return openDB().then((database) => {
            return new Promise((resolve, reject) => {
                const tx = database.transaction(STORE_NAME, "readwrite");
                const store = tx.objectStore(STORE_NAME);
                const item = {
                    type: type,
                    data: data,
                    createdAt: new Date().toISOString(),
                    synced: false
                };
                const req = store.add(item);
                req.onsuccess = () => resolve(req.result);
                req.onerror = () => reject(req.error);
            });
        });
    }

    function getQueue() {
        return openDB().then((database) => {
            return new Promise((resolve, reject) => {
                const tx = database.transaction(STORE_NAME, "readonly");
                const store = tx.objectStore(STORE_NAME);
                const req = store.getAll();
                req.onsuccess = () => resolve(req.result.filter((i) => !i.synced));
                req.onerror = () => reject(req.error);
            });
        });
    }

    function getPendingCount() {
        return getQueue().then((items) => items.length);
    }

    function removeFromQueue(ids) {
        if (!ids || ids.length === 0) return Promise.resolve();
        return openDB().then((database) => {
            return new Promise((resolve, reject) => {
                const tx = database.transaction(STORE_NAME, "readwrite");
                const store = tx.objectStore(STORE_NAME);
                ids.forEach((id) => store.delete(id));
                tx.oncomplete = () => resolve();
                tx.onerror = () => reject(tx.error);
            });
        });
    }

    function markAsSynced(ids) {
        if (!ids || ids.length === 0) return Promise.resolve();
        return openDB().then((database) => {
            return new Promise((resolve, reject) => {
                const tx = database.transaction(STORE_NAME, "readwrite");
                const store = tx.objectStore(STORE_NAME);
                const getReq = store.getAll();
                getReq.onsuccess = () => {
                    const all = getReq.result;
                    all.forEach((item) => {
                        if (ids.includes(item.id)) {
                            item.synced = true;
                            store.put(item);
                        }
                    });
                    resolve();
                };
                getReq.onerror = () => reject(getReq.error);
            });
        });
    }

    function parseMontant(val) {
        if (val == null || val === "") return null;
        const s = String(val).replace(/\s/g, "").replace(",", ".").replace(/\./g, "");
        const n = parseFloat(s);
        return isNaN(n) ? null : n;
    }

    function collectRevenueFormData(form) {
        const fd = new FormData(form);
        const get = (n) => fd.get(n) || "";
        const montant = parseMontant(get("montant"));
        if (montant == null || montant < 0) return null;

        const data = {
            paroisse_id: get("paroisse_id") || null,
            revenue_category_id: get("revenue_category_id"),
            revenue_type_id: get("revenue_type_id"),
            date_recette: get("date_recette"),
            montant: montant,
            methode_paiement: get("methode_paiement") || "especes",
            reference_paiement: get("reference_paiement") || null,
            notes: get("notes") || null,
            donateur_nom: get("donateur_nom") || null,
            donateur_telephone: get("donateur_telephone") ? "242" + String(get("donateur_telephone")).replace(/\D/g, "") : null,
            jour_semaine: get("jour_semaine") || null,
            periode_messe: get("jour_semaine") === "dimanche" ? "dimanche" : (get("jour_semaine") ? "semaine" : null),
            mois_location: get("mois_location") || null,
            _temp_id: "rev-" + Date.now()
        };

        if (!data.revenue_category_id || !data.revenue_type_id || !data.date_recette) return null;
        return data;
    }

    function collectExpenseFormData(form) {
        const fd = new FormData(form);
        const get = (n) => fd.get(n) || "";
        const montant = parseMontant(get("montant"));
        if (montant == null || montant < 0) return null;

        const categorie = get("categorie_charge") || "charge_fixe";
        const dateDep = get("date_depense");
        let jourSemaine = get("jour_semaine");
        if (!jourSemaine && dateDep) {
            const d = new Date(dateDep);
            const jours = ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"];
            jourSemaine = jours[d.getDay()];
        }

        const data = {
            paroisse_id: get("paroisse_id") || null,
            categorie_charge: categorie,
            type_charge: categorie === "alimentation_popote" ? "alimentation" : (get("type_charge") || "autre"),
            date_depense: dateDep,
            montant: montant,
            jour_semaine: jourSemaine || null,
            libelle: get("libelle") || null,
            facture_reference: get("facture_reference") || null,
            fournisseur: get("fournisseur") || null,
            methode_paiement: get("methode_paiement") || "especes",
            notes: get("notes") || null,
            _temp_id: "exp-" + Date.now()
        };

        if (!data.date_depense) return null;
        return data;
    }

    function syncToServer() {
        return getQueue().then((items) => {
            if (items.length === 0) return;

            const revenues = [];
            const expenses = [];
            const ids = [];

            items.forEach((item) => {
                ids.push(item.id);
                if (item.type === "revenue") {
                    revenues.push({ action: "create", data: item.data });
                } else if (item.type === "expense") {
                    expenses.push({ action: "create", data: item.data });
                }
            });

            const payload = { revenues, expenses };
            const csrf = document.querySelector('meta[name="csrf-token"]');
            const token = csrf ? csrf.getAttribute("content") : "";

            return fetch(SYNC_URL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": token,
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(payload),
                credentials: "same-origin"
            }).then((res) => {
                if (!res.ok) throw new Error("Sync failed: " + res.status);
                return res.json();
            }).then((json) => {
                if (json.success) {
                    return markAsSynced(ids).then(() => {
                        if (typeof toastr !== "undefined") {
                            toastr.success("Synchronisation terminée : " + (revenues.length + expenses.length) + " élément(s) enregistré(s).");
                        }
                        updatePendingUI();
                    });
                }
                throw new Error(json.message || "Erreur de synchronisation");
            }).catch((err) => {
                if (typeof toastr !== "undefined") {
                    toastr.error("Échec de la synchronisation. Réessayez plus tard.");
                }
                console.warn("Offline sync error:", err);
            });
        });
    }

    function updatePendingUI() {
        getPendingCount().then((count) => {
            const el = document.getElementById("offline-pending-count");
            const textEl = document.getElementById("offline-pending-text");
            const banner = document.getElementById("offline-pending-banner");
            if (el) el.textContent = count;
            if (textEl) textEl.textContent = count;
            if (banner) banner.style.display = (count > 0 && navigator.onLine) ? "" : "none";
        });
    }

    function init() {
        if (!window.indexedDB) return;

        const revenueForms = document.querySelectorAll('form[data-offline-sync="revenue"]');
        const expenseForms = document.querySelectorAll('form[data-offline-sync="expense"]');

        function handleSubmit(e, type, collectFn) {
            if (navigator.onLine) return;

            e.preventDefault();
            const form = e.target;
            const data = collectFn(form);
            if (!data) {
                if (typeof toastr !== "undefined") {
                    toastr.error("Veuillez remplir tous les champs obligatoires.");
                }
                return false;
            }

            addToQueue(type, data).then(() => {
                if (typeof toastr !== "undefined") {
                    toastr.success("Enregistré pour synchronisation lorsque la connexion sera rétablie.");
                }
                form.reset();
                updatePendingUI();
            }).catch(() => {
                if (typeof toastr !== "undefined") {
                    toastr.error("Impossible d'enregistrer en local.");
                }
            });
            return false;
        }

        revenueForms.forEach((form) => {
            form.addEventListener("submit", (e) => handleSubmit(e, "revenue", collectRevenueFormData), true);
        });
        expenseForms.forEach((form) => {
            form.addEventListener("submit", (e) => handleSubmit(e, "expense", collectExpenseFormData), true);
        });

        window.addEventListener("online", () => {
            syncToServer();
            document.querySelectorAll("#offline-create-notice").forEach((el) => { el.style.display = "none"; });
        });

        window.addEventListener("offline", () => {
            document.querySelectorAll("#offline-create-notice").forEach((el) => { el.style.display = ""; });
        });

        if (!navigator.onLine) {
            document.querySelectorAll("#offline-create-notice").forEach((el) => { el.style.display = ""; });
        }

        updatePendingUI();
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }

    window.OfflineSync = {
        getPendingCount: getPendingCount,
        syncNow: syncToServer,
        updateUI: updatePendingUI
    };
})();
