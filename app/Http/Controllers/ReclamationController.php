<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function index()
    {
        $reclamations = Reclamation::with('order')->get();

        return response()->json(['reclamations' => $reclamations]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'titre' => 'required',
            'description' => 'required',
        ]);
        $reclamation = Reclamation::create([
            'order_id' => $request->order_id,
            'titre' => $request->titre,
            'description' => $request->description,
        ]);

        return response()->json(['reclamation' => $reclamation], 201);
    }

    public function destroy(Reclamation $reclamation)
    {
        $reclamation->delete();

        return response()->json(['message' => 'Reclamation deleted successfully']);
    }
}

