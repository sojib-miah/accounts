<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo ">
        <a href="{{ route('dashboard.index') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-3">ComitsBD</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        @can('dashboard-view')
            <li class="menu-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-smart-home"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>
        @endcan

        <!-- company -->
        <li
            class="menu-item {{ request()->routeIs('company.*') || request()->routeIs('branch.*') || request()->routeIs('user.*') || request()->routeIs('payment-type.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fa-brands fa-studiovinari me-3"></i>
                Company
            </a>
            <ul class="menu-sub">
                @can('company-list')
                    <li class="menu-item {{ request()->routeIs('company.index') ? 'active' : '' }}">
                        <a href="{{ route('company.index') }}" class="menu-link">
                            Company
                        </a>
                    </li>
                @endcan
                @can('branch-list')
                    <li class="menu-item {{ request()->routeIs('branch.index') ? 'active' : '' }}">
                        <a href="{{ route('branch.index') }}" class="menu-link">
                            Branch
                        </a>
                    </li>
                @endcan
                @can('payment-type-list')
                    <li class="menu-item {{ request()->routeIs('payment-type.index') ? 'active' : '' }}">
                        <a href="{{ route('payment-type.index') }}" class="menu-link">
                            Payment Type
                        </a>
                    </li>
                @endcan
                @can('company-user-list')
                    <li class="menu-item {{ request()->routeIs('user.index') ? 'active' : '' }}">
                        <a href="{{ route('user.index') }}" class="menu-link">
                            User
                        </a>
                    </li>
                @endcan
            </ul>
        </li>

        <!-- Accounts -->
        <li class="menu-item {{ request()->routeIs('accounts.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fa-solid fa-person-circle-check me-3"></i>
                Accounts
            </a>
            <ul class="menu-sub">
                @can('account-list')
                    <li class="menu-item {{ request()->routeIs('accounts.index') ? 'active' : '' }}">
                        <a href="{{ route('accounts.index') }}" class="menu-link">
                            Account
                        </a>
                    </li>
                @endcan
            </ul>
        </li>

        <!-- Income seals-->
        <li
            class="menu-item {{ request()->routeIs('receiver.*') || request()->routeIs('income.category.*') || request()->routeIs('income.*') || request()->routeIs('income.receipt.*') || request()->routeIs('challan.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fa-solid fa-money-bill-trend-up me-3"></i>
                Sales
            </a>
            <ul class="menu-sub">
                @can('receiver-list-list')
                    <li class="menu-item {{ request()->routeIs('receiver.index') ? 'active' : '' }}">
                        <a href="{{ route('receiver.index') }}" class="menu-link">
                            Customer List
                        </a>
                    </li>
                @endcan
                @can('income-category-list-list')
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
                @endcan
                @can('income-challan-list')
                    <li class="menu-item {{ request()->routeIs('challan.index') ? 'active' : '' }}">
                        <a href="{{ route('challan.index') }}" class="menu-link">
                            Challan
                        </a>
                    </li>
                @endcan
                @can('income-receipt-list')
                    <li class="menu-item {{ request()->routeIs('income.receipt.index') ? 'active' : '' }}">
                        <a href="{{ route('income.receipt.index') }}" class="menu-link">
                            Invoice
                        </a>
                    </li>
                @endcan
                <li class="menu-item {{ request()->routeIs('income.receipt.index') ? 'active' : '' }}">
                    <a href="{{ route('income.receipt.index') }}" class="menu-link">
                        Sales Details
                    </a>
                </li>
            </ul>
        </li>

        <!-- expense -->
        <li
            class="menu-item {{ request()->routeIs('party.*') || request()->routeIs('category.*') || request()->routeIs('account-head.*') || request()->routeIs('receipt.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fa-solid fa-briefcase me-3"></i>
                Expense
            </a>
            <ul class="menu-sub">
                @can('payee-list-list')
                    <li class="menu-item {{ request()->routeIs('party.index') ? 'active' : '' }}">
                        <a href="{{ route('party.index') }}" class="menu-link">
                            Payee List
                        </a>
                    </li>
                @endcan
                @can('expense-category-list-list')
                    <li class="menu-item {{ request()->routeIs('category.index') ? 'active' : '' }}">
                        <a href="{{ route('category.index') }}" class="menu-link">
                            Category List
                        </a>
                    </li>
                @endcan
                @can('expense-list-list')
                    <li class="menu-item {{ request()->routeIs('account-head.index') ? 'active' : '' }}">
                        <a href="{{ route('account-head.index') }}" class="menu-link">
                            Expense Description
                        </a>
                    </li>
                @endcan
                @can('expense-receipt-list')
                    <li class="menu-item {{ request()->routeIs('receipt.expense.index') ? 'active' : '' }}">
                        <a href="{{ route('receipt.expense.index') }}" class="menu-link">
                            Expense Receipt
                        </a>
                    </li>
                @endcan
                <li class="menu-item {{ request()->routeIs('receipt.expense.index') ? 'active' : '' }}">
                    <a href="{{ route('receipt.expense.index') }}" class="menu-link">
                        Expense Details
                    </a>
                </li>
            </ul>
        </li>

        <!-- contact -->
        <li class="menu-item {{ request()->routeIs('contact.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fa fa-users me-3"></i>
                Contact
            </a>
            <ul class="menu-sub">
                @can('contact-list')
                    <li class="menu-item {{ request()->routeIs('contact.index') ? 'active' : '' }}">
                        <a href="{{ route('contact.index') }}" class="menu-link">
                            Contact
                        </a>
                    </li>
                @endcan
            </ul>
        </li>

        <!-- users -->
        <li class="menu-item {{ request()->routeIs('users.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div data-i18n="Comits User">Comits User</div>
            </a>
            <ul class="menu-sub">
                @can('user-list')
                    <li class="menu-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="menu-link">
                            <div data-i18n="List">List</div>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>

        {{-- role and permission  --}}
        <li
            class="menu-item {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-settings"></i>
                <div data-i18n="Roles & Permissions">Roles & Permissions</div>
            </a>
            <ul class="menu-sub">
                @can('role-list')
                    <li class="menu-item {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                        <a href="{{ route('roles.index') }}" class="menu-link">
                            <div data-i18n="Roles">Roles</div>
                        </a>
                    </li>
                @endcan
                @can('permission-list')
                    <li class="menu-item {{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                        <a href="{{ route('permissions.index') }}" class="menu-link">
                            <div data-i18n="Permission">Permission</div>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>

        {{-- Settings  --}}
        <li
            class="menu-item {{ request()->routeIs('settings*') || request()->routeIs('contacts*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fa-solid fa-gear me-3"></i>
                Settings
            </a>
            <ul class="menu-sub">
                @can('general-settings-list')
                    <li class="menu-item {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                        <a href="{{ route('settings.index') }}" class="menu-link">
                            General Setting
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
    </ul>
</aside>
