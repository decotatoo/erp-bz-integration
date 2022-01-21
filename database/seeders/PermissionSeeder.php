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
                'name' => 'bin-packer-visualiser',
                'modul_name' => 'Packing Management',
                'submodul_name' => 'Bin Packer',
                'menu_description' => 'Visualise Packing Simulation',
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

            

            [
                'name' => 'commerce-category-list',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Website Category',
                'menu_description' => 'View a Website Category',
            ],
            [
                'name' => 'commerce-category-create',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Website Category',
                'menu_description' => 'Create a New Website Category',
            ],
            [
                'name' => 'commerce-category-edit',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Website Category',
                'menu_description' => 'Edit a Website Category',
            ],
            [
                'name' => 'commerce-category-delete',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Website Category',
                'menu_description' => 'Delete a Website Category',
            ],

            

            [
                'name' => 'commerce-catalog-list',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Website Catalog',
                'menu_description' => 'View a Website Catalog',
            ],
            [
                'name' => 'commerce-catalog-create',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Website Catalog',
                'menu_description' => 'Create a New Website Catalog',
            ],
            [
                'name' => 'commerce-catalog-edit',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Website Catalog',
                'menu_description' => 'Edit a Website Catalog',
            ],
            [
                'name' => 'commerce-catalog-delete',
                'modul_name' => 'Website Management',
                'submodul_name' => 'Website Catalog',
                'menu_description' => 'Delete a Website Catalog',
            ],













            [
                'name' => 'sales-order-online-list',
                'modul_name' => 'Sales Order',
                'submodul_name' => 'Sales Order [ONLINE]',
                'menu_description' => 'View the Sales Order [ONLINE] list',
            ],
            [
                'name' => 'sales-order-online-edit',
                'modul_name' => 'Sales Order',
                'submodul_name' => 'Sales Order [ONLINE]',
                'menu_description' => 'Edit a Sales Order [ONLINE]',
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
