<?php

use Illuminate\Database\Seeder;
use \Faker\Factory as Faker;

class DosenSeeder extends Seeder
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
            $nomor_induk = ($i==1? 'DOSENTEST01': "DOSEN00$i");
            DB::table('dosen')->insert([
                'nama' => ($i %2 == 0? $faker->firstNameMale .' '.$faker->lastName:$faker->firstNameFemale .' '.$faker->lastName),
                'nomor_induk' => $nomor_induk,
                'tempat_lahir' => $faker->city,
                'tgl_lahir' => $faker->dateTimeBetween('-40 years', '-25 years')->format('Y-m-d'),
                'jenis_kelamin' => ($i %2 == 0? 'L':'P'),
				'hp' => $faker->phoneNumber,
				'nidn' => $faker->isbn13,
                'alamat' => $faker->address,
				'agama' =>$faker->randomElement($array = array('Islam','Katolik','Hindu','Protestan')),
                'jurusan_id' => 'C55201'
            ]);
        }
    }
}
