@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>
                @if ($message->sender_id == Auth::id())
                    Message envoyé à {{ $message->receiver->name }}
                @else
                    Message reçu de {{ $message->sender->name }}
                @endif
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary">Retour aux messages</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                @if ($message->sender_id == Auth::id())
                    <span class="fw-bold">À:</span> {{ $message->receiver->name }}
                @else
                    <span class="fw-bold">De:</span> {{ $message->sender->name }}
                @endif
            </div>
            <div>{{ $message->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div class="card-body">
            <p class="card-text">{{ $message->content }}</p>
        </div>
        <div class="card-footer d-flex justify-content-between">
            @if ($message->sender_id != Auth::id())
                <a href="{{ route('contact.user', $message->sender_id) }}" class="btn btn-primary">Répondre</a>
            @else
                <div></div>
            @endif
            
            <form action="{{ route('messages.destroy', $message) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message?')">Supprimer</button>
            </form>
        </div>
    </div>
</div>
@endsection
