<?php

use Illuminate\Database\Seeder;
use \Faker\Factory as Faker;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for($i=0;$i<20;$i++) {
            DB::table('jadwal')->insert([
                'hari' =>$faker->randomElement($array = array('senin','selasa','rabu','kamis','jumat','sabtu')),
                'jam_masuk' =>$faker->randomElement(
						$array = array(
							'06:00:00','07:00:00','08:00:00'
							)
						),
                'jam_keluar' =>$faker->randomElement(
						$array = array(
							'09:00:00','10:00:00','11:00:00'
							)
						),
				'pengampu_id' => "pengampu_id0$i",
				'ruangan_id' => "$i"
            ]);
        }
    }
}
