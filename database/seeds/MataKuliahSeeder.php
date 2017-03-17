<?php

use Illuminate\Database\Seeder;
use \Faker\Factory as Faker;

class MataKuliahSeeder extends Seeder
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
            $id = "Matkul00$i";
			$kode = "kuliah0$i";
            DB::table('mata_kuliah')->insert([
				'id' => $id,
                'kode' => $kode,
                'nama' => $faker->randomElement(
							$array = array(
							'Rekayasa Perangkat Lunak',
							'Bahasa Indonesia',
							'Pemprograman Framework',
							'Metode Penulisan Ilmiah',
							'Pemprograman Web',
							'Komunikasi Data',
							'Etika Profesi',
							'Algoritma',
							'E-Bisnis',
							'Integensia Buatan',
							'Pemprograman Client Server',
							'Bahasa Inggris',
							'Kalkulus',
							'Dasar Komputer',
							'Matematika Diskrit',
							'Statistik'
							)),
                'sks' => $faker->randomElement($array = array(2,3,4)),
                'semester' => $faker->numberBetween(1,8),
                'jurusan_id' => 'C55201'
            ]);
        }
    }
}
