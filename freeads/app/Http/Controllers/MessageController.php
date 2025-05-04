<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $receivedMessages = Auth::user()->receivedMessages()->with('sender')->latest()->paginate(10);
        
        return view('messages.index', compact('receivedMessages'));
    }

    public function sent()
    {
        $sentMessages = Auth::user()->sentMessages()->with('receiver')->latest()->paginate(10);
        
        return view('messages.sent', compact('sentMessages'));
    }

    public function create(Request $request)
    {
        $users = User::where('id', '!=', Auth::id())->get();
        $receiverId = $request->receiver_id;
        
        return view('messages.create', compact('users', 'receiverId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $message = new Message();
        $message->sender_id = Auth::id();
        $message->receiver_id = $request->receiver_id;
        $message->content = $request->content;
        $message->save();

        return redirect()->route('messages.sent')
            ->with('success', 'Message envoyé avec succès.');
    }

    public function show(Message $message)
    {
        if (Auth::id() !== $message->sender_id && Auth::id() !== $message->receiver_id) {
            return redirect()->route('messages.index')
                ->with('error', 'Vous n\'êtes pas autorisé à voir ce message.');
        }
        
        // Marcar como leído si somos el destinatario
        if (Auth::id() === $message->receiver_id && !$message->read) {
            $message->read = true;
            $message->save();
        }
        
        return view('messages.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        if (Auth::id() !== $message->sender_id && Auth::id() !== $message->receiver_id) {
            return redirect()->route('messages.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer ce message.');
        }
        
        $message->delete();
        
        return redirect()->back()
            ->with('success', 'Message supprimé avec succès.');
    }
    
    public function contactUser($userId)
    {
        $receiver = User::findOrFail($userId);
        $users = User::where('id', '!=', Auth::id())->get();
        
        return view('messages.create', compact('users', 'receiver'));
    }
}
