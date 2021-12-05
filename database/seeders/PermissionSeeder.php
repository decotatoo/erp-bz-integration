<?php

namespace Decotatoo\WoocommerceIntegration\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'TODO-PERMISSION-WI',
                'modul_name' => 'TODO-PERMISSION-WI',
                'submodul_name' => 'TODO-PERMISSION-WI',
                'menu_description' => 'TODO-PERMISSION-WI',
            ],

        ];


        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'modul_name' => $permission['modul_name'],
                    'submodul_name' => $permission['submodul_name'],
                    'menu_description' => $permission['menu_description'],
                ]
            );
        }
    }
}
