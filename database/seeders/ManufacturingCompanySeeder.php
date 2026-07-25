<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManufacturingCompanySeeder extends Seeder
{
    /**
     * Seed manufacturing company data.
     */
    public function run(): void
    {
        $company = DB::table('companies')->first();

        if (! $company) {
            $this->command?->error('No company found. Run erp:install first.');

            return;
        }

        $userId = DB::table('users')->first()->id ?? 1;

        $this->seedDepartments($company->id, $userId);
        $this->seedJobPositions($company->id, $userId);
        $this->seedWorkCenters($company->id, $userId);
        $this->seedProducts($company->id, $userId);

        $this->command?->info('Manufacturing company data seeded successfully.');
    }

    protected function seedDepartments(int $companyId, int $userId): void
    {
        $departments = [
            ['name' => 'Production', 'color' => '#10B981'],
            ['name' => 'Quality Assurance', 'color' => '#3B82F6'],
            ['name' => 'Warehouse', 'color' => '#F59E0B'],
            ['name' => 'Maintenance', 'color' => '#EF4444'],
            ['name' => 'HR & Admin', 'color' => '#8B5CF6'],
        ];

        foreach ($departments as $dept) {
            $exists = DB::table('employees_departments')
                ->where('name', $dept['name'])
                ->where('company_id', $companyId)
                ->exists();

            if (! $exists) {
                DB::table('employees_departments')->insert([
                    'company_id'    => $companyId,
                    'creator_id'    => $userId,
                    'name'          => $dept['name'],
                    'complete_name' => $dept['name'],
                    'color'         => $dept['color'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        $this->command?->info('  Departments seeded.');
    }

    protected function seedJobPositions(int $companyId, int $userId): void
    {
        $positions = [
            'Production Manager',
            'Line Supervisor',
            'Machine Operator',
            'QC Inspector',
            'Warehouse Manager',
            'Store Keeper',
            'Maintenance Technician',
            'HR Officer',
        ];

        foreach ($positions as $position) {
            $exists = DB::table('employees_job_positions')
                ->where('name', $position)
                ->where('company_id', $companyId)
                ->exists();

            if (! $exists) {
                DB::table('employees_job_positions')->insert([
                    'company_id' => $companyId,
                    'creator_id' => $userId,
                    'name'       => $position,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command?->info('  Job positions seeded.');
    }

    protected function seedWorkCenters(int $companyId, int $userId): void
    {
        $workCenters = [
            [
                'name'             => 'Assembly Line A',
                'code'             => 'WC-AL01',
                'working_state'    => 'productive',
                'time_efficiency'  => 100.00,
                'default_capacity' => 1.00,
                'costs_per_hour'   => 50000.00,
                'setup_time'       => 15.00,
                'cleanup_time'     => 10.00,
                'oee_target'       => 85.00,
            ],
            [
                'name'             => 'Assembly Line B',
                'code'             => 'WC-AL02',
                'working_state'    => 'productive',
                'time_efficiency'  => 95.00,
                'default_capacity' => 1.00,
                'costs_per_hour'   => 50000.00,
                'setup_time'       => 15.00,
                'cleanup_time'     => 10.00,
                'oee_target'       => 80.00,
            ],
            [
                'name'             => 'CNC Machine',
                'code'             => 'WC-CNC01',
                'working_state'    => 'productive',
                'time_efficiency'  => 110.00,
                'default_capacity' => 2.00,
                'costs_per_hour'   => 75000.00,
                'setup_time'       => 30.00,
                'cleanup_time'     => 15.00,
                'oee_target'       => 90.00,
            ],
            [
                'name'             => 'Quality Check Station',
                'code'             => 'WC-QC01',
                'working_state'    => 'productive',
                'time_efficiency'  => 100.00,
                'default_capacity' => 1.00,
                'costs_per_hour'   => 40000.00,
                'setup_time'       => 5.00,
                'cleanup_time'     => 5.00,
                'oee_target'       => 95.00,
            ],
            [
                'name'             => 'Packaging Area',
                'code'             => 'WC-PKG01',
                'working_state'    => 'productive',
                'time_efficiency'  => 100.00,
                'default_capacity' => 1.00,
                'costs_per_hour'   => 35000.00,
                'setup_time'       => 10.00,
                'cleanup_time'     => 10.00,
                'oee_target'       => 85.00,
            ],
        ];

        foreach ($workCenters as $wc) {
            $exists = DB::table('manufacturing_work_centers')
                ->where('code', $wc['code'])
                ->where('company_id', $companyId)
                ->exists();

            if (! $exists) {
                DB::table('manufacturing_work_centers')->insert([
                    ...$wc,
                    'company_id' => $companyId,
                    'creator_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command?->info('  Work centers seeded.');
    }

    protected function seedWarehouses(int $companyId, int $userId): void
    {
        $warehouses = [
            ['name' => 'Main Warehouse', 'code' => 'WH-MAIN'],
            ['name' => 'Raw Material Storage', 'code' => 'WH-RM'],
            ['name' => 'Finished Goods', 'code' => 'WH-FG'],
        ];

        foreach ($warehouses as $wh) {
            $exists = DB::table('inventories_warehouses')
                ->where('code', $wh['code'])
                ->where('company_id', $companyId)
                ->exists();

            if (! $exists) {
                DB::table('inventories_warehouses')->insert([
                    ...$wh,
                    'reception_steps'  => 1,
                    'delivery_steps'   => 1,
                    'company_id'       => $companyId,
                    'creator_id'       => $userId,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }

        $this->command?->info('  Warehouses seeded.');
    }

    protected function seedProducts(int $companyId, int $userId): void
    {
        $uomId = DB::table('uoms')->first()->id ?? 1;
        $categoryId = DB::table('product_categories')->first()->id ?? 1;

        $rawMaterials = [
            ['name' => 'Steel Sheet', 'reference' => 'RM-001', 'type' => 'consu', 'price' => 150000],
            ['name' => 'Aluminum Bar', 'reference' => 'RM-002', 'type' => 'consu', 'price' => 200000],
            ['name' => 'Plastic Resin', 'reference' => 'RM-003', 'type' => 'consu', 'price' => 75000],
            ['name' => 'Screws & Bolts', 'reference' => 'RM-004', 'type' => 'consu', 'price' => 5000],
            ['name' => 'Paint (Blue)', 'reference' => 'RM-005', 'type' => 'consu', 'price' => 120000],
        ];

        $products = [
            ['name' => 'Metal Cabinet', 'reference' => 'FG-001', 'type' => 'product', 'price' => 2500000, 'cost' => 1500000],
            ['name' => 'Steel Table', 'reference' => 'FG-002', 'type' => 'product', 'price' => 1800000, 'cost' => 1000000],
            ['name' => 'Aluminum Frame', 'reference' => 'FG-003', 'type' => 'product', 'price' => 800000, 'cost' => 500000],
        ];

        foreach ($rawMaterials as $product) {
            $exists = DB::table('products_products')
                ->where('reference', $product['reference'])
                ->where('company_id', $companyId)
                ->exists();

            if (! $exists) {
                DB::table('products_products')->insert([
                    ...$product,
                    'company_id'  => $companyId,
                    'creator_id'  => $userId,
                    'uom_id'      => $uomId,
                    'category_id' => $categoryId,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        foreach ($products as $product) {
            $exists = DB::table('products_products')
                ->where('reference', $product['reference'])
                ->where('company_id', $companyId)
                ->exists();

            if (! $exists) {
                DB::table('products_products')->insert([
                    ...$product,
                    'company_id'  => $companyId,
                    'creator_id'  => $userId,
                    'uom_id'      => $uomId,
                    'category_id' => $categoryId,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        $this->command?->info('  Products seeded.');
    }
}
