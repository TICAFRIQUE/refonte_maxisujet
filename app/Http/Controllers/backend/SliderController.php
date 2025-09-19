<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
       try {
            $sliders = Slider::all();
            return view('backend.pages.slider.index', compact('sliders'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des sliders');
        }
    }

    public function create()
    {
        return view('backend.pages.slider.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string',
                'bouton_text' => 'nullable|string|max:255',
                'bouton_url' => 'nullable|url',
                'position' => 'required|integer|min:1',
                'statut' => 'required|in:active,desactive',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB max
            ]);

            $slider = Slider::create($request->only([
                'titre',
                'description',
                'bouton_text',
                'bouton_url',
                'position',
                'statut'
            ]));

            if ($request->hasFile('image')) {
                $slider->addMediaFromRequest('image')->toMediaCollection('slider');
            }

            return redirect()->route('slider.index')
                ->with('success', 'Slider créé avec succès');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
     try {
            $slider = Slider::findOrFail($id);
            return view('backend.pages.slider.edit', compact('slider'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $slider = Slider::findOrFail($id);

            $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string',
                'bouton_text' => 'nullable|string|max:255',
                'bouton_url' => 'nullable|url',
                'position' => 'required|integer|min:1',
                'statut' => 'required|in:active,desactive',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB max
            ]);

            $slider->update($request->only([
                'titre',
                'description',
                'bouton_text',
                'bouton_url',
                'position',
                'statut'
            ]));

            if ($request->hasFile('image')) {
                $slider->clearMediaCollection('slider');
                $slider->addMediaFromRequest('image')->toMediaCollection('slider');
            }

            return redirect()->route('slider.index')
                ->with('success', 'Slider modifié avec succès');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue: ' . $th->getMessage());
        }
    }

    public function delete($id)
    {
        try {


            $slider = Slider::findOrFail($id);
            $slider->clearMediaCollection('slider'); // supprimer l'image associée au slider
            $slider->delete();
            return response()->json(['success' => true, 'message' => 'Slider supprimé avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression: ' . $th->getMessage()]);
        }
    }
}
