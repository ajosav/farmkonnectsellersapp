<?php

use App\UserPosition;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserPositionSeeder::class);
        $this->call(CommoditySeeder::class);
        $this->call(UnitSeeder::class);

        $positions = UserPosition::select('name')->get();
        if(count(Permission::all()) <= 0) {
            foreach ($positions as $position) {
                Permission::create(['name' => $position->name]);
            }
        }

    }
}
