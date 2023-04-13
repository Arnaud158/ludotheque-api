<?php

namespace Database\Seeders;

use App\Models\Adherent;
use Illuminate\Database\Seeder;

class RolesAdherentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Adherent::findOrFail(1);
        $admin->roles()->attach([1, 2, 3, 4]);
        $admin->save();

        $mod = Adherent::findOrFail(2);
        $mod->roles()->attach([2, 3, 4]);
        $mod->save();

        $premium = Adherent::findOrFail(3);
        $premium->roles()->attach([3, 4]);
        $premium->save();

        $adherent = Adherent::findOrFail(4);
        $adherent->roles()->attach([4]);
        $adherent->save();

        for ($i = 5; $i <= AdherentsSeeder::$numberOfUserAdded + 4; $i++) {
            $user = Adherent::findOrFail($i);
            $user->roles()->attach(4);
            $user->save();
        }
    }
}
