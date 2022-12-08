<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Role;
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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = User::create([
            'username' => 'raphaeldanu',
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
        ];
        $superAdmin = Role::create(['name' => 'Human Resource Manager']);

        foreach ($permissions as $permission){
            $permit = Permission::create(['name' => $permission]);
            $superAdmin->givePermissionTo($permit);
        }

        $user->assignRole($superAdmin);

    }
}
