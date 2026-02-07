@extends('layouts.app')

@section('title', 'Modifier une dépense')
@section('page-title', 'Modifier la dépense')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="fas fa-calculator me-2"></i>
                    Modifier la dépense
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

                <form action="{{ route('expenses.update', $expense) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @include('expenses._form')

                    <div class="text-end mt-4 pt-4 border-top">
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

