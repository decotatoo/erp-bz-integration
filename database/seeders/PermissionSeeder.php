<?php

namespace Decotatoo\Bz\Database\Seeders;

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

            [
                'name' => 'bin-list',
                'modul_name' => 'Inventory',
                'submodul_name' => 'Bin Setup',
                'menu_description' => 'View a Bin Setup',
            ],

            [
                'name' => 'bin-create',
                'modul_name' => 'Inventory',
                'submodul_name' => 'Bin Setup',
                'menu_description' => 'Create a New Bin Setup',
            ],

            [
                'name' => 'bin-edit',
                'modul_name' => 'Inventory',
                'submodul_name' => 'Bin Setup',
                'menu_description' => 'Edit a Bin Setup',
            ],

            [
                'name' => 'bin-delete',
                'modul_name' => 'Inventory',
                'submodul_name' => 'Bin Setup',
                'menu_description' => 'Delete a Bin Setup',
            ],




























            [
                'name' => 'unit-box-list',
                'modul_name' => 'Inventory',
                'submodul_name' => 'Unit Boxes Setup',
                'menu_description' => 'View a Unit Box Setup',
            ],

            [
                'name' => 'unit-box-create',
                'modul_name' => 'Inventory',
                'submodul_name' => 'Unit Boxes Setup',
                'menu_description' => 'Create a New Unit Box Setup',
            ],

            [
                'name' => 'unit-box-edit',
                'modul_name' => 'Inventory',
                'submodul_name' => 'Unit Boxes Setup',
                'menu_description' => 'Edit a Unit Box Setup',
            ],

            [
                'name' => 'unit-box-delete',
                'modul_name' => 'Inventory',
                'submodul_name' => 'Unit Boxes Setup',
                'menu_description' => 'Delete a Unit Box Setup',
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
