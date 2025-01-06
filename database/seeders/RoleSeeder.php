<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->truncate();
        DB::table('model_has_roles')->truncate();

        $roles = [
            'DEO', 'SO-AB', 'SED', 'LIAISON-OFFICER', 'CEO-DEA', 'PMIU', 'QAED', 'PCTB',
            'PEF', 'PEIMA', 'PEC', 'PTF', 'NMST', 'DAANISH AUTHORITY', 'CLC', 'Assembly',
            'Minister', 'AS', 'DS',   'SS', 'Super Admin', 'Admin', 'Sectary'
        ];

        // Create roles
        foreach ($roles as $roleName) {
            Role::create(['name' => $roleName]);
        }
    }
}
