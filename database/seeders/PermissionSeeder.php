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
            // Permission Commerce Categories

            [
                'name' => 'commerce-category-list',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Commerce Categories',
                'menu_description' => 'View a Commerce Categories',
            ],

            [
                'name' => 'commerce-category-create',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Commerce Categories',
                'menu_description' => 'Create a New Commerce Categories',
            ],

            [
                'name' => 'commerce-category-edit',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Commerce Categories',
                'menu_description' => 'Update a Commerce Categories',
            ],

            [
                'name' => 'commerce-category-delete',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Commerce Categories',
                'menu_description' => 'Delete a Commerce Categories',
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
