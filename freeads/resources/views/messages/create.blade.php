@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Nouveau message</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary">Retour aux messages</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('messages.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="receiver_id" class="form-label">Destinataire</label>
                    <select class="form-select @error('receiver_id') is-invalid @enderror" id="receiver_id" name="receiver_id" required>
                        <option value="">Sélectionner un destinataire</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ (isset($receiver) && $receiver->id == $user->id) || old('receiver_id') == $user->id || (isset($receiverId) && $receiverId == $user->id) ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('receiver_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Message</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5" required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Envoyer le message</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
