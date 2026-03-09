{{-- Modal Aide formulaire Catégorie de recettes --}}
<div class="modal fade" id="revenueCategoryHelpModal" tabindex="-1" aria-labelledby="revenueCategoryHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="revenueCategoryHelpModalLabel">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Aide : catégorie de recettes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><strong>Code</strong> — Identifiant technique unique (ex. quete_ordinaire, location, dons). Sert au tri et aux rapports.</li>
                    <li class="mb-2"><strong>Nom</strong> — Libellé affiché dans les listes et formulaires (ex. Quête ordinaire, Location, Dons).</li>
                    <li class="mb-2"><strong>Ordre</strong> — Numéro pour afficher les catégories dans l’ordre souhaité (0, 1, 2…). Plus le nombre est petit, plus la catégorie apparaît en haut.</li>
                    <li class="mb-2"><strong>Description</strong> — Optionnel : précisions sur l’usage de cette catégorie.</li>
                    <li class="mb-0"><strong>Actif</strong> — Désactivez pour masquer la catégorie dans les listes sans supprimer les données déjà enregistrées.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">J’ai compris</button>
            </div>
        </div>
    </div>
</div>
