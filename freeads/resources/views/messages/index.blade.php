@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Mes messages reçus</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('messages.sent') }}" class="btn btn-outline-secondary me-2">Messages envoyés</a>
            <a href="{{ route('messages.create') }}" class="btn btn-primary">Nouveau message</a>
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
    
    <div class="card">
        <div class="card-body">
            @if ($receivedMessages->count() > 0)
                <div class="list-group">
                    @foreach ($receivedMessages as $message)
                        <a href="{{ route('messages.show', $message) }}" class="list-group-item list-group-item-action {{ $message->read ? '' : 'fw-bold' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Message de {{ $message->sender->name }}</h5>
                                <small>{{ $message->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <p class="mb-1">{{ \Illuminate\Support\Str::limit($message->content, 100) }}</p>
                            <small>{{ $message->read ? 'Lu' : 'Non lu' }}</small>
                        </a>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $receivedMessages->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">Vous n'avez pas encore de messages.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
