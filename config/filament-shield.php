<?php

use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource;
use Filament\Pages\Dashboard;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Webkul\Support\Services\ModuleFilter;

$excludedResources = [];
$excludedPages = [];
$excludedWidgets = [];

if (! ModuleFilter::isActive('sale')) {
    $excludedResources = array_merge($excludedResources, [
        'Webkul\\Sales\\Filament\\Clusters\\Orders\\Resources\\SaleOrderResource',
        'Webkul\\Sales\\Filament\\Clusters\\Products\\Resources\\ProductResource',
        'Webkul\\Sales\\Filament\\Clusters\\PluginSettings\\Resources\\SalesSettingsResource',
        'Webkul\\Sales\\Filament\\Clusters\\Configuration\\Resources\\SalesTeamResource',
    ]);
}

if (! ModuleFilter::isActive('purchase')) {
    $excludedResources = array_merge($excludedResources, [
        'Webkul\\Purchases\\Filament\\Admin\\Clusters\\Orders\\Resources\\PurchaseOrderResource',
        'Webkul\\Purchases\\Filament\\Admin\\Clusters\\Products\\Resources\\ProductResource',
    ]);
}

if (! ModuleFilter::isActive('accounting')) {
    $excludedResources = array_merge($excludedResources, [
        'Webkul\\Accounting\\Filament\\Clusters\\Accounting\\Resources\\JournalResource',
        'Webkul\\Accounting\\Filament\\Clusters\\Customers\\Resources\\AccountMoveResource',
        'Webkul\\Accounting\\Filament\\Clusters\\Vendors\\Resources\\AccountMoveResource',
    ]);
}

if (! ModuleFilter::isActive('invoice')) {
    $excludedResources = array_merge($excludedResources, [
        'Webkul\\Invoices\\Filament\\Clusters\\Customers\\Resources\\InvoiceResource',
        'Webkul\\Invoices\\Filament\\Clusters\\Vendors\\Resources\\BillResource',
    ]);
}

if (! ModuleFilter::isActive('inventory')) {
    $excludedResources = array_merge($excludedResources, [
        'Webkul\\Inventory\\Filament\\Clusters\\Products\\Resources\\ProductResource',
        'Webkul\\Inventory\\Filament\\Clusters\\Operations\\Resources\\StockPickingResource',
    ]);
    $excludedPages = array_merge($excludedPages, [
        'Webkul\\Inventory\\Filament\\Pages\\Overview',
    ]);
}

if (! ModuleFilter::isActive('manufacturing')) {
    $excludedResources = array_merge($excludedResources, [
        'Webkul\\Manufacturing\\Filament\\Clusters\\Products\\Resources\\BillOfMaterialResource',
        'Webkul\\Manufacturing\\Filament\\Clusters\\Operations\\Resources\\ManufacturingOrderResource',
        'Webkul\\Manufacturing\\Filament\\Clusters\\Configurations\\Resources\\WorkCenterResource',
        'Webkul\\Manufacturing\\Filament\\Clusters\\Configurations\\Resources\\OperationResource',
    ]);
}

if (! ModuleFilter::isActive('employee')) {
    $excludedResources = array_merge($excludedResources, [
        'Webkul\\Employee\\Filament\\Resources\\EmployeeResource',
        'Webkul\\Employee\\Filament\\Resources\\DepartmentResource',
    ]);
}

if (! ModuleFilter::isActive('project')) {
    $excludedResources = array_merge($excludedResources, [
        'Webkul\\Projects\\Filament\\Resources\\ProjectResource',
        'Webkul\\Projects\\Filament\\Resources\\TaskResource',
        'Webkul\\Timesheets\\Filament\\Resources\\TimesheetResource',
    ]);
}

if (! ModuleFilter::isActive('website')) {
    $excludedResources = array_merge($excludedResources, [
        'Webkul\\Website\\Filament\\Admin\\Resources\\PageResource',
        'Webkul\\Website\\Filament\\Admin\\Resources\\PartnerResource',
    ]);
    $excludedPages = array_merge($excludedPages, [
        'Webkul\\Website\\Filament\\Admin\\Pages\\WebsiteDashboard',
    ]);
}

if (! ModuleFilter::isActive('contact')) {
    $excludedResources = array_merge($excludedResources, [
        'Webkul\\Contacts\\Filament\\Resources\\PartnerResource',
    ]);
}

if (! ModuleFilter::isActive('maintenance')) {
    $excludedResources = array_merge($excludedResources, [
        'Webkul\\Maintenance\\Filament\\Resources\\EquipmentResource',
    ]);
}

if (! ModuleFilter::isActive('recruitment')) {
    $excludedPages = array_merge($excludedPages, [
        'Webkul\\Recruitments\\Filament\\Pages\\Recruitments',
    ]);
}

return [

    /*
    |--------------------------------------------------------------------------
    | Shield Resource
    |--------------------------------------------------------------------------
    */

    'shield_resource' => [
        'slug'            => 'shield/roles',
        'show_model_path' => true,
        'cluster'         => null,
        'tabs'            => [
            'pages'              => true,
            'widgets'            => true,
            'resources'          => true,
            'custom_permissions' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Multi-Tenancy
    |--------------------------------------------------------------------------
    */

    'tenant_model' => null,

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    */

    'auth_provider_model' => 'Webkul\\Security\\Models\\User',

    /*
    |--------------------------------------------------------------------------
    | Super Admin
    |--------------------------------------------------------------------------
    */

    'super_admin' => [
        'enabled'         => false,
        'name'            => 'super_admin',
        'define_via_gate' => false,
        'intercept_gate'  => 'before',
    ],

    /*
    |--------------------------------------------------------------------------
    | Panel User
    |--------------------------------------------------------------------------
    */

    'panel_user' => [
        'enabled' => true,
        'name'    => 'Admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Builder
    |--------------------------------------------------------------------------
    */

    'permissions' => [
        'separator' => '_',
        'case'      => 'lower_snake',
        'generate'  => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Policies
    |--------------------------------------------------------------------------
    */

    'policies' => [
        'path'     => app_path('Policies'),
        'merge'    => false,
        'generate' => false,
        'methods'  => [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'restore',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore_any',
            'reorder',
        ],
        'single_parameter_methods' => [
            'view_any',
            'create',
            'delete_any',
            'force_delete_any',
            'restore_any',
            'reorder',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    */

    'localization' => [
        'enabled' => false,
        'key'     => 'filament-shield::filament-shield',
    ],

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    */

    'resources' => [
        'subject' => 'model',
        'manage'  => [],
        'exclude' => array_unique(array_merge(
            [RoleResource::class],
            $excludedResources,
        )),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    */

    'pages' => [
        'subject' => 'class',
        'prefix'  => 'view',
        'exclude' => array_unique(array_merge(
            [Dashboard::class],
            $excludedPages,
        )),
    ],

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    */

    'widgets' => [
        'subject' => 'class',
        'prefix'  => 'view',
        'exclude' => array_unique(array_merge(
            [AccountWidget::class, FilamentInfoWidget::class],
            $excludedWidgets,
        )),
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Permissions
    |--------------------------------------------------------------------------
    */

    'custom_permissions' => [],

    /*
    |--------------------------------------------------------------------------
    | Entity Discovery
    |--------------------------------------------------------------------------
    */

    'discovery' => [
        'discover_all_resources' => false,
        'discover_all_widgets'   => false,
        'discover_all_pages'     => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Policy
    |--------------------------------------------------------------------------
    */

    'register_role_policy' => true,

];
