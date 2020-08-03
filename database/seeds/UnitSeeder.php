<?php

use App\Model\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->delete();
        Unit::insert(array(
            0 =>
            array(
                'id' => 1,
                'unit_code' => 'pc',
                'unit_name' => 'piece',
                'base_unit' => null,
                'operator' => '*',
                'operation_value' => 1,
                'created_at' => now(),
            ),
            1 =>
            array(
                'id' => 2,
                'unit_code' => 'dozen',
                'unit_name' => 'dozen box',
                'base_unit' => 1,
                'operator' => '*',
                'operation_value' => 12,
                'created_at' => now(),
            ),
            2 =>
            array(
                'id' => 3,
                'unit_code' => 'carton',
                'unit_name' => 'carton box',
                'base_unit' => 1,
                'operator' => '*',
                'operation_value' => 24,
                'created_at' => now(),
            ),
            3 =>
            array(
                'id' => 4,
                'unit_code' => 'm',
                'unit_name' => 'meter',
                'base_unit' => null,
                'operator' => '*',
                'operation_value' => 1,
                'created_at' => now(),
            ),
            4 =>
            array(
                'id' => 5,
                'unit_code' => 'kg',
                'unit_name' => 'Kilogram',
                'base_unit' => 1,
                'operator' => '*',
                'operation_value' => 1,
                'created_at' => now(),
            ),
            5 =>
            array(
                'id' => 6,
                'unit_code' => 'gm',
                'unit_name' => 'gram',
                'base_unit' => 5,
                'operator' => '/',
                'operation_value' => 1000,
                'created_at' => now(),
            )
        ));
    }
}
