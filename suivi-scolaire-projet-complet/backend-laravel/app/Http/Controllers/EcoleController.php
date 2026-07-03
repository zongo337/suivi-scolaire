<?php
namespace App\Http\Controllers;

use App\Models\Ecole;
use Illuminate\Http\Request;

class EcoleController extends Controller
{
    public function index()
    {
        $ecoles = Ecole::all();
        return view('ecoles.index', compact('ecoles'));
    }

    public function create(){
        return view('ecoles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'       => 'required|string|max:100',
            'adresse'   => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'email'     => 'nullable|email',
            'directeur' => 'nullable|string|max:100',
        ]);

        Ecole::create($data);

        return redirect()->route('ecoles.index')
               ->with('success', 'École ajoutée avec succès.');
    }

    public function activer(Ecole $ecole)
    {
        Ecole::query()->update(['active' => false]);
        $ecole->update(['active' => true]);

        return redirect()->route('ecoles.index')
               ->with('success', "École « {$ecole->nom} » activée.");
    }

    public function destroy(Ecole $ecole)
    {
        $ecole->delete();
        return redirect()->route('ecoles.index')
               ->with('success', 'École supprimée.');
    }
}