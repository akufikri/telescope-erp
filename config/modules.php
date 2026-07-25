<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Active Modules
    |--------------------------------------------------------------------------
    |
    | List of navigation groups that are enabled. Use 'all' to enable everything,
    | or comma-separated list like 'sale,inventory,accounting'.
    |
    | Available navigation groups:
    | - dashboard, contact, sale, purchase
    | - manufacturing, maintenance, inventory
    | - invoice, accounting, project
    | - employee, time-off, recruitment
    | - website, barcode, setting, help
    |
    */

    'active' => array_filter(
        array_map('trim', explode(',', env('ACTIVE_MODULES', 'all')))
    ),

    /*
    |--------------------------------------------------------------------------
    | Module Mapping
    |--------------------------------------------------------------------------
    |
    | Maps navigation groups to their required plugins.
    |
    */

    'mapping' => [
        'dashboard'     => ['analytics'],
        'contact'       => ['contacts'],
        'product'       => ['products'],
        'partner'       => ['partners'],
        'sale'          => ['sales'],
        'purchase'      => ['purchases'],
        'invoice'       => ['invoices'],
        'accounting'    => ['accounts', 'accounting'],
        'inventory'     => ['inventories', 'barcode'],
        'manufacturing' => ['manufacturing'],
        'maintenance'   => ['maintenance'],
        'project'       => ['projects', 'timesheets'],
        'employee'      => ['employees', 'time-off', 'recruitments'],
        'time-off'      => ['time-off'],
        'recruitment'   => ['recruitments'],
        'website'       => ['website', 'blogs'],
        'barcode'       => ['barcode'],
        'setting'       => ['support'],
        'help'          => ['support'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Core Plugins (Always Active)
    |--------------------------------------------------------------------------
    |
    | These plugins are always loaded regardless of ACTIVE_MODULES.
    |
    */

    'core' => [
        'security',
        'support',
        'plugin-manager',
        'chatter',
        'fields',
        'payments',
        'full-calendar',
        'table-views',
    ],

];
