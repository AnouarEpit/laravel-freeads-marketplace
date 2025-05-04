@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Retour aux annonces
            </a>
            <h1>{{ $annonce->title }}</h1>
        </div>
        <div class="col-md-4 text-end">
            @if (Auth::id() === $annonce->user_id)
                <a href="{{ route('annonces.edit', $annonce) }}" class="btn btn-primary">Modifier</a>
                
                <form action="{{ route('annonces.destroy', $annonce) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce?')">Supprimer</button>
                </form>
            @endif
        </div>
    </div>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            @if ($annonce->photos->count() > 0)
                <div id="annonceCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($annonce->photos as $key => $photo)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $photo->path) }}" class="d-block w-100" alt="Photo {{ $key + 1 }}" style="height: 400px; object-fit: contain;">
                            </div>
                        @endforeach
                    </div>
                    @if ($annonce->photos->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#annonceCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#annonceCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>
                    @endif
                </div>
            @else
                <div class="bg-light d-flex justify-content-center align-items-center mb-4" style="height: 400px;">
                    <p class="text-muted">Pas de photo</p>
                </div>
            @endif
            
            <div class="card mb-4">
                <div class="card-header">
                    Description
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $annonce->description }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    Informations
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ number_format($annonce->price, 2) }} €</h5>
                    <p class="card-text"><strong>Vendeur:</strong> {{ $annonce->user->name }}</p>
                    <p class="card-text"><strong>Publiée le:</strong> {{ $annonce->created_at->format('d/m/Y à H:i') }}</p>
                    @if ($annonce->created_at != $annonce->updated_at)
                        <p class="card-text"><strong>Mise à jour le:</strong> {{ $annonce->updated_at->format('d/m/Y à H:i') }}</p>
                    @endif
                </div>
            </div>
            
            @if (Auth::check() && Auth::id() !== $annonce->user_id)
                <div class="card">
                    <div class="card-header">
                        Contacter le vendeur
                    </div>
                    <div class="card-body">
                        <a href="#" class="btn btn-success w-100">Envoyer un message</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
