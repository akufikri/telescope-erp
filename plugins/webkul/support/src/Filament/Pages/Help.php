<?php

namespace Webkul\Support\Filament\Pages;

use Filament\Infolists\Components\ViewEntry;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Webkul\Support\Enums\NavigationGroup;
use Webkul\Support\Services\ModuleFilter;

class Help extends Page
{
    protected string $view = 'support::pages.help';

    protected static ?string $slug = 'help';

    protected static ?int $navigationSort = 1;

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->sort(static::getNavigationSort())
                ->isActiveWhen(fn (): bool => request()->routeIs(static::getRouteName()))
                ->url(static::getUrl()),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('support::filament/pages/help.navigation.label');
    }

    public static function getNavigationGroup(): string|\UnitEnum
    {
        return NavigationGroup::Help;
    }

    public function getTitle(): string
    {
        return __('support::filament/pages/help.title');
    }

    public function getHeading(): string
    {
        return __('support::filament/pages/help.heading');
    }

    public function getSubheading(): ?string
    {
        return __('support::filament/pages/help.subheading');
    }

    public function guidesInfolist(Schema $schema): Schema
    {
        return $schema->components([
            $this->guideCardsGrid($this->guides()),
        ]);
    }

    public function servicesInfolist(Schema $schema): Schema
    {
        return $schema->components([
            $this->cardsGrid($this->services()),
        ]);
    }

    public function resourcesInfolist(Schema $schema): Schema
    {
        return $schema->components([
            $this->cardsGrid($this->resources()),
        ]);
    }

    protected function cardsGrid(array $cards): Grid
    {
        return Grid::make([
            'default' => 1,
            'md'      => 2,
            'xl'      => 3,
        ])->schema(
            array_map(
                fn (array $card, int $index): ViewEntry => ViewEntry::make("card_{$index}")
                    ->hiddenLabel()
                    ->view('support::pages.partials.help-card', ['card' => $card]),
                $cards,
                array_keys($cards),
            )
        );
    }

    protected function guideCardsGrid(array $cards): Grid
    {
        return Grid::make([
            'default' => 1,
            'md'      => 2,
            'xl'      => 3,
        ])->schema(
            array_map(
                fn (array $card, int $index): ViewEntry => ViewEntry::make("guide_{$index}")
                    ->hiddenLabel()
                    ->view('support::pages.partials.guide-card', ['card' => $card]),
                $cards,
                array_keys($cards),
            )
        );
    }

    protected function guides(): array
    {
        $guides = [];

        $allGuides = [
            'dashboard' => [
                'icon'        => 'heroicon-o-squares-2x2',
                'title'       => 'Dashboard',
                'description' => 'Overview of key business metrics, charts, and KPIs. Customize widgets to focus on what matters most to your role.',
                'steps'       => [
                    'Access Dashboard from the top navigation menu.',
                    'Click the gear icon to customize visible widgets.',
                    'Use the date range filter to adjust the reporting period.',
                    'Drag and drop widgets to rearrange the layout.',
                ],
            ],
            'manufacturing' => [
                'icon'        => 'heroicon-o-building-office-2',
                'title'       => 'Manufacturing',
                'description' => 'Manage Bill of Materials (BOM), Manufacturing Orders, Work Orders, and Work Centers for production planning.',
                'steps'       => [
                    'Create a Bill of Materials (BOM) to define product components.',
                    'Generate a Manufacturing Order from a BOM or sales order.',
                    'Assign Work Orders to Work Centers for tracking.',
                    'Track production progress in real-time via the Manufacturing dashboard.',
                    'Record completed quantities and scrap for accurate inventory updates.',
                ],
            ],
            'inventory' => [
                'icon'        => 'heroicon-o-cube',
                'title'       => 'Inventory',
                'description' => 'Track stock levels, manage warehouses, process receipts and deliveries, and set reorder rules.',
                'steps'       => [
                    'View current stock levels from the Inventory overview.',
                    'Create receipts for incoming products from purchase orders.',
                    'Process deliveries for outgoing products from sales orders.',
                    'Set minimum stock rules to trigger automatic replenishment.',
                    'Use the Barcode module for fast stock scanning and operations.',
                ],
            ],
            'project' => [
                'icon'        => 'heroicon-o-clipboard-document-check',
                'title'       => 'Project',
                'description' => 'Plan, track, and manage projects with tasks, milestones, timesheets, and team assignments.',
                'steps'       => [
                    'Create a new project and define its scope and timeline.',
                    'Break down projects into tasks with deadlines and assignees.',
                    'Track task progress using Kanban or Gantt views.',
                    'Log timesheets against tasks for accurate time tracking.',
                    'Use project analytics to monitor budget and进度.',
                ],
            ],
            'employees' => [
                'icon'        => 'heroicon-o-users',
                'title'       => 'Employees',
                'description' => 'Manage employee profiles, departments, positions, contracts, and organizational structure.',
                'steps'       => [
                    'Add new employees with their personal and job information.',
                    'Organize employees into departments and positions.',
                    'Track employment contracts and probation periods.',
                    'Manage employee documents and attachments.',
                    'Use the org chart to visualize company structure.',
                ],
            ],
            'website' => [
                'icon'        => 'heroicon-o-globe-alt',
                'title'       => 'Website',
                'description' => 'Build and manage your company website with pages, blogs, and content management.',
                'steps'       => [
                    'Create and edit website pages using the visual editor.',
                    'Publish blog posts to share company news and updates.',
                    'Manage navigation menus and page hierarchy.',
                    'Configure SEO settings for better search visibility.',
                    'Preview changes before publishing to production.',
                ],
            ],
            'barcode' => [
                'icon'        => 'heroicon-o-qr-code',
                'title'       => 'Barcode',
                'description' => 'Generate and scan barcodes for products, locations, and operations. Speed up warehouse and inventory tasks.',
                'steps'       => [
                    'Generate barcodes for products from the Product page.',
                    'Use a barcode scanner or mobile camera to scan items.',
                    'Process receipts, deliveries, and inventory adjustments via scan.',
                    'Print barcode labels for products and locations.',
                    'Configure barcode formats and prefixes in Settings.',
                ],
            ],
            'sale' => [
                'icon'        => 'heroicon-o-banknotes',
                'title'       => 'Sales',
                'description' => 'Manage quotations, sales orders, and customer relationships through the sales pipeline.',
                'steps'       => [
                    'Create quotations for customers with products and pricing.',
                    'Convert quotations to sales orders upon confirmation.',
                    'Track order status from quotation to delivery and invoicing.',
                    'Apply discounts, promotions, and special pricing.',
                    'Use the Sales dashboard to monitor pipeline and performance.',
                ],
            ],
            'purchase' => [
                'icon'        => 'heroicon-o-shopping-cart',
                'title'       => 'Purchases',
                'description' => 'Manage purchase orders, vendor relationships, and procurement workflows.',
                'steps'       => [
                    'Create purchase orders for suppliers.',
                    'Receive products and match against purchase orders.',
                    'Track vendor bills and payment status.',
                    'Manage vendor relationships and performance.',
                    'Use reorder rules to automate procurement.',
                ],
            ],
            'invoice' => [
                'icon'        => 'heroicon-o-document-text',
                'title'       => 'Invoices',
                'description' => 'Create, send, and track customer invoices and vendor bills.',
                'steps'       => [
                    'Generate invoices from sales orders or manually.',
                    'Send invoices to customers via email.',
                    'Track payment status and send reminders.',
                    'Record vendor bills and schedule payments.',
                    'Reconcile payments with bank statements.',
                ],
            ],
            'accounting' => [
                'icon'        => 'heroicon-o-calculator',
                'title'       => 'Accounting',
                'description' => 'Full double-entry accounting with journals, charts of accounts, financial reports, and bank reconciliation.',
                'steps'       => [
                    'Set up your Chart of Accounts to match your business structure.',
                    'Record journal entries for all financial transactions.',
                    'Reconcile bank statements with accounting records.',
                    'Generate financial reports (P&L, Balance Sheet, Cash Flow).',
                    'Manage multi-currency transactions and exchange rates.',
                ],
            ],
            'time-off' => [
                'icon'        => 'heroicon-o-calendar',
                'title'       => 'Time Off',
                'description' => 'Manage employee leave requests, time-off types, allocations, and approval workflows.',
                'steps'       => [
                    'Define time-off types (annual, sick, personal, etc.).',
                    'Allocate leave days to employees.',
                    'Submit and approve time-off requests.',
                    'View team calendar for availability planning.',
                    'Generate time-off reports for payroll processing.',
                ],
            ],
            'recruitment' => [
                'icon'        => 'heroicon-o-user-plus',
                'title'       => 'Recruitment',
                'description' => 'Track job positions, applicants, interviews, and hiring workflows.',
                'steps'       => [
                    'Create job positions with requirements and descriptions.',
                    'Track applicants through recruitment stages.',
                    'Schedule and record interview feedback.',
                    'Generate job offers for selected candidates.',
                    'Hire candidates and auto-create employee records.',
                ],
            ],
        ];

        foreach ($allGuides as $key => $guide) {
            if (ModuleFilter::isActive($key)) {
                $guides[] = $guide;
            }
        }

        return $guides;
    }

    protected function services(): array
    {
        return [
            [
                'icon'        => 'heroicon-o-cloud',
                'title'       => __('support::filament/pages/help.services.cloud.title'),
                'description' => __('support::filament/pages/help.services.cloud.description'),
                'url'         => 'https://telescope-erp.com/cloud-hosting',
                'button'      => __('support::filament/pages/help.services.cloud.button'),
            ],
            [
                'icon'        => 'heroicon-o-lifebuoy',
                'title'       => __('support::filament/pages/help.services.support.title'),
                'description' => __('support::filament/pages/help.services.support.description'),
                'url'         => 'https://telescope-erp.com/erp-support-maintenance-services',
                'button'      => __('support::filament/pages/help.services.support.button'),
            ],
            [
                'icon'        => 'heroicon-o-key',
                'title'       => __('support::filament/pages/help.services.paid.title'),
                'description' => __('support::filament/pages/help.services.paid.description'),
                'url'         => 'https://telescope-erp.com/custom-erp-development',
                'button'      => __('support::filament/pages/help.services.paid.button'),
            ],
        ];
    }

    protected function resources(): array
    {
        return [
            [
                'icon'        => 'heroicon-o-puzzle-piece',
                'title'       => __('support::filament/pages/help.resources.extensions.title'),
                'description' => __('support::filament/pages/help.resources.extensions.description'),
                'url'         => 'https://store.webkul.com/catalogsearch/result/?cat=All+Categories&q=Telescope ERP',
                'button'      => __('support::filament/pages/help.resources.extensions.button'),
            ],
            [
                'icon'        => 'heroicon-o-document-text',
                'title'       => __('support::filament/pages/help.resources.docs.title'),
                'description' => __('support::filament/pages/help.resources.docs.description'),
                'url'         => 'https://devdocs.telescope-erp.com',
                'button'      => __('support::filament/pages/help.resources.docs.button'),
            ],
            [
                'icon'        => 'heroicon-o-book-open',
                'title'       => __('support::filament/pages/help.resources.guide.title'),
                'description' => __('support::filament/pages/help.resources.guide.description'),
                'url'         => 'https://docs.telescope-erp.com',
                'button'      => __('support::filament/pages/help.resources.guide.button'),
            ],
        ];
    }
}
