@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modifier l'annonce</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('annonces.update', $annonce) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Titre de l'annonce</label>
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $annonce->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" required>{{ old('description', $annonce->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Prix (€)</label>
                            <input id="price" type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $annonce->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($annonce->photos->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Photos actuelles</label>
                                <div class="row">
                                    @foreach ($annonce->photos as $photo)
                                        <div class="col-md-3 mb-2">
                                            <img src="{{ asset('storage/' . $photo->path) }}" class="img-thumbnail" alt="Photo actuelle">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="photos" class="form-label">Ajouter des photos</label>
                            <input id="photos" type="file" class="form-control @error('photos.*') is-invalid @enderror" name="photos[]" multiple accept="image/*">
                            <small class="form-text text-muted">Formats acceptés: jpeg, png, jpg, gif. Taille maximale: 2Mo.</small>
                            @error('photos.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Mettre à jour l'annonce
                            </button>
                            <a href="{{ route('annonces.show', $annonce) }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
