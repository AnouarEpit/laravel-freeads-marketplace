<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce; // Añade esta línea para importar el modelo

class IndexController extends Controller
{
    public function showIndex()
    {
        $latestAnnonces = Annonce::with('photos')
                        ->latest()
                        ->take(6)
                        ->get();
        
        return view('index', compact('latestAnnonces'));
    }
}
