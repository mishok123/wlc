<div>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Project Details</h3>
                <p class="mt-1 text-sm text-gray-600">Update the project information and status.</p>
                <div class="mt-4">
                    <a href="{{ route('admin.projects') }}" class="text-sm text-indigo-600 hover:text-indigo-900">&larr; Back to Projects</a>
                </div>

                {{-- Score Summary Card --}}
                <div class="mt-6 bg-white rounded-lg shadow p-4 border">
                    <h4 class="font-bold text-gray-800 mb-3">Score Summary</h4>
                    
                    @if(session()->has('scores_recalculated'))
                        <div class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded mb-2">
                            {{ session('scores_recalculated') }}
                        </div>
                    @endif

                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500">Reputation Score</span>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(max(($reputation_score / ($categoryMaxRep ?: 1)) * 100, 0), 100) }}%"></div>
                                </div>
                                <span class="text-sm font-bold {{ $reputation_score >= 0 ? 'text-blue-700' : 'text-red-700' }}">{{ number_format($reputation_score ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Privacy Score</span>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(max(($privacy_score / ($categoryMaxPriv ?: 1)) * 100, 0), 100) }}%"></div>
                                </div>
                                <span class="text-sm font-bold {{ $privacy_score >= 0 ? 'text-green-700' : 'text-red-700' }}">{{ number_format($privacy_score ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <button wire:click="recalculateScores" type="button" class="mt-3 w-full text-center text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded-md transition">
                        🔄 Recalculate Scores
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form wire:submit="save">
                <div class="shadow-lg sm:rounded-xl sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        
                        @if(session()->has('message'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                                {{ session('message') }}
                            </div>
                        @endif

                        {{-- Admin Controls Section --}}
                        @if(auth()->user() && auth()->user()->isAdmin())
                        <div class="bg-red-50 p-6 rounded-xl border-2 border-red-200 space-y-6 mb-8">
                            <h3 class="font-bold text-red-800 text-lg border-b border-red-200 pb-2 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                Admin Controls
                            </h3>

                            <div class="grid grid-cols-6 gap-6">
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
                                    <label for="trust_score" class="block text-sm font-bold text-red-700 mb-1">Current WLC Score</label>
                                    <input type="number" wire:model="trust_score" id="trust_score" readonly class="mt-1 block w-full rounded-lg border border-red-200 bg-red-100/50 p-2.5 text-sm text-red-900 shadow-sm cursor-not-allowed font-bold" title="Calculated from scoring logic">
                                    @error('trust_score') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-6 gap-6">
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
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-bold text-red-700 mb-1">Ownership Verified</label>
                                    <div class="mt-2 flex items-center gap-2 p-2.5 bg-white border border-red-100 rounded-lg">
                                        <input type="checkbox" id="ownership_verified_cb" wire:model.live="ownership_verified_field" class="rounded border-gray-300 text-red-600 focus:ring-red-500 w-5 h-5 cursor-pointer">
                                        <label for="ownership_verified_cb" class="text-sm font-medium text-gray-700 cursor-pointer">Verified by Admin</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Mixer-specific Admin Overrides --}}
                            @if($isMixerCategory)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-red-100/50 rounded-lg border border-red-200">
                                    <div class="md:col-span-2">
                                        <h4 class="text-xs font-bold text-red-800 uppercase tracking-widest mb-1">Mixer Scoring Overrides</h4>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-bold text-red-700 mb-2">FEE Category Override</label>
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
                                        <p class="text-[10px] text-red-500 mb-2">Manually set the star rating for support category.</p>
                                        <input type="number" wire:model="customer_support_rating" min="0" max="5" step="1" class="w-full rounded-lg border border-red-300 bg-white p-2.5 text-sm text-gray-900 focus:border-red-500 focus:ring-red-500 shadow-sm">
                                    </div>
                                </div>
                            @endif

                        </div>
                        @else
                            {{-- Non-admins see read-only status and hidden inputs to satisfy validation --}}
                            <div class="grid grid-cols-6 gap-6 mb-6 pb-6 border-b">
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">List Status</label>
                                    <div class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5 text-sm text-gray-700 shadow-sm">
                                        {{ ucfirst($list_status) }}
                                    </div>
                                    <input type="hidden" wire:model="list_status">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">WLC Score</label>
                                    <div class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5 text-sm text-gray-700 shadow-sm font-bold">
                                        {{ number_format($trust_score, 2) }}
                                    </div>
                                    <input type="hidden" wire:model="trust_score">
                                    <input type="hidden" wire:model="potential_risk">
                                </div>
                            </div>
                        @endif

                        <hr class="border-gray-200">

                        <!-- Basic Info -->
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                                <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <label for="website_url" class="block text-sm font-medium text-gray-700">Website URL <span class="text-xs text-gray-400">(One URL per line)</span></label>
                                <textarea wire:model="website_url" id="website_url" rows="3" placeholder="https://..." class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"></textarea>
                                @error('website_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <label for="terms_url" class="block text-sm font-medium text-gray-700">Terms of Conditions URL</label>
                                <input type="url" wire:model="terms_url" id="terms_url" placeholder="https://example.com/terms" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                @error('terms_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-6">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" wire:model="description" rows="4" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"></textarea>
                                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                                    <input type="email" wire:model="contact_email" id="contact_email" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="email@example.com">
                                    @error('contact_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="contact_telegram" class="block text-sm font-medium text-gray-700">Telegram</label>
                                    <input type="text" wire:model="contact_telegram" id="contact_telegram" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="@username">
                                    @error('contact_telegram') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="contact_discord" class="block text-sm font-medium text-gray-700">Discord</label>
                                    <input type="text" wire:model="contact_discord" id="contact_discord" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="username#0000">
                                    @error('contact_discord') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="contact_simplex" class="block text-sm font-medium text-gray-700">SimpleX</label>
                                    <textarea wire:model="contact_simplex" id="contact_simplex" rows="3" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="SimpleX ID"></textarea>
                                    @error('contact_simplex') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <label class="block text-sm font-medium text-gray-700">Logo</label>
                                <div class="mt-1 flex items-center">
                                    @if($existingLogo)
                                        <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100 mr-4">
                                            <img src="{{ asset('storage/' . $existingLogo) }}" class="h-full w-full object-cover">
                                        </span>
                                    @endif
                                    <input type="file" wire:model="logo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 300KB. 1:1 ratio, max 512x512.</p>
                                @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                <select id="category_id" wire:model.live="category_id" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr class="border-gray-200 my-6">

                        <!-- SEO Info -->
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 border-b pb-2 flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">SEO Settings</h3>
                                    <p class="text-sm text-gray-500">Auto-generated on submission, but can be manually overridden here.</p>
                                </div>
                                <button type="button" wire:click="regenerateSeo" class="text-xs bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded transition">
                                    🔄 Regenerate Auto SEO
                                </button>
                            </div>
                            
                            @if(session()->has('seo_message'))
                                <div class="col-span-6 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-sm w-full">
                                    {{ session('seo_message') }}
                                </div>
                            @endif
                            
                            <div class="col-span-6">
                                <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                                <input type="text" wire:model="meta_title" id="meta_title" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                @error('meta_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-span-6">
                                <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                                <textarea wire:model="meta_description" id="meta_description" rows="3" class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"></textarea>
                                @error('meta_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- ==================== MIXER SPECIFIC FIELDS ==================== --}}
                        @if($isMixerCategory)
                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 space-y-5 mt-6">
                            <h3 class="font-bold text-gray-700 text-lg border-b pb-2">Mixer Scoring Fields</h3>

                            {{-- #1 Age (datetime) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Age Related Data <span class="text-xs text-gray-400">(Launch Date)</span></label>
                                <input type="date" wire:model.live="launch_date" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            {{-- #2 Community (Textarea) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Community Related Data <span class="text-xs text-gray-400">(One URL per line)</span></label>
                                <textarea wire:model="community" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="https://bitcointalk.org/..."></textarea>
                            </div>

                            {{-- #3 Guarantee Fund (Radio) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Guarantee Fund Related Data</label>
                                <div class="space-y-2 pl-1">
                                    @foreach(['No escrow', 'Escrow under $10k', 'Escrow between $10k to $20k', 'Escrow above $20 to $50k', 'Escrow above $50k'] as $opt)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" wire:model.live="guarantee_fund" value="{{ $opt }}" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">{{ $opt }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            @if($guarantee_fund && $guarantee_fund !== 'No escrow')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Guarantee Url</label>
                                <input type="url" wire:model="guarantee_url" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="https://example.com/guarantee-proof">
                                @error('guarantee_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            @endif


                            {{-- #4 Privacy KYC (Radio) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Privacy Related Data</label>
                                <div class="space-y-2 pl-1">
                                    @foreach(['Privacy KYC 0 (Guaranteed No KYC)', 'Privacy KYC 1', 'Privacy KYC 2', 'Privacy KYC 3'] as $opt)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" wire:model="privacy_kyc" value="{{ $opt }}" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">{{ $opt }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- New Fees Detail Section --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-100 rounded-lg border border-gray-200">
                                <div class="md:col-span-3 pb-1 border-b border-gray-300">
                                    <label class="block text-sm font-bold text-gray-700">Fees</label>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Min (%)</label>
                                    <input type="number" step="0.01" wire:model="fee_min" placeholder="0.00" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                                    @error('fee_min') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Max (%)</label>
                                    <input type="number" step="0.01" wire:model="fee_max" placeholder="0.00" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                                    @error('fee_max') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Fixed</label>
                                    <input type="number" step="0.00000001" wire:model="fee_fixed" placeholder="0.00000000" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                                    @error('fee_fixed') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>



                            {{-- #7 LOG Verifiable (Checkbox) --}}
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="log_verifiable_cb" wire:model.live="log_verifiable" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5 cursor-pointer">
                                <label for="log_verifiable_cb" class="text-sm font-medium text-gray-700 cursor-pointer">LOG Verifiable</label>
                            </div>

                            @if($log_verifiable)
                            <div class="pl-7 space-y-4 transition-all duration-300">
                                <div>
                                    <label for="bitcoin_address" class="block text-xs font-medium text-gray-500 mb-1">Bitcoin Address</label>
                                    <input type="text" id="bitcoin_address" wire:model="bitcoin_address" placeholder="e.g. 1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm bg-gray-50">
                                    @error('bitcoin_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="pgp_public_key" class="block text-xs font-medium text-gray-500 mb-1">PGP Public Key</label>
                                    <textarea id="pgp_public_key" wire:model="pgp_public_key" rows="6" placeholder="-----BEGIN PGP PUBLIC KEY BLOCK-----..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm font-mono text-xs bg-gray-50"></textarea>
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                                <span x-text="tag"></span>
                                                <button type="button" @click="removeTag(index)" class="flex-shrink-0 ml-1.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-indigo-400 hover:bg-indigo-200 hover:text-indigo-500 focus:outline-none focus:bg-indigo-500 focus:text-white">
                                                    <span class="sr-only">Remove tag</span>
                                                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>
                                    </div>
                                    <input type="text" x-model="newTag" @keydown.enter.prevent="addTag()" @keydown.comma.prevent="addTag()" @blur="addTag()" placeholder="Add a coin and press Enter or Comma..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                                </div>
                            </div>

                            {{-- #11 Have Tor (Checkbox) --}}
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="have_tor_cb" wire:model.live="have_tor" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5 cursor-pointer">
                                <label for="have_tor_cb" class="text-sm font-medium text-gray-700 cursor-pointer">Have Tor</label>
                            </div>

                            {{-- #11b Tor URLs (Conditional Textarea) --}}
                            @if($have_tor)
                            <div class="pl-7 pb-2 transition-all duration-300">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Tor URLs (one per line)</label>
                                <textarea wire:model="tor_urls" rows="3" placeholder="http://example.onion..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm bg-indigo-50/30"></textarea>
                                @error('tor_urls') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            @endif

                            {{-- #12 No Registration Policy (Checkbox) --}}
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="no_reg_policy_cb" wire:model.live="no_reg_policy" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5 cursor-pointer">
                                <label for="no_reg_policy_cb" class="text-sm font-medium text-gray-700 cursor-pointer">No Registration Policy</label>
                            </div>

                            {{-- #13 No Log Policy (Checkbox) --}}
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="no_log_policy_field_cb" wire:model.live="no_log_policy_field" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5 cursor-pointer">
                                <label for="no_log_policy_field_cb" class="text-sm font-medium text-gray-700 cursor-pointer">No Log Policy</label>
                            </div>

                            {{-- #14 Own Liquidity/Bankroll (Checkbox) --}}
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="own_liquidity_field_cb" wire:model.live="own_liquidity_field" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5 cursor-pointer">
                                <label for="own_liquidity_field_cb" class="text-sm font-medium text-gray-700 cursor-pointer">Own Liquidity / Bankroll</label>
                            </div>

                            {{-- Conditional Liquidity Detail Fields --}}
                            @if($own_liquidity_field)
                            <div class="pl-7 grid grid-cols-1 md:grid-cols-2 gap-4 pb-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Amount in USD {{ isset($settings['min_bankroll_amount']) && $settings['min_bankroll_amount'] > 0 ? '(Minimum '.number_format($settings['min_bankroll_amount']).')' : '' }}</label>
                                    <input type="number" step="0.01" wire:model="liquidity_amount" placeholder="0.00" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                                    @error('liquidity_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Proved URL</label>
                                    <input type="url" wire:model="liquidity_proof_url" placeholder="https://..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                                    @error('liquidity_proof_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @endif

                            {{-- #16 Code Audited (Checkbox) --}}
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="code_audited_cb" wire:model.live="code_audited" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5 cursor-pointer">
                                <label for="code_audited_cb" class="text-sm font-medium text-gray-700 cursor-pointer">Code Audited by Third Party</label>
                            </div>

                            @if($code_audited)
                            <div class="pl-7 pb-2 transition-all duration-300">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Audit URL</label>
                                <input type="url" wire:model="audit_url" placeholder="https://..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm bg-indigo-50/10">
                                @error('audit_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            @endif


                        </div>
                        @endif

                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
