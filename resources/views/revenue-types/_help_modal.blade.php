{{-- Modal Aide formulaire Type de recette --}}
<div class="modal fade" id="revenueTypeHelpModal" tabindex="-1" aria-labelledby="revenueTypeHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="revenueTypeHelpModalLabel">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Aide : type de recette
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><strong>Catégorie</strong> — La catégorie à laquelle ce type appartient (ex. Quête ordinaire, Location). Les types sont regroupés par catégorie dans le formulaire de saisie des recettes.</li>
                    <li class="mb-2"><strong>Code</strong> — Identifiant technique unique (ex. messe_dimanche, boutique_1). Sert aux rapports et au calcul automatique (ex. jour de la quête).</li>
                    <li class="mb-2"><strong>Nom</strong> — Libellé affiché (ex. Messe dimanche, Boutique 1).</li>
                    <li class="mb-2"><strong>Ordre</strong> — Numéro pour afficher les types dans l’ordre souhaité au sein de la catégorie.</li>
                    <li class="mb-2"><strong>Description</strong> — Optionnel : précisions sur ce type de recette.</li>
                    <li class="mb-0"><strong>Actif</strong> — Désactivez pour masquer le type dans les listes sans supprimer l’historique.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">J’ai compris</button>
            </div>
        </div>
    </div>
</div>
