<?php

use App\UserPosition;
use Illuminate\Database\Seeder;

class UserPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('user_positions')->delete();
        UserPosition::insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Farm Manager',
                'created_at' => now(),
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Commodity Distributor',
                'created_at' => now(),
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'Commodity Retailer',
                'created_at' => now(),
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'Commodity Consumer',
                'created_at' => now(),
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'Logistic Company',
                'created_at' => now(),
            )
        ));
    }
}
