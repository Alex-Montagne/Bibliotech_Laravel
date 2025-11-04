<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Categorie;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    public function index(Request $request)
{
    $query = Livre::with('categorie');

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('titre', 'LIKE', "%{$search}%")
              ->orWhere('auteur', 'LIKE', "%{$search}%");
        });
    }

    $livres = $query->orderBy('titre')->paginate(10);
    $livres->appends($request->all());

    $categories = Categorie::orderBy('nom')->get();

    return view('livres.index', compact('livres', 'categories'));
}

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('livres.create', compact('categories'));
        
    }

    /**
     * Sauvegarder un nouveau livre
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255', // 📝 Note: En séance 4, nous transformerons ceci en relation vers un modèle Auteur
            'isbn' => 'required|string|unique:livres|size:13',
            'categorie_id' => 'required|exists:categories,id',
            'resume' => 'nullable|string|max:1000',
            'date_publication' => 'required|date|before_or_equal:today',
            'pages' => 'required|integer|min:1|max:9999',
            'disponible' => 'boolean'
        ]);

        $livre = Livre::create($validated);

        return redirect()
            ->route('livres.show', $livre)
            ->with('success', 'Livre créé avec succès !');
    }

    /**
     * Afficher un livre spécifique
     */
    public function show(Livre $livre)
    {
        return view('livres.show', compact('livre'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Livre $livre)
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('livres.edit', compact('livre', 'categories'));
    }

    /**
     * Mettre à jour un livre
     */
    public function update(Request $request, Livre $livre)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255', // 📝 Note: En séance 4, nous transformerons ceci en relation vers un modèle Auteur
            'isbn' => 'required|string|size:13|unique:livres,isbn,' . $livre->id,
            'categorie_id' => 'required|exists:categories,id',
            'resume' => 'nullable|string|max:1000',
            'date_publication' => 'required|date|before_or_equal:today',
            'pages' => 'required|integer|min:1|max:9999',
            'disponible' => 'boolean'
        ]);

        $livre->update($validated);

        return redirect()
            ->route('livres.show', $livre)
            ->with('success', 'Livre mis à jour avec succès !');
    }

    /**
     * Supprimer un livre
     */
    public function destroy(Livre $livre)
    {
        $livre->delete();

        return redirect()
            ->route('livres.index')
            ->with('success', 'Livre supprimé avec succès !');
    }

    /**
     * Recherche de livres avec Eloquent
     * SÉANCE 2 : Utiliser les scopes Eloquent pour la recherche
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        // Utilisation des scopes Eloquent pour la recherche
        $livres = Livre::with('categorie')
            ->when($query, function ($queryBuilder, $searchTerm) {
                return $queryBuilder->recherche($searchTerm);
            })
            ->get();

        return view('livres.search', [
            'livres' => $livres,
            'query' => $query,
            'total' => $livres->count()
        ]);
    }
}
