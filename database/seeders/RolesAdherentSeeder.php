<?php

namespace Database\Seeders;

use App\Models\Adherent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $mod->roles()->attach([1, 2, 3]);
        $mod->save();

        $premium = Adherent::findOrFail(3);
        $premium->roles()->attach([1, 2]);
        $premium->save();

        $adherent = Adherent::findOrFail(4);
        $adherent->roles()->attach([1]);
        $adherent->save();

        for ($i = 5; $i <= AdherentsSeeder::$numberOfUserAdded + 4; $i++) {
            $user = Adherent::findOrFail($i);
            for ($j = $i; $j <= 5; $j++) {
                $user->roles()->attach($j);
            }
            $user->save();
        }
    }
}
