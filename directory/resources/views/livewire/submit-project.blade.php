<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Submit New Project</h1>
        
        <p class="mb-6 text-gray-600">
            Submit your project for listing in the WeLiveCrypto Directory. All submissions are subject to manual review by our moderation team.
        </p>

        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-6">
            {{-- Basic Info --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                <input wire:model="name" type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div x-data="{
                validateLogo(e) {
                    const file = e.target.files[0];
                    
                    if (!file) {
                        @this.set('logo', null);
                        return;
                    }

                    const clear = () => {
                        e.target.value = '';
                        @this.set('logo', null);
                    };

                    // 1. Check size (300KB)
                    if (file.size > 300 * 1024) {
                        alert('Error: File is too large. Maximum size allowed is 300KB.');
                        clear();
                        return;
                    }

                    // 2. Check dimensions and ratio
                    const img = new Image();
                    img.onload = () => {
                        const width = img.width;
                        const height = img.height;
                        
                        if (width !== height) {
                            alert('Error: The logo must be a perfect square (1:1 ratio). Your image is ' + width + 'x' + height + '.');
                            clear();
                            return;
                        }
                        
                        if (width > 512 || height > 512) {
                            alert('Error: Maximum dimensions allowed are 512x512 pixels. Your image is ' + width + 'x' + height + '.');
                            clear();
                            return;
                        }

                        // Validation Passed - Start Upload
                        @this.upload('logo', file);
                    };
                    img.onerror = () => {
                        alert('Error: The selected file is not a valid image.');
                        clear();
                    };
                    img.src = URL.createObjectURL(file);
                }
            }">
                <label class="block text-sm font-medium text-gray-700 mb-1">Project Logo</label>
                <div class="flex items-center space-x-4">
                    @if ($logo && method_exists($logo, 'temporaryUrl'))
                        <img src="{{ $logo->temporaryUrl() }}" class="w-16 h-16 rounded-full object-cover border">
                    @endif
                    <input type="file" accept="image/png, image/jpeg" @change="validateLogo($event)" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 300KB. 1:1 ratio, max 512x512.</p>
                @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select wire:model.live="category_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Select a Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Website URL <span class="text-xs text-gray-400">(One URL per line)</span></label>
                <textarea wire:model="website_url" rows="3" placeholder="https://..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                @error('website_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Terms of Conditions URL</label>
                <input wire:model="terms_url" type="url" placeholder="https://example.com/terms" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('terms_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea wire:model="description" rows="5" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                    <input wire:model="contact_email" type="email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="email@example.com">
                    @error('contact_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telegram</label>
                    <input wire:model="contact_telegram" type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="@username">
                    @error('contact_telegram') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discord</label>
                    <input wire:model="contact_discord" type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="username#0000">
                    @error('contact_discord') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SimpleX</label>
                    <textarea wire:model="contact_simplex" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="SimpleX ID"></textarea>
                    @error('contact_simplex') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- ==================== MIXER SPECIFIC FIELDS ==================== --}}
            @if($isMixerCategory)
            <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 space-y-5">
                <h3 class="font-bold text-gray-700 text-lg border-b pb-2">Mixer Details & Scoring Fields</h3>
                <p class="text-sm text-gray-500">These fields determine the Reputation and Privacy scores for your project.</p>

                {{-- #1 Age (datetime) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Age Related Data <span class="text-xs text-gray-400">(Launch Date)</span></label>
                    <input type="date" wire:model.live="launch_date" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                {{-- #2 Community (Textarea) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Community Related Data <span class="text-xs text-gray-400">(One URL per line)</span></label>
                    <textarea wire:model="community" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="https://bitcointalk.org/...&#10;https://altcoinstalks.com/..."></textarea>
                </div>

                {{-- #3 Guarantee Fund (Radio button) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Guarantee Fund Related Data</label>
                    <div class="space-y-2 pl-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model.live="guarantee_fund" value="No escrow" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">No escrow</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model.live="guarantee_fund" value="Escrow under $10k" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Escrow under $10k</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model.live="guarantee_fund" value="Escrow between $10k to $20k" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Escrow between $10k to $20k</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model.live="guarantee_fund" value="Escrow above $20 to $50k" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Escrow above $20 to $50k</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model.live="guarantee_fund" value="Escrow above $50k" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Escrow above $50k</span>
                        </label>
                    </div>
                </div>

                @if($guarantee_fund && $guarantee_fund !== 'No escrow')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guarantee Url</label>
                        <input type="url" wire:model="guarantee_url" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="https://example.com/guarantee-proof">
                        @error('guarantee_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                @endif

                {{-- #4 Privacy KYC (Radio button) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Privacy Related Data</label>
                    <div class="space-y-2 pl-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="privacy_kyc" value="Privacy KYC 0 (Guaranteed No KYC)" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Privacy KYC 0 (Guaranteed No KYC)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="privacy_kyc" value="Privacy KYC 1" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Privacy KYC 1</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="privacy_kyc" value="Privacy KYC 2" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Privacy KYC 2</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="privacy_kyc" value="Privacy KYC 3" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Privacy KYC 3</span>
                        </label>
                    </div>
                </div>

                {{-- New Fees Detail Section --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-100 rounded-lg border border-gray-200">
                    <div class="md:col-span-3 pb-1 border-b border-gray-300">
                        <label class="block text-sm font-bold text-gray-700">Fees</label>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Min (%)</label>
                        <input type="number" step="0.01" wire:model="fee_min" placeholder="0.00" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        @error('fee_min') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Max (%)</label>
                        <input type="number" step="0.01" wire:model="fee_max" placeholder="0.00" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        @error('fee_max') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Fixed</label>
                        <input type="number" step="0.00000001" wire:model="fee_fixed" placeholder="0.00000000" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        @error('fee_fixed') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>                {{-- #7 LOG Verifiable (Checkbox) --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="log_verifiable_cb" wire:model.live="log_verifiable" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5 cursor-pointer">
                    <label for="log_verifiable_cb" class="text-sm font-medium text-gray-700 cursor-pointer">LOG Verifiable</label>
                </div>

                @if($log_verifiable)
                <div class="pl-7 space-y-4 transition-all duration-300">
                    <div>
                        <label for="bitcoin_address" class="block text-xs font-medium text-gray-500 mb-1">Bitcoin Address</label>
                        <input type="text" id="bitcoin_address" wire:model="bitcoin_address" placeholder="e.g. 1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        @error('bitcoin_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="pgp_public_key" class="block text-xs font-medium text-gray-500 mb-1">PGP Public Key</label>
                        <textarea id="pgp_public_key" wire:model="pgp_public_key" rows="6" placeholder="-----BEGIN PGP PUBLIC KEY BLOCK-----..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm font-mono text-xs"></textarea>
                        @error('pgp_public_key') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif

                {{-- #8 Supported Coin (Tag Input) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supported Coins <span class="text-xs text-gray-400">(Comma separated or press Enter)</span></label>
                    <div x-data="{
                        newTag: '',
                        tags: @entangle('supported_coin'),
                        get tagsArray() {
                            return this.tags ? this.tags.split(',').filter(t => t.trim() !== '') : [];
                        },
                        addTag() {
                            if (this.newTag.trim() !== '') {
                                let current = this.tagsArray;
                                if (!current.includes(this.newTag.trim())) {
                                    current.push(this.newTag.trim());
                                    this.tags = current.join(',');
                                }
                                this.newTag = '';
                            }
                        },
                        removeTag(index) {
                            let current = this.tagsArray;
                            current.splice(index, 1);
                            this.tags = current.join(',');
                        }
                    }" class="w-full">
                        <div class="flex flex-wrap gap-2 mb-2" x-show="tagsArray.length > 0">
                            <template x-for="(tag, index) in tagsArray" :key="index">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <span x-text="tag"></span>
                                    <button type="button" @click="removeTag(index)" class="flex-shrink-0 ml-1.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-blue-400 hover:bg-blue-200 hover:text-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white">
                                        <span class="sr-only">Remove tag</span>
                                        <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                            <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                                        </svg>
                                    </button>
                                </span>
                            </template>
                        </div>
                        <input type="text" x-model="newTag" @keydown.enter.prevent="addTag()" @keydown.comma.prevent="addTag()" @blur="addTag()" placeholder="Add a coin and press Enter or Comma..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                    </div>
                </div>

                {{-- #11 Have Tor (Checkbox) --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="have_tor_cb" wire:model.live="have_tor" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5 cursor-pointer">
                    <label for="have_tor_cb" class="text-sm font-medium text-gray-700 cursor-pointer">Have Tor</label>
                </div>

                {{-- #11b Tor URLs (Conditional Textarea) --}}
                @if($have_tor)
                <div class="pl-7 pb-2 transition-all duration-300">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tor URLs (one per line)</label>
                    <textarea wire:model="tor_urls" rows="3" placeholder="http://example.onion..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm bg-blue-50/30"></textarea>
                    @error('tor_urls') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                @endif

                {{-- #12 No Registration Policy (Checkbox) --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="no_reg_policy_cb" wire:model.live="no_reg_policy" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5 cursor-pointer">
                    <label for="no_reg_policy_cb" class="text-sm font-medium text-gray-700 cursor-pointer">No Registration Policy</label>
                </div>

                {{-- #13 No Log Policy (Checkbox) --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="no_log_policy_field_cb" wire:model.live="no_log_policy_field" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5 cursor-pointer">
                    <label for="no_log_policy_field_cb" class="text-sm font-medium text-gray-700 cursor-pointer">No Log Policy</label>
                </div>

                {{-- #14 Own Liquidity/Bankroll (Checkbox) --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="own_liquidity_field_cb" wire:model.live="own_liquidity_field" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5 cursor-pointer">
                    <label for="own_liquidity_field_cb" class="text-sm font-medium text-gray-700 cursor-pointer">Own Liquidity / Bankroll</label>
                </div>

                {{-- Conditional Liquidity Detail Fields --}}
                @if($own_liquidity_field)
                <div class="pl-7 grid grid-cols-1 md:grid-cols-2 gap-4 pb-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Amount in USD {{ isset($settings['min_bankroll_amount']) && $settings['min_bankroll_amount'] > 0 ? '(Minimum '.number_format($settings['min_bankroll_amount']).')' : '' }}</label>
                        <input type="number" step="0.01" wire:model="liquidity_amount" placeholder="0.00" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        @error('liquidity_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Proved URL</label>
                        <input type="url" wire:model="liquidity_proof_url" placeholder="https://..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        @error('liquidity_proof_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif


                {{-- #16 Code Audited (Checkbox) --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="code_audited_cb" wire:model.live="code_audited" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5 cursor-pointer">
                    <label for="code_audited_cb" class="text-sm font-medium text-gray-700 cursor-pointer">Code Audited by Third Party</label>
                </div>

                @if($code_audited)
                <div class="pl-7 pb-2 transition-all duration-300">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Audit URL</label>
                    <input type="url" wire:model="audit_url" placeholder="https://..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm bg-blue-50/10">
                    @error('audit_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                @endif




            </div>
            @endif

            {{-- Ownership Verification --}}
            <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                <div class="flex items-start gap-3">
                    <div class="flex items-center h-5">
                        <input id="verify_ownership" wire:model.live="verify_ownership" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </div>
                    <div class="text-sm">
                        <label for="verify_ownership" class="font-bold text-gray-700 cursor-pointer">Verify Ownership</label>
                        <p class="text-gray-500 mt-1">Check this box if you are the owner and want to verify ownership of this project.</p>
                    </div>
                </div>

                @if($verify_ownership && $verification_code)
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200 shadow-sm animate-fade-in">
                        <p class="text-sm text-blue-800 font-medium">To complete verification please send in instant message using XMPP messaging (<a href="https://welivecrypto.com/forum/index.php?topic=5.0" target="_blank" class="underline">Learn to Set up XMPP contact</a>)</p>
                        <p class="mt-2 text-lg font-bold text-blue-900">{{ $settings['admin_xmpp_contact'] ?? $settings['admin_telegram_contact'] ?? 'our admin' }}</p>
                        <p class="mt-2 text-sm text-blue-800">Include the following verification code in your message:</p>
                        <div class="mt-3 inline-flex items-center justify-center px-4 py-3 bg-white rounded border-2 border-dashed border-blue-300 text-xl font-mono tracking-widest text-gray-900 shadow-sm select-all">
                            {{ $verification_code }}
                        </div>
                    </div>
                @endif
            </div>

            @if(auth()->check() && auth()->user()->isAdmin())
                <div class="bg-red-50 p-6 rounded-lg border-2 border-red-200 space-y-6">
                    <h3 class="font-bold text-red-800 text-lg border-b border-red-200 pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        Admin Controls
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="list_status" class="block text-sm font-bold text-red-700 mb-1">List Status</label>
                            <select wire:model.live="list_status" id="list_status" class="mt-1 block w-full rounded-lg border border-red-300 bg-white p-2.5 text-sm text-gray-900 focus:border-red-500 focus:ring-red-500 shadow-sm">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="verified">Verified</option>
                                <option value="scam">Scam</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            @error('list_status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                            {{-- Scam Custom Message --}}
                            @if($list_status === 'scam')
                                <div class="mt-4 p-3 bg-white border border-red-200 rounded-lg shadow-sm">
                                    <label for="scam_reason" class="block text-xs font-bold text-red-700 uppercase mb-1">Scam Custom Message (HTML Support)</label>
                                    <textarea wire:model="scam_reason" id="scam_reason" rows="4" class="w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" placeholder="Enter custom scam warning..."></textarea>
                                    @error('scam_reason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    <p class="mt-1 text-[10px] text-red-500 italic">Overrides global scam warning.</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-bold text-red-700 mb-1">⚠️ Potential Risk <span class="text-xs font-normal text-red-400">(Admin Override)</span></label>
                            <select wire:model.live="potential_risk" class="mt-1 block w-full rounded-lg border border-red-300 bg-white p-2.5 text-sm text-gray-900 focus:border-red-500 focus:ring-red-500 shadow-sm">
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                            @error('potential_risk') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                            {{-- Potential Risk Message --}}
                            @if($potential_risk === 'Yes')
                                <div class="mt-4 p-3 bg-white border border-red-200 rounded-lg shadow-sm">
                                    <label for="potential_risk_message" class="block text-xs font-bold text-red-800 uppercase mb-1">Potential Risk Custom Message (HTML Support)</label>
                                    <textarea wire:model="potential_risk_message" id="potential_risk_message" rows="4" class="w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" placeholder="Enter custom potential risk details..."></textarea>
                                    @error('potential_risk_message') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    <p class="mt-1 text-[10px] text-red-600 italic">This message will override the global risk warning.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Ownership Verified Section --}}
                    <div class="flex items-center gap-2 p-3 bg-white border border-red-200 rounded-lg shadow-sm">
                        <input type="checkbox" id="ownership_verified_cb" wire:model.live="ownership_verified_field" class="rounded border-gray-300 text-red-600 focus:ring-red-500 w-5 h-5 cursor-pointer">
                        <label for="ownership_verified_cb" class="text-sm font-bold text-red-700 cursor-pointer">Ownership Verified <span class="text-xs font-normal text-red-400">(Admin Override)</span></label>
                    </div>

                    {{-- Mixer-specific Admin Fields --}}
                    @if($isMixerCategory)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-red-100/50 rounded-lg border border-red-200">
                            <div class="md:col-span-2">
                                <h4 class="text-xs font-bold text-red-800 uppercase tracking-widest mb-2">Mixer Scoring Overrides</h4>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-red-700 mb-2">FEE Related Category</label>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach(['High fees', 'Medium fees', 'Low fees', 'Ultra low fees'] as $opt)
                                    <label class="flex items-center gap-2 p-2 bg-white rounded border border-red-100 hover:border-red-300 cursor-pointer transition">
                                        <input type="radio" wire:model="fee" value="{{ $opt }}" class="text-red-600 focus:ring-red-500">
                                        <span class="text-xs text-gray-700">{{ $opt }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-red-700 mb-1">Customer Support Rating (0-5)</label>
                                <p class="text-[10px] text-red-500 mb-2">Affects the "Customer Support" scoring category.</p>
                                <input type="number" wire:model="customer_support_rating" min="0" max="5" step="1" class="w-full rounded-lg border border-red-300 bg-white p-2.5 text-sm text-gray-900 focus:border-red-500 focus:ring-red-500 shadow-sm">
                            </div>
                        </div>
                    @else
                        {{-- Hidden inputs to satisfy model properties if they are used --}}
                        <input type="hidden" wire:model="list_status">
                        <input type="hidden" wire:model="potential_risk">
                    @endif
                </div>
            @endif

            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200">
                    Submit Project
                </button>
            </div>
        </form>
    </div>
</div>