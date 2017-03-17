<?php

use Illuminate\Database\Seeder;
use \Faker\Factory as Faker;

class RencanaStudiSeeder extends Seeder
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
            $nomor_induk = ($i==1? 'CMHSTEST01': "CXX90909$i");
            DB::table('rencana_studi')->insert([
				'id' => "rencana$i",
                'tahun_ajaran' => '2016',
                'mahasiswa_id' => $nomor_induk,
                'tgl_pengisian' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
                'tgl_pengajuan' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
                'status' => 'Aktif',
                'ips' => $faker->randomFloat(2,2,4)
            ]);
        }
    }
}
