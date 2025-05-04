<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Ads - Annonces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="mt-4 mb-5">
            <h1 class="text-center">Free Ads</h1>
            <p class="text-center">Votre site d'annonces gratuites</p>
        </header>
        
        <main>
            <div class="jumbotron bg-light p-5 rounded mb-5">
                <h2>Bienvenue sur Free Ads</h2>
                <p>Le meilleur endroit pour publier et trouver des annonces.</p>
                <hr class="my-4">
                <p>Inscrivez-vous ou connectez-vous pour accéder à toutes les fonctionnalités.</p>
                <div class="mt-3">
                    <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">S'inscrire</a>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-12">
                    <h3>Dernières annonces</h3>
                    <hr>
                </div>
            </div>
            
            <div class="row">
                @foreach($latestAnnonces as $annonce)
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
                            </div>
                            <div class="card-footer bg-white">
                                <a href="{{ route('annonces.show', $annonce) }}" class="btn btn-sm btn-outline-primary">Voir détails</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-3 mb-5">
                <a href="{{ route('annonces.index') }}" class="btn btn-primary">Voir toutes les annonces</a>
            </div>
        </main>
        
        <footer class="mt-5 text-center">
            <p>&copy; {{ date('Y') }} Free Ads</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
