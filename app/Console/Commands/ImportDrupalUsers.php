<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportDrupalUsers extends Command
{
    /**
     * Points offerts aux comptes importés (choix du client, différent des points
     * d'inscription "normaux" — voir PointsService::POINTS_INSCRIPTION).
     */
    private const POINTS_COMPTE_IMPORTE = 100;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:drupal-users
        {path=recovery/maxi_users.sql : Chemin du dump SQL de la table maxi_users}
        {--dry-run : Analyse et affiche le résumé sans rien écrire en base}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importe les comptes de l\'ancien site Drupal (name, email, mot de passe) dans la nouvelle table users. Le mot de passe Drupal est conservé tel quel : la vérification/migration se fait au premier login (voir UserControlleur::login).';

    public function handle(): int
    {
        $path = base_path($this->argument('path'));
        $dryRun = (bool) $this->option('dry-run');

        if (!file_exists($path)) {
            $this->error("Fichier introuvable : {$path}");
            return self::FAILURE;
        }

        $tmpTable = '_import_drupal_users_' . time();
        $sql = str_replace('`maxi_users`', "`{$tmpTable}`", file_get_contents($path));

        $this->info('Chargement du dump dans une table temporaire...');
        DB::unprepared($sql);

        try {
            $rows = DB::table($tmpTable)->where('uid', '>', 0)->orderBy('uid')->get();
            $this->info(count($rows) . ' comptes trouvés dans l\'export (hors utilisateur anonyme uid=0).');

            $existingEmails = DB::table('users')->whereNotNull('email')->pluck('email')
                ->map(fn ($email) => Str::lower($email))->flip();

            $roleAuteurId = DB::table('roles')->where('name', 'auteur')->value('id');
            if (!$roleAuteurId) {
                $this->error('Le rôle "auteur" est introuvable — importez d\'abord les rôles/permissions.');
                return self::FAILURE;
            }

            $toInsert = [];
            $roleAssignments = [];
            $skippedNoEmail = 0;
            $skippedExisting = 0;
            $now = Carbon::now();

            foreach ($rows as $row) {
                $email = Str::lower(trim($row->mail ?? ''));

                if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $skippedNoEmail++;
                    continue;
                }

                if ($existingEmails->has($email)) {
                    $skippedExisting++;
                    continue;
                }
                // Empêche aussi les doublons à l'intérieur même de l'export (garde le premier).
                $existingEmails->put($email, true);

                $userId = IdGenerator::generate(['table' => 'users', 'length' => 10, 'prefix' => mt_rand()]);

                $toInsert[] = [
                    'id' => $userId,
                    'username' => trim($row->name) !== '' ? trim($row->name) : $email,
                    'email' => $email,
                    'phone' => null,
                    'password' => $row->pass, // hash Drupal "$S$..." conservé tel quel, migré au premier login
                    'role' => 'auteur',
                    'profil' => null,
                    'points' => self::POINTS_COMPTE_IMPORTE,
                    'statut' => ((int) $row->status === 1) ? 'active' : 'desactive',
                    'last_login_at' => $row->login > 0 ? Carbon::createFromTimestamp($row->login) : null,
                    'created_at' => $row->created > 0 ? Carbon::createFromTimestamp($row->created) : $now,
                    'updated_at' => $now,
                ];

                $roleAssignments[] = [
                    'role_id' => $roleAuteurId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $userId,
                ];
            }

            $this->info('À importer : ' . count($toInsert));
            $this->info('Ignorés (email déjà présent) : ' . $skippedExisting);
            $this->info('Ignorés (email manquant/invalide) : ' . $skippedNoEmail);

            if ($dryRun) {
                $this->warn('Mode --dry-run : rien n\'a été écrit en base.');
                return self::SUCCESS;
            }

            DB::transaction(function () use ($toInsert, $roleAssignments) {
                foreach (array_chunk($toInsert, 500) as $chunk) {
                    DB::table('users')->insert($chunk);
                }
                foreach (array_chunk($roleAssignments, 500) as $chunk) {
                    DB::table('model_has_roles')->insert($chunk);
                }
            });

            $this->info('Import terminé avec succès.');
            return self::SUCCESS;
        } finally {
            DB::statement("DROP TABLE IF EXISTS `{$tmpTable}`");
        }
    }
}
