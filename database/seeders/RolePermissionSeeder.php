<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // === PERMISSIONS ===
        $permissions = [
            // User Management
            'user_view', 'user_create', 'user_edit', 'user_delete', 'user_reset_password', 'user_assign_role',

            // Employee Management
            'employee_view', 'employee_create', 'employee_edit', 'employee_delete',
            'position_view', 'position_create', 'position_edit', 'position_delete',
            'division_view', 'division_create', 'division_edit', 'division_delete',
            'area_view', 'area_create', 'area_edit', 'area_delete',
            'work_unit_view', 'work_unit_create', 'work_unit_edit', 'work_unit_delete',

            // ATK Items & Stock
            'atk_item_view', 'atk_item_create', 'atk_item_edit', 'atk_item_delete',
            'atk_stock_view',
            'atk_stock_adjustment_view', 'atk_stock_adjustment_create', 'atk_stock_adjustment_edit', 'atk_stock_adjustment_delete',

            // ATK Requests
            'atk_out_request_view', 'atk_out_request_create', 'atk_out_request_submit', 'atk_out_request_edit', 'atk_out_request_cancel',
            'atk_out_request_approve', 'atk_out_request_reject', 'atk_out_request_receive', 'atk_out_request_print',

            // PO & Receive
            'atk_purchase_order_view', 'atk_purchase_order_create', 'atk_purchase_order_edit', 'atk_purchase_order_delete',
            'atk_receive_view', 'atk_receive_create', 'atk_receive_edit', 'atk_receive_delete',

            // Returns
            'atk_return_view', 'atk_return_create', 'atk_return_edit', 'atk_return_delete',

            // Supplier
            'supplier_view', 'supplier_create', 'supplier_edit', 'supplier_delete',

            // Reports
            'report_view', 'report_export'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // === ROLES ===
        $roles = ['superadmin', 'gaf', 'hr', 'manager', 'employee'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // === ROLE -> PERMISSIONS ASSIGNMENT ===
        Role::findByName('superadmin')->syncPermissions(Permission::all());

        Role::findByName('gaf')->syncPermissions([
            'atk_item_view', 'atk_item_create', 'atk_item_edit', 'atk_item_delete',
            'atk_stock_view', 'stock_adjustment_create',
            'atk_out_request_view', 'atk_out_request_approve', 'atk_out_request_reject', 'atk_out_request_receive',
            'atk_purchase_order_view', 'atk_purchase_order_create', 'atk_purchase_order_edit', 'atk_purchase_order_delete',
            'atk_receive_view', 'atk_receive_create',
            'atk_return_view', 'atk_return_create',
            'supplier_view', 'supplier_create', 'supplier_edit', 'supplier_delete',
        ]);

        Role::findByName('hr')->syncPermissions([
            'employee_view', 'employee_create', 'employee_edit', 'employee_delete',
            'position_view', 'position_create', 'position_edit', 'position_delete',
            'division_view', 'division_create', 'division_edit', 'division_delete',
            'area_view', 'area_create', 'area_edit', 'area_delete',
            'work_unit_view', 'work_unit_create', 'work_unit_edit', 'work_unit_delete',
        ]);

        Role::findByName('manager')->syncPermissions([
            'employee_view',
            'atk_item_view', 'atk_stock_view', 'atk_out_request_view', 'atk_out_request_approve',
            'atk_purchase_order_view', 'atk_return_view', 'report_view', 'atk_out_request_print'
        ]);

        Role::findByName('employee')->syncPermissions([
            'atk_stock_view', 'atk_out_request_create', 'atk_out_request_submit', 'atk_out_request_view', 'atk_out_request_cancel', 'atk_return_create'
        ]);

        // === DEFAULT USERS (OPTIONAL) ===
        $users = [
            [
                'employee_id' => '50088776',
                'password' => 'QWERTY123',
                'role' => 'superadmin'
            ],
            [
                'employee_id' => '50163456',
                'password' => 'QWERTY1234',
                'role' => 'gaf'
            ],
        ];

        foreach ($users as $u) {
            $user = User::firstOrCreate([
                'employee_id' => $u['employee_id'],
            ], [
                'id' => Str::uuid(),
                'password' => Hash::make($u['password']),
                'status' => 'active',
            ]);

            $user->syncRoles([$u['role']]);
        }
    }
}
