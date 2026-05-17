<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @if(isset($meta_title) && $meta_title)
            {{ $meta_title }}
        @else
            {{ isset($title) && $title ? $title . ' - ' : '' }}{{ \App\Models\Setting::where('key', 'meta_title')->value('value') ?: (\App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'WeLiveCrypto Directory')) }}
        @endif
    </title>
    <meta name="description"
        content="{{ isset($meta_description) && $meta_description ? $meta_description : (\App\Models\Setting::where('key', 'meta_description')->value('value') ?: '') }}">

    <!-- Tailwind CSS via CDN for Quick Dev -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @if(isset($settings['font_url']) && $settings['font_url'])
        <link href="{{ $settings['font_url'] }}" rel="stylesheet">
    @elseif(isset($settings['font_family']))
        <link
            href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $settings['font_family']) }}&display=swap"
            rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    @endif
    @if(isset($settings['favicon']) && $settings['favicon'])
        <link rel="icon" href="{{ asset('storage/' . $settings['favicon']) }}">
    @endif
    <style>
        :root {
            /* Directory Global */
            --dir-bg:
                {{ $settings['dir_bg_color'] ?? '#111827' }}
            ;
            --dir-text:
                {{ $settings['dir_text_color'] ?? '#f3f4f6' }}
            ;
            --dir-accent:
                {{ $settings['dir_accent_color'] ?? '#22c55e' }}
            ;

            /* Sidebar */
            --sidebar-bg:
                {{ $settings['sidebar_bg_color'] ?? 'transparent' }}
            ;
            --sidebar-filter-bg:
                {{ $settings['sidebar_filter_bg'] ?? 'rgba(0,0,0,0.4)' }}
            ;
            --sidebar-filter-border:
                {{ $settings['sidebar_filter_border'] ?? '#374151' }}
            ;
            --sidebar-header:
                {{ $settings['sidebar_header_color'] ?? '#22c55e' }}
            ;

            /* Cards */
            --card-bg:
                {{ $settings['card_bg_color'] ?? '#111111' }}
            ;
            --card-border:
                {{ $settings['card_border_color'] ?? '#1f2937' }}
            ;
            --card-title:
                {{ $settings['card_title_color'] ?? '#ffffff' }}
            ;
            --card-text:
                {{ $settings['card_text_color'] ?? '#9ca3af' }}
            ;

            /* Badges */
            --badge-safe-text:
                {{ $settings['badge_safe_text'] ?? '#4ade80' }}
            ;
            --badge-safe-bg:
                {{ $settings['badge_safe_bg'] ?? 'rgba(20, 83, 45, 0.4)' }}
            ;
            --badge-safe-border:
                {{ $settings['badge_safe_border'] ?? 'rgba(22, 101, 52, 0.5)' }}
            ;

            --badge-danger-text:
                {{ $settings['badge_danger_text'] ?? '#f87171' }}
            ;
            --badge-danger-bg:
                {{ $settings['badge_danger_bg'] ?? 'rgba(127, 29, 29, 0.4)' }}
            ;
            --badge-danger-border:
                {{ $settings['badge_danger_border'] ?? 'rgba(153, 27, 27, 0.5)' }}
            ;

            --badge-info-text:
                {{ $settings['badge_info_text'] ?? '#60a5fa' }}
            ;
            --badge-info-bg:
                {{ $settings['badge_info_bg'] ?? 'rgba(30, 58, 138, 0.3)' }}
            ;
            --badge-info-border:
                {{ $settings['badge_info_border'] ?? 'rgba(30, 64, 175, 0.5)' }}
            ;

            --badge-warning-text:
                {{ $settings['badge_warning_text'] ?? '#facc15' }}
            ;
            --badge-warning-bg:
                {{ $settings['badge_warning_bg'] ?? 'rgba(113, 63, 18, 0.3)' }}
            ;
            --badge-warning-border:
                {{ $settings['badge_warning_border'] ?? 'rgba(133, 77, 14, 0.5)' }}
            ;

            --card-cat-bg:
                {{ $settings['card_cat_bg'] ?? 'rgba(30, 58, 138, 0.3)' }}
            ;
            --card-cat-text:
                {{ $settings['card_cat_text'] ?? '#93c5fd' }}
            ;
            --card-cat-border:
                {{ $settings['card_cat_border'] ?? 'rgba(30, 64, 175, 0.5)' }}
            ;

            --card-wlc-poor:
                {{ $settings['card_wlc_poor_bg'] ?? '#dc2626' }}
            ;
            --card-wlc-fair:
                {{ $settings['card_wlc_fair_bg'] ?? '#FFBF00' }}
            ;
            --card-wlc-good:
                {{ $settings['card_wlc_good_bg'] ?? '#dcfce7' }}
            ;
            --card-wlc-vgood:
                {{ $settings['card_wlc_vgood_bg'] ?? '#7cff7c' }}
            ;
            --card-wlc-excellent:
                {{ $settings['card_wlc_excellent_bg'] ?? '#16a34a' }}
            ;

            --card-status-verified:
                {{ $settings['card_status_verified'] ?? '#3b82f6' }}
            ;
            --card-status-approved:
                {{ $settings['card_status_approved'] ?? '#22c55e' }}
            ;
            --card-status-pending:
                {{ $settings['card_status_pending'] ?? '#eab308' }}
            ;
            --card-status-scam:
                {{ $settings['card_status_scam'] ?? '#dc2626' }}
            ;

            --card-escrow:
                {{ $settings['card_escrow_bg'] ?? '#15803d' }}
            ;
            --card-ownership:
                {{ $settings['card_ownership_text'] ?? '#00ff00' }}
            ;

            --card-coins-bg:
                {{ $settings['card_coins_bg'] ?? '#1a442a' }}
            ;
            --card-coins-text:
                {{ $settings['card_coins_text'] ?? '#86e2a2' }}
            ;
            --card-fee-bg:
                {{ $settings['card_fee_bg'] ?? '#293247' }}
            ;
            --card-fee-text:
                {{ $settings['card_fee_text'] ?? '#a1b0cd' }}
            ;

            --card-tor-true-bg:
                {{ $settings['card_tor_true_bg'] ?? '#1a442a' }}
            ;
            --card-tor-true-text:
                {{ $settings['card_tor_true_text'] ?? '#86e2a2' }}
            ;
            --card-tor-true-border:
                {{ $settings['card_tor_true_border'] ?? '#16a34a' }}
            ;
            --card-tor-false-text:
                {{ $settings['card_tor_false_text'] ?? '#ef4444' }}
            ;
            --card-tor-false-border:
                {{ $settings['card_tor_false_border'] ?? '#dc2626' }}
            ;

            --card-attr-bg:
                {{ $settings['card_attr_bg'] ?? '#1a1c23' }}
            ;
            --card-attr-text:
                {{ $settings['card_attr_text'] ?? '#a0a5b1' }}
            ;
            --card-attr-border:
                {{ $settings['card_attr_border'] ?? '#2a3041' }}
            ;

            --card-policy-true-bg:
                {{ $settings['card_policy_true_bg'] ?? '#1a442a' }}
            ;
            --card-policy-true-text:
                {{ $settings['card_policy_true_text'] ?? '#86e2a2' }}
            ;
            --card-policy-true-border:
                {{ $settings['card_policy_true_border'] ?? '#16a34a' }}
            ;
            --card-policy-false-bg:
                {{ $settings['card_policy_false_bg'] ?? '#441a1a' }}
            ;
            --card-policy-false-text:
                {{ $settings['card_policy_false_text'] ?? '#e28686' }}
            ;
            --card-policy-false-border:
                {{ $settings['card_policy_false_border'] ?? '#dc2626' }}
            ;
            --card-policy-check-true:
                {{ $settings['card_policy_check_true'] ?? '#22c55e' }}
            ;
            --card-policy-check-false:
                {{ $settings['card_policy_check_false'] ?? '#ef4444' }}
            ;

            --score-poor:
                {{ $settings['card_score_poor'] ?? '#ef4444' }}
            ;
            --score-poor-border:
                {{ $settings['card_score_poor_border'] ?? 'rgba(239, 68, 68, 0.5)' }}
            ;
            --score-fair:
                {{ $settings['card_score_fair'] ?? '#FFBF00' }}
            ;
            --score-fair-border:
                {{ $settings['card_score_fair_border'] ?? 'rgba(255, 191, 0, 0.5)' }}
            ;
            --score-good:
                {{ $settings['card_score_good'] ?? '#7cff7c' }}
            ;
            --score-good-border:
                {{ $settings['card_score_good_border'] ?? 'rgba(124, 255, 124, 0.5)' }}
            ;
            --score-excellent:
                {{ $settings['card_score_excellent'] ?? '#22c55e' }}
            ;
            --score-excellent-border:
                {{ $settings['card_score_excellent_border'] ?? 'rgba(34, 197, 94, 0.5)' }}
            ;

            --score-poor-bg:
                {{ $settings['card_wlc_poor_bg'] ?? '#ef4444' }}
            ;
            --score-fair-bg:
                {{ $settings['card_wlc_fair_bg'] ?? '#FFBF00' }}
            ;
            --score-good-bg:
                {{ $settings['card_wlc_good_bg'] ?? '#22c55e' }}
            ;
            --score-vgood-bg:
                {{ $settings['card_wlc_vgood_bg'] ?? '#3b82f6' }}
            ;
            --score-excellent-bg:
                {{ $settings['card_wlc_excellent_bg'] ?? '#8b5cf6' }}
            ;

            --review-pos:
                {{ $settings['card_review_pos'] ?? '#22c55e' }}
            ;
            --review-neu:
                {{ $settings['card_review_neu'] ?? '#d1d5db' }}
            ;
            --review-neg:
                {{ $settings['card_review_neg'] ?? '#ef4444' }}
            ;

            --card-kyc-bg:
                {{ $settings['card_kyc_bg'] ?? '#18211b' }}
            ;
            --card-kyc-text:
                {{ $settings['card_kyc_text'] ?? '#ffffff' }}
            ;
            --card-kyc-border:
                {{ $settings['card_kyc_border'] ?? '#22c55e' }}
            ;

            --card-comm-true-bg:
                {{ $settings['card_comm_true_bg'] ?? '#1d1b31' }}
            ;
            --card-comm-true-text:
                {{ $settings['card_comm_true_text'] ?? '#716cd5' }}
            ;
            --card-comm-true-border:
                {{ $settings['card_comm_true_border'] ?? '#716cd5' }}
            ;
            --card-comm-false-bg:
                {{ $settings['card_comm_false_bg'] ?? 'rgba(31, 41, 55, 0.3)' }}
            ;
            --card-comm-false-text:
                {{ $settings['card_comm_false_text'] ?? '#4b5563' }}
            ;
            --card-comm-false-border:
                {{ $settings['card_comm_false_border'] ?? 'rgba(31, 41, 55, 0.5)' }}
            ;

            --card-age-text:
                {{ $settings['card_item_age'] ?? '#6b7280' }}
            ;
            --card-online:
                {{ $settings['card_online_dot'] ?? '#22c55e' }}
            ;
            --card-offline:
                {{ $settings['card_offline_dot'] ?? '#ef4444' }}
            ;

            --card-log-true-bg:
                {{ $settings['card_log_true_bg'] ?? '#ffffff' }}
            ;
            --card-log-true-text:
                {{ $settings['card_log_true_text'] ?? '#15803d' }}
            ;
            --card-log-true-border:
                {{ $settings['card_log_true_border'] ?? '#15803d' }}
            ;
            --card-log-false-bg:
                {{ $settings['card_log_false_bg'] ?? 'rgba(31, 41, 55, 0.3)' }}
            ;
            --card-log-false-text:
                {{ $settings['card_log_false_text'] ?? '#4b5563' }}
            ;
            --card-log-false-border:
                {{ $settings['card_log_false_border'] ?? 'rgba(31, 41, 55, 0.5)' }}
            ;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased"
    style="background-color: {{ $settings['background_color'] ?? '#f9fafb' }}; font-family: '{{ $settings['font_family'] ?? 'Inter' }}', sans-serif; font-size: {{ $settings['base_font_size'] ?? '16px' }};">
    <nav x-data="{ mobileMenuOpen: false }" class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex justify-between h-16">
                {{-- Left Side: Logo & Desktop Links --}}
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="font-bold text-xl text-blue-600">
                            @if(isset($settings['logo']) && $settings['logo'])
                                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="WeLiveCrypto"
                                    class="h-8 w-auto">
                            @else
                                WeLiveCrypto
                            @endif
                        </a>
                    </div>
                    <div class="hidden space-x-8 lg:-my-px lg:ml-10 lg:flex">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-blue-700 transition duration-150 ease-in-out">
                            Catalog
                        </a>
                        <a href="{{ rtrim((string) config('smf.url', 'http://localhost/wlc/forum'), '/') }}/"
                            class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Forum
                        </a>
                    </div>
                </div>

                {{-- Right Side: Desktop Auth & Mobile Menu Toggle --}}
                <div class="flex items-center gap-4">
                    {{-- Desktop Auth Links --}}
                    <div class="hidden lg:flex items-center space-x-4">
                        @auth
                            <a href="{{ route('projects.submit') }}"
                                class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                + Submit Project
                            </a>

                            @if(Auth::user()->isModerator())
                                <a href="{{ route('admin.dashboard') }}"
                                    class="text-sm font-medium text-red-600 hover:text-red-800">
                                    Admin Dashboard
                                </a>
                            @endif

                            <div class="text-sm text-gray-500">
                                Hello, {{ Auth::user()->name }}
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 cursor-pointer">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Login /
                                Register</a>
                        @endauth
                    </div>

                    {{-- Mobile Menu Button --}}
                    <div class="flex lg:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="text-gray-600 hover:text-gray-900 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Menu Overlay/Drawer --}}
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-full" class="fixed inset-0 z-50 lg:hidden" role="dialog"
            aria-modal="true">
            {{-- Backdrop --}}
            <div @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/50"></div>

            {{-- Menu Content --}}
            <div class="fixed inset-y-0 right-0 w-full max-w-xs bg-white shadow-xl flex flex-col p-6">
                <div class="flex items-center justify-between mb-8">
                    <span class="font-bold text-xl text-blue-600">Menu</span>
                    <button @click="mobileMenuOpen = false" class="text-gray-500 hover:text-gray-800">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col space-y-4">
                    <a href="{{ route('home') }}"
                        class="text-lg font-medium text-gray-900 hover:text-blue-600">Catalog</a>
                    <a href="{{ rtrim((string) config('smf.url', 'http://localhost/wlc/forum'), '/') }}/"
                        class="text-lg font-medium text-gray-900 hover:text-blue-600">Forum</a>

                    <hr class="border-gray-100">

                    @auth
                        <a href="{{ route('projects.submit') }}" class="text-lg font-medium text-blue-600">+ Submit
                            Project</a>
                        @if(Auth::user()->isModerator())
                            <a href="{{ route('admin.dashboard') }}" class="text-lg font-medium text-red-600">Admin
                                Dashboard</a>
                        @endif
                        <div class="pt-4 text-sm text-gray-500">Logged in as {{ Auth::user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left text-lg font-medium text-gray-600 hover:text-gray-900">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-lg font-medium text-gray-900 hover:text-blue-600">Login /
                            Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-slate-900 text-white py-12 mt-auto border-t border-slate-800">
        <div class="container mx-auto px-4 text-center">
            <div
                class="text-xs text-gray-500 flex flex-wrap justify-center items-center gap-1 footer-copyright-container">
                <span>&copy; 2026 - {{ date('Y') }}</span>
                <div class="inline-rich-text">
                    {!! $settings['footer_copyright'] ?? 'WeLive CRYPTO. All rights reserved.' !!}
                </div>
            </div>

            <style>
                .footer-copyright-container p {
                    display: inline;
                    margin: 0;
                }

                .inline-rich-text a {
                    color: inherit;
                    text-decoration: underline;
                }

                .inline-rich-text a:hover {
                    color: #fff;
                }
            </style>
        </div>
    </footer>
    <script src="{{ asset('vendor/livewire/livewire.js') }}" data-csrf="{{ csrf_token() }}"
        data-update-uri="{{ url('/livewire/update') }}" data-navigate-once="true"></script>
</body>

</html>