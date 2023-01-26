<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalaryRangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $executive = Level::create(['name' => 'Executive']);
        $executive->salaryRanges()->createMany([
            [
                'name' => 'Low',
                'base_salary' => 10000000
            ],
            [
                'name' => 'Mid',
                'base_salary' => 12500000
            ],
            [
                'name' => 'High',
                'base_salary' => 15000000
            ],
        ]);

        $manager2 = Level::create(['name' => 'Manager 2']);
        $manager2->salaryRanges()->createMany([
            [
                'name' => 'Low',
                'base_salary' => 5949783
            ],
            [
                'name' => 'Mid',
                'base_salary' => 6445598,
            ],
            [
                'name' => 'High',
                'base_salary' => 7933044 
            ],
        ]);
        
        $manager1 = Level::create(['name' => 'Manager 1']);
        $manager1->salaryRanges()->createMany([
            [
                'name' => 'Low',
                'base_salary' => 3966522 
            ],
            [
                'name' => 'Mid',
                'base_salary' => 4462337
            ],
            [
                'name' => 'High',
                'base_salary' => 4958153
            ],
        ]);

        $supervisor2 = Level::create(['name' => 'Supervisor 2']);
        $supervisor2->salaryRanges()->createMany([
            [
                'name' => 'Low',
                'base_salary' => 2233261
            ],
            [
                'name' => 'Mid',
                'base_salary' => 2333261
            ],
            [
                'name' => 'High',
                'base_salary' => 2433261
            ],
        ]);
        
        $supervisor1 = Level::create(['name' => 'Supervisor 1']);
        $supervisor1->salaryRanges()->createMany([
            [
                'name' => 'Low',
                'base_salary' => 2058261
            ],
            [
                'name' => 'Mid',
                'base_salary' => 2083261
            ],
            [
                'name' => 'High',
                'base_salary' => 2108261
            ],
        ]);

        $rankNFile2 = Level::create(['name' => 'Rank & File']);
        $rankNFile2->salaryRanges()->createMany([
            [
                'name' => 'Low',
                'base_salary' => 1983261,
            ],
            [
                'name' => 'Mid',
                'base_salary' => 2008261,
            ],
            [
                'name' => 'High',
                'base_salary' => 2033261,
            ],
        ]);
        
        $dailyWorker = Level::create(['name' => 'Daily Worker']);
        $dailyWorker->salaryRanges()->createMany([
            [
                'name' => 'Standart',
                'base_salary' => 79330, 
            ],
        ]);
    }
}
