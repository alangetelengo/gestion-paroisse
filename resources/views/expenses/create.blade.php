@extends('layouts.app')

@section('title', 'Ajouter une dépense')
@section('page-title', 'Ajouter une dépense')

@push('styles')
<style>
#expenseHelpModal .help-list { margin-bottom: 0; padding-left: 1.25rem; }
#expenseHelpModal .help-list li { margin-bottom: 0.5rem; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h4 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Nouvelle dépense
                </h4>
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#expenseHelpModal" title="Aide">
                    <i class="fas fa-info-circle me-1"></i> Aide
                </button>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <h6 class="alert-heading mb-2">Erreurs de validation</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @include('expenses._form')

                    <div class="text-end mt-4 pt-4 border-top">
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Aide --}}
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
@endsection

