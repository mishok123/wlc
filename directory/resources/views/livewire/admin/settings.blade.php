<div>
    <style>
        .color-preview-checkerboard {
            background-image: linear-gradient(45deg, #e5e7eb 25%, transparent 25%),
                linear-gradient(-45deg, #e5e7eb 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, #e5e7eb 75%),
                linear-gradient(-45deg, transparent 75%, #e5e7eb 75%);
            background-size: 8px 8px;
            background-position: 0 0, 0 4px, 4px -4px, -4px 0px;
            background-color: white;
        }
    </style>

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">General Settings</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Customize the look and feel of your application.
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form wire:submit="save">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 mb-4 rounded-t-lg flex gap-4 border-b">
                        <button type="button" wire:click="$set('activeTab', 'general')"
                            class="px-4 py-2 font-medium text-sm rounded-md transition {{ $activeTab === 'general' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                            General
                        </button>
                        <button type="button" wire:click="$set('activeTab', 'design')"
                            class="px-4 py-2 font-medium text-sm rounded-md transition {{ $activeTab === 'design' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                            Design & Colors
                        </button>
                        <button type="button" wire:click="$set('activeTab', 'status_messages')"
                            class="px-4 py-2 font-medium text-sm rounded-md transition {{ $activeTab === 'status_messages' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                            Status Messages
                        </button>
                        <button type="button" wire:click="$set('activeTab', 'project_cards')"
                            class="px-4 py-2 font-medium text-sm rounded-md transition {{ $activeTab === 'project_cards' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                            Project Cards
                        </button>
                        <button type="button" wire:click="$set('activeTab', 'ad_popup')"
                            class="px-4 py-2 font-medium text-sm rounded-md transition {{ $activeTab === 'ad_popup' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                            Ad Popup
                        </button>
                    </div>

                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <!-- Success Message -->
                        @if (session()->has('message'))
                            <div class="rounded-md bg-green-50 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- General Tab -->
                        @if($activeTab === 'general')
                                <!-- Favicon -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Favicon</label>
                                    <div class="mt-1 flex items-center">
                                        @if ($existingFavicon)
                                            <span class="inline-block h-8 w-8 rounded overflow-hidden bg-gray-100">
                                                <img src="{{ $existingFavicon }}" alt="Favicon" class="h-full w-full object-cover">
                                            </span>
                                        @else
                                            <span class="inline-block h-8 w-8 rounded overflow-hidden bg-gray-100">
                                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </span>
                                        @endif
                                        <button type="button"
                                            class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative">
                                            <span>Change</span>
                                            <input wire:model="favicon" type="file"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        </button>
                                    </div>
                                    @if ($favicon)
                                        <span class="text-sm text-gray-500 mt-2 block">New favicon selected:
                                            {{ $favicon->getClientOriginalName() }}</span>
                                    @endif
                                    @error('favicon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Logo -->
                                <label class="block text-sm font-medium text-gray-700">Logo</label>
                                <div class="mt-1 flex items-center">
                                    @if ($existingLogo)
                                        <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                            <img src="{{ $existingLogo }}" alt="Logo" class="h-full w-full object-cover">
                                        </span>
                                    @else
                                        <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    @endif
                                    <button type="button"
                                        class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative">
                                        <span>Change</span>
                                        <input wire:model="logo" type="file"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    </button>
                                </div>
                                @if ($logo)
                                    <span class="text-sm text-gray-500 mt-2 block">New logo selected:
                                        {{ $logo->getClientOriginalName() }}</span>
                                @endif
                                @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- General Configuration -->
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Site Title</label>
                                    <input type="text" wire:model="site_title"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('site_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Meta Title</label>
                                    <input type="text" wire:model="meta_title"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="SEO Title...">
                                    @error('meta_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-6">
                                    <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                                    <textarea wire:model="meta_description" rows="2"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="SEO Description..."></textarea>
                                    @error('meta_description') <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="font_family" class="block text-sm font-medium text-gray-700">Base Font Family
                                        (Google Fonts)</label>
                                    <input type="text" wire:model="font_family" id="font_family"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="e.g. Inter">
                                    <p class="mt-1 text-xs text-gray-500">Enter the font family name exactly as on Google Fonts.
                                    </p>
                                    @error('font_family') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="font_url" class="block text-sm font-medium text-gray-700">Font Import URL
                                        (Optional)</label>
                                    <input type="text" wire:model="font_url" id="font_url"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="e.g. https://fonts.googleapis.com/css2?family=Inter...">
                                    <p class="mt-1 text-xs text-gray-500">Leave blank if using the default font loading
                                        mechanism.</p>
                                </div>
                            </div>

                            <!-- Base Font Size -->
                            <div>
                                <label for="base_font_size" class="block text-sm font-medium text-gray-700">Base Font
                                    Size</label>
                                <select wire:model="base_font_size" id="base_font_size"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="12px">Small (12px)</option>
                                    <option value="14px">Normal (14px)</option>
                                    <option value="16px">Medium (16px)</option>
                                    <option value="18px">Large (18px)</option>
                                </select>
                                @error('base_font_size') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Colors -->
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-2">
                                    <label for="primary_color" class="block text-sm font-medium text-gray-700">Primary
                                        Color</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <div
                                            class="w-10 h-10 rounded border border-gray-300 color-preview-checkerboard overflow-hidden flex-shrink-0">
                                            <div class="w-full h-full" style="background-color: {{ $primary_color }}"></div>
                                        </div>
                                        <input type="text" wire:model.live="primary_color"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-2">
                                    <label for="secondary_color" class="block text-sm font-medium text-gray-700">Secondary
                                        Color</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <div
                                            class="w-10 h-10 rounded border border-gray-300 color-preview-checkerboard overflow-hidden flex-shrink-0">
                                            <div class="w-full h-full" style="background-color: {{ $secondary_color }}"></div>
                                        </div>
                                        <input type="text" wire:model.live="secondary_color"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-2">
                                    <label for="background_color" class="block text-sm font-medium text-gray-700">Background
                                        Color</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <div
                                            class="w-10 h-10 rounded border border-gray-300 color-preview-checkerboard overflow-hidden flex-shrink-0">
                                            <div class="w-full h-full" style="background-color: {{ $background_color }}"></div>
                                        </div>
                                        <input type="text" wire:model.live="background_color"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>

                            <!-- Warning Messages -->
                            <div class="grid grid-cols-6 gap-6 mt-6 pt-6 border-t border-gray-200">
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Scam Warning Message</label>
                                    <textarea wire:model="scam_warning_message" rows="3"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Enter warning message for projects marked as SCAM..."></textarea>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Potential Risk Warning
                                        Message</label>
                                    <textarea wire:model="risk_warning_message" rows="3"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Enter warning message for projects marked with POTENTIAL RISK..."></textarea>
                                </div>
                            </div>

                            <!-- XMPP Verification Contact -->
                            <div class="grid grid-cols-6 gap-6 mt-6 pt-6 border-t border-gray-200">
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Admin XMPP Contact (For Project
                                        Verification)</label>
                                    <input type="text" wire:model="admin_xmpp_contact"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="e.g. welivecrypto">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Minimum Bankroll Amount (USD) for "Own Liquidity"</label>
                                    <input type="number" wire:model="min_bankroll_amount"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="e.g. 5000">
                                </div>
                            </div>

                            <!-- Footer Copyright -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Footer Copyright Text (Supports
                                    HTML/Links)</label>
                                <div wire:ignore>
                                    <textarea id="footer_copyright_editor"
                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{!! $footer_copyright !!}</textarea>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">You can add links and basic formatting here.</p>
                                @error('footer_copyright') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                    {{-- Summernote Assets & Initialization --}}
                    @push('styles')
                        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"
                            rel="stylesheet">
                    @endpush

                    @push('scripts')
                        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
                            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
                            crossorigin="anonymous"></script>
                        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

                        <script>
                            $(document).ready(function () {
                                $('#footer_copyright_editor').summernote({
                                    placeholder: 'Enter footer copyright text...',
                                    tabsize: 2,
                                    height: 100,
                                    toolbar: [
                                        ['style', ['style']],
                                        ['font', ['bold', 'underline', 'clear']],
                                        ['color', ['color']],
                                        ['para', ['ul', 'ol', 'paragraph']],
                                        ['insert', ['link']],
                                        ['view', ['codeview']]
                                    ],
                                    callbacks: {
                                        onChange: function (contents, $editable) {
                                            @this.set('footer_copyright', contents);
                                        }
                                    }
                                });
                            });

                            document.addEventListener('livewire:navigates', () => {
                                $('#footer_copyright_editor').summernote('destroy');
                            });
                        </script>
                    @endpush

                    <!-- Design Tab -->
                    @if($activeTab === 'design')
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Directory Global</h3>
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Directory Background</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <input type="color" wire:model.live="dir_bg_color"
                                            class="h-10 w-10 border bg-white border-gray-300 rounded-md p-0 overflow-hidden cursor-pointer">
                                        <input type="text" wire:model="dir_bg_color"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                <div class="col-span-6 sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Directory Text</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <div
                                            class="w-10 h-10 rounded border border-gray-300 color-preview-checkerboard overflow-hidden flex-shrink-0">
                                            <div class="w-full h-full" style="background-color: {{ $dir_text_color }}"></div>
                                        </div>
                                        <input type="text" wire:model.live="dir_text_color"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                <div class="col-span-6 sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Directory Accent</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <div
                                            class="w-10 h-10 rounded border border-gray-300 color-preview-checkerboard overflow-hidden flex-shrink-0">
                                            <div class="w-full h-full" style="background-color: {{ $dir_accent_color }}"></div>
                                        </div>
                                        <input type="text" wire:model.live="dir_accent_color"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mt-6">Sidebar</h3>
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Sidebar Background</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <input type="text" wire:model="sidebar_bg_color"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            placeholder="transparent">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Supports transparent/rgba</p>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Sidebar Header Color</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <div
                                            class="w-10 h-10 rounded border border-gray-300 color-preview-checkerboard overflow-hidden flex-shrink-0">
                                            <div class="w-full h-full" style="background-color: {{ $sidebar_header_color }}">
                                            </div>
                                        </div>
                                        <input type="text" wire:model.live="sidebar_header_color"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Filter BG</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <div
                                            class="w-10 h-10 rounded border border-gray-300 color-preview-checkerboard overflow-hidden flex-shrink-0">
                                            <div class="w-full h-full" style="background-color: {{ $sidebar_filter_bg }}"></div>
                                        </div>
                                        <input type="text" wire:model.live="sidebar_filter_bg"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            placeholder="rgba(0,0,0,0.4)">
                                    </div>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Filter Border</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <div
                                            class="w-10 h-10 rounded border border-gray-300 color-preview-checkerboard overflow-hidden flex-shrink-0">
                                            <div class="w-full h-full" style="background-color: {{ $sidebar_filter_border }}">
                                            </div>
                                        </div>
                                        <input type="text" wire:model.live="sidebar_filter_border"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mt-6">Project Cards</h3>
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Card Background</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <input type="color" wire:model.live="card_bg_color"
                                            class="h-10 w-10 border bg-white border-gray-300 rounded-md p-0 overflow-hidden cursor-pointer">
                                        <input type="text" wire:model="card_bg_color"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Card Border</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <input type="color" wire:model.live="card_border_color"
                                            class="h-10 w-10 border bg-white border-gray-300 rounded-md p-0 overflow-hidden cursor-pointer">
                                        <input type="text" wire:model="card_border_color"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Card Title Color</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <input type="color" wire:model.live="card_title_color"
                                            class="h-10 w-10 border bg-white border-gray-300 rounded-md p-0 overflow-hidden cursor-pointer">
                                        <input type="text" wire:model="card_title_color"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Card Text Color</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <input type="color" wire:model.live="card_text_color"
                                            class="h-10 w-10 border bg-white border-gray-300 rounded-md p-0 overflow-hidden cursor-pointer">
                                        <input type="text" wire:model="card_text_color"
                                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mt-6">Condition Badges</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Safe -->
                                <div class="grid grid-cols-6 gap-2 items-end">
                                    <div class="col-span-1 text-sm font-medium text-gray-700 h-10 flex items-center">
                                        Verified/Safe</div>
                                    <div class="col-span-1">
                                        <label class="text-xs text-gray-500">Text</label>
                                        <div class="flex gap-1">
                                            <div
                                                class="w-8 h-8 rounded border border-gray-300 color-preview-checkerboard flex-shrink-0 overflow-hidden">
                                                <div class="w-full h-full" style="background-color: {{ $badge_safe_text }}">
                                                </div>
                                            </div>
                                            <input type="text" wire:model.live="badge_safe_text"
                                                class="w-full text-xs border-gray-300 rounded">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-xs text-gray-500">Background (RGBA)</label>
                                        <div class="flex gap-1">
                                            <div
                                                class="w-8 h-8 rounded border border-gray-300 color-preview-checkerboard flex-shrink-0 overflow-hidden">
                                                <div class="w-full h-full" style="background-color: {{ $badge_safe_bg }}"></div>
                                            </div>
                                            <input type="text" wire:model.live="badge_safe_bg"
                                                class="w-full text-xs border-gray-300 rounded">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-xs text-gray-500">Border (RGBA)</label>
                                        <div class="flex gap-1">
                                            <div
                                                class="w-8 h-8 rounded border border-gray-300 color-preview-checkerboard flex-shrink-0 overflow-hidden">
                                                <div class="w-full h-full" style="background-color: {{ $badge_safe_border }}">
                                                </div>
                                            </div>
                                            <input type="text" wire:model.live="badge_safe_border"
                                                class="w-full text-xs border-gray-300 rounded">
                                        </div>
                                    </div>
                                </div>

                                <!-- Danger -->
                                <div class="grid grid-cols-6 gap-2 items-end">
                                    <div class="col-span-1 text-sm font-medium text-gray-700 h-10 flex items-center">Scam/Danger
                                    </div>
                                    <div class="col-span-1">
                                        <label class="text-xs text-gray-500">Text</label>
                                        <div class="flex gap-1">
                                            <div
                                                class="w-8 h-8 rounded border border-gray-300 color-preview-checkerboard flex-shrink-0 overflow-hidden">
                                                <div class="w-full h-full" style="background-color: {{ $badge_danger_text }}">
                                                </div>
                                            </div>
                                            <input type="text" wire:model.live="badge_danger_text"
                                                class="w-full text-xs border-gray-300 rounded">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-xs text-gray-500">Background (RGBA)</label>
                                        <div class="flex gap-1">
                                            <div
                                                class="w-8 h-8 rounded border border-gray-300 color-preview-checkerboard flex-shrink-0 overflow-hidden">
                                                <div class="w-full h-full" style="background-color: {{ $badge_danger_bg }}">
                                                </div>
                                            </div>
                                            <input type="text" wire:model.live="badge_danger_bg"
                                                class="w-full text-xs border-gray-300 rounded">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-xs text-gray-500">Border (RGBA)</label>
                                        <div class="flex gap-1">
                                            <div
                                                class="w-8 h-8 rounded border border-gray-300 color-preview-checkerboard flex-shrink-0 overflow-hidden">
                                                <div class="w-full h-full" style="background-color: {{ $badge_danger_border }}">
                                                </div>
                                            </div>
                                            <input type="text" wire:model.live="badge_danger_border"
                                                class="w-full text-xs border-gray-300 rounded">
                                        </div>
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="grid grid-cols-6 gap-2 items-end">
                                    <div class="col-span-1 text-sm font-medium text-gray-700 h-10 flex items-center">
                                        Approved/Info</div>
                                    <div class="col-span-1">
                                        <label class="text-xs text-gray-500">Text</label>
                                        <div class="flex gap-1">
                                            <div
                                                class="w-8 h-8 rounded border border-gray-300 color-preview-checkerboard flex-shrink-0 overflow-hidden">
                                                <div class="w-full h-full" style="background-color: {{ $badge_info_text }}">
                                                </div>
                                            </div>
                                            <input type="text" wire:model.live="badge_info_text"
                                                class="w-full text-xs border-gray-300 rounded">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-xs text-gray-500">Background (RGBA)</label>
                                        <div class="flex gap-1">
                                            <div
                                                class="w-8 h-8 rounded border border-gray-300 color-preview-checkerboard flex-shrink-0 overflow-hidden">
                                                <div class="w-full h-full" style="background-color: {{ $badge_info_bg }}"></div>
                                            </div>
                                            <input type="text" wire:model.live="badge_info_bg"
                                                class="w-full text-xs border-gray-300 rounded">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-xs text-gray-500">Border (RGBA)</label>
                                        <div class="flex gap-1">
                                            <div
                                                class="w-8 h-8 rounded border border-gray-300 color-preview-checkerboard flex-shrink-0 overflow-hidden">
                                                <div class="w-full h-full" style="background-color: {{ $badge_info_border }}">
                                                </div>
                                            </div>
                                            <input type="text" wire:model.live="badge_info_border"
                                                class="w-full text-xs border-gray-300 rounded">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                <!-- Project Cards Tab -->
                @if($activeTab === 'project_cards')
                    <div class="space-y-12 pb-10">
                        {{-- Section: Header & Status --}}
                        <div>
                            <h3
                                class="text-lg font-bold text-gray-900 border-b-2 border-gray-100 pb-2 mb-6 uppercase tracking-wider">
                                1. Header & Status Badges</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Category Badge --}}
                                <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-6 shadow-sm">
                                    <span
                                        class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] block">Category
                                        Badge (Top Left)</span>
                                    <div class="space-y-4">
                                        <div class="space-y-1.5 flex flex-col border-b border-gray-100 pb-3">
                                            <label class="text-[10px] font-bold text-gray-500 uppercase">Background</label>
                                            <div class="flex gap-3 items-center">
                                                <input type="color" wire:model.live="card_cat_bg"
                                                    class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                <input type="text" wire:model.live="card_cat_bg"
                                                    class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                            </div>
                                        </div>
                                        <div class="space-y-1.5 flex flex-col border-b border-gray-100 pb-3">
                                            <label class="text-[10px] font-bold text-gray-500 uppercase">Text Color</label>
                                            <div class="flex gap-3 items-center">
                                                <input type="color" wire:model.live="card_cat_text"
                                                    class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                <input type="text" wire:model.live="card_cat_text"
                                                    class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                            </div>
                                        </div>
                                        <div class="space-y-1.5 flex flex-col">
                                            <label class="text-[10px] font-bold text-gray-500 uppercase">Border
                                                Color</label>
                                            <div class="flex gap-3 items-center">
                                                <input type="color" wire:model.live="card_cat_border"
                                                    class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                <input type="text" wire:model.live="card_cat_border"
                                                    class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Verification Status --}}
                                <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-6">
                                    <span
                                        class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] block">Status
                                        Indicators & Conditions</span>
                                    <div class="space-y-6">
                                        <div class="space-y-4">
                                            <div class="space-y-1.5 flex flex-col border-b border-gray-100 pb-3">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase">Verified
                                                    Icon</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_status_verified"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_status_verified"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                            <div class="space-y-1.5 flex flex-col border-b border-gray-100 pb-3">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase">Approved
                                                    Icon</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_status_approved"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_status_approved"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                            <div class="space-y-1.5 flex flex-col border-b border-gray-100 pb-3">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase">Owner
                                                    Verified</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_ownership_text"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_ownership_text"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-y-4 pt-2">
                                            <div class="space-y-1.5 flex flex-col">
                                                <label class="text-[10px] font-bold text-yellow-600 uppercase">Pending
                                                    Background</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_status_pending"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_status_pending"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                            <div class="space-y-1.5 flex flex-col">
                                                <label class="text-[10px] font-bold text-red-600 uppercase">Scam
                                                    Background</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_status_scam"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_status_scam"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                            <div class="space-y-1.5 flex flex-col">
                                                <label class="text-[10px] font-bold text-green-700 uppercase">Escrow
                                                    Shield</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_escrow_bg"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_escrow_bg"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Feature Indicators --}}
                        <div>
                            <h3
                                class="text-lg font-bold text-gray-900 border-b-2 border-gray-100 pb-2 mb-6 uppercase tracking-wider">
                                2. Feature Indicators (Badges)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Coins & Fee --}}
                                <div class="space-y-6">
                                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-6">
                                        <span
                                            class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em]">Coins
                                            Badge</span>
                                        <div class="space-y-4">
                                            <div class="space-y-3 flex flex-col border-b border-gray-100 pb-3">
                                                <label
                                                    class="text-[10px] font-bold text-gray-500 uppercase">Background</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_coins_bg"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                    <input type="text" wire:model.live="card_coins_bg"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9 shadow-sm">
                                                </div>
                                            </div>
                                            <div class="space-y-3 flex flex-col border-b border-gray-100 pb-3">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase">Text
                                                    color</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_coins_text"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                    <input type="text" wire:model.live="card_coins_text"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9 shadow-sm">
                                                </div>
                                            </div>
                                            <div class="space-y-3 flex flex-col">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase">Border
                                                    color</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_coins_border"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                    <input type="text" wire:model.live="card_coins_border"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9 shadow-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-6">
                                        <span class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em]">Fee
                                            Badge</span>
                                        <div class="space-y-4">
                                            <div class="space-y-3 flex flex-col border-b border-gray-100 pb-3">
                                                <label
                                                    class="text-[10px] font-bold text-gray-500 uppercase">Background</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_fee_bg"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                    <input type="text" wire:model.live="card_fee_bg"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9 shadow-sm">
                                                </div>
                                            </div>
                                            <div class="space-y-3 flex flex-col border-b border-gray-100 pb-3">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase">Text
                                                    color</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_fee_text"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                    <input type="text" wire:model.live="card_fee_text"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9 shadow-sm">
                                                </div>
                                            </div>
                                            <div class="space-y-3 flex flex-col">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase">Border
                                                    color</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_fee_border"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                    <input type="text" wire:model.live="card_fee_border"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9 shadow-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- TOR & LOG Feature --}}
                                <div class="grid grid-cols-1 gap-6">
                                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-6 shadow-sm">
                                        <span class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em]">TOR
                                            Status Indicators</span>
                                        <div class="space-y-6">
                                            <div class="space-y-4 border-b border-gray-100 pb-4">
                                                <label
                                                    class="text-[10px] font-bold text-green-600 uppercase tracking-widest block pb-1">HAVE
                                                    TOR (True)</label>
                                                <div class="space-y-3">
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_tor_true_bg"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_tor_true_bg"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Background Color">
                                                    </div>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_tor_true_text"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_tor_true_text"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Text Color">
                                                    </div>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_tor_true_border"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_tor_true_border"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Border Color">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-y-4">
                                                <label
                                                    class="text-[10px] font-bold text-red-600 uppercase tracking-widest block pb-1">NO
                                                    TOR (False)</label>
                                                <div class="space-y-3">
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_tor_false_bg"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_tor_false_bg"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Background Color">
                                                    </div>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_tor_false_text"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_tor_false_text"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Text Color">
                                                    </div>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_tor_false_border"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_tor_false_border"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Border Color">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-6 shadow-sm">
                                        <span class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em]">LOG
                                            Verifiable Indicators</span>
                                        <div class="space-y-6">
                                            <div class="space-y-4 border-b border-gray-100 pb-4">
                                                <label
                                                    class="text-[10px] font-bold text-green-600 uppercase tracking-widest block pb-1">VERIFIABLE
                                                    (True)</label>
                                                <div class="space-y-3">
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_log_true_bg"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_log_true_bg"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Background Color">
                                                    </div>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_log_true_text"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_log_true_text"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Text Color">
                                                    </div>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_log_true_border"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_log_true_border"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Border Color">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-y-4">
                                                <label
                                                    class="text-[10px] font-bold text-red-600 uppercase tracking-widest block pb-1">MISSING
                                                    (False)</label>
                                                <div class="space-y-3">
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_log_false_bg"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_log_false_bg"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Background Color">
                                                    </div>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_log_false_text"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_log_false_text"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Text Color">
                                                    </div>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_log_false_border"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_log_false_border"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                            placeholder="Border Color">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Policy Checklist --}}
                        <div>
                            <h3
                                class="text-lg font-bold text-gray-900 border-b-2 border-gray-100 pb-2 mb-6 uppercase tracking-wider">
                                3. Policy Compliance Indicators</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Policy Met --}}
                                <div class="bg-green-50/30 p-6 rounded-2xl border border-green-100 space-y-6 shadow-sm">
                                    <span
                                        class="text-sm font-bold text-green-800 flex items-center gap-2 uppercase tracking-wide">
                                        <span
                                            class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-sm shadow-green-200"></span>
                                        Policy Met (True)
                                    </span>
                                    <div class="space-y-4">
                                        <div class="space-y-1.5 flex flex-col border-b border-green-100/50 pb-3">
                                            <label class="text-[10px] uppercase font-black text-gray-400">Background</label>
                                            <div class="flex gap-3 items-center">
                                                <input type="color" wire:model.live="card_policy_true_bg"
                                                    class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                <input type="text" wire:model.live="card_policy_true_bg"
                                                    class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                            </div>
                                        </div>
                                        <div class="space-y-1.5 flex flex-col border-b border-green-100/50 pb-3">
                                            <label class="text-[10px] uppercase font-black text-gray-400">Text Color</label>
                                            <div class="flex gap-3 items-center">
                                                <input type="color" wire:model.live="card_policy_true_text"
                                                    class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                <input type="text" wire:model.live="card_policy_true_text"
                                                    class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                            </div>
                                        </div>
                                        <div class="space-y-1.5 flex flex-col border-b border-green-100/50 pb-3">
                                            <label class="text-[10px] uppercase font-black text-gray-400">Border
                                                Color</label>
                                            <div class="flex gap-3 items-center">
                                                <input type="color" wire:model.live="card_policy_true_border"
                                                    class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                <input type="text" wire:model.live="card_policy_true_border"
                                                    class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                            </div>
                                        </div>
                                        <div class="space-y-1.5 flex flex-col">
                                            <label class="text-[10px] uppercase font-black text-gray-400">Checkmark
                                                (icon)</label>
                                            <div class="flex gap-3 items-center">
                                                <input type="color" wire:model.live="card_policy_check_true"
                                                    class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                <input type="text" wire:model.live="card_policy_check_true"
                                                    class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Policy Fails --}}
                                <div class="bg-red-50/30 p-6 rounded-2xl border border-red-100 space-y-6 shadow-sm">
                                    <span
                                        class="text-sm font-bold text-red-800 flex items-center gap-2 uppercase tracking-wide">
                                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-sm shadow-red-200"></span>
                                        Policy Fails (False)
                                    </span>
                                    <div class="space-y-4">
                                        <div class="space-y-1.5 flex flex-col border-b border-red-100/50 pb-3">
                                            <label class="text-[10px] uppercase font-black text-gray-400">Background</label>
                                            <div class="flex gap-3 items-center">
                                                <input type="color" wire:model.live="card_policy_false_bg"
                                                    class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                <input type="text" wire:model.live="card_policy_false_bg"
                                                    class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                            </div>
                                        </div>
                                        <div class="space-y-1.5 flex flex-col border-b border-red-100/50 pb-3">
                                            <label class="text-[10px] uppercase font-black text-gray-400">Text Color</label>
                                            <div class="flex gap-3 items-center">
                                                <input type="color" wire:model.live="card_policy_false_text"
                                                    class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                <input type="text" wire:model.live="card_policy_false_text"
                                                    class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                            </div>
                                        </div>
                                        <div class="space-y-1.5 flex flex-col border-b border-red-100/50 pb-3">
                                            <label class="text-[10px] uppercase font-black text-gray-400">Border
                                                Color</label>
                                            <div class="flex gap-3 items-center">
                                                <input type="color" wire:model.live="card_policy_false_border"
                                                    class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                <input type="text" wire:model.live="card_policy_false_border"
                                                    class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                            </div>
                                        </div>
                                        <div class="space-y-1.5 flex flex-col">
                                            <label class="text-[10px] uppercase font-black text-gray-400">Cross
                                                (icon)</label>
                                            <div class="flex gap-3 items-center">
                                                <input type="color" wire:model.live="card_policy_check_false"
                                                    class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                <input type="text" wire:model.live="card_policy_check_false"
                                                    class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Performance Scores --}}
                        <div>
                            <h3
                                class="text-lg font-bold text-gray-900 border-b-2 border-gray-100 pb-2 mb-6 uppercase tracking-wider">
                                4. Quality & Score Tiers</h3>
                            <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                                    <div class="space-y-6">
                                        <span
                                            class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] block">Badge
                                            Labels (Global)</span>
                                        <div class="space-y-4">
                                            <div class="space-y-1.5 flex flex-col border-b border-gray-100 pb-3">
                                                <label
                                                    class="text-[10px] font-bold text-gray-500 uppercase">Background</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_attr_bg"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_attr_bg"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                            <div class="space-y-1.5 flex flex-col border-b border-gray-100 pb-3">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase">Label
                                                    Text</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_attr_text"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_attr_text"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                            <div class="space-y-1.5 flex flex-col">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase">Label
                                                    Border</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_attr_border"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_attr_border"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-6">
                                        <span
                                            class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] block">WLC
                                            Score Range BG Items</span>
                                        <div class="space-y-4">
                                            <div class="grid grid-cols-5 gap-2">
                                                <div class="flex flex-col gap-1.5">
                                                    <input type="color" wire:model.live="card_wlc_poor_bg"
                                                        class="w-full h-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <span
                                                        class="text-[9px] font-bold text-center text-red-500 uppercase">Poor</span>
                                                </div>
                                                <div class="flex flex-col gap-1.5">
                                                    <input type="color" wire:model.live="card_wlc_fair_bg"
                                                        class="w-full h-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <span
                                                        class="text-[9px] font-bold text-center text-yellow-500 uppercase">Fair</span>
                                                </div>
                                                <div class="flex flex-col gap-1.5">
                                                    <input type="color" wire:model.live="card_wlc_good_bg"
                                                        class="w-full h-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <span
                                                        class="text-[9px] font-bold text-center text-green-500 uppercase">Good</span>
                                                </div>
                                                <div class="flex flex-col gap-1.5">
                                                    <input type="color" wire:model.live="card_wlc_vgood_bg"
                                                        class="w-full h-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <span
                                                        class="text-[9px] font-bold text-center text-indigo-500 uppercase">V.Good</span>
                                                </div>
                                                <div class="flex flex-col gap-1.5">
                                                    <input type="color" wire:model.live="card_wlc_excellent_bg"
                                                        class="w-full h-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <span
                                                        class="text-[9px] font-bold text-center text-blue-500 uppercase">Excel.</span>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 gap-2">
                                                <input type="text" wire:model.live="card_wlc_poor_bg"
                                                    class="text-[10px] border-gray-200 rounded h-8 shadow-sm"
                                                    placeholder="Poor Hex">
                                                <input type="text" wire:model.live="card_wlc_fair_bg"
                                                    class="text-[10px] border-gray-200 rounded h-8 shadow-sm"
                                                    placeholder="Fair Hex">
                                                <input type="text" wire:model.live="card_wlc_good_bg"
                                                    class="text-[10px] border-gray-200 rounded h-8 shadow-sm"
                                                    placeholder="Good Hex">
                                                <input type="text" wire:model.live="card_wlc_vgood_bg"
                                                    class="text-[10px] border-gray-200 rounded h-8 shadow-sm"
                                                    placeholder="V.Good Hex">
                                                <input type="text" wire:model.live="card_wlc_excellent_bg"
                                                    class="text-[10px] border-gray-200 rounded h-8 shadow-sm"
                                                    placeholder="Excel. Hex">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 pt-8">
                                    <span
                                        class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] block mb-6">Visual
                                        Scoring Map (Tiers)</span>
                                    <div class="grid grid-cols-1 gap-8">
                                        <div class="p-8 bg-white rounded-2xl border border-red-100 space-y-6 shadow-sm">
                                            <label
                                                class="text-[10px] font-bold text-red-600 uppercase tracking-widest block border-b border-red-50 pb-2">Tier:
                                                Poor</label>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Text
                                                        Color</label>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_score_poor"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                        <input type="text" wire:model.live="card_score_poor"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Border
                                                        Color</label>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_score_poor_border"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                        <input type="text" wire:model.live="card_score_poor_border"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-8 bg-white rounded-2xl border border-yellow-100 space-y-6 shadow-sm">
                                            <label
                                                class="text-[10px] font-bold text-yellow-600 uppercase tracking-widest block border-b border-yellow-50 pb-2">Tier:
                                                Fair</label>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Text
                                                        Color</label>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_score_fair"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                        <input type="text" wire:model.live="card_score_fair"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Border
                                                        Color</label>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_score_fair_border"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                        <input type="text" wire:model.live="card_score_fair_border"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-8 bg-white rounded-2xl border border-green-100 space-y-6 shadow-sm">
                                            <label
                                                class="text-[10px] font-bold text-green-600 uppercase tracking-widest block border-b border-green-50 pb-2">Tier:
                                                Good</label>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Text
                                                        Color</label>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_score_good"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                        <input type="text" wire:model.live="card_score_good"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Border
                                                        Color</label>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_score_good_border"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                        <input type="text" wire:model.live="card_score_good_border"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-8 bg-white rounded-2xl border border-indigo-100 space-y-6 shadow-sm">
                                            <label
                                                class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest block border-b border-indigo-50 pb-2">Tier:
                                                Excellent</label>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Text
                                                        Color</label>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_score_excellent"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                        <input type="text" wire:model.live="card_score_excellent"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Border
                                                        Color</label>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_score_excellent_border"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200">
                                                        <input type="text" wire:model.live="card_score_excellent_border"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Footer Elements --}}
                        <div>
                            <h3
                                class="text-lg font-bold text-gray-900 border-b-2 border-gray-100 pb-2 mb-6 uppercase tracking-wider">
                                5. Footer & Community Badges</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-6">
                                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-6 shadow-sm">
                                        <span
                                            class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] block">Feedback
                                            Colors (Tiers)</span>
                                        <div class="space-y-6">
                                            <div class="space-y-2 border-b border-gray-100 pb-3">
                                                <label class="text-[10px] font-bold text-green-600 uppercase">Positive
                                                    (+)</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_review_pos"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_review_pos"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                            <div class="space-y-2 border-b border-gray-100 pb-3">
                                                <label class="text-[10px] font-bold text-yellow-600 uppercase">Neutral
                                                    (=)</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_review_neu"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_review_neu"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-bold text-red-600 uppercase">Negative
                                                    (-)</label>
                                                <div class="flex gap-3 items-center">
                                                    <input type="color" wire:model.live="card_review_neg"
                                                        class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                    <input type="text" wire:model.live="card_review_neg"
                                                        class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-6">
                                        {{-- KYC --}}
                                        <div
                                            class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-6 shadow-sm">
                                            <span
                                                class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] block">KYC
                                                Status Badge</span>
                                            <div class="space-y-6">
                                                <div class="space-y-2 border-b border-gray-100 pb-3">
                                                    <label
                                                        class="text-[10px] font-bold text-gray-500 uppercase">Background</label>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_kyc_bg"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_kyc_bg"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                    </div>
                                                </div>
                                                <div class="space-y-2 border-b border-gray-100 pb-3">
                                                    <label class="text-[10px] font-bold text-gray-500 uppercase">Text
                                                        Color</label>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_kyc_text"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_kyc_text"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-bold text-gray-500 uppercase">Border
                                                        Color</label>
                                                    <div class="flex gap-3 items-center">
                                                        <input type="color" wire:model.live="card_kyc_border"
                                                            class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                        <input type="text" wire:model.live="card_kyc_border"
                                                            class="flex-1 text-xs border-gray-300 rounded-lg h-9">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Community --}}
                                        <div
                                            class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-6 shadow-sm">
                                            <span
                                                class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] block">Community
                                                Indicators</span>
                                            <div class="space-y-6">
                                                <div class="space-y-4 border-b border-gray-100 pb-4">
                                                    <label
                                                        class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest block pb-1">EXISTS
                                                        (True)</label>
                                                    <div class="space-y-3">
                                                        <div class="flex gap-3 items-center">
                                                            <input type="color" wire:model.live="card_comm_true_bg"
                                                                class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                            <input type="text" wire:model.live="card_comm_true_bg"
                                                                class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                                placeholder="Background Color">
                                                        </div>
                                                        <div class="flex gap-3 items-center">
                                                            <input type="color" wire:model.live="card_comm_true_text"
                                                                class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                            <input type="text" wire:model.live="card_comm_true_text"
                                                                class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                                placeholder="Text Color">
                                                        </div>
                                                        <div class="flex gap-3 items-center">
                                                            <input type="color" wire:model.live="card_comm_true_border"
                                                                class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                            <input type="text" wire:model.live="card_comm_true_border"
                                                                class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                                placeholder="Border Color">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="space-y-4">
                                                    <label
                                                        class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block pb-1">MISSING
                                                        (False)</label>
                                                    <div class="space-y-3">
                                                        <div class="flex gap-3 items-center">
                                                            <input type="color" wire:model.live="card_comm_false_bg"
                                                                class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                            <input type="text" wire:model.live="card_comm_false_bg"
                                                                class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                                placeholder="Background Color">
                                                        </div>
                                                        <div class="flex gap-3 items-center">
                                                            <input type="color" wire:model.live="card_comm_false_text"
                                                                class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                            <input type="text" wire:model.live="card_comm_false_text"
                                                                class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                                placeholder="Text Color">
                                                        </div>
                                                        <div class="flex gap-3 items-center">
                                                            <input type="color" wire:model.live="card_comm_false_border"
                                                                class="h-9 w-10 p-1 rounded cursor-pointer border-gray-200 shadow-sm">
                                                            <input type="text" wire:model.live="card_comm_false_border"
                                                                class="flex-1 text-xs border-gray-300 rounded-lg h-9"
                                                                placeholder="Border Color">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endif

                    <!-- Status Messages Tab -->
                    @if($activeTab === 'status_messages')
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Status Specific Messages</h3>
                        <p class="text-sm text-gray-500 mt-1 mb-6">These messages will appear in the Project Status card on
                            the details page based on the current status.</p>

                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Pending Message</label>
                                <textarea wire:model="pending_status_message" rows="4"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Message for Pending status..."></textarea>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Approved Message</label>
                                <textarea wire:model="approved_status_message" rows="4"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Message for Approved status..."></textarea>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Rejected Message</label>
                                <textarea wire:model="rejected_status_message" rows="4"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Message for Rejected status..."></textarea>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Verified Message</label>
                                <textarea wire:model="verified_status_message" rows="4"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Message for Verified status..."></textarea>
                            </div>
                        </div>
                    @endif

                    {{-- Ad Popup Tab --}}
                    @if($activeTab === 'ad_popup')
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Popup Ad Settings</h3>
                        <p class="text-sm text-gray-500 mt-1 mb-6">Configure the fixed popup ad that appears in the bottom
                            right corner of project detail pages.</p>

                        {{-- Enable/Disable Checkbox --}}
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200 mb-6">
                            <div class="flex items-center h-5">
                                <input id="popup_ad_enabled" type="checkbox" wire:model="popup_ad_enabled"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div>
                                <label for="popup_ad_enabled" class="text-sm font-bold text-gray-900 cursor-pointer">Enable
                                    Popup Ad</label>
                                <p class="text-xs text-gray-500">When enabled, the ad will appear on all project detail
                                    pages.</p>
                            </div>
                        </div>

                        {{-- Ad Content --}}
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Ad Title</label>
                                <input type="text" wire:model="popup_ad_title"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="e.g. Leading Bitcoin Mixer">
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Ad Description</label>
                                <input type="text" wire:model="popup_ad_description"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="e.g. We prioritize your crypto anonymity!">
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Button Text</label>
                                <input type="text" wire:model="popup_ad_button_text"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="e.g. MIX NOW">
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Button Link (URL)</label>
                                <input type="url" wire:model="popup_ad_button_link"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="https://example.com">
                            </div>
                        </div>

                        {{-- Logo & Icon Uploads --}}
                        <div class="grid grid-cols-6 gap-6 mt-6 pt-6 border-t border-gray-200">
                            {{-- Logo --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ad Logo (Top Left)</label>
                                <p class="text-xs text-gray-500 mb-2">This appears as the brand logo in the top left of the
                                    ad. Recommended: PNG with transparency, ~120x40px.</p>
                                <div class="flex items-center gap-3">
                                    @if ($existingPopupAdLogo)
                                        <span class="inline-block h-10 w-auto rounded overflow-hidden bg-gray-800 p-1">
                                            <img src="{{ $existingPopupAdLogo }}" alt="Ad Logo"
                                                class="h-full w-auto object-contain">
                                        </span>
                                    @else
                                        <span
                                            class="inline-block h-10 w-20 rounded overflow-hidden bg-gray-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M4 5h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1zm16 12V6H4v11l4-4 3 3 5-5 4 4z" />
                                            </svg>
                                        </span>
                                    @endif
                                    <button type="button"
                                        class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 relative">
                                        <span>Upload Logo</span>
                                        <input wire:model="popup_ad_logo" type="file" accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    </button>
                                </div>
                                @if ($popup_ad_logo)
                                    <span class="text-sm text-gray-500 mt-2 block">Selected:
                                        {{ $popup_ad_logo->getClientOriginalName() }}</span>
                                @endif
                                @error('popup_ad_logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Background Icon --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Background Icon (Right
                                    Side)</label>
                                <p class="text-xs text-gray-500 mb-2">This appears as the large decorative icon on the
                                    right. Recommended: PNG with transparency, ~150x150px.</p>
                                <div class="flex items-center gap-3">
                                    @if ($existingPopupAdIcon)
                                        <span class="inline-block h-14 w-14 rounded overflow-hidden bg-gray-800 p-1">
                                            <img src="{{ $existingPopupAdIcon }}" alt="Ad Icon"
                                                class="h-full w-full object-contain">
                                        </span>
                                    @else
                                        <span
                                            class="inline-block h-14 w-14 rounded overflow-hidden bg-gray-100 flex items-center justify-center">
                                            <svg class="h-8 w-8 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M4 5h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1zm16 12V6H4v11l4-4 3 3 5-5 4 4z" />
                                            </svg>
                                        </span>
                                    @endif
                                    <button type="button"
                                        class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 relative">
                                        <span>Upload Icon</span>
                                        <input wire:model="popup_ad_icon" type="file" accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    </button>
                                </div>
                                @if ($popup_ad_icon)
                                    <span class="text-sm text-gray-500 mt-2 block">Selected:
                                        {{ $popup_ad_icon->getClientOriginalName() }}</span>
                                @endif
                                @error('popup_ad_icon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Live Preview --}}
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Live Preview</h4>
                            <div class="flex justify-end">
                                <div class="relative w-[380px] rounded-2xl overflow-hidden shadow-2xl"
                                    style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);">
                                    <div class="relative p-5 pr-16">
                                        {{-- Close button --}}
                                        <button type="button"
                                            class="absolute top-3 right-3 w-6 h-6 flex items-center justify-center text-gray-400 hover:text-white transition-colors z-10">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>

                                        {{-- Logo --}}
                                        @if($existingPopupAdLogo)
                                            <img src="{{ $existingPopupAdLogo }}" alt="Preview"
                                                class="h-6 w-auto mb-2 object-contain">
                                        @else
                                            <div class="h-6 w-20 bg-gray-700/50 rounded mb-2"></div>
                                        @endif

                                        {{-- Text --}}
                                        <p class="text-orange-400 font-bold italic text-sm leading-tight">
                                            {{ $popup_ad_title ?: 'Leading Bitcoin Mixer' }}</p>
                                        <p class="text-gray-300 text-xs mt-0.5">
                                            {{ $popup_ad_description ?: 'We prioritize your crypto anonymity!' }}</p>

                                        {{-- Button --}}
                                        <div class="mt-3">
                                            <span
                                                class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-white text-xs font-black uppercase tracking-wider"
                                                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                                                {{ $popup_ad_button_text ?: 'MIX NOW' }}
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Background Icon --}}
                                    @if($existingPopupAdIcon)
                                        <img src="{{ $existingPopupAdIcon }}"
                                            class="absolute -right-2 top-1/2 -translate-y-1/2 w-28 h-28 object-contain opacity-80"
                                            alt="">
                                    @else
                                        <div
                                            class="absolute -right-2 top-1/2 -translate-y-1/2 w-28 h-28 rounded-full bg-purple-600/20 border border-purple-500/20">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Settings
                    </button>
                </div>
        </div>
        </form>
    </div>
</div>
</div>