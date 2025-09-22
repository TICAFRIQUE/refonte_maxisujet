  /** Forgot password - send link with Laravel Password */
  // public function sendResetLink(Request $request)
  // {
  // $request->validate(['email' => 'required|email:rfc,dns']);
  // $status = Password::sendResetLink($request->only('email'));
  // if ($status === Password::RESET_LINK_SENT) {
  // Alert::success('Email envoyé', __($status));
  // return back();
  // }
  // return back()->withErrors(['email' => __($status)]);
  // }











  

  //** Forgot password - send link with sendemail basic */
  // public function sendResetLink(Request $request)
  // {
  // $request->validate(['email' => 'required|email:rfc,dns']);

  // // Vérifier si l'utilisateur existe
  // $user = \App\Models\User::where('email', $request->email)->first();
  // if (!$user) {
  // return back()->withErrors(['email' => "Aucun utilisateur trouvé avec cet email."]);
  // }

  // // Générer le token de reset
  // $token = app('auth.password.broker')->createToken($user);

  // // Générer le lien de reset
  // $resetLink = url(route('password.reset', [
  // 'token' => $token,
  // 'email' => $request->email,
  // ], false));

  // // Rendre la vue Blade dans une variable (contenu HTML de l'email)
  // $htmlContent = View::make('frontend.pages.user.password.email', [
  // 'user' => $user,
  // 'resetLink' => $resetLink,
  // ])->render();

  // // Envoyer l'email
  // Mail::send([], [], function ($message) use ($request, $htmlContent) {
  // $message->to($request->email)
  // ->subject('Mot de passe MaxiSujets')
  // ->html($htmlContent); // ✅ au lieu de setBody
  // });

  // return back()->with('status', 'Un lien de réinitialisation a été envoyé à votre email.');
  // }