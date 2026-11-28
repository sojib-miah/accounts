<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo mt-5 mb-3">
        <a href="{{ route('dashboard.index') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-3">{{ __('common.comits_soft') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        @can('dashboard-view')
            <li class="menu-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}" class="menu-link">
                    <i class="fa fa-home me-3"></i>
                    <span>{{ __('common.dashboard') }}</span>
                </a>
            </li>
        @endcan

        <!-- company -->
        @can('menu-company-list')
            <li
                class="menu-item {{ request()->routeIs('company.*') || request()->routeIs('branch.*') || request()->routeIs('user.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa-brands fa-studiovinari me-3"></i>
                    {{ __('common.company') }}
                </a>
                <ul class="menu-sub">
                    @can('company-list')
                        <li class="menu-item {{ request()->routeIs('company.index') ? 'active' : '' }}">
                            <a href="{{ route('company.index') }}" class="menu-link">
                                {{ __('common.company') }}
                            </a>
                        </li>
                    @endcan
                    @can('branch-list')
                        <li class="menu-item {{ request()->routeIs('branch.index') ? 'active' : '' }}">
                            <a href="{{ route('branch.index') }}" class="menu-link">
                                {{ __('common.branch') }}
                            </a>
                        </li>
                    @endcan
                    @can('company-user-list')
                        <li class="menu-item {{ request()->routeIs('user.index') ? 'active' : '' }}">
                            <a href="{{ route('user.index') }}" class="menu-link">
                                {{ __('common.user') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        <!-- party -->
        <li
            class="menu-item {{ request()->routeIs('supplier-company.index') || request()->routeIs('supplier.index') || request()->routeIs('customer-company.index') || request()->routeIs('receiver.index') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fa-solid fa-champagne-glasses me-3"></i>
                {{ __('common.party') }}
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('supplier-company.index') ? 'active' : '' }}">
                    <a href="{{ route('supplier-company.index') }}" class="menu-link">
                        {{ __('common.supplier') }}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('supplier.index') ? 'active' : '' }}">
                    <a href="{{ route('supplier.index') }}" class="menu-link">
                        {{ __('common.suppliers_list') }}
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('customer-company.index') ? 'active' : '' }}">
                    <a href="{{ route('customer-company.index') }}" class="menu-link">
                        {{ __('common.customer') }}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('receiver.index') ? 'active' : '' }}">
                    <a href="{{ route('receiver.index') }}" class="menu-link">
                        {{ __('common.customers_list') }}
                    </a>
                </li>
            </ul>
        </li>

        <!-- Accounts -->
        @can('menu-account-list')
            <li
                class="menu-item {{ request()->routeIs('accounts.*') || request()->routeIs('payment-type.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa-solid fa-person-circle-check me-3"></i>
                    {{ __('common.accounts') }}
                </a>
                <ul class="menu-sub">
                    @can('payment-type-list')
                        <li class="menu-item {{ request()->routeIs('payment-type.index') ? 'active' : '' }}">
                            <a href="{{ route('payment-type.index') }}" class="menu-link">
                                {{ __('common.payment_type') }}
                            </a>
                        </li>
                    @endcan
                    @can('account-list')
                        <li class="menu-item {{ request()->routeIs('accounts.index') ? 'active' : '' }}">
                            <a href="{{ route('accounts.index') }}" class="menu-link">
                                {{ __('common.account') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        {{-- products  --}}
        @can('menu-product-list')
            <li
                class="menu-item {{ request()->routeIs('product.*') || request()->routeIs('product-category.*') || request()->routeIs('brand.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa-brands fa-product-hunt me-3"></i>
                    {{ __('common.products') }}
                </a>
                <ul class="menu-sub">
                    @can('product-category-list')
                        <li class="menu-item {{ request()->routeIs('product-category.index') ? 'active' : '' }}">
                            <a href="{{ route('product-category.index') }}" class="menu-link">
                                {{ __('common.category_list') }}
                            </a>
                        </li>
                    @endcan
                    @can('brand-list')
                        <li class="menu-item {{ request()->routeIs('brand.index') ? 'active' : '' }}">
                            <a href="{{ route('brand.index') }}" class="menu-link">
                                {{ __('common.brand_list') }}
                            </a>
                        </li>
                    @endcan
                    @can('product-list')
                        <li class="menu-item {{ request()->routeIs('product.index') ? 'active' : '' }}">
                            <a href="{{ route('product.index') }}" class="menu-link">
                                {{ __('common.products_list') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        <!-- purchase -->
        @can('menu-purchase-list')
            <li
                class="menu-item {{ request()->routeIs('purchase.*') || request()->routeIs('purchase.payment.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa-solid fa-basket-shopping me-3"></i>
                    {{ __('common.purchase') }}
                </a>
                <ul class="menu-sub">
                    {{-- @can('supplier-company-list')
                        <li class="menu-item {{ request()->routeIs('supplier-company.index') ? 'active' : '' }}">
                            <a href="{{ route('supplier-company.index') }}" class="menu-link">
                                Supplier Company
                            </a>
                        </li>
                    @endcan
                    @can('supplier-list')
                        <li class="menu-item {{ request()->routeIs('supplier.index') ? 'active' : '' }}">
                            <a href="{{ route('supplier.index') }}" class="menu-link">
                                Supplier List
                            </a>
                        </li>
                    @endcan --}}
                    @can('purchase-list')
                        <li class="menu-item {{ request()->routeIs('purchase.index') ? 'active' : '' }}">
                            <a href="{{ route('purchase.index') }}" class="menu-link">
                                {{ __('common.purchase_order') }}
                            </a>
                        </li>
                    @endcan
                    @can('make-payment-list')
                        <li class="menu-item {{ request()->routeIs('purchase.payment.index') ? 'active' : '' }}">
                            <a href="{{ route('purchase.payment.index') }}" class="menu-link">
                                {{ __('common.make_payment') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        <!-- warehouse -->
        @can('menu-warehouse-list')
            <li class="menu-item {{ request()->routeIs('warehouse.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa fa-warehouse me-3"></i>
                    {{ __('common.warehouse') }}
                </a>
                <ul class="menu-sub">
                    @can('warehouse-list')
                        <li class="menu-item {{ request()->routeIs('warehouse.index') ? 'active' : '' }}">
                            <a href="{{ route('warehouse.index') }}" class="menu-link">
                                {{ __('common.warehouse_list') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        <!-- inventory -->
        @can('menu-inventory-list')
            <li class="menu-item {{ request()->routeIs('inventory.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa fa-boxes me-3"></i>
                    {{ __('common.inventory') }}
                </a>
                <ul class="menu-sub">
                    @can('inventory-list')
                        <li class="menu-item {{ request()->routeIs('inventory.index') ? 'active' : '' }}">
                            <a href="{{ route('inventory.index') }}" class="menu-link">
                                {{ __('common.inventory') }}
                            </a>
                        </li>
                    @endcan
                    @can('inventory-lowstock-list')
                        <li class="menu-item {{ request()->routeIs('inventory.lowStock') ? 'active' : '' }}">
                            <a href="{{ route('inventory.lowStock') }}" class="menu-link">
                                {{ __('common.low_stock') }}
                            </a>
                        </li>
                    @endcan
                    @can('inventory-report-list')
                        <li class="menu-item {{ request()->routeIs('inventory.report') ? 'active' : '' }}">
                            <a href="{{ route('inventory.report') }}" class="menu-link">
                                {{ __('common.stock_report') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        <!-- Income seals-->
        @can('menu-sales-list')
            <li
                class="menu-item {{ request()->routeIs('income.category.*') || request()->routeIs('income.*') || request()->routeIs('income.receipt.*') || request()->routeIs('challan.*') || request()->routeIs('income.*') || request()->routeIs('sales.order.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa-solid fa-money-bill-trend-up me-3"></i>
                    {{ __('common.sales') }}
                </a>
                <ul class="menu-sub">
                    {{-- @can('receiver-company-list')
                        <li class="menu-item {{ request()->routeIs('customer-company.index') ? 'active' : '' }}">
                            <a href="{{ route('customer-company.index') }}" class="menu-link">
                                Customer Company
                            </a>
                        </li>
                    @endcan
                    @can('receiver-list-list')
                        <li class="menu-item {{ request()->routeIs('receiver.index') ? 'active' : '' }}">
                            <a href="{{ route('receiver.index') }}" class="menu-link">
                                Customer List
                            </a>
                        </li>
                    @endcan --}}
                    {{-- @can('income-category-list-list')
                        <li class="menu-item {{ request()->routeIs('income.category.index') ? 'active' : '' }}">
                            <a href="{{ route('income.category.index') }}" class="menu-link">
                                Category List
                            </a>
                        </li>
                    @endcan
                    @can('income-list-list')
                        <li class="menu-item {{ request()->routeIs('income.index') ? 'active' : '' }}">
                            <a href="{{ route('income.index') }}" class="menu-link">
                                Item List
                            </a>
                        </li>
                    @endcan --}}
                    @can('income-salesorder-list')
                        <li class="menu-item {{ request()->routeIs('sales.order.index') ? 'active' : '' }}">
                            <a href="{{ route('sales.order.index') }}" class="menu-link">
                                {{ __('common.sales_order') }}
                            </a>
                        </li>
                    @endcan
                    @can('income-challan-list')
                        <li class="menu-item {{ request()->routeIs('challan.index') ? 'active' : '' }}">
                            <a href="{{ route('challan.index') }}" class="menu-link">
                                {{ __('common.challan') }}
                            </a>
                        </li>
                    @endcan
                    @can('income-receipt-list')
                        <li class="menu-item {{ request()->routeIs('income.receipt.index') ? 'active' : '' }}">
                            <a href="{{ route('income.receipt.index') }}" class="menu-link">
                                {{ __('common.invoice') }}
                            </a>
                        </li>
                    @endcan
                    @can('income-details-list')
                        <li class="menu-item {{ request()->routeIs('income.invoice') ? 'active' : '' }}">
                            <a href="{{ route('income.invoice') }}" class="menu-link">
                                {{ __('common.income_invoice') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        {{-- direct income  --}}
        <li class="menu-item {{ request()->routeIs('direct.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fa-solid fa-diamond-turn-right me-3"></i>
                {{ __('common.direct_sales') }}
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('direct.income.index') ? 'active' : '' }}">
                    <a href="{{ route('direct.income.index') }}" class="menu-link">
                        {{ __('common.direct_sales') }}
                    </a>
                </li>
            </ul>
        </li>

        <!-- expense -->
        @can('menu-expense-list')
            <li
                class="menu-item {{ request()->routeIs('party.*') || request()->routeIs('category.*') || request()->routeIs('account-head.*') || request()->routeIs('receipt.*') || request()->routeIs('expense.*') || request()->routeIs('customer-expense.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa-solid fa-briefcase me-3"></i>
                    {{ __('common.expense') }}
                </a>
                <ul class="menu-sub">
                    {{-- @can('payee-company-list')
                        <li class="menu-item {{ request()->routeIs('customer-expense.index') ? 'active' : '' }}">
                            <a href="{{ route('customer-expense.index') }}" class="menu-link">
                                Customer Company
                            </a>
                        </li>
                    @endcan
                    @can('payee-list-list')
                        <li class="menu-item {{ request()->routeIs('party.index') ? 'active' : '' }}">
                            <a href="{{ route('party.index') }}" class="menu-link">
                                Customer List
                            </a>
                        </li>
                    @endcan --}}
                    @can('expense-category-list-list')
                        <li class="menu-item {{ request()->routeIs('category.index') ? 'active' : '' }}">
                            <a href="{{ route('category.index') }}" class="menu-link">
                                {{ __('common.category_list') }}
                            </a>
                        </li>
                    @endcan
                    @can('expense-list-list')
                        <li class="menu-item {{ request()->routeIs('account-head.index') ? 'active' : '' }}">
                            <a href="{{ route('account-head.index') }}" class="menu-link">
                                {{ __('common.expense_description') }}
                            </a>
                        </li>
                    @endcan
                    @can('expense-receipt-list')
                        <li class="menu-item {{ request()->routeIs('receipt.expense.index') ? 'active' : '' }}">
                            <a href="{{ route('receipt.expense.index') }}" class="menu-link">
                                {{ __('common.expense_receipt') }}
                            </a>
                        </li>
                    @endcan
                    @can('expense-details-list')
                        <li class="menu-item {{ request()->routeIs('expense.details') ? 'active' : '' }}">
                            <a href="{{ route('expense.details') }}" class="menu-link">
                                {{ __('common.expense_details') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        {{-- report --}}
        <li
            class="menu-item {{ request()->routeIs('report.*') || request()->routeIs('receipt.return.index') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fa-solid fa-chart-simple me-3"></i>
                {{ __('common.report') }}
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('report.balance') ? 'active' : '' }}">
                    <a href="{{ route('report.balance') }}" class="menu-link">
                        {{ __('common.balance_report') }}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('report.sales') ? 'active' : '' }}">
                    <a href="{{ route('report.sales') }}" class="menu-link">
                        {{ __('common.sales_report') }}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('report.purchase') ? 'active' : '' }}">
                    <a href="{{ route('report.purchase') }}" class="menu-link">
                        {{ __('common.purchase_report') }}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('report.expense') ? 'active' : '' }}">
                    <a href="{{ route('report.expense') }}" class="menu-link">
                        {{ __('common.expense_receipt') }}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('report.profit') ? 'active' : '' }}">
                    <a href="{{ route('report.profit') }}" class="menu-link">
                        {{ __('common.profit_report') }}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('report.receivable') ? 'active' : '' }}">
                    <a href="{{ route('report.receivable') }}" class="menu-link">
                        {{ __('common.receivable_report') }}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('report.payable') ? 'active' : '' }}">
                    <a href="{{ route('report.payable') }}" class="menu-link">
                        {{ __('common.payable_report') }}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('report.received') ? 'active' : '' }}">
                    <a href="{{ route('report.received') }}" class="menu-link">
                        {{ __('common.money_received_report') }}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('report.paid') ? 'active' : '' }}">
                    <a href="{{ route('report.paid') }}" class="menu-link">
                        {{ __('common.money_paid_report') }}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('receipt.return.index') ? 'active' : '' }}">
                    <a href="{{ route('receipt.return.index') }}" class="menu-link">
                        {{ __('common.return_report') }}
                    </a>
                </li>
            </ul>
        </li>

        <!-- users -->
        @can('menu-comits-user-list')
            <li class="menu-item {{ request()->routeIs('users.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-users"></i>
                    <div data-i18n="Comits User">{{ __('common.bims_user') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('user-list')
                        <li class="menu-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="menu-link">
                                <div data-i18n="List">{{ __('common.user_list') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('user-list')
                        <li class="menu-item {{ request()->routeIs('users.deleted') ? 'active' : '' }}">
                            <a href="{{ route('users.deleted') }}" class="menu-link">
                                <div data-i18n="Deleted User">{{ __('common.deleted_user') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        {{-- role and permission  --}}
        @can('menu-role-permission-list')
            <li
                class="menu-item {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-settings"></i>
                    <div data-i18n="Roles & Permissions">{{ __('common.roles_permissions') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('role-list')
                        <li class="menu-item {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                            <a href="{{ route('roles.index') }}" class="menu-link">
                                <div data-i18n="Roles">{{ __('common.roles') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('permission-list')
                        <li class="menu-item {{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                            <a href="{{ route('permissions.index') }}" class="menu-link">
                                <div data-i18n="Permission">{{ __('common.permissions') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        {{-- package  --}}
        @can('menu-package-list')
            <li
                class="menu-item {{ request()->routeIs('package.*') || request()->routeIs('admin.company-package.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa-solid fa-cubes me-3"></i>
                    {{ __('common.package') }}
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('package.index') ? 'active' : '' }}">
                        <a href="{{ route('package.index') }}" class="menu-link">
                            {{ __('common.package') }}
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.company-package.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.company-package.index') }}" class="menu-link">
                            {{ __('common.user_package') }}
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        {{-- contact  --}}
        @can('menu-package-list')
            <li class="menu-item {{ request()->routeIs('contact.show') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa-solid fa-phone me-3"></i>
                    {{ __('common.contact') }}
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('contact.show') ? 'active' : '' }}">
                        <a href="{{ route('contact.show') }}" class="menu-link">
                            {{ __('common.contact_form') }}
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        {{-- payment result  --}}
        @can('menu-package-list')
            <li class="menu-item {{ request()->routeIs('admin.payment.index') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa-solid fa-money-bill me-3"></i>
                    {{ __('common.payment_list') }}
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('admin.payment.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.payment.index') }}" class="menu-link">
                            {{ __('common.payment_list') }}
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        {{-- Settings  --}}
        @can('menu-setting-list')
            <li
                class="menu-item {{ request()->routeIs('settings*') || request()->routeIs('admin.login-as-user') || request()->routeIs('admin.activity-log.index') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="fa-solid fa-gear me-3"></i>
                    {{ __('common.settings') }}
                </a>
                <ul class="menu-sub">
                    @can('general-settings-list')
                        <li class="menu-item {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                            <a href="{{ route('settings.index') }}" class="menu-link">
                                {{ __('common.general_settings') }}
                            </a>
                        </li>
                    @endcan
                    @can('menu-comits-user-list')
                        @hasrole('Super-Admin')
                            <li class="menu-item {{ request()->routeIs('admin.login-as-user') ? 'active' : '' }}">
                                <a href="{{ route('admin.login-as-user') }}" class="menu-link">
                                    {{ __('common.login_as_user') }}
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('admin.activity-log.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.activity-log.index') }}" class="menu-link">
                                    {{ __('common.activity_logs') }}
                                </a>
                            </li>
                        @endhasrole
                    @endcan
                </ul>
            </li>
        @endcan

        {{-- cache clear --}}
        <li class="menu-item">
            <a href="{{ route('cache.clear') }}" class="menu-link">
                <i class="fa-solid fa-arrows-spin me-3"></i>
                {{ __('common.cache_clear') }}
            </a>
        </li>
    </ul>

    <div class="m-4">
        <a href="{{ route('upgrade.index') }}" class="btn btn-success btn-lg shadow">
            <i class="fa-brands fa-web-awesome me-3"></i>
            {{ __('common.upgrade_to_pro') }}
        </a>
    </div>
</aside>
