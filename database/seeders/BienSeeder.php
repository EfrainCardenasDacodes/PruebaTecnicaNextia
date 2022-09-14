<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class BienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filename = storage_path('app/BienSeed.csv');
        $file = fopen($filename,"r");

        while(! feof($file)) {
            $data = fgetcsv($file)

            DB::table('Bienes')->insert([
                [
                    'id' => $data[0],
                    "articulo" => $data[1],
                    "descripcion" => $data[2],
                    'updated_at' => new UTCDateTime(),
                    'created_at' => new UTCDateTime()
                ]
            ]);
        }

        fclose($file);
    }
}
