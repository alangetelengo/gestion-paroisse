@extends('layouts.app')

@section('title', 'Configuration')
@section('page-title', 'Configuration de l\'application')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Paramètres de l'application
                </h4>
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

                {{-- Onglets Bootstrap --}}
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="identite-tab" data-bs-toggle="tab" data-bs-target="#identite" type="button" role="tab">
                            Identité de la paroisse
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="couleurs-tab" data-bs-toggle="tab" data-bs-target="#couleurs" type="button" role="tab">
                            Charte de couleurs
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="boutons-tab" data-bs-toggle="tab" data-bs-target="#boutons" type="button" role="tab">
                            Boutons et titres
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="actions-tab" data-bs-toggle="tab" data-bs-target="#actions" type="button" role="tab">
                            Actions du tableau
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="generaux-tab" data-bs-toggle="tab" data-bs-target="#generaux" type="button" role="tab">
                            Paramètres généraux
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="telephone-tab" data-bs-toggle="tab" data-bs-target="#telephone" type="button" role="tab">
                            Téléphone / Pays
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pdf-tab" data-bs-toggle="tab" data-bs-target="#pdf" type="button" role="tab">
                            En-tête PDF
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Section 1: Identité de la paroisse --}}
                    <div class="tab-pane fade show active" id="identite" role="tabpanel">
                        <form action="{{ route('configurations.update-bulk') }}" method="POST" class="config-section-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="section" value="identite">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom de la paroisse</label>
                                    <input type="text" name="nom_paroisse" class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'nom_paroisse') }}"
                                           placeholder="Ex: SAINT-ESPRIT DE MOUNGALI">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Chemin du logo</label>
                                    <input type="text" name="logo_path" class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'logo_path') }}"
                                           placeholder="/images/logo-paroisse.svg">
                                    <small class="text-muted">Ex: /images/logo-paroisse.svg</small>
                                </div>
                            </div>

                            <div class="text-end mt-4 pt-4 border-top">
                                <button type="submit" class="btn btn-primary">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Section 2: Charte de couleurs --}}
                    <div class="tab-pane fade" id="couleurs" role="tabpanel">
                        <form action="{{ route('configurations.update-bulk') }}" method="POST" class="config-section-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="section" value="couleurs">

                            <div class="row">
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur primaire</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_primaire" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_primaire') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_primaire') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur secondaire</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_secondaire" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_secondaire') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_secondaire') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur succès</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_succes" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_succes') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_succes') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur info</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_info" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_info') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_info') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur avertissement</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_avertissement" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_avertissement') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_avertissement') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur danger</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_danger" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_danger') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_danger') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4 pt-4 border-top">
                                <button type="submit" class="btn btn-primary">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Section 3: Boutons et titres --}}
                    <div class="tab-pane fade" id="boutons" role="tabpanel">
                        <form action="{{ route('configurations.update-bulk') }}" method="POST" class="config-section-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="section" value="boutons">

                            <div class="row">
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur bouton d'ajout</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_bouton_ajout" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_bouton_ajout') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_bouton_ajout') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                    <small class="text-muted">Couleur des boutons "Ajouter"</small>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur bouton d'ajout (hover)</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_bouton_ajout_hover" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_bouton_ajout_hover') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_bouton_ajout_hover') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                    <small class="text-muted">Couleur au survol</small>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur titre de page</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_titre_page" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_titre_page') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_titre_page') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                    <small class="text-muted">Couleur des en-têtes de cartes</small>
                                </div>
                            </div>

                            <div class="text-end mt-4 pt-4 border-top">
                                <button type="submit" class="btn btn-primary">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Section 4: Actions du tableau --}}
                    <div class="tab-pane fade" id="actions" role="tabpanel">
                        <form action="{{ route('configurations.update-bulk') }}" method="POST" class="config-section-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="section" value="actions">

                            <div class="row">
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur action "Voir"</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_action_voir" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_action_voir') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_action_voir') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                    <small class="text-muted">Bouton d'information</small>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur action "Modifier"</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_action_modifier" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_action_modifier') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_action_modifier') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                    <small class="text-muted">Bouton d'édition</small>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <label class="form-label">Couleur action "Supprimer"</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" name="couleur_action_supprimer" class="form-control form-control-color me-3"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_action_supprimer') }}">
                                        <input type="text" value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'couleur_action_supprimer') }}"
                                               class="form-control" readonly style="flex: 1;">
                                    </div>
                                    <small class="text-muted">Bouton de suppression</small>
                                </div>
                            </div>

                            <div class="text-end mt-4 pt-4 border-top">
                                <button type="submit" class="btn btn-primary">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Section 5: Paramètres généraux --}}
                    <div class="tab-pane fade" id="generaux" role="tabpanel">
                        <form action="{{ route('configurations.update-bulk') }}" method="POST" class="config-section-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="section" value="generaux">

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Monnaie</label>
                                    <input type="text" name="monnaie" class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'monnaie') }}"
                                           placeholder="FCFA">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Format de date</label>
                                    <input type="text" name="format_date" class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'format_date') }}"
                                           placeholder="d/m/Y">
                                    <small class="text-muted">Ex: d/m/Y</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Format d'heure</label>
                                    <input type="text" name="format_heure" class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'format_heure') }}"
                                           placeholder="H:i">
                                    <small class="text-muted">Ex: H:i</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Langue</label>
                                    <select name="langue" class="form-control">
                                        <option value="fr" {{ \App\Helpers\ParoisseConfig::get($paroisseId, 'langue') == 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="en" {{ \App\Helpers\ParoisseConfig::get($paroisseId, 'langue') == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-end mt-4 pt-4 border-top">
                                <button type="submit" class="btn btn-primary">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Section 6: Téléphone / Pays --}}
                    <div class="tab-pane fade" id="telephone" role="tabpanel">
                        <form action="{{ route('configurations.update-bulk') }}" method="POST" class="config-section-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="section" value="telephone">

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pays (code ISO)</label>
                                    <input type="text"
                                           name="phone_country"
                                           class="form-control text-uppercase"
                                           data-transform="upper"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'phone_country', 'CG') }}"
                                           placeholder="Ex: CG">
                                    <small class="text-muted">Code pays ISO 2 lettres (ex: CG, FR, BE...)</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Indicatif téléphonique</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+</span>
                                        @php
                                            $dial = \App\Helpers\ParoisseConfig::get($paroisseId, 'phone_dial_code', '+242');
                                            $dial = ltrim($dial, '+');
                                        @endphp
                                        <input type="text"
                                               name="phone_dial_code"
                                               class="form-control"
                                               value="{{ $dial }}"
                                               placeholder="Ex: 242">
                                    </div>
                                    <small class="text-muted">Sans le + (ex: 242 pour Congo)</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Regex téléphone</label>
                                    <input type="text"
                                           name="phone_regex"
                                           class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'phone_regex', '/^(\+242|242|0)?[ \-]?[0-9]{9}$/') }}">
                                    <small class="text-muted">
                                        Expression régulière PHP pour valider le format. Défaut (Congo) :
                                        <code>/^(\+242|242|0)?[ \-]?[0-9]{9}$/</code>
                                    </small>
                                </div>
                            </div>

                            <div class="text-end mt-4 pt-4 border-top">
                                <button type="submit" class="btn btn-primary">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Section 7: En-tête PDF --}}
                    <div class="tab-pane fade" id="pdf" role="tabpanel">
                        <form action="{{ route('configurations.update-bulk') }}" method="POST" class="config-section-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="section" value="pdf">

                            <div class="alert alert-info mb-3">
                                Configurez l'apparence de l'en-tête des rapports financiers téléchargés en PDF.
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Afficher le logo</label>
                                    <select name="pdf_header_show_logo" class="form-control">
                                        <option value="1" @selected(\App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_show_logo', true))>Oui</option>
                                        <option value="0" @selected(!\App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_show_logo', true))>Non</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Chemin du logo</label>
                                    <input type="text"
                                           name="pdf_header_logo"
                                           class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_logo', '') }}"
                                           placeholder="/images/logo-paroisse.png">
                                    <small class="text-muted">Chemin relatif depuis le dossier public (ex: /images/logo.png)</small>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Largeur du logo (px)</label>
                                    <input type="number"
                                           name="pdf_header_logo_width"
                                           class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_logo_width', '80') }}"
                                           min="20"
                                           max="200">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Titre de l'en-tête</label>
                                    <input type="text"
                                           name="pdf_header_title"
                                           class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_title', '') }}"
                                           placeholder="Laissez vide pour utiliser le nom de la paroisse">
                                    <small class="text-muted">Si vide, le nom de la paroisse sera utilisé</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sous-titre</label>
                                    <input type="text"
                                           name="pdf_header_subtitle"
                                           class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_subtitle', '') }}"
                                           placeholder="Ex: Paroisse catholique">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Adresse</label>
                                    <input type="text"
                                           name="pdf_header_address"
                                           class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_address', '') }}"
                                           placeholder="Laissez vide pour utiliser l'adresse de la paroisse">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text"
                                           name="pdf_header_phone"
                                           class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_phone', '') }}"
                                           placeholder="Laissez vide pour utiliser le téléphone de la paroisse">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email"
                                           name="pdf_header_email"
                                           class="form-control"
                                           value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_email', '') }}"
                                           placeholder="Laissez vide pour utiliser l'email de la paroisse">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Texte personnalisé</label>
                                    <textarea name="pdf_header_custom_text"
                                              class="form-control"
                                              rows="2"
                                              placeholder="Texte supplémentaire à afficher dans l'en-tête">{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_custom_text', '') }}</textarea>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Couleur de fond de l'en-tête</label>
                                    <div class="d-flex gap-2">
                                        <input type="color"
                                               name="pdf_header_bg_color"
                                               class="form-control form-control-color"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_bg_color', '#003366') }}">
                                        <input type="text"
                                               name="pdf_header_bg_color_text"
                                               class="form-control"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_bg_color', '#003366') }}"
                                               placeholder="#003366">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Couleur du texte de l'en-tête</label>
                                    <div class="d-flex gap-2">
                                        <input type="color"
                                               name="pdf_header_text_color"
                                               class="form-control form-control-color"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_text_color', '#FFFFFF') }}">
                                        <input type="text"
                                               name="pdf_header_text_color_text"
                                               class="form-control"
                                               value="{{ \App\Helpers\ParoisseConfig::get($paroisseId, 'pdf_header_text_color', '#FFFFFF') }}"
                                               placeholder="#FFFFFF">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4 pt-4 border-top">
                                <button type="submit" class="btn btn-primary">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Mise à jour automatique des champs texte lors du changement de couleur
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="color"]').forEach(function(colorInput) {
            colorInput.addEventListener('input', function() {
                const container = this.closest('.d-flex');
                if (container) {
                    const textInput = container.querySelector('input[type="text"]');
                    if (textInput) {
                        textInput.value = this.value.toUpperCase();
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection
