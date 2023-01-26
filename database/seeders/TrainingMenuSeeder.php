<?php

namespace Database\Seeders;

use App\Models\TrainingSubject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrainingMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subject1 = TrainingSubject::create([
            'subject' => "Corporate Training"
        ]);

        $subject2 = TrainingSubject::create([
            'subject' => "Generic Training"
        ]);

        $subject3 = TrainingSubject::create([
            'subject' => "Departmental Training"
        ]);
    }
}
