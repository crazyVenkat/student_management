<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Programme;
use App\Models\Department;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();

        foreach ($departments as $dept) {

            Programme::create([
                'department_id' => $dept->id,
                'name' => 'UG - ' . $dept->name
            ]);

            Programme::create([
                'department_id' => $dept->id,
                'name' => 'PG - ' . $dept->name
            ]);
        }
    }
}
