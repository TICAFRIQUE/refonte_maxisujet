<?php

namespace App\Http\Controllers\Backend;

use App\Models\Rubrique;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class RubriqueController extends Controller
{
    public function index(Request $request)
    {
        $query = Rubrique::with('auteur');

        // Filtres
        if ($request->filled('type_rubrique')) {
            $query->where('type_rubrique', $request->type_rubrique);
        }

        if ($request->filled('statut')) {
            $est_publie = $request->statut === 'publie';
            $query->where('est_publie', $est_publie);
        }

        if ($request->filled('recherche')) {
            $query->where(function ($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->recherche . '%')
                  ->orWhere('contenu', 'like', '%' . $request->recherche . '%');
            });
        }

        $rubriques = $query->orderBy('created_at', 'desc')->paginate(15);
        $typesRubriques = Rubrique::getTypesRubriques();

        return view('backend.pages.rubrique.index', compact('rubriques', 'typesRubriques'));
    }

    public function create()
    {
        $typesRubriques = Rubrique::getTypesRubriques();
        return view('backend.pages.rubrique.create', compact('typesRubriques'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'resume' => 'nullable|string|max:500',
            'type_rubrique' => 'required|in:' . implode(',', array_keys(Rubrique::getTypesRubriques())),
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'est_publie' => 'boolean',
            'est_featured' => 'boolean',
            'ordre_affichage' => 'integer|min:0',
            'tags' => 'nullable|string',
            'date_publication' => 'nullable|date'
        ]);

        $donnees = $request->except('image_principale', 'tags');
        
        // Traitement des tags
        if ($request->filled('tags')) {
            $donnees['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $donnees['auteur_id'] =Auth::user()->id;
        $donnees['slug'] = Str::slug($request->titre);

        if (!$request->filled('date_publication') && $request->est_publie) {
            $donnees['date_publication'] = now();
        }

        $rubrique = Rubrique::create($donnees);

        // Gestion de l'image principale
        if ($request->hasFile('image_principale')) {
            $rubrique->addMediaFromRequest('image_principale')
                     ->toMediaCollection('image_principale');
        }

        Alert::success('Succès', 'Rubrique créée avec succès !');
        return redirect()->route('backend.rubrique.index');
    }

    public function show(Rubrique $rubrique)
    {
        return view('backend.pages.rubrique.show', compact('rubrique'));
    }

    public function edit(Rubrique $rubrique)
    {
        $typesRubriques = Rubrique::getTypesRubriques();
        return view('backend.pages.rubrique.edit', compact('rubrique', 'typesRubriques'));
    }

    public function update(Request $request, Rubrique $rubrique)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'resume' => 'nullable|string|max:500',
            'type_rubrique' => 'required|in:' . implode(',', array_keys(Rubrique::getTypesRubriques())),
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'est_publie' => 'boolean',
            'est_featured' => 'boolean',
            'ordre_affichage' => 'integer|min:0',
            'tags' => 'nullable|string',
            'date_publication' => 'nullable|date'
        ]);

        $donnees = $request->except('image_principale', 'tags');
        
        // Traitement des tags
        if ($request->filled('tags')) {
            $donnees['tags'] = array_map('trim', explode(',', $request->tags));
        } else {
            $donnees['tags'] = null;
        }

        if (!$request->filled('date_publication') && $request->est_publie && !$rubrique->est_publie) {
            $donnees['date_publication'] = now();
        }

        $rubrique->update($donnees);

        // Gestion de l'image principale
        if ($request->hasFile('image_principale')) {
            $rubrique->clearMediaCollection('image_principale');
            $rubrique->addMediaFromRequest('image_principale')
                     ->toMediaCollection('image_principale');
        }

        Alert::success('Succès', 'Rubrique modifiée avec succès !');
        return redirect()->route('backend.rubrique.index');
    }

    public function destroy(Rubrique $rubrique)
    {
        $rubrique->clearMediaCollection('image_principale');
        $rubrique->clearMediaCollection('images_contenu');
        $rubrique->delete();

        Alert::success('Succès', 'Rubrique supprimée avec succès !');
        return redirect()->route('backend.rubrique.index');
    }

    public function toggleStatut(Rubrique $rubrique)
    {
        $rubrique->update([
            'est_publie' => !$rubrique->est_publie,
            'date_publication' => !$rubrique->est_publie ? now() : $rubrique->date_publication
        ]);

        $statut = $rubrique->est_publie ? 'publié' : 'masqué';
        Alert::success('Succès', "Rubrique {$statut} avec succès !");
        
        return back();
    }

    public function toggleFeatured(Rubrique $rubrique)
    {
        $rubrique->update(['est_featured' => !$rubrique->est_featured]);
        
        $statut = $rubrique->est_featured ? 'mise en avant' : 'retirée de la mise en avant';
        Alert::success('Succès', "Rubrique {$statut} avec succès !");
        
        return back();
    }
}
