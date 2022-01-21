<?php

namespace Decotatoo\Bz\Database\Seeders;

use App\Models\Rate;
use Decotatoo\Bz\Models\CommerceCatalog;
use Illuminate\Database\Seeder;

class CommerceCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $catalogs = [
            [
                'id' => 1,
                'name' => 'Autumn-Winter',
                'is_published' => false,
            ],
            [
                'id' => 2,
                'name' => 'Spring-Summer',
                'is_published' => true,
            ],
        ];

        foreach ($catalogs as $catalog) {
            CommerceCatalog::updateOrCreate(
                ['id' => $catalog['id']],
                [
                    'name' => $catalog['name'],
                    'is_published' => $catalog['is_published'],
                ]
            );
        }
    }
}
