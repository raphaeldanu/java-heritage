<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Ptkp;
use App\Models\Role;
use App\Models\User;
use App\Models\Level;
use App\Models\Department;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $user = User::create([
            'username' => 'raphaeldanu',
            'password' => Hash::make('op[kl;m,.'),
            'active' => 1
        ]);

        $user2 = User::create([
            'username' => 'mariahelga',
            'password' => Hash::make('op[kl;m,.'),
            'active' => 1
        ]);

        $user3 = User::create([
            'username' => 'wahyunug',
            'password' => Hash::make('op[kl;m,.'),
            'active' => 1
        ]);

        $user4 = User::create([
            'username' => 'herwindosat',
            'password' => Hash::make('op[kl;m,.'),
            'active' => 1
        ]);

        $user5 = User::create([
            'username' => 'satriobag',
            'password' => Hash::make('op[kl;m,.'),
            'active' => 1
        ]);

        $permissions = [
            'view-roles',
            'create-roles',
            'update-roles',
            'delete-roles',
            'view-users',
            'create-users',
            'update-users',
            'delete-users',
            'activate-users',
            'change-password-users',
            'view-departments',
            'create-departments',
            'update-departments',
            'delete-departments',
            'view-levels',
            'create-levels',
            'update-levels',
            'delete-levels',
            'view-salary-ranges',
            'create-salary-ranges',
            'update-salary-ranges',
            'delete-salary-ranges',
            'view-positions',
            'create-positions',
            'update-positions',
            'delete-positions',
            'view-all-employees',
            'view-employees',
            'view-all-employees-detail',
            'view-employees-detail',
            'create-employees',
            'update-employees',
            'delete-employees',
            'export-employees',
            'update-employee-leave',
            'view-residence',
            'create-residence',
            'update-residence',
            'delete-residence',
            'view-families',
            'create-families',
            'update-families',
            'delete-families',
            'view-all-leave-requests',
            'view-leave-requests',
            'approve-all-leave-requests',
            'approve-leave-requests',
            'delete-leave-requests',
            'view-all-training-menus',
            'view-training-menus',
            'create-training-menus',
            'update-training-menus',
            'delete-training-menus',
            'view-all-training-subjects',
            'view-training-subjects',
            'create-training-subjects',
            'update-training-subjects',
            'delete-training-subjects',
            'view-all-trainings',
            'view-trainings',
            'create-trainings',
            'update-trainings',
            'delete-trainings',
            'view-all-attendances',
            'view-attendances',
            'create-attendances',
            'update-attendances',
            'delete-attendances',
            'view-all-ptkps',
            'view-ptkps',
            'create-ptkps',
            'update-ptkps',
            'delete-ptkps',
            'view-all-schedules',
            'view-schedules',
            'create-schedules',
            'update-schedules',
            'delete-schedules',
            'view-all-salaries',
            'view-salaries',
            'create-salaries',
            'update-salaries',
            'delete-salaries',
        ];
        $superAdmin = Role::create(['name' => 'Human Resource Manager']);
        $staff = Role::create(['name' => "Human Resource Staff"]);

        foreach ($permissions as $permission){
            $permit = Permission::create(['name' => $permission]);
            $superAdmin->givePermissionTo($permit);
        }
        $staff->givePermissionTo([0, 4, 10, 14, 18, 22]);

        $user->assignRole($superAdmin);

        $user2->assignRole($staff);

        $this->call([
            PositionSeeder::class,
            SalaryRangeSeeder::class,
            TrainingMenuSeeder::class,
        ]);

        $employee = $user->employee()->create([
            'position_id' => 3,
            'salary_range_id' => 2,
            'nik' => '3374082509010002',
            'nip' => 'JH.0001',
            'bpjs_tk_number' => '01526084818',
            'bpjs_kes_number' => '0001526084818',
            'npwp_number' => '3374082509010002',
            'name' => "Raphael Adhimas Aryandanu Santoso",
            'employment_status' => "contract",
            'first_join' => '2023-01-01',
            'last_contract_start' => '2023-01-01',
            'last_contract_end' => '2023-12-31',
            'birth_place' => 'Semarang',
            'birth_date' => '2001-09-25',
            'gender' => 'male',
            'tax_status' => 'TK0',
            'address_on_id' => 'Jl. Dr. Wahidin 110B Semarang',
            'phone_number' => '082135763151',
            'blood_type' => 'O',
        ]);

        $employee->leave()->create();

        $employee2 = $user2->employee()->create([
            'position_id' => 4,
            'salary_range_id' => 11,
            'nik' => '3374082509010007',
            'nip' => 'JH.0002',
            'bpjs_tk_number' => '01526084819',
            'bpjs_kes_number' => '0001526084819',
            'npwp_number' => '3374082509010007',
            'name' => "Maria Helga Naning Alviana",
            'employment_status' => "contract",
            'first_join' => '2023-01-10',
            'last_contract_start' => '2023-01-10',
            'last_contract_end' => '2023-01-09',
            'birth_place' => 'Semarang',
            'birth_date' => '2004-06-12',
            'gender' => 'female',
            'tax_status' => 'K0',
            'address_on_id' => 'Jl. Talang Semarang',
            'phone_number' => '082135763151',
            'blood_type' => 'B',
        ]);

        $employee2->leave()->create();

        $employee3 = $user3->employee()->create([
            'position_id' => 7,
            'salary_range_id' => 11,
            'nik' => '3374082509010009',
            'nip' => 'JH.0003',
            'bpjs_tk_number' => '01526084820',
            'bpjs_kes_number' => '0001526084820',
            'npwp_number' => '3374082509010009',
            'name' => "Wahyu Adi Nugroho",
            'employment_status' => "contract",
            'first_join' => '2023-01-10',
            'last_contract_start' => '2023-01-10',
            'last_contract_end' => '2024-01-09',
            'birth_place' => 'Bawen',
            'birth_date' => '2001-06-12',
            'gender' => 'male',
            'tax_status' => 'TK0',
            'address_on_id' => 'Jl. jalan yuk, Bawen',
            'phone_number' => '082135763153',
            'blood_type' => 'AB',
        ]);

        $employee3->leave()->create();

        $employee4 = $user4->employee()->create([
            'position_id' => 6,
            'salary_range_id' => 2,
            'nik' => '3374082509010010',
            'nip' => 'JH.0004',
            'bpjs_tk_number' => '01526084821',
            'bpjs_kes_number' => '0001526084821',
            'npwp_number' => '3374082509010010',
            'name' => "Herwindo Satrio Utomo",
            'employment_status' => "contract",
            'first_join' => '2023-01-10',
            'last_contract_start' => '2023-01-10',
            'last_contract_end' => '2024-01-09',
            'birth_place' => 'Semarang',
            'birth_date' => '2001-02-06',
            'gender' => 'male',
            'tax_status' => 'TK0',
            'address_on_id' => 'Jl. Durian, Semarang',
            'phone_number' => '082135763155',
            'blood_type' => 'O',
        ]);

        $employee4->leave()->create();

        $employee5 = $user5->employee()->create([
            'position_id' => 5,
            'salary_range_id' => 18,
            'nik' => '3374082509010011',
            'nip' => 'JH.0005',
            'bpjs_tk_number' => '01526084822',
            'bpjs_kes_number' => '0001526084822',
            'npwp_number' => '3374082509010011',
            'name' => "Satrio Bagus Imanulloh",
            'employment_status' => "contract",
            'first_join' => '2023-01-10',
            'last_contract_start' => '2023-01-10',
            'last_contract_end' => '2024-01-09',
            'birth_place' => 'Pekalongan',
            'birth_date' => '2001-05-06',
            'gender' => 'male',
            'tax_status' => 'TK0',
            'address_on_id' => 'Jl. Durian, Pekalongan',
            'phone_number' => '082135763155',
            'blood_type' => 'O',
        ]);

        $employee5->leave()->create();

        $employee2->leaveRequests()->create([
            'leave_type' => 'melahirkan',
            'start_date' => '2023-01-31',
            'end_date' => '2023-03-01',
            'status' => 'waiting',
        ]);

        $employee4->leaveRequests()->create([
            'leave_type' => 'khitanan',
            'start_date' => '2023-01-27',
            'end_date' => '2023-02-03',
            'status' => 'waiting',
        ]);

        $employee5->leaveRequests()->create([
            'leave_type' => 'khitanan',
            'start_date' => '2023-01-31',
            'end_date' => '2023-02-01',
            'status' => 'approved_with_note',
            'note_from_approver' => 'Ojo kesuen',
            'approved_by' => 1,
        ]);

        $ptkps = [
            [
                'tax_status' => 'TK0',
                'fee' => 54000000,
            ],
            [
                'tax_status' => 'TK1',
                'fee' => 58500000,
            ],
            [
                'tax_status' => 'TK2',
                'fee' => 63000000,
            ],
            [
                'tax_status' => 'TK3',
                'fee' => 67500000,
            ],
            [
                'tax_status' => 'K0',
                'fee' => 58500000,
            ],
            [
                'tax_status' => 'K1',
                'fee' => 63000000,
            ],
            [
                'tax_status' => 'K2',
                'fee' => 67500000,
            ],
            [
                'tax_status' => 'K3',
                'fee' => 72000000,
            ],
        ];

        foreach ($ptkps as $ptkp) {
            Ptkp::create($ptkp);
        }
    }
}
