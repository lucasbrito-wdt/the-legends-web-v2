<?php

use Illuminate\Database\Seeder;

class MoneyOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('z_money_offer')->insert([
            [
                'description' => '200 The Legend\'s Points',
                'points' => '200',
                'value' => 20,
                'points_additional' => '10',
                'item_id' => '13132',
                'item_name' => '',
            ],
            [
                'description' => '400 The Legend\'s Points',
                'points' => '400',
                'value' => 40,
                'points_additional' => '30',
                'item_id' => '13132',
                'item_name' => '',
            ],
            [
                'description' => '600 The Legend\'s Points',
                'points' => '600',
                'value' => 60,
                'points_additional' => '60',
                'item_id' => '13132',
                'item_name' => '',
            ],
            [
                'description' => '800 The Legend\'s Points',
                'points' => '800',
                'value' => 80,
                'points_additional' => '80',
                'item_id' => '13132',
                'item_name' => '',
            ],
            [
                'description' => '1500 The Legend\'s Points',
                'points' => '1500',
                'value' => 150,
                'points_additional' => '150',
                'item_id' => '13132',
                'item_name' => '',
            ],
            [
                'description' => '1800 The Legend\'s Points',
                'points' => '1800',
                'value' => 180,
                'points_additional' => '180',
                'item_id' => '13132',
                'item_name' => '',
            ],
            [
                'description' => '2100 The Legend\'s Points',
                'points' => '2100',
                'value' => 210,
                'points_additional' => '210',
                'item_id' => '13132',
                'item_name' => '',
            ],
            [
                'description' => '2300 The Legend\'s Points',
                'points' => '2300',
                'value' => 230,
                'points_additional' => '230',
                'item_id' => '13132',
                'item_name' => '',
            ],
            [
                'description' => '2600 The Legend\'s Points',
                'points' => '2600',
                'value' => 260,
                'points_additional' => '260',
                'item_id' => '13132',
                'item_name' => '',
            ],
            [
                'description' => '4000 The Legend\'s Points',
                'points' => '4000',
                'value' => 400,
                'points_additional' => '400',
                'item_id' => '13132',
                'item_name' => '',
            ],
            [
                'description' => '4400 The Legend\'s Points',
                'points' => '4400',
                'value' => 440,
                'points_additional' => '400',
                'item_id' => '13132',
                'item_name' => '',
            ],
        ]);
    }
}
