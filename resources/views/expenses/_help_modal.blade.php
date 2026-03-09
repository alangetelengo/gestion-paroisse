{{-- Modal Aide formulaire Dépense --}}
<div class="modal fade" id="expenseHelpModal" tabindex="-1" aria-labelledby="expenseHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expenseHelpModalLabel">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Aide : comment enregistrer une dépense ?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Choisissez d’abord la <strong>catégorie de charge</strong> selon le type de dépense :</p>
                <ul class="help-list small">
                    <li><strong>Charge fixe</strong> — Dépenses récurrentes (électricité, eau, gaz, internet, gardiennage, salaire ouvrier, carburant, hosties, maintenance, etc.). Ces dépenses ne sont pas déduites des recettes ; un rapport mensuel/annuel permet d’informer la hiérarchie.</li>
                    <li><strong>Charge variable</strong> — Dépenses qui varient selon l’activité.</li>
                    <li><strong>Charge exceptionnelle</strong> — Dépenses ponctuelles ou non récurrentes.</li>
                    <li><strong>Alimentation (Subvention Popote)</strong> — Dépenses d’alimentation de la paroisse financées par la subvention popote. À choisir pour les achats de nourriture (riz, huile, légumes, etc.). Le formulaire affiche alors : date, jour, libellé de l’alimentation achetée et montant. Un rapport mensuel/annuel compare la subvention reçue à ces dépenses.</li>
                </ul>
                <p class="small text-muted mb-0 mt-3">
                    <i class="fas fa-lightbulb text-warning me-1"></i>
                    Pour les charges fixes, variables ou exceptionnelles : renseignez le type de charge, la référence facture et le fournisseur si besoin. Pour l’alimentation popote : indiquez le libellé de l’article acheté.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">J’ai compris</button>
            </div>
        </div>
    </div>
</div>
