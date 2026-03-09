{{-- Modal Aide formulaire Recette --}}
<div class="modal fade" id="revenueHelpModal" tabindex="-1" aria-labelledby="revenueHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="revenueHelpModalLabel">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Aide : comment enregistrer une recette ?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><strong>Date de la recette</strong> — Date à laquelle la recette a été perçue.</li>
                    <li class="mb-2"><strong>Catégorie / Type</strong> — Choisissez d’abord la catégorie (ex. Quête ordinaire, Location, Dons…), puis le type de recette. Les types proposés dépendent de la catégorie.</li>
                    <li class="mb-2"><strong>Jour de la semaine</strong> — Affiché automatiquement pour la quête ordinaire selon la date (ex. dimanche). Lecture seule.</li>
                    <li class="mb-2"><strong>Mois de paiement du loyer</strong> — Pour les loyers de boutique : indiquez le mois et l’année concernés par ce paiement.</li>
                    <li class="mb-2"><strong>Montant</strong> — Saisissez le montant en {{ \App\Helpers\ParoisseConfig::get(null, 'monnaie', 'FCFA') }}. Les séparateurs de milliers sont ajoutés automatiquement.</li>
                    <li class="mb-2"><strong>Méthode de paiement</strong> — Espèces, chèque, virement, carte, mobile money.</li>
                    <li class="mb-2"><strong>Référence paiement</strong> — Générée automatiquement ; vous pouvez la laisser telle quelle sauf besoin particulier.</li>
                    <li class="mb-0"><strong>Notes</strong> — Optionnel : précisions (origine du don, nom du donateur, etc.).</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">J’ai compris</button>
            </div>
        </div>
    </div>
</div>
