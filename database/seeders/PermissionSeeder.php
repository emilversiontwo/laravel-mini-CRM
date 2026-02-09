<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::query()->truncate();

        $adminRole = Role::query()->where('name', '=', 'admin')->first();
        $managerRole = Role::query()->where('name', '=', 'manager')->first();

        $userCrudPermission = Permission::create(['name' => 'user.crud']);
        $userCrudPermission->assignRole($adminRole);

        $ticketIndexPermission = Permission::create(['name' => 'ticket.index']);
        $ticketIndexPermission->assignRole($adminRole);
        $ticketIndexPermission->assignRole($managerRole);

        $ticketUpdatePermission = Permission::create(['name' => 'ticket.update']);
        $ticketUpdatePermission->assignRole($adminRole);
        $ticketUpdatePermission->assignRole($managerRole);
    }
}
