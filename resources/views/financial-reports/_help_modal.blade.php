{{-- Modal Aide rapports financiers --}}
<div class="modal fade" id="financialReportHelpModal" tabindex="-1" aria-labelledby="financialReportHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="financialReportHelpModalLabel">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Aide : rapport mensuel de justification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Ce rapport agrège les <strong>recettes</strong> (popote/subvention) et les <strong>dépenses</strong> sur la période choisie, puis affiche le solde (excédent ou déficit).</p>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><strong>Paroisse</strong> — (Super admin uniquement) Choisissez la paroisse pour laquelle générer le rapport.</li>
                    <li class="mb-2"><strong>Mois / Année</strong> — Sélectionnez le mois et l’année de la période à analyser.</li>
                    <li class="mb-2"><strong>Calculer le rapport</strong> — Lance le calcul : total recettes, total dépenses (par catégorie), solde et listes détaillées.</li>
                    <li class="mb-0"><strong>Enregistrer ce rapport</strong> — (Si vous avez le droit) Enregistre une version figée du rapport pour archivage et historique.</li>
                </ul>
                <p class="small text-muted mt-3 mb-0">
                    <i class="fas fa-lightbulb text-warning me-1"></i>
                    Les liens en haut de page permettent d’accéder au rapport Subvention Popote, au rapport Charges fixes et à la liste des rapports déjà enregistrés.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">J’ai compris</button>
            </div>
        </div>
    </div>
</div>
