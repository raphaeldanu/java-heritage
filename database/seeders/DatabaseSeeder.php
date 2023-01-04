<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'password' => Hash::make('12345678'),
            'active' => 1
        ]);

        $user2 = User::create([
            'username' => 'mariahelga',
            'password' => Hash::make('12345678'),
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
            'view-employees',
            'view-employee-detail',
            'create-employees',
            'update-employees',
            'delete-employees',
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
        ]);
    }
}
