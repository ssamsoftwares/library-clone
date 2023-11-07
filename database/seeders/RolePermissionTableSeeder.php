<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                "name" => "superadmin"
            ],
            [
                "name" => "admin"
            ],
            [
                "name" => "manager"
            ],
        ];

        $permissions = [
            ["name" => "student-list"],
            ["name" => "student-view"],
            ["name" => "student-create"],
            ["name" => "student-edit"],
            ["name" => "student-delete"],
            ["name" => "plan-list"],
            ["name" => "plan-view"],
            ["name" => "plan-create"],
            ["name" => "plan-edit"],
            ["name" => "plan-delete"],
            ["name" => "user-list"],
            ["name" => "user-view"],
            ["name" => "user-create"],
            ["name" => "user-edit"],
            ["name" => "user-delete"],
            ["name" => "role-list"],
            ["name" => "role-view"],
            ["name" => "role-create"],
            ["name" => "role-edit"],
            ["name" => "role-delete"],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
