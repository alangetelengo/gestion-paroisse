@extends('layouts.app')

@section('title', 'Ajouter - ' . (\App\Models\Sacrament::TYPES[$type] ?? $type))
@section('page-title', 'Ajouter un ' . (\App\Models\Sacrament::TYPES[$type] ?? $type))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-heart me-2"></i>
                    Nouveau {{ \App\Models\Sacrament::TYPES[$type] ?? $type }}
                </h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sacraments.store') }}" method="POST">
                    @csrf
                    @include('sacraments._form', [
                        'sacrament' => $sacrament,
                        'type' => $type,
                        'paroisses' => $paroisses,
                        'paroisseId' => $paroisseId ?? null,
                        'celebrants' => $celebrants,
                        'members' => $members ?? collect(),
                    ])
                    <div class="text-end mt-4 pt-4 border-top">
                        <a href="{{ route('sacraments.index', ['type' => $type]) }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
