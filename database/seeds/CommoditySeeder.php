<?php

use App\Commodity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommoditySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('commodities')->delete();
        Commodity::insert(
            [
                [
                    'name' => 'Yam',
                    'created_at' => now()
                ],
                [
                    'name' => 'Maize',
                    'created_at' => now()
                ],
                [
                    'name' => 'Cassava',
                    'created_at' => now()
                ],
                [
                    'name' => 'Potato',
                    'created_at' => now()
                ],
                [
                    'name' => 'Tomato and Pepper',
                    'created_at' => now()
                ],
                [
                    'name' => 'Cashew',
                    'created_at' => now()
                ],
                [
                    'name' => 'Mango',
                    'created_at' => now()
                ],
                [
                    'name' => 'Pear',
                    'created_at' => now()
                ],
                [
                    'name' => 'Rice',
                    'created_at' => now()
                ],
                [
                    'name' => 'Beans',
                    'created_at' => now()
                ],
                [
                    'name' => 'Watermelon',
                    'created_at' => now()
                ],
                [
                    'name' => 'Melon',
                    'created_at' => now()
                ],
                [
                    'name' => 'Cherry',
                    'created_at' => now()
                ]
            ]
        );
    }
}
