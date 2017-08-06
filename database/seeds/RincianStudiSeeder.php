<?php

use Illuminate\Database\Seeder;
use \Faker\Factory as Faker;

class RincianStudiSeeder extends Seeder
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
            DB::table('rincian_studi')->insert([
				'id' => "rincian$i",
                'kelas_diambil_id' => "pengampu_id0$i",
				'jumlah_kehadiran' => 12,
				'nilai_tugas' => 4,
				'nilai_uts' => 3,
				'nilai_praktikum' => 4,
				'nilai_uas' => 3,
				'nilai_huruf' => 'A',
				'nilai_angka' => 3.50,
				'status_lulus' => "LULUS",
				'rencana_studi_id' => "rencana$i",
            ]);
        }
    }
}
