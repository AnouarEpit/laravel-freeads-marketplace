<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnonceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $annonces = Annonce::with('photos', 'user')->latest()->paginate(10);
        
        return view('annonces.index', compact('annonces'));
    }

    public function create()
    {
        return view('annonces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $annonce = new Annonce();
        $annonce->title = $request->title;
        $annonce->description = $request->description;
        $annonce->price = $request->price;
        $annonce->user_id = Auth::id();
        $annonce->save();

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('photos', 'public');
                
                $photoModel = new Photo();
                $photoModel->path = $path;
                $annonce->photos()->save($photoModel);
            }
        }

        return redirect()->route('annonces.show', $annonce)
            ->with('success', 'Annonce créée avec succès.');
    }

    public function show(Annonce $annonce)
    {
        return view('annonces.show', compact('annonce'));
    }

    public function edit(Annonce $annonce)
    {
        if (Auth::id() !== $annonce->user_id) {
            return redirect()->route('annonces.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette annonce.');
        }
        
        return view('annonces.edit', compact('annonce'));
    }

    public function update(Request $request, Annonce $annonce)
    {
        if (Auth::id() !== $annonce->user_id) {
            return redirect()->route('annonces.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette annonce.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $annonce->title = $request->title;
        $annonce->description = $request->description;
        $annonce->price = $request->price;
        $annonce->save();

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('photos', 'public');
                
                $photoModel = new Photo();
                $photoModel->path = $path;
                $annonce->photos()->save($photoModel);
            }
        }

        return redirect()->route('annonces.show', $annonce)
            ->with('success', 'Annonce mise à jour avec succès.');
    }

    public function destroy(Annonce $annonce)
    {
        if (Auth::id() !== $annonce->user_id) {
            return redirect()->route('annonces.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette annonce.');
        }
        
        // Eliminar fotos
        foreach ($annonce->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }
        
        $annonce->delete();
        
        return redirect()->route('annonces.index')
            ->with('success', 'Annonce supprimée avec succès.');
    }
}
