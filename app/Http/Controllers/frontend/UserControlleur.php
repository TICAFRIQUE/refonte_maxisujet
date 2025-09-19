<?php

namespace App\Http\Controllers\frontend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Http;

class UserControlleur extends Controller
{




    /**register form */
    public function registerForm()
    {
        try {
            return view('frontend.pages.user.register');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }


    /**register user */
    public function register(Request $request)
    {
        $request->validate([
            'profil' => 'required|string|in:eleve,etudiant,enseignant,parent',
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:users,username'
            ],
            // 'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'required|email:rfc,dns|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
            'g-recaptcha-response' => 'required',
        ], [
            'username.regex' => "Le nom d'utilisateur ne peut contenir que des lettres, chiffres, points, tirets et underscores.",
            'terms.accepted' => 'Vous devez accepter les conditions pour continuer.',
            'g-recaptcha-response.required' => 'Veuillez valider le reCAPTCHA.',
        ]);

        // Vérification du reCAPTCHA côté serveur
        $recaptcha = $request->input('g-recaptcha-response');
        $secret = env('NOCAPTCHA_SECRET');
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $recaptcha,
            'remoteip' => $request->ip(),
        ]);
        if (!$response->json('success')) {
            Alert::error('Erreur', 'La vérification reCAPTCHA a échoué.');
            return back()->withInput();
        }

        try {
            $user = new User();
            $user->profil = $request->profil;
            $user->username = trim($request->username);
            $user->phone = $request->phone;
            $user->email = strtolower(trim($request->email));
            $user->role = $request->role ?? 'auteur';
            $user->password = Hash::make($request->password);
            $user->save();

            // Donner des points d'inscription
            $pointsService = new \App\Services\PointsService();
            $pointsService->giveRegistrationPoints($user);


            Auth::login($user);

            Alert::success('Inscription réussie', 'Bienvenue sur MaxiSujets !');
            return redirect()->route('accueil');
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Erreur lors de l\'inscription : ' . $e->getMessage());
            return back()->withInput();
        }
    }




    /**login form */
    public function loginForm()
    {
        try {
            return view('frontend.pages.user.login');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }
    /**login user */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // email ou username
            'password' => 'required|string',
            // 'remember' => 'nullable|boolean',
        ]);

        try {
            $login = $request->input('login');
            $password = $request->input('password');
            // $remember = (bool) $request->boolean('remember');

            // Déterminer si le login est un email ou un username
            $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            if (Auth::attempt([$field => $login, 'password' => $password])) {

                // Donner des points de connexion quotidienne
                $user = Auth::user();
                $pointsService = new \App\Services\PointsService();
                $pointsService->giveDailyLoginPoints($user);

                Alert::success('Bienvenue', 'Connexion réussie.');
                return redirect()->intended(route('accueil'));
            }

            return back()
                ->withErrors(['login' => "Identifiants incorrects. Veuillez vérifier vos informations de connexion."])
                ->withInput($request->only('login', 'remember'));
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Erreur lors de la connexion : ' . $e->getMessage());
            return back()->withInput($request->only('login', 'remember'));
        }
    }


    /**logout user */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            Alert::success('Déconnexion réussie', 'Vous avez été déconnecté avec succès.');
            return redirect()->route('accueil');
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Erreur lors de la déconnexion : ' . $e->getMessage());
            return back();
        }
    }

    /** Forgot password - show form */
    public function showForgot()
    {
        return view('frontend.pages.user.password.forgot');
    }

    /** Forgot password - send link */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email:rfc,dns']);
        $status = Password::sendResetLink($request->only('email'));
        if ($status === Password::RESET_LINK_SENT) {
            Alert::success('Email envoyé', __($status));
            return back();
        }
        return back()->withErrors(['email' => __($status)]);
    }

    /** Reset password - show form */
    public function showReset(string $token)
    {
        return view('frontend.pages.user.password.reset', ['token' => $token]);
    }

    /** Reset password - handle */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            Alert::success('Mot de passe réinitialisé', __($status));
            return redirect()->route('user.loginForm');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
