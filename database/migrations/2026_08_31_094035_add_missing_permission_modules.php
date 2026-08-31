<?php

use App\Models\Module;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Rôles à pleins pouvoirs qui reçoivent automatiquement toute permission créée
     * (même logique que ModuleController::store()).
     */
    private const ROLES_PLEINS_POUVOIRS = ['superadmin', 'administrateur', 'developpeur'];

    /**
     * Modules qui existaient déjà côté back-office (routes de mutation actives) mais
     * n'avaient jamais eu de module/permissions créés pour eux : leurs routes de
     * création/modification/suppression n'étaient donc protégées que par le contrôle
     * d'accès large du middleware "admin", pas par une permission précise.
     */
    private const MODULES_A_CREER = ['parametre', 'compte-admin', 'auteur', 'permission', 'module', 'slider', 'rubrique'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roles = Role::whereIn('name', self::ROLES_PLEINS_POUVOIRS)->get();

        foreach (self::MODULES_A_CREER as $moduleName) {
            $module = Module::firstOrCreate(['name' => $moduleName]);

            $permissions = collect(['creer-', 'voir-', 'modifier-', 'supprimer-'])
                ->map(fn ($prefix) => Permission::firstOrCreate([
                    'name' => $prefix . $moduleName,
                    'module_id' => $module->id,
                    'guard_name' => 'web',
                ]));

            $roles->each(fn ($role) => $role->givePermissionTo($permissions));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $modules = Module::whereIn('name', self::MODULES_A_CREER)->get();

        Permission::whereIn('module_id', $modules->pluck('id'))->delete();
        Module::whereIn('id', $modules->pluck('id'))->delete();
    }
};
