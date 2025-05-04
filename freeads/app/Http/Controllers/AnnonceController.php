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

    public function index(Request $request)
    {
        $query = Annonce::with('photos', 'user');
        
        // Búsqueda por título o descripción
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filtro por precio mínimo
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        // Filtro por precio máximo
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Ordenamiento
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }
        
        $annonces = $query->paginate(10)->withQueryString();
        
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
    
    public function recommended()
    {
        $user = Auth::user();
        
        // Si el usuario no está autenticado o no tiene anuncios, mostrar los más recientes
        if (!$user || $user->annonces->isEmpty()) {
            $recommendedAnnonces = Annonce::with('photos', 'user')
                                    ->latest()
                                    ->take(6)
                                    ->get();
            
            return view('annonces.recommended', compact('recommendedAnnonces'));
        }
        
        // Obtener palabras clave de los anuncios del usuario
        $userKeywords = [];
        foreach ($user->annonces as $userAnnonce) {
            // Extraer palabras clave del título y descripción
            $titleWords = explode(' ', strtolower($userAnnonce->title));
            $descWords = explode(' ', strtolower($userAnnonce->description));
            
            // Combinar y filtrar palabras cortas
            $keywords = array_merge($titleWords, $descWords);
            $keywords = array_filter($keywords, function($word) {
                return strlen($word) > 3; // Ignorar palabras cortas
            });
            
            $userKeywords = array_merge($userKeywords, $keywords);
        }
        
        // Contar frecuencia de palabras
        $keywordCounts = array_count_values($userKeywords);
        arsort($keywordCounts); // Ordenar por frecuencia
        
        // Tomar las 5 palabras clave más frecuentes
        $topKeywords = array_slice($keywordCounts, 0, 5, true);
        
        // Buscar anuncios que contengan estas palabras clave
        $query = Annonce::with('photos', 'user')
                ->where('user_id', '!=', $user->id); // Excluir anuncios del usuario
        
        foreach (array_keys($topKeywords) as $keyword) {
            $query->orWhere('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
        }
        
        $recommendedAnnonces = $query->latest()->take(6)->get();
        
        // Si no hay suficientes recomendaciones, completar con anuncios recientes
        if ($recommendedAnnonces->count() < 6) {
            $existingIds = $recommendedAnnonces->pluck('id')->toArray();
            
            $additionalAnnonces = Annonce::with('photos', 'user')
                                ->where('user_id', '!=', $user->id)
                                ->whereNotIn('id', $existingIds)
                                ->latest()
                                ->take(6 - $recommendedAnnonces->count())
                                ->get();
            
            $recommendedAnnonces = $recommendedAnnonces->concat($additionalAnnonces);
        }
        
        return view('annonces.recommended', compact('recommendedAnnonces'));
    }
}
