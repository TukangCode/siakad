<?php

use Illuminate\Database\Seeder;
use \Faker\Factory as Faker;

class PengampuKelasSeeder extends Seeder
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
            $pengampu_id = "pengampu_id0$i";
			$matkul_id = "Matkul00$i";
			$dosen_id = ($i %2 == 0? "DOSENTEST01": "DOSEN002");
            DB::table('Pengampu_kelas')->insert([
				'id' => $pengampu_id,
				'tahun_ajaran' => '2016',
				'tgl_penetapan' => $faker->dateTimeBetween('-2 years', '-1 years')->format('Y-m-d'),
				'kelas' => 'A',
				'jumlah_peminat' => 100,
				'jumlah_pengambil' => 20,
                'dosen_id' => $dosen_id,
                'mata_kuliah_id' => $matkul_id
            ]);
        }
    }
}
