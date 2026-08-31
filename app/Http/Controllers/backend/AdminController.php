<?php

namespace App\Http\Controllers\backend;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    //
    public function login(Request $request)
    {

        if (request()->method() == 'GET') {
            return view('backend.pages.auth-admin.login');
        } elseif (request()->method() == 'POST') {
            $credentials = $request->validate([
                'email' => ['required',],
                'password' => ['required'],
            ]);
            if (Auth::attempt($credentials)) {
                Alert::success('Connexion réussie, bienvenue ' . Auth::user()->username, 'Success Message');
                return redirect()->route('dashboard.index');
            } else {
                // Alert::error('Email ou mot de passe incorrect' , 'Error Message');
                // return back();
                return back()->withError('Email ou mot de passe incorrect');
            }
        }
    }



    //logout admin
    public function logout(Request $request)
    {


        Auth::logout();

        Alert::success('Vous etes deconnecté', 'Success Message');
        return Redirect()->route('admin.login');
    }



    //Liste des users admin

    public function index()
    {

        // Seuls les rôles d'administration : les auteurs (contributeurs inscrits
        // publiquement) ont leur propre écran de gestion, voir AuteurController.
        $data_role = Role::where('name', '!=', 'auteur')->get();

        $data_admin = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', '!=', 'auteur');
        })->get();

        return view('backend.pages.auth-admin.register.index', compact('data_admin', 'data_role'));
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|regex:/^[0-9]{10}$/|unique:users,phone',
                'role' => 'required|exists:roles,name',
                'password' => 'required|min:6',
            ]);

            // Vérifier si le téléphone existe déjà
            if (User::where('phone', $request->phone)->exists()) {
                Alert::error('Le numéro de téléphone existe déjà associé à un utilisateur', 'Erreur');
                return back()->withInput();
            }

            // Vérification supplémentaire pour le numéro de téléphone
            if (!preg_match('/^[0-9]{10}$/', $request->phone)) {
                return back()->with('Erreur', 'Le numéro de téléphone doit contenir exactement 10 chiffres.');
                // Alert::error('Erreur', 'Le numéro de téléphone doit contenir exactement 10 chiffres.');
                // return back();
            }

            // Vérifier si l'email existe déjà
            if (User::where('email', $request->email)->exists()) {
                return back()->with('L\'adresse email existe déjà associé à un utilisateur', 'Erreur');

                // Alert::error('L\'adresse email existe déjà associé à un utilisateur', 'Erreur');
                // return back()->withInput();
            }

            $data_user = User::firstOrCreate([
                'username' => $request['username'],
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);

            if ($request->has('role')) {
                $data_user->assignRole($request['role']);
            }

            Alert::success('Opération réussie', 'Succès');
            return back();
        } catch (\Exception $e) {

            return back()->with('error', 'Une erreur est survenue lors de la création : ' . $e->getMessage());


            // Alert::error('Erreur', $e->getMessage());
            // return back();
        }
    }



    public function update(Request $request, $id)
    {

        try {
            $user = User::findOrFail($id);

            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'required|regex:/^[0-9]{10}$/|unique:users,phone,' . $user->id,
                'role' => 'required|exists:roles,name',
                'password' => 'nullable|string|min:6',
            ]);

            $updateData = [
                'username' => $request['username'],
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            if ($request->has('role')) {
                $user->syncRoles($request['role']);
            }

            Alert::success('Opération réussie', 'Les informations ont été mises à jour');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage());
            // Alert::error('Erreur', 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage());
            return back();
        }
    }

    public function delete($id)
    {
        try {
            if ((string) $id === (string) Auth::id()) {
                return response()->json([
                    'status' => 409,
                    'message' => 'Vous ne pouvez pas supprimer votre propre compte.',
                ]);
            }

            $user = User::whereHas('roles', function ($query) {
                $query->where('name', '!=', 'auteur');
            })->find($id);

            if (!$user) {
                return response()->json(['status' => 404]);
            }

            $remainingAdmins = User::whereHas('roles', function ($query) {
                $query->where('name', '!=', 'auteur');
            })->where('id', '!=', $id)->count();

            if ($remainingAdmins === 0) {
                return response()->json([
                    'status' => 409,
                    'message' => 'Impossible de supprimer le dernier compte administrateur.',
                ]);
            }

            // Suppression douce (récupérable) : jamais de forceDelete depuis cet écran,
            // ce compte peut avoir publié des sujets qu'on ne veut pas perdre en cascade.
            $user->delete();

            return response()->json([
                'status' => 200,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }



    public function profil($id)
    {

        $data_admin = User::find($id);
        $data_role = Role::get();
        return view('backend.pages.auth-admin.register.profil', compact('data_admin', 'data_role'));
    }

    public function changePassword(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {

            Alert::error('Ancien mot de passe incorrect', 'Error Message');
            return back();
        }

        User::whereId($user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        Alert::success('Operation réussi', 'Success Message');
        return back();
    }
}
