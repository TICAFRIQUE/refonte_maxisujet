<?php

use App\Models\Module;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    private const ROLES_PLEINS_POUVOIRS = ['superadmin', 'administrateur', 'developpeur'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $module = Module::firstOrCreate(['name' => 'info-flash']);

        $permissions = collect(['creer-', 'voir-', 'modifier-', 'supprimer-'])
            ->map(fn ($prefix) => Permission::firstOrCreate([
                'name' => $prefix . 'info-flash',
                'module_id' => $module->id,
                'guard_name' => 'web',
            ]));

        Role::whereIn('name', self::ROLES_PLEINS_POUVOIRS)->get()
            ->each(fn ($role) => $role->givePermissionTo($permissions));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $module = Module::where('name', 'info-flash')->first();
        if ($module) {
            Permission::where('module_id', $module->id)->delete();
            $module->delete();
        }
    }
};
