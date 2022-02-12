<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configs')->truncate();
        Config::create(['name'=>'vat','value'=>0.18]);
        Config::create(['name' => 'coupon', 'value' => 0.10]);
        Config::create(['name' => 'discount', 'value' => 0.15]);
        Config::create(['name' => 'royalty Price', 'value' => 1500]);
    }
}
