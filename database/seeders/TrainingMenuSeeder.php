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

        $subject1->trainingMenu()->createMany([
            ['title' => "Group Vision, Mission, Values"],
            ['title' => "Company History and Profiles"],
            ['title' => "DHM - Organization Chart"],
            ['title' => "DHM - Board of Directors and Management"],
            ['title' => "Inspiring Brands"],
            ['title' => "Touches of Indonesia"],
            ['title' => "DAFAM Greetings"],
            ['title' => "DAFAM Leadership Philosopy of Ki Hajar Dewantoro"],
        ]);

        $subject2 = TrainingSubject::create([
            'subject' => "Generic Training"
        ]);

        $subject2->trainingMenu()->createMany([
            ['title' => "DHM - Standard Grooming"],
            ['title' => "DHM - Standard Telephone Courtesy"],
            ['title' => "DHM - Standard Email Etiquette"],
            ['title' => "DHM - Customer Behaviour and Attitude"],
            ['title' => "Emergency Management Committee (EMC)"],
            ['title' => "Personal Hygiene & Sanitation"],
        ]);

        $subject3 = TrainingSubject::create([
            'subject' => "Departmental Training"
        ]);

        $subject3->trainingMenu()->createMany([
            ['department_id' => 8, 'title' => "Administration"],
            ['department_id' => 8, 'title' => "Barter Agreement"],
            ['department_id' => 8, 'title' => "Booking Letter"],
            ['department_id' => 8, 'title' => "Complimentary Room Request"],
            ['department_id' => 8, 'title' => "Confirmation Letter"],
            ['department_id' => 8, 'title' => "Contract Rate"],
            ['department_id' => 8, 'title' => "Courtessy"],
            ['department_id' => 8, 'title' => "Customer Gathering"],
            ['department_id' => 8, 'title' => "Entertainment"],
            ['department_id' => 8, 'title' => "Familiarization Tour"],
            ['department_id' => 8, 'title' => "Grooming & Attitude"],
            ['department_id' => 8, 'title' => "Group Booking Procedure"],
            ['department_id' => 8, 'title' => "Group Booking During Peak Season"],
            ['department_id' => 8, 'title' => "Guarantee Letter"],
            ['department_id' => 8, 'title' => "Guaranteed Reservation"],
            ['department_id' => 8, 'title' => "Reservation Policy"],
            ['department_id' => 8, 'title' => "Room Allotment"],
            ['department_id' => 8, 'title' => "Room Concessions"],
            ['department_id' => 8, 'title' => "Sales & Marketing Report"],
            ['department_id' => 8, 'title' => "Sales Blitz"],
            ['department_id' => 8, 'title' => "Sales Call"],
            ['department_id' => 8, 'title' => "Sales Database"],
            ['department_id' => 8, 'title' => "Telemarketing"],
        ]);
    }
}
