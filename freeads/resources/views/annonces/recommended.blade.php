@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Annonces recommandées pour vous</h1>
            <p class="text-muted">Basées sur vos intérêts</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary">Toutes les annonces</a>
        </div>
    </div>
    
    <div class="row">
        @forelse ($recommendedAnnonces as $annonce)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if ($annonce->photos->count() > 0)
                        <img src="{{ asset('storage/' . $annonce->photos->first()->path) }}" class="card-img-top" alt="{{ $annonce->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex justify-content-center align-items-center" style="height: 200px;">
                            <p class="text-muted">Pas de photo</p>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $annonce->title }}</h5>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($annonce->description, 100) }}</p>
                        <p class="card-text fw-bold">{{ number_format($annonce->price, 2) }} €</p>
                        <p class="card-text text-muted small">
                            Par {{ $annonce->user->name }} - {{ $annonce->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('annonces.show', $annonce) }}" class="btn btn-sm btn-outline-primary">Voir détails</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Aucune annonce recommandée pour le moment.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
