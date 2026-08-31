<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\InfoFlash;
use Illuminate\Http\Request;

class InfoFlashController extends Controller
{
    public function index()
    {
        try {
            $infoFlashes = InfoFlash::ordered()->get();
            return view('backend.pages.info-flash.index', compact('infoFlashes'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des infos flash: ' . $th->getMessage());
        }
    }

    public function create()
    {
        return view('backend.pages.info-flash.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:255',
                'lien' => 'nullable|url',
                'lien_texte' => 'nullable|string|max:50',
                'type' => 'required|in:info,succes,attention,urgent',
                'position' => 'required|integer|min:1',
                'statut' => 'required|in:active,desactive',
            ]);

            InfoFlash::create($request->only(['message', 'lien', 'lien_texte', 'type', 'position', 'statut']));

            return redirect()->route('info-flash.index')->with('success', 'Info flash créée avec succès');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $infoFlash = InfoFlash::findOrFail($id);
            return view('backend.pages.info-flash.edit', compact('infoFlash'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $infoFlash = InfoFlash::findOrFail($id);

            $request->validate([
                'message' => 'required|string|max:255',
                'lien' => 'nullable|url',
                'lien_texte' => 'nullable|string|max:50',
                'type' => 'required|in:info,succes,attention,urgent',
                'position' => 'required|integer|min:1',
                'statut' => 'required|in:active,desactive',
            ]);

            $infoFlash->update($request->only(['message', 'lien', 'lien_texte', 'type', 'position', 'statut']));

            return redirect()->route('info-flash.index')->with('success', 'Info flash modifiée avec succès');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue: ' . $th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            InfoFlash::findOrFail($id)->delete();
            return response()->json(['status' => 200]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => $th->getMessage()]);
        }
    }
}
