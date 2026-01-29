@extends('layouts.app')

@section('title', $sacrament->type_label . ' - ' . ($sacrament->beneficiary_name ?: optional($sacrament->beneficiary)->prenom . ' ' . optional($sacrament->beneficiary)->nom))
@section('page-title', $sacrament->type_label)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-heart me-2"></i>
                    {{ $sacrament->type_label }}
                </h4>
                <div class="card-action">
                    @can(\App\Http\Controllers\SacramentController::TYPE_PERMISSIONS[$sacrament->type]['edit'] ?? 'edit_baptisms')
                    <a href="{{ route('sacraments.edit', $sacrament) }}" class="btn btn-warning btn-sm">Modifier</a>
                    @endcan
                    <a href="{{ route('sacraments.index', ['type' => $sacrament->type]) }}" class="btn btn-secondary btn-sm">Retour à la liste</a>
                </div>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Date de célébration</dt>
                    <dd class="col-sm-9">{{ $sacrament->date_celebration?->format('d/m/Y') }}</dd>

                    <dt class="col-sm-3">Bénéficiaire / Nom</dt>
                    <dd class="col-sm-9">{{ $sacrament->beneficiary_name ?: ($sacrament->beneficiary ? $sacrament->beneficiary->prenom . ' ' . $sacrament->beneficiary->nom : '—') }}</dd>

                    <dt class="col-sm-3">Lieu</dt>
                    <dd class="col-sm-9">{{ $sacrament->lieu ?? '—' }}</dd>

                    <dt class="col-sm-3">Célébrant</dt>
                    <dd class="col-sm-9">{{ $sacrament->celebrant ? $sacrament->celebrant->prenom . ' ' . $sacrament->celebrant->nom : '—' }}</dd>

                    @if($sacrament->paroisse && auth()->user()->hasRole('super_admin'))
                    <dt class="col-sm-3">Paroisse</dt>
                    <dd class="col-sm-9">{{ $sacrament->paroisse->nom }}</dd>
                    @endif

                    @if($sacrament->notes)
                    <dt class="col-sm-3">Notes</dt>
                    <dd class="col-sm-9">{{ $sacrament->notes }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
