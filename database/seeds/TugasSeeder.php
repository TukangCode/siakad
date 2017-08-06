<?php

use Illuminate\Database\Seeder;
use \Faker\Factory as Faker;

class TugasSeeder extends Seeder
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
            DB::table('tugas')->insert([
                'nama_tugas' => $faker->sentence(6,true),
                'keterangan' => $faker->paragraph(3,true),
                'deadline' => $faker->dateTimeBetween('now', '+10 days')->format('Y-m-d'),
				'pengampu_id' => "pengampu_id0$i",
            ]);
        }
    }
}
