@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Mes messages envoyés</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary me-2">Messages reçus</a>
            <a href="{{ route('messages.create') }}" class="btn btn-primary">Nouveau message</a>
        </div>
    </div>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            @if ($sentMessages->count() > 0)
                <div class="list-group">
                    @foreach ($sentMessages as $message)
                        <a href="{{ route('messages.show', $message) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Message à {{ $message->receiver->name }}</h5>
                                <small>{{ $message->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <p class="mb-1">{{ \Illuminate\Support\Str::limit($message->content, 100) }}</p>
                            <small>{{ $message->read ? 'Lu par le destinataire' : 'Non lu par le destinataire' }}</small>
                        </a>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $sentMessages->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">Vous n'avez pas encore envoyé de messages.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
