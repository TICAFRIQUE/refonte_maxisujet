<?php

namespace App\Http\Controllers\backend;

use App\Models\Parametre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ParametreController extends Controller
{
    public function index()
    {
        $data_parametre = Parametre::with('media')->first();

        //get status mode maintenance
        // $data_maintenance = Maintenance::latest()->select('type')->first();

        // recuperer la liste des sauvegardes du projet
        $appName = config('app.name');
        $backup = Storage::disk('local')->files('' . $appName . '/');


        // dd($data_parametre->toArray());
        return view('backend.pages.parametre.index', compact('data_parametre',  'backup'));
    }

    // Télécharger un fichier de sauvegarde
    public function downloadBackup($file)
    {
        // $path = "Restaurant/" . $file;

        // if (Storage::disk('local')->exists($path)) {
        //     return Storage::disk('local')->download($path);
        // }
        $appName = config('app.name');
        $path = storage_path("app/private/" . $appName . "/" . $file);

        if (file_exists($path)) {
            return response()->download($path);
        }

        Alert::error('Fichier non trouvé.', 'Error Message');

        return back();

        // return redirect()->back()->with('error', 'Fichier non trouvé.');
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'nom_projet' => 'nullable|string|max:255',
                'email1' => 'nullable|email|max:255',
                'email2' => 'nullable|email|max:255',
                'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                'logo_header' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
                'logo_footer' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            ]);

            //verify if data exist
            $data_exist = Parametre::with('media')->first();

            if ($data_exist) {
                //insert data
                //update data if record exist
                $data_parametre = tap($data_exist)->update([
                    'lien_facebook' => $request['lien_facebook'],
                    'lien_instagram' => $request['lien_instagram'],
                    'lien_twitter' => $request['lien_twitter'],
                    'lien_linkedin' => $request['lien_linkedin'],
                    'lien_tiktok' => $request['lien_tiktok'],

                    //infos application
                    'nom_projet' => $request['nom_projet'],
                    'description_projet' => $request['description_projet'],
                    'contact1' => $request['contact1'],
                    'contact2' => $request['contact2'],
                    'contact3' => $request['contact3'],

                    'email1' => $request['email1'],
                    'email2' => $request['email2'],

                    'localisation' => $request['localisation'],
                    'google_maps' => $request['google_maps'],
                    'siege_social' => $request['siege_social'],

                    //security
                    // 'mode_maintenance'=>'',
                ]);

                // Remplace le fichier existant de chaque collection (clearMediaCollection
                // est un no-op sans danger si la collection est déjà vide).
                foreach (['cover', 'logo_header', 'logo_footer'] as $collection) {
                    if ($request->hasFile($collection)) {
                        $data_parametre->clearMediaCollection($collection);
                        $data_parametre->addMediaFromRequest($collection)->toMediaCollection($collection);
                    }
                }
            } else {
                $data_parametre = Parametre::create([
                    'lien_facebook' => $request['lien_facebook'],
                    'lien_instagram' => $request['lien_instagram'],
                    'lien_twitter' => $request['lien_twitter'],
                    'lien_linkedin' => $request['lien_linkedin'],
                    'lien_tiktok' => $request['lien_tiktok'],

                    //infos application
                    'nom_projet' => $request['nom_projet'],
                    'description_projet' => $request['description_projet'],
                    'contact1' => $request['contact1'],
                    'contact2' => $request['contact2'],
                    'contact3' => $request['contact3'],

                    'email1' => $request['email1'],
                    'email2' => $request['email2'],

                    'localisation' => $request['localisation'],
                    'google_maps' => $request['google_maps'],
                    'siege_social' => $request['siege_social'],

                    //security
                    // 'mode_maintenance'=>'',
                ]);

                foreach (['cover', 'logo_header', 'logo_footer'] as $collection) {
                    if ($request->hasFile($collection)) {
                        $data_parametre->addMediaFromRequest($collection)->toMediaCollection($collection);
                    }
                }
            }



            Alert::success('Operation réussi', 'Success Message');

            return back();
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }


    /**
     * Supprime les fichiers de cache de l'application.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function optimizeClear()
    {
        Artisan::call('optimize:clear');
        return response()->json(['message' => 'cache clear', 'status' => 200], 200);
    }


    /**
     * Desactive le mode maintenance de l'application.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function maintenanceUp()
    {

        Artisan::call('up');
        Parametre::first()->update([
            'mode_maintenance' => 'up',
        ]);
        return response()->json(['message' => 'mode maintenance desactivé', 'status' => 200], 200);
    }


    /**
     * Active le mode maintenance de l'application.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function maintenanceDown()
    {

        Artisan::call('down', [
            '--secret' => 'admin@',
            '--render' => 'backend.pages.maintenance-mode-view',
        ]);
        Parametre::first()->update([
            'mode_maintenance' => 'down',
        ]);
        return response()->json(['message' => 'mode maintenance activé', 'status' => 200], 200);
    }
}
