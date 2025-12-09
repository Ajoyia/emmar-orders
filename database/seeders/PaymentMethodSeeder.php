<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('payment_methods')->truncate();
        $data = [
            [
                'name' => 'Cash',
                'emmar_mapping' => 'Ch_DineIn',
                'payment_id' => '95687127-ab3e-42bf-afe5-f71512106110'
            ],
            [
                'name' => 'Gift Card',
                'emmar_mapping' => 'Ch_DineIn',
                'payment_id' => '95687127-ad4a-4389-8d1e-f4377c5690dc'
            ],[
                'name' => 'House Account',
                'emmar_mapping' => 'Ch_DineIn',
                'payment_id' => '95687127-af16-4908-ae2b-4140a1d012ce'
            ],[
                'name' => 'Card',
                'emmar_mapping' => 'Ch_DineIn',
                'payment_id' => '957a4434-58ad-44c8-b5c8-a029dd294720'
            ], [
                'name' => 'DRIVU CASH',
                'emmar_mapping' => 'Ch_Drivu',
                'payment_id' => '957a4440-37a1-4a81-9fd0-95b1d61819d2'
            ], [
                'name' => 'DRIVU CARD',
                'emmar_mapping' => 'Ch_Drivu',
                'payment_id' => '957a4449-b9f2-4eb0-b5ce-bd84f2ea728e'
            ], [
                'name' => 'TALABAT SIMIT',
                'emmar_mapping' => 'Ch_Talabat',
                'payment_id' => '957a4455-08ee-471b-8803-bffbd7d86f4a'
            ], [
                'name' => 'TALABAT TERIYAKI',
                'emmar_mapping' => 'Ch_Talabat',
                'payment_id' => '957a4469-38f6-43fc-b7a6-03ce390ecb1d'
            ], [
                'name' => 'TALABAT CAKE LAB',
                'emmar_mapping' => 'Ch_Talabat',
                'payment_id' => '957a4471-d8d5-47b2-92f6-176b934300de'
            ], [
                'name' => 'TALABAT ROTI',
                'emmar_mapping' => 'Ch_Talabat',
                'payment_id' => '957a4478-1d9d-4cb2-a690-2ad75bf0d7be'
            ], [
                'name' => 'TALABAT DXBLENDS',
                'emmar_mapping' => 'Ch_Talabat',
                'payment_id' => '957a447e-1f28-4eab-9bc4-9de3ee92313e'
            ], [
                'name' => 'TALABAT SEATTLE BURGER',
                'emmar_mapping' => 'Ch_Talabat',
                'payment_id' => '957a4487-06f5-4527-9d86-cc0f7cb60238'
            ], [
                'name' => 'ROASTERY CASH',
                'emmar_mapping' => 'Ch_DineIn',
                'payment_id' => '957a448c-61b4-49d4-bf79-2c3fab4ab09d'
            ], [
                'name' => 'ROASTERY CARD',
                'emmar_mapping' => 'Ch_DineIn',
                'payment_id' => '957a4492-84f0-4c82-8f95-826fea145516'
            ], [
                'name' => 'Cash-House Account Settlement',
                'emmar_mapping' => 'Ch_DineIn',
                'payment_id' => '9661e614-b318-4d4e-95f1-9b631953b68a'
            ], [
                'name' => 'Card-House Account Settlement',
                'emmar_mapping' => 'Ch_DineIn',
                'payment_id' => '9661e630-7d28-4194-b6d3-fdb439f3df58'
            ], [
                'name' => 'Loyalty Card',
                'emmar_mapping' => 'Ch_DineIn',
                'payment_id' => '96742f44-edce-48d2-bbca-f163d4b97ccd'
            ], [
                'name' => 'Loyalty Card',
                'emmar_mapping' => 'Ch_DineIn',
                'payment_id' => '96742fa9-b9f8-4740-a406-a80c1a57cb85'
            ], [
                'name' => 'House A/C Settlement - INDEX',
                'emmar_mapping' => 'Ch_DineIn',
                'payment_id' => '97be478b-88fa-486a-b964-661d008fe481'
            ]

        ];

        PaymentMethod::upsert($data, 'id');
    }
}
