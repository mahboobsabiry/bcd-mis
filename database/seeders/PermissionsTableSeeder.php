<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // ========== Admin ==========
            [
                'name'      => 'site_admin' // Admin special permissions
            ],
            [
                'name'      => 'user_mgmt' // Manage all Permission, Roles and Users
            ],
            [
                'name'      => 'user_view'
            ],
            [
                'name'      => 'setting_mgmt' // Manage app settings
            ],

            // Documents
            [
                'name'      => 'docs_view'
            ],
            [
                'name'      => 'docs_create'
            ],
            [
                'name'      => 'docs_edit'
            ],
            [
                'name'      => 'docs_delete'
            ],
            // ========== ASYCUDA ==========
            [
                'name'      => 'asycuda_view' // Asycuda View Permission
            ],
            // Asycuda Users
            [
                'name'      => 'asy_user_view'
            ],
            [
                'name'      => 'asy_user_create'
            ],
            [
                'name'      => 'asy_user_edit'
            ],
            [
                'name'      => 'asy_user_delete'
            ],
            // Asycuda Companies Activity License
            [
                'name'      => 'asy_coal_view'
            ],
            [
                'name'      => 'asy_coal_create'
            ],
            [
                'name'      => 'asy_coal_edit'
            ],
            [
                'name'      => 'asy_coal_delete'
            ],

            // ========== Office ==========
            [
                'name'      => 'organization_view'
            ],
            [
                'name'      => 'office_view'
            ],
            // Positions
            [
                'name'      => 'office_position_view'
            ],
            [
                'name'      => 'office_position_create'
            ],
            [
                'name'      => 'office_position_edit'
            ],
            [
                'name'      => 'office_position_delete'
            ],
            // Employees
            [
                'name'      => 'office_employee_view'
            ],
            [
                'name'      => 'office_employee_create'
            ],
            [
                'name'      => 'office_employee_edit'
            ],
            [
                'name'      => 'office_employee_delete'
            ],
            // Agents
            [
                'name'      => 'office_agent_view'
            ],
            [
                'name'      => 'office_agent_create'
            ],
            [
                'name'      => 'office_agent_edit'
            ],
            [
                'name'      => 'office_agent_delete'
            ],
            // Companies
            [
                'name'      => 'office_company_view'
            ],
            // Hostel
            [
                'name'      => 'office_hostel_view'
            ],
            [
                'name'      => 'office_hostel_create'
            ],
            [
                'name'      => 'office_hostel_edit'
            ],
            [
                'name'      => 'office_hostel_delete'
            ],
            [
                'name'      => 'office_employee_leave_view'
            ],
            [
                'name'      => 'office_employee_leave_create'
            ],
            [
                'name'      => 'office_employee_leave_edit'
            ],
            [
                'name'      => 'office_employee_leave_delete'
            ],
            // ========== Warehouse ==========
            [
                'name'      => 'warehouse_view'
            ],
            // Assurance
            [
                'name'      => 'warehouse_assurance_view'
            ],
            [
                'name'      => 'warehouse_assurance_create'
            ],
            [
                'name'      => 'warehouse_assurance_edit'
            ],
            [
                'name'      => 'warehouse_assurance_delete'
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
