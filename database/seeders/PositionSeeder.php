<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminNGeneral = Department::create(['name' => 'Admin & General']);
        $adminNGeneral->positions()->createMany([
            ['name' => "General Manager"],
            ['name' => "Operation Secretary"],
        ]);

        $humanResource = Department::create(['name' => 'Human Resource']);
        $humanResource->positions()->createMany([
            ['name' => 'Human Resource Manager'],
            ['name' => 'Human Resource Coordinator'],
            ['name' => 'Payroll and Admin']
        ]);

        $accounting = Department::create(['name' => 'Accounting']);
        $accounting->positions()->createMany([
            ['name' => 'Chief Accounting'],
            ['name' => 'Income Auditor'],
            ['name' => 'General Cashier'],
            ['name' => 'Cost Control Material'],
            ['name' => 'Accounts Receivable'],
            ['name' => 'Accounts Payable'],
            ['name' => 'IT Officer'],
            ['name' => 'Purchasing'],
            ['name' => 'Receiving'],
            ['name' => 'Night Auditor'],
            ['name' => 'Store Keeper'],
        ]);

        $frontOffice = Department::create(['name' => 'Front Office']);
        $frontOffice->positions()->createMany([
            ['name' => 'Front Office Manager'],
            ['name' => 'Front Office Manager Assistant'],
            ['name' => 'Duty Manager'],
            ['name' => 'Captain Bell Boy'],
            ['name' => 'Bell Boy'],
            ['name' => 'Front Desk Attendant'],
            ['name' => 'Guest Relation Officer'],
        ]);

        $houseKeeping = Department::create(['name' => 'House Keeping']);
        $houseKeeping->positions()->createMany([
            ['name' => 'Executive Housekeeper'],
            ['name' => 'House Keeping Supervisor'],
            ['name' => 'Steward Supervisor'],
            ['name' => 'Order Taker'],
            ['name' => 'Room Attendant'],
            ['name' => 'Gardener'],
            ['name' => 'Gym Instructor'],
        ]);

        $fBS = Department::create(['name' => 'Food & Beverage Service']);
        $fBS->positions()->createMany([
            ['name' => 'Banquette Manager'],
            ['name' => 'Waitress'],
            ['name' => 'Waiter'],
            ['name' => 'Food & Beverage Product Outlet Manager'],
            ['name' => 'Assistant Restaurant Manager'],
            ['name' => 'Supervisor'],
            ['name' => 'Food & Beverage Admin'],
            ['name' => 'Bartender'],
        ]);

        $fBP = Department::create(['name' => 'Food & Beverage Product']);
        $fBP->positions()->createMany([
            ['name' => 'Executive Sous Chef'],
            ['name' => 'Sous Chef'],
            ['name' => 'Sous Chef Chinese'],
            ['name' => 'Dimsum Chef'],
            ['name' => 'Chef de Partie'],
            ['name' => 'Commis Chef'],
            ['name' => 'Senior Cook Banquette'],
            ['name' => 'Demi Chef de Partie'],
            ['name' => 'Pastry Chef'],
            ['name' => 'Steward Supervisor'],
            ['name' => 'Steward'],
        ]);

        $salesMarketing = Department::create(['name' => 'Sales & Marketing']);
        $salesMarketing->positions()->createMany([
            ['name' => 'Sales Executive'],
            ['name' => 'Sales Admin'],
            ['name' => 'Wedding Consultant'],
            ['name' => 'Senior Sales Coordinator'],
            ['name' => 'Assistant Marketing Manager'],
            ['name' => 'Graphic Designer'],
            ['name' => 'Marketing Staff'],
        ]);

        $engineer = Department::create(['name' => 'Engineering']);
        $engineer->positions()->createMany([
            ['name' => 'Chief Engineer'],
            ['name' => 'Assistant Chief Engineer'],
            ['name' => 'Engineering Supervisor'],
            ['name' => 'Engineering Staff'],
            ['name' => 'Shift Leader'],
        ]);
    }
}
