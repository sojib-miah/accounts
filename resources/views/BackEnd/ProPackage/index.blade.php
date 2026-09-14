@extends('BackEnd.Layouts.layout')

@section('title', 'Packages')

@section('content')
    <div class="container py-5 mt-5">
        <div class="row justify-content-center mb-4">
            <div class="col-xl-8 col-lg-10 text-center">
                <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                    <i class="fas fa-crown me-1"></i>
                    Flexible Plans
                </span>
                <h1 class="fw-bold text-dark mb-2">
                    Choose Your Perfect Package
                </h1>
                <p class="text-muted mb-0">
                    Simple and transparent pricing designed to help
                    your business grow faster.
                </p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse ($packages as $index => $package)
                @php
                    $styles = [
                        [
                            'class' => 'primary',
                            'icon' => 'fa-paper-plane',
                            'description' => 'Perfect for small businesses getting started.',
                        ],
                        [
                            'class' => 'success',
                            'icon' => 'fa-crown',
                            'description' => 'Great for growing businesses with more needs.',
                        ],
                        [
                            'class' => 'purple',
                            'icon' => 'fa-star',
                            'description' => 'Ideal for established businesses with higher volume.',
                        ],
                        [
                            'class' => 'orange',
                            'icon' => 'fa-gem',
                            'description' => 'For large organizations with unlimited potential.',
                        ],
                    ];
                    $style = $styles[$index % count($styles)];
                    $isPopular = $index === 1;
                    $features = [
                        'Users' => $package->user_limit,
                        'Companies' => $package->company_limit,
                        'Income Records' => $package->income_limit,
                        'Expense Records' => $package->expense_limit,
                        'Challans' => $package->challan_limit,
                        'Branches' => $package->branch_limit,
                        'Parties' => $package->party_limit,
                        'Accounts' => $package->account_limit,
                        'Storage' => $package->storage_limit,
                        'Payment Types' => $package->payment_type_limit,
                        'Categories' => $package->category_limit,
                        'Item List' => $package->item_list_limit,
                        'Sales Orders' => $package->sales_order_limit,
                    ];
                    $formatLimit = function ($name, $value) {
                        if ((int) $value === 0) {
                            return 'Unlimited';
                        }
                        if ($name === 'Storage') {
                            return number_format($value) . ' GB';
                        }
                        return number_format($value);
                    };
                @endphp
                <div class="col-12 col-sm-6 col-lg-6 col-xl-3">
                    <div
                        class="card package-card h-100 border shadow-sm
                        {{ $isPopular ? 'popular-package' : '' }}">
                        {{-- Popular Badge --}}
                        @if ($isPopular)
                            <div class="popular-badge">
                                <span class="badge rounded-pill bg-success px-3 py-2">
                                    <i class="fas fa-star me-1"></i>
                                    Most Popular
                                </span>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column p-4">
                            <div class="text-center mb-3">
                                <div class="package-icon
                                    icon-{{ $style['class'] }}">
                                    <i class="fas {{ $style['icon'] }}"></i>
                                </div>
                            </div>
                            <h4 class="text-center fw-bold text-dark mb-2">
                                {{ $package->name }}
                            </h4>
                            <p class="text-center text-muted small mb-3 package-description">
                                {{ $package->remarks ?: $style['description'] }}
                            </p>
                            <div
                                class="price-box
                                price-{{ $style['class'] }}
                                text-center rounded-3 py-3 mb-3">
                                <div class="fw-bold">
                                    <span class="currency">
                                        ৳
                                    </span>
                                    <span class="price-number">
                                        {{ number_format($package->price, 0) }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    / month
                                </small>
                            </div>
                            <div class="package-features flex-grow-1">
                                @foreach ($features as $feature => $value)
                                    <div
                                        class="d-flex justify-content-between
                                        align-items-center feature-row">
                                        <div class="text-muted">
                                            <i
                                                class="fas fa-check-circle
                                                text-success me-2">
                                            </i>
                                            {{ $feature }}
                                        </div>
                                        <span class="fw-semibold text-dark small">
                                            {{ $formatLimit($feature, $value) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <a href="#"
                                    class="btn btn-{{ $style['class'] }}
                                          package-button w-100 py-2">
                                    Get Started
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    @if ($index === 0)
                                        Best for small teams.
                                    @elseif ($index === 1)
                                        Most popular choice.
                                    @elseif ($index === 2)
                                        Power up your business.
                                    @else
                                        Maximum flexibility.
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="text-muted mb-3">
                                <i class="fas fa-box-open fa-3x"></i>
                            </div>
                            <h5 class="fw-bold">
                                No Packages Available
                            </h5>
                            <p class="text-muted mb-0">
                                Please create a package to display it here.
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-xl-8">
                <div
                    class="d-flex flex-wrap justify-content-center
                            align-items-center gap-4 text-muted">
                    <div class="d-flex align-items-center gap-2">
                        <span class="benefit-icon">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                        <small>
                            Secure & Reliable
                        </small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="benefit-icon">
                            <i class="fas fa-headset"></i>
                        </span>
                        <small>
                            24/7 Support
                        </small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="benefit-icon">
                            <i class="fas fa-sync-alt"></i>
                        </span>
                        <small>
                            Upgrade Anytime
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .package-card {
            position: relative;
            border-radius: 16px !important;
            transition: all .3s ease;
            overflow: hidden;
        }

        .package-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, .10) !important;
        }

        .popular-package {
            border: 2px solid #20b86b !important;
            box-shadow:
                0 10px 30px rgba(25, 135, 84, .12) !important;
        }

        .popular-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 2;
        }

        .package-icon {
            width: 62px;
            height: 62px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 24px;
        }

        .icon-primary {
            background: #eaf4ff;
            color: #0d6efd;
        }

        .icon-success {
            background: #e9f9f0;
            color: #198754;
        }

        .icon-purple {
            background: #f1eaff;
            color: #6f42c1;
        }

        .icon-orange {
            background: #fff0e8;
            color: #fd7e14;
        }

        .package-description {
            min-height: 40px;
            line-height: 1.5;
        }

        .price-box {
            border: 0;
        }

        .price-primary {
            background: #edf6ff;
        }

        .price-success {
            background: #eafaf2;
        }

        .price-purple {
            background: #f3edff;
        }

        .price-orange {
            background: #fff1e9;
        }

        .price-number {
            font-size: 31px;
            line-height: 1;
        }

        .currency {
            font-size: 18px;
            vertical-align: 4px;
            font-weight: 700;
        }

        .feature-row {
            min-height: 32px;
            padding: 4px 0;
            border-bottom: 1px solid #f0f2f4;
            font-size: 12px;
        }

        .feature-row:last-child {
            border-bottom: 0;
        }

        .feature-row .fa-check-circle {
            font-size: 12px;
        }

        .package-button {
            border-radius: 9px;
            font-weight: 600;
            border: 0;
            transition: all .2s ease;
        }

        .package-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(0, 0, 0, .12);
        }

        .btn-purple {
            background: #6f42c1;
            color: #fff;
        }

        .btn-purple:hover {
            background: #5d35a5;
            color: #fff;
        }

        .btn-orange {
            background: #fd7e14;
            color: #fff;
        }

        .btn-orange:hover {
            background: #e96b08;
            color: #fff;
        }

        .benefit-icon {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff;
            box-shadow:
                0 3px 10px rgba(0, 0, 0, .08);

            font-size: 12px;
        }

        @media (max-width: 1199.98px) {
            .package-card {
                min-height: 650px;
            }
        }

        @media (max-width: 991.98px) {
            .package-card {
                min-height: auto;
            }
        }

        @media (max-width: 575.98px) {
            .package-header h1 {
                font-size: 27px;
            }

            .package-card {
                border-radius: 14px !important;
            }

            .package-card .card-body {
                padding: 20px !important;
            }
        }
    </style>
@endsection
