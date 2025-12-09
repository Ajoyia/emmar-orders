<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('branches')->truncate();
        $data = [
            [
                'branch_id' => '95687127-a5d3-47e9-9090-3316e6265f5c',
                'name' => 'Dubia Hills Branch',
                'unit_no' => 'BP1-B8-GF 01',
                'lease_code' => 't0015423',
            ]

        ];

        Branch::upsert($data, 'id');
    }
}
