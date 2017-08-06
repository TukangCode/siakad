<?php

use Illuminate\Database\Seeder;
use \Faker\Factory as Faker;

class PengumumanSeeder extends Seeder
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
            DB::table('pengumuman')->insert([
                'perihal' => 
					$faker->randomElement($array = array('Workshop','Seminar','Pelatihan'))
					.' '.
					$faker->randomElement($array = array('Pemprograman','Peningkatan SDM','Penguasaan Teknologi')),
                'keterangan' => $faker->paragraph(3,true),
				'dosen_id' => ($i %2 == 0? "DOSENTEST01": "DOSEN002")
            ]);
        }
    }
}
