@extends('layouts.app')

@section('title', 'Membres')
@section('page-title', 'Modifier un membre')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="fas fa-user me-2"></i>
                    Modifier le membre
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

                <form action="{{ route('members.update', $member) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('members._form', ['member' => $member, 'paroisses' => $paroisses])

                    <div class="text-end mt-4 pt-4 border-top">
                        <a href="{{ route('members.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

