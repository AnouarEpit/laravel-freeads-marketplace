@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Toutes les annonces</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('annonces.create') }}" class="btn btn-primary">Créer une annonce</a>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Rechercher des annonces
                </div>
                <div class="card-body">
                    <form action="{{ route('annonces.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="search" class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Titre ou description" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="min_price" class="form-label">Prix min</label>
                                <input type="number" class="form-control" id="min_price" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="max_price" class="form-label">Prix max</label>
                                <input type="number" class="form-control" id="max_price" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="sort" class="form-label">Trier par</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date (récent d'abord)</option>
                                    <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date (ancien d'abord)</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix (croissant)</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix (décroissant)</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end mb-3">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="row">
        @forelse ($annonces as $annonce)
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
                        
                        @if (Auth::id() === $annonce->user_id)
                            <a href="{{ route('annonces.edit', $annonce) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                            
                            <form action="{{ route('annonces.destroy', $annonce) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce?')">Supprimer</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Aucune annonce disponible pour le moment.
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $annonces->links() }}
    </div>
</div>
@endsection
