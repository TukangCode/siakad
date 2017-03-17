<?php

use Illuminate\Database\Seeder;
use \Faker\Factory as Faker;

class RuanganSeeder extends Seeder
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
            DB::table('ruangans')->insert([
				'ruang' =>$faker->randomElement(
					$array = array(
					'R1A','R2B','LAB-1A','LAB-2B','R2C','R1D','LAB-3A','LAB-3B'
					)
				),
                'keterangan' => 'Ruangan Perkuliahan'
            ]);
        }
    }
}
