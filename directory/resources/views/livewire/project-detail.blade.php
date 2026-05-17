<div class="container mx-auto px-4 py-8">
    {{-- Warning Messages Section --}}
    @if($project->list_status === 'scam')
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-red-800 uppercase tracking-wider">SCAM WARNING</h3>
                <div class="mt-1 text-sm text-red-700 whitespace-pre-line">
                    @if($project->scam_reason)
                        {!! $project->scam_reason !!}
                    @elseif(!empty($settings['scam_warning_message']))
                        {{ $settings['scam_warning_message'] }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @php 
        $riskVal = $project->getDynamicField('potential_risk');
        $hasRisk = !empty($riskVal) && strtolower(trim($riskVal)) !== 'no';
    @endphp
    @if($hasRisk)
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded-lg shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-yellow-800 uppercase tracking-wider">POTENTIAL RISK WARNING</h3>
                <div class="mt-1 text-sm text-yellow-700 whitespace-pre-line">
                    @if($project->potential_risk_message)
                        {!! $project->potential_risk_message !!}
                    @elseif(!empty($settings['risk_warning_message']))
                        {{ $settings['risk_warning_message'] }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Status Messages Section --}}
    @php
        $statusMessage = '';
        if($project->list_status === 'pending') $statusMessage = \App\Models\Setting::getValue('pending_status_message');
        elseif($project->list_status === 'approved') $statusMessage = \App\Models\Setting::getValue('approved_status_message');
        elseif($project->list_status === 'rejected') $statusMessage = \App\Models\Setting::getValue('rejected_status_message');
        elseif($project->list_status === 'verified') $statusMessage = \App\Models\Setting::getValue('verified_status_message');
    @endphp

    @if($statusMessage)
        <div class="p-4 mb-6 rounded-lg shadow-sm border-l-4 
            @if($project->list_status === 'pending') bg-yellow-50 border-yellow-500 text-yellow-800
            @elseif($project->list_status === 'approved') bg-green-50 border-green-500 text-green-800
            @elseif($project->list_status === 'rejected') bg-gray-50 border-gray-400 text-gray-800
            @elseif($project->list_status === 'verified') bg-blue-50 border-blue-500 text-blue-800
            @else bg-gray-50 border-gray-300 text-gray-800 @endif">
            
            <div class="flex">
                <div class="flex-shrink-0 text-xl">
                    @if($project->list_status === 'pending') ⏳
                    @elseif($project->list_status === 'approved') <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    @elseif($project->list_status === 'rejected') <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    @elseif($project->list_status === 'verified') <svg class="h-6 w-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    @endif
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold uppercase tracking-wider
                        @if($project->list_status === 'pending') text-yellow-800
                        @elseif($project->list_status === 'approved') text-green-800
                        @elseif($project->list_status === 'rejected') text-gray-800
                        @elseif($project->list_status === 'verified') text-blue-800
                        @endif">
                        {{ $project->list_status }}
                    </h3>
                    <div class="mt-1 text-sm whitespace-pre-line">
                        {!! nl2br(e($statusMessage)) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Header Section --}}
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                        {{ $project->category->name }}
                    </span>
                    @if($project->ownership_verified)
                        <div class="flex items-center gap-1 text-green-700 bg-green-50 px-2.5 py-0.5 rounded-full border border-green-200 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-[10px] font-bold uppercase tracking-wide">Ownership Verified</span>
                        </div>
                    @endif
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $project->name }}</h1>
                <div class="flex flex-wrap gap-x-4 gap-y-2 mb-2">
                @foreach($project->website_urls as $url)
                    @php 
                        $host = parse_url($url, PHP_URL_HOST) ?: $url;
                        $host = preg_replace('/^www\./', '', $host);
                    @endphp
                    @php $urlStatus = $project->getUrlStatus($url); @endphp
                    <a href="{{ $url }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-sm font-medium break-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9-9c1.657 0 3 4.03 3 9s-1.343 9-3 9m0-18c-1.657 0-3 4.03-3 9s1.343 9 3 9m-9-9a9 9 0 019-9"></path></svg>
                        <span>{{ $host }}</span>
                        @if($urlStatus === 'online')
                            <span class="w-2 h-2 rounded-full bg-green-500 shadow-sm" title="Online"></span>
                        @else
                            <span class="w-2 h-2 rounded-full bg-red-500 shadow-sm" title="Offline"></span>
                        @endif
                    </a>
                @endforeach
                </div>

                @php $torUrls = $project->getDynamicField('tor_urls'); @endphp
                @if($project->getDynamicField('have_tor') === 'Yes' && $torUrls)
                    <div class="flex flex-wrap gap-x-4 gap-y-2">
                    @foreach(explode("\n", str_replace(["\r\n", "\r"], "\n", $torUrls)) as $url)
                        @php 
                            $url = trim($url);
                            $displayText = preg_replace('/^https?:\/\//', '', $url);
                        @endphp
                        @if($url)
                            <a href="{{ $url }}" target="_blank" rel="nofollow" class="text-purple-600 hover:underline flex items-center gap-1 text-sm font-medium break-all">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"/><path d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18Z"/><path d="M12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12C10 13.1046 10.8954 14 12 14Z"/></svg>
                                <span>{{ parse_url($url, PHP_URL_HOST) ?: $url }}</span>
                            </a>
                        @endif
                    @endforeach
                    </div>
                @endif
            </div>
            <div class="mt-6 md:mt-0 flex flex-col items-center md:items-end w-full md:w-auto pt-6 md:pt-0 border-t md:border-t-0 border-gray-100">
                <div class="flex items-center justify-center md:justify-end gap-2 mb-1">
                    <div class="flex items-center gap-1">
                        <span class="text-lg font-bold text-gray-900">+</span>
                        <span class="text-lg font-bold text-green-600">{{ $project->positive_count }}</span>
                    </div>
                    <span class="text-gray-400 mx-1">/</span>
                    <div class="flex items-center gap-1">
                        <span class="text-lg font-bold text-gray-900">=</span>
                        <span class="text-lg font-bold text-gray-700">{{ $project->neutral_count }}</span>
                    </div>
                    <span class="text-gray-400 mx-1">/</span>
                    <div class="flex items-center gap-1">
                        <span class="text-lg font-bold text-gray-900">-</span>
                        <span class="text-lg font-bold text-yellow-600">{{ $project->negative_count }}</span>
                    </div>
                </div>
                <p class="text-gray-500 text-xs font-bold uppercase tracking-wider text-center md:text-right">{{ $project->review_count }} Feedback</p>

                @php
                    $maxRep = $categoryMaxRep;
                    $maxPriv = $categoryMaxPriv;
                    
                    $rawRep = $project->reputation_score ?? 0;
                    $rawPriv = $project->privacy_score ?? 0;
                    
                    $scaledRep = ($rawRep / ($maxRep ?: 1)) * 10;
                    $scaledPriv = ($rawPriv / ($maxPriv ?: 1)) * 10;
                    $scaledWLC = (($rawRep + $rawPriv) / (($maxRep + $maxPriv) ?: 1)) * 10;
                @endphp

                <div class="mt-3 text-center md:text-right">
                     <span class="font-semibold text-gray-700">WLC Score:</span> 
                     <span class="font-bold {{ $scaledWLC >= 8 ? 'text-green-600' : ($scaledWLC >= 5 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($scaledWLC, 2) }}/10
                     </span>
                </div>
                @if($project->reputation_score || $project->privacy_score)
                <div class="mt-2 space-y-1.5 w-full max-w-[200px] md:max-w-none">
                    <div class="flex items-center justify-between md:justify-end gap-2">
                        <span class="text-sm font-semibold text-gray-500 w-20 text-left md:text-right">Reputation:</span>
                        <div class="flex-1 md:flex-none md:w-20 bg-gray-200 rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(max($scaledRep * 10, 0), 100) }}%"></div>
                        </div>
                        <span class="text-sm font-bold w-10 text-right {{ $scaledRep >= 5 ? 'text-blue-600' : 'text-red-600' }}">{{ number_format($scaledRep, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between md:justify-end gap-2">
                        <span class="text-sm font-semibold text-gray-500 w-20 text-left md:text-right">Privacy:</span>
                        <div class="flex-1 md:flex-none md:w-20 bg-gray-200 rounded-full h-1.5">
                            <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ $scaledPriv * 10 }}%"></div>
                        </div>
                        <span class="text-sm font-bold w-10 text-right {{ $scaledPriv >= 5 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($scaledPriv, 2) }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Description --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">About {{ $project->name }}</h2>
                <div class="prose max-w-none text-gray-700">
                    {{ $project->description }}
                </div>
            </div>

            {{-- Features & Attributes --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">Features & Information</h2>
                
                {{-- Dynamic Insights Section --}}
                @php $insights = $project->getConditionalInsights(); @endphp
                @if(!empty($insights))
                    <div class="mb-8 space-y-3">
                        @foreach($insights as $insight)
                            <div class="flex items-start gap-3 p-3 rounded-lg border 
                                @if($insight['type'] === 'success') bg-green-50 border-green-200 text-green-800
                                @elseif($insight['type'] === 'warning') bg-yellow-50 border-yellow-200 text-yellow-800
                                @elseif($insight['type'] === 'danger') bg-red-50 border-red-200 text-red-800
                                @else bg-blue-50 border-blue-200 text-blue-800 @endif shadow-sm">
                                
                                <div class="flex-shrink-0 mt-0.5">
                                    @if($insight['type'] === 'success')
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    @elseif($insight['type'] === 'warning')
                                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    @elseif($insight['type'] === 'danger')
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                    @else
                                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                    @endif
                                </div>
                                <div class="text-sm font-medium leading-relaxed">
                                    {{ $insight['message'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr class="border-gray-100 mb-6">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                    @php
                        $excludeKeys = ['customer_support_rating', 'community', 'fees', 'fee', 'delay', 'min_service_fee', 'fixed_fee', 'time_delay', 'supported_coin', 'cryptocurrency', 'supported_cryptos', 'fee_min', 'fee_max', 'fee_fixed', 'tor_urls', 'liquidity_amount', 'liquidity_proof_url', 'audit_url', 'own_liquidity_field', 'launch_date', 'age', 'ownership_verified'];
                         $mixerSecurityKeys = ['privacy_kyc', 'have_tor', 'no_reg_policy', 'no_log_policy_field', 'log_verifiable', 'code_audited', 'phone_required', 'legally_registered', 'potential_risk', 'guarantee_fund'];
                    @endphp
                    
                    @foreach($project->fieldValues as $fv)
                        @if(!$fv->field || in_array($fv->field->key, array_merge($excludeKeys, $mixerSecurityKeys))) @continue @endif
                        <div>
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">{{ $fv->field->name }}</span>
                            <span class="text-gray-900 font-medium">{{ $fv->value }}</span>
                        </div>
                    @endforeach


                    @if($project->industry)
                        <div>
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">Industry</span>
                            <span class="text-gray-900 font-medium">{{ $project->industry }}</span>
                        </div>
                    @endif

                    @if($project->jurisdiction)
                        <div>
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">Jurisdiction</span>
                            <span class="text-gray-900 font-medium">{{ $project->jurisdiction }}</span>
                        </div>
                    @endif

                    @if($project->regulatory_status)
                        <div>
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">Regulatory Status</span>
                            <span class="text-gray-900 font-medium">{{ $project->regulatory_status }}</span>
                        </div>
                    @endif

                    @if(!empty($project->supported_networks))
                        <div>
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">Network Support</span>
                            <div class="flex flex-wrap gap-1.5 mt-1">
                                @foreach($project->supported_networks as $net => $enabled)
                                    @if($enabled)
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-[10px] font-bold rounded uppercase border border-gray-200">{{ strtoupper($net) }}</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Mixer Security & Privacy --}}
            @if($project->category->slug === 'mixers')
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Security & Privacy Details
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    @php
                        $getMixerVal = function($key) use ($project) {
                            return $project->getDynamicField($key);
                        };
                    @endphp

                    @if($val = $getMixerVal('privacy_kyc'))
                        <div>
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">Privacy / KYC</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ str_contains(strtolower($val), 'no kyc') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ str_ireplace(['Privacy ', '(Guaranteed No KYC)'], '', $val) }}
                            </span>
                        </div>
                    @endif

                    @if($project->letter_of_guarantee)
                        <div>
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">Letter of Guarantee</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Provided
                            </span>
                        </div>
                    @endif

                    @if($val = $getMixerVal('log_verifiable'))
                        <div>
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">LOG Verifiable</span>
                            <div class="flex flex-col gap-2 mt-1">
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ strtolower($val) === 'yes' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $val === 'Yes' ? 'LOG Provided / Verifiable' : 'No LOG Provided' }}
                                    </span>
                                </div>
                                
                                @if(strtolower($val) === 'yes')
                                    @if($project->bitcoin_address)
                                        <div class="text-xs text-gray-700 bg-gray-50 p-2.5 rounded-lg border border-gray-200 mt-1 max-w-md">
                                            <span class="block font-bold text-gray-500 text-[10px] uppercase tracking-wider mb-0.5">Bitcoin Address</span>
                                            <span class="font-mono break-all select-all text-gray-800 font-bold text-[13px]">{{ $project->bitcoin_address }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($project->pgp_public_key)
                                        <div class="mt-1">
                                            <a href="data:text/plain;charset=utf-8,{{ rawurlencode($project->pgp_public_key) }}" download="{{ \Illuminate\Support\Str::slug($project->name) }}-pgp-key.txt" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-500 text-white font-bold rounded-lg text-xs uppercase transition shadow-md">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                Download PGP Key
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($getMixerVal('have_tor') === 'Yes' && $torUrls = $getMixerVal('tor_urls'))
                        <div class="md:col-span-2">
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">Tor Onion URLs</span>
                            <div class="text-sm text-gray-900 font-medium whitespace-pre-line bg-gray-50 p-3 rounded border border-gray-100 mt-1">
                                @foreach(explode("\n", $torUrls) as $url)
                                    @php $url = trim($url); @endphp
                                    @if($url)
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                            <a href="{{ $url }}" target="_blank" rel="nofollow" class="text-blue-600 hover:underline break-all">
                                                {{ parse_url($url, PHP_URL_HOST) ?: $url }}
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @foreach(['no_reg_policy' => 'No Registration Policy', 'no_log_policy_field' => 'No Log Policy'] as $key => $label)
                        @if($val = $getMixerVal($key))
                            <div>
                                <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">{{ $label }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ strtolower($val) === 'yes' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $val }}
                                </span>
                            </div>
                        @endif
                    @endforeach

                    @if($getMixerVal('code_audited') === 'Yes')
                        <div>
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">Code Audited</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">Yes</span>
                                @if($auditUrl = $getMixerVal('audit_url'))
                                    <a href="{{ $auditUrl }}" target="_blank" rel="nofollow" class="ml-2 text-xs text-blue-600 hover:underline inline-flex items-center gap-1">
                                        View Report
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($val = $getMixerVal('guarantee_fund'))
                        <div class="md:col-span-2">
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider">Guarantee Fund / Escrow</span>
                            <div class="mt-1">
                                <span class="text-gray-900 font-medium">{{ $val }}</span>
                                @if($val !== 'No escrow' && $project->guarantee_url)
                                    <a href="{{ $project->guarantee_url }}" target="_blank" rel="nofollow" class="ml-2 text-xs text-blue-600 hover:underline inline-flex items-center gap-1">
                                        View Guarantee
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Category Specifics --}}
            @if($project->category->slug === 'mixers' || $project->category->slug === 'exchanges')
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">Fees & Limits</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                        $feeMin = $getMixerVal('fee_min') ?? $project->min_service_fee;
                        $feeMax = $getMixerVal('fee_max');
                        $feeFixed = $getMixerVal('fee_fixed') ?? $project->fixed_fee;
                        $delay = $getMixerVal('delay') ?? $project->min_time_delay ?? $project->time_delay;
                    @endphp

                    @if($feeMin || $feeMax || $feeFixed)
                        <div class="p-4 bg-gray-50 rounded border border-gray-100">
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Service Fees</span>
                            <div class="space-y-1">
                                @if($feeMin || $feeMax)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Dynamic Fee:</span>
                                        <span class="font-bold text-gray-900">
                                            @if($feeMin && $feeMax)
                                                {{ $feeMin }}% - {{ $feeMax }}%
                                            @else
                                                {{ $feeMin ?? $feeMax }}%
                                            @endif
                                        </span>
                                    </div>
                                @endif
                                @if($feeFixed)
                                    <div class="flex justify-between items-center border-t border-gray-200 pt-1 mt-1">
                                        <span class="text-sm text-gray-600">Fixed Fee:</span>
                                        <span class="font-bold text-gray-900">{{ $feeFixed }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($feeEnum = $getMixerVal('fee'))
                        <div class="p-4 bg-gray-50 rounded border border-gray-100 flex flex-col justify-center">
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Fee Rating</span>
                            <span class="font-bold text-lg text-gray-900">{{ $feeEnum }}</span>
                        </div>
                    @endif

                    @if($delay)
                         <div class="p-4 bg-gray-50 rounded border border-gray-100 flex flex-col justify-center">
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Processing Delay</span>
                            <span class="font-bold text-lg text-gray-900">{{ $delay }}{{ is_numeric($delay) ? ' mins' : '' }}</span>
                        </div>
                    @endif

                    @if($getMixerVal('own_liquidity_field') === 'Yes')
                         <div class="p-4 bg-gray-50 rounded border border-gray-100 col-span-1 md:col-span-2">
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Own Liquidity</span>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                                @if($amount = $getMixerVal('liquidity_amount'))
                                    <span class="font-bold text-lg text-gray-900">{{ $amount }}</span>
                                @endif
                                @if($proof = $getMixerVal('liquidity_proof_url'))
                                    <a href="{{ $proof }}" target="_blank" rel="nofollow" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                                        Proof of Liquidity
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($project->withdrawal_fees)
                         <div class="p-4 bg-gray-50 rounded border border-gray-100 col-span-1 md:col-span-2">
                            <span class="block text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Withdrawal Fees</span>
                            <span class="text-gray-900 font-medium">{{ $project->withdrawal_fees }}</span>
                        </div>
                    @endif

                    @if($risk = $getMixerVal('potential_risk'))
                        <div class="p-4 bg-red-50 rounded border border-red-100 col-span-1 md:col-span-2">
                            <span class="block text-red-500 text-xs font-bold uppercase tracking-wider mb-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Potential Risk Disclosure
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ strtolower(trim($risk)) === 'no' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ strtolower(trim($risk)) === 'no' ? 'No' : 'Yes' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Reviews Section --}}
            <livewire:project-reviews :project="$project" />
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Request Action Container --}}
            <div class="flex flex-wrap items-center justify-end gap-3">
                @if($project->list_status === 'pending')
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                        {{ $project->requests()->where('request_type', 'approve')->count() }} Requests
                    </span>
                    <button wire:click="requestAction('approve')" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow shadow-indigo-200">
                        <svg wire:loading wire:target="requestAction" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Request for Approve
                    </button>
                @elseif($project->list_status === 'approved')
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                        {{ $project->requests()->where('request_type', 'verify')->count() }} Requests
                    </span>
                    <button wire:click="requestAction('verify')" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow shadow-indigo-200">
                        <svg wire:loading wire:target="requestAction" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Request for Verify
                    </button>
                @endif

                @if($project->list_status !== 'pending')
                    <button wire:click="openReportModal" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 transition ease-in-out duration-150 shadow shadow-red-200">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Report to Moderator
                    </button>
                @endif
            </div>


            @if(session()->has('request_success'))
                <div class="bg-green-50 border border-green-200 p-3 rounded-md shadow-sm">
                    <p class="text-xs text-green-700 font-bold uppercase">{{ session('request_success') }}</p>
                </div>
            @endif

            @if(session()->has('request_error'))
                <div class="bg-red-50 border border-red-200 p-3 rounded-md shadow-sm">
                    <p class="text-xs text-red-700 font-bold uppercase">{{ session('request_error') }}</p>
                </div>
            @endif

            {{-- Status Card --}}
            <div class="bg-white rounded-lg shadow p-6 border-t-4 
                @if($project->list_status === 'verified') border-blue-500
                @elseif($project->list_status === 'approved') border-green-500
                @elseif($project->list_status === 'pending') border-yellow-500
                @elseif($project->list_status === 'rejected') border-gray-400
                @elseif($project->list_status === 'scam') border-red-600
                @else border-gray-300 @endif">
                
                <h3 class="font-bold text-gray-900 mb-4">Project Status</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        @if($project->list_status === 'verified')
                            <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="font-bold text-blue-700 uppercase">Verified Listing</span>
                        @elseif($project->list_status === 'approved')
                            <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="font-bold text-green-700 uppercase">Approved</span>
                        @elseif($project->list_status === 'pending')
                            <span class="text-xl">⏳</span>
                            <span class="font-bold text-yellow-700 uppercase">Pending Review</span>
                        @elseif($project->list_status === 'rejected')
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span class="font-bold text-gray-700 uppercase">Rejected</span>
                        @elseif($project->list_status === 'scam')
                            <span class="font-bold text-red-700 uppercase">⚠️ SCAM WARNING</span>
                        @else
                            <span class="font-bold text-gray-700 uppercase">{{ $project->list_status }}</span>
                        @endif
                    </div>
                </div>
                
                @if($project->ownership_verified)
                    <div class="flex items-center gap-2 text-sm text-green-700 bg-green-50 p-2 rounded border border-green-200 mt-4 shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-bold uppercase tracking-wide text-xs">Ownership Verified</span>
                    </div>
                @endif
                
                @if($launchDate = $project->getDynamicField('launch_date'))
                    <div class="flex items-center gap-2 text-sm text-gray-700 bg-gray-50 p-2 rounded border border-gray-100 mt-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="flex-1">Launched: <span class="font-bold">{{ \Carbon\Carbon::parse($launchDate)->format('M d, Y') }}</span></span>
                    </div>
                @endif
                
                @if($age = $project->getDynamicField('age'))
                    <div class="flex items-center gap-2 text-sm text-gray-700 bg-gray-50 p-2 rounded border border-gray-100 mt-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="flex-1">Age Bracket: <span class="font-bold border-b border-dashed border-gray-400 pb-0.5" title="System calculated">{{ $age }}</span></span>
                    </div>
                @endif
            </div>

            {{-- Customer Support Card --}}
            @php $supportRating = $project->getDynamicField('customer_support_rating'); @endphp
            @if($supportRating)
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 overflow-hidden relative group">
                <div class="absolute top-0 right-0 w-24 h-24 -mr-8 -mt-8 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                
                <h3 class="font-bold text-gray-400 mb-5 uppercase text-[10px] tracking-[0.2em] relative">Customer Support</h3>
                
                <div class="flex items-center gap-5 relative">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex flex-col items-center justify-center shadow-lg shadow-blue-100 ring-4 ring-blue-50">
                            <span class="text-white font-black text-2xl leading-none">{{ floatval($supportRating) }}</span>
                            <div class="flex gap-0.5 mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="w-1 h-1 rounded-full {{ $i <= floatval($supportRating) ? 'bg-white' : 'bg-white/30' }}"></div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-1 mb-1">
                            @php $ratingNum = floatval($supportRating); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $ratingNum ? 'text-amber-400' : 'text-gray-200' }} drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="text-sm font-bold text-gray-900 tracking-tight">Average Rating</p>
                        <p class="text-[11px] text-gray-500 font-medium">Based on user experience</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Supported Cryptos --}}
            @php
                $supportedCoins = $project->getDynamicField('supported_coin') ?? $project->getDynamicField('cryptocurrency');
                $coinsArray = $supportedCoins ? array_map('trim', explode(',', $supportedCoins)) : ($project->supported_cryptos ?? []);
            @endphp

            @if(!empty($coinsArray))
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-gray-900 mb-4">Supported Cryptos</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($coinsArray as $crypto)
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-100">{{ strtoupper($crypto) }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Official Channels --}}
            @php
                $communityField = $project->getDynamicField('community');
                $communityLinks = $communityField ? array_filter(array_map('trim', explode("\n", str_replace(["\r\n", "\r"], "\n", $communityField)))) : [];
                $channels = array_merge($project->discussion_channels ?? [], $communityLinks);
                $channels = array_unique($channels);
            @endphp
            @if(!empty($channels))
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-gray-900 mb-4">Community & Social</h3>
                <ul class="space-y-2">
                    @foreach($channels as $channel)
                        <li>
                            @if(filter_var($channel, FILTER_VALIDATE_URL))
                                <a href="{{ $channel }}" target="_blank" rel="nofollow" class="text-blue-600 hover:underline text-sm break-all">
                                    {{ preg_replace('/^www\./', '', parse_url($channel, PHP_URL_HOST)) ?: $channel }}
                                </a>
                            @else
                                <span class="text-sm text-gray-700 break-all">{{ $channel }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Contact Details --}}
            @if($project->contact_email || $project->contact_telegram || $project->contact_discord || $project->contact_simplex)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-gray-900 mb-4">Contact Details</h3>
                <ul class="space-y-3">
                    @if($project->contact_email)
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <div class="flex-1">
                                <span class="block text-gray-500 text-[10px] font-bold uppercase tracking-wider">Email</span>
                                <a href="mailto:{{ $project->contact_email }}" class="text-blue-600 hover:underline text-sm font-medium">Send Email</a>
                            </div>
                        </li>
                    @endif
                    @if($project->contact_telegram)
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13M22 2L15 22L11 13M22 2L2 9L11 13"/></svg>
                            <div class="flex-1">
                                <span class="block text-gray-500 text-[10px] font-bold uppercase tracking-wider">Telegram</span>
                                @if(str_starts_with($project->contact_telegram, '@'))
                                    <a href="https://t.me/{{ substr($project->contact_telegram, 1) }}" target="_blank" class="text-blue-600 hover:underline text-sm font-medium">Contact via Telegram</a>
                                @elseif(str_starts_with($project->contact_telegram, 'http'))
                                    <a href="{{ $project->contact_telegram }}" target="_blank" class="text-blue-600 hover:underline text-sm font-medium">Contact via Telegram</a>
                                @else
                                    <span class="text-gray-900 text-sm font-medium">{{ $project->contact_telegram }}</span>
                                @endif
                            </div>
                        </li>
                    @endif
                    @if($project->contact_discord)
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m0-18h14a2 2 0 002-2V3a1 1 0 00-1-1H3a1 1 0 00-1 1v1a2 2 0 002 2z"></path></svg>
                            <div class="flex-1">
                                <span class="block text-gray-500 text-[10px] font-bold uppercase tracking-wider">Discord</span>
                                @if(str_starts_with($project->contact_discord, 'http'))
                                    <a href="{{ $project->contact_discord }}" target="_blank" class="text-blue-600 hover:underline text-sm font-medium">Contact via Discord</a>
                                @else
                                    <span class="text-gray-900 text-sm font-medium">{{ $project->contact_discord }}</span>
                                @endif
                            </div>
                        </li>
                    @endif
                    @if($project->contact_simplex)
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <div class="flex-1">
                                <span class="block text-gray-500 text-[10px] font-bold uppercase tracking-wider">SimpleX</span>
                                @if(str_starts_with($project->contact_simplex, 'http'))
                                    <a href="{{ $project->contact_simplex }}" target="_blank" class="text-blue-600 hover:underline text-sm font-medium">Contact via SimpleX</a>
                                @else
                                    <span class="text-gray-900 text-sm font-medium">{{ $project->contact_simplex }}</span>
                                @endif
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
            @endif

            {{-- Project Change Log --}}
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-indigo-500">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Project Change Log
                </h3>

                @if($activityLogs->isEmpty())
                    <p class="text-xs text-gray-500 italic">No modifications have been recorded for this project yet.</p>
                @else
                    <div class="relative pl-4 border-l border-gray-100 space-y-4">
                        @foreach($activityLogs as $log)
                            @php
                                $dotColor = 'bg-indigo-500';
                                if ($log->action === 'created') $dotColor = 'bg-green-500';
                                elseif ($log->action === 'deleted') $dotColor = 'bg-red-500';
                            @endphp
                            <div class="relative">
                                {{-- Icon Dot --}}
                                <span class="absolute -left-[21px] top-1.5 flex h-2.5 w-2.5 items-center justify-center rounded-full {{ $dotColor }} ring-4 ring-white"></span>
                                
                                <div class="text-[11px] text-gray-400 font-medium">
                                    {{ $log->created_at->diffForHumans() }}
                                </div>
                                <div class="text-xs font-semibold text-gray-800 mt-0.5">
                                    {{ $log->description }}
                                </div>
                                <div class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mt-1 flex items-center gap-1.5">
                                    <span>By:</span>
                                    <span class="text-gray-700">
                                        {{ $log->user ? $log->user->name : 'System/Anonymous' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Report Modal --}}
    @if($showReportModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="isolation: isolate;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" wire:click="closeReportModal"></div>

            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-100/50 z-10">
                <div class="bg-white px-6 pt-8 pb-6 sm:px-10">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-2xl bg-red-50 sm:mx-0 sm:h-14 sm:w-14 border border-red-100">
                            <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-5 text-center sm:mt-0 sm:ml-6 sm:text-left w-full">
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight" id="modal-title">
                                Report Project
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 font-medium">
                                Something wrong with <span class="text-gray-900 font-bold decoration-red-200 underline underline-offset-4 decoration-2">{{ $project->name }}</span>? Let us know.
                            </p>
                            <div class="mt-6">
                                <textarea wire:model="reportDescription" rows="4" 
                                    class="block w-full px-5 py-4 text-gray-900 border-gray-200 rounded-2xl focus:ring-red-500 focus:border-red-500 sm:text-sm shadow-sm placeholder-gray-400 bg-gray-50/30 resize-none font-medium transition-all"
                                    placeholder="Briefly explain the issue..."></textarea>
                                @error('reportDescription') <p class="mt-2 text-xs text-red-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50/50 px-6 py-5 sm:px-10 sm:flex sm:flex-row-reverse gap-3 border-t border-gray-100/50">
                    <button type="button" wire:click="submitReport" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center items-center px-8 py-3 bg-red-600 text-sm font-black text-white rounded-2xl hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/20 active:scale-[0.98] transition-all sm:w-auto shadow-lg shadow-red-100">
                        <svg wire:loading wire:target="submitReport" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Submit Report
                    </button>
                    <button type="button" wire:click="closeReportModal"
                        class="mt-3 sm:mt-0 w-full inline-flex justify-center px-8 py-3 bg-white border border-gray-200 text-sm font-bold text-gray-700 rounded-2xl hover:bg-gray-50 transition-all sm:w-auto">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Fixed Popup Ad (Bottom Right) --}}
    @php
        $adEnabled = \App\Models\Setting::getValue('popup_ad_enabled', '0') === '1';
        $adTitle = \App\Models\Setting::getValue('popup_ad_title');
        $adDescription = \App\Models\Setting::getValue('popup_ad_description');
        $adButtonText = \App\Models\Setting::getValue('popup_ad_button_text');
        $adButtonLink = \App\Models\Setting::getValue('popup_ad_button_link');
        $adLogoPath = \App\Models\Setting::getValue('popup_ad_logo');
        $adIconPath = \App\Models\Setting::getValue('popup_ad_icon');
        $adLogo = $adLogoPath ? \Illuminate\Support\Facades\Storage::disk('public')->url($adLogoPath) : null;
        $adIcon = $adIconPath ? \Illuminate\Support\Facades\Storage::disk('public')->url($adIconPath) : null;
    @endphp

    @if($adEnabled && ($adTitle || $adDescription))
    <style>
        @keyframes blobFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40px, -50px) scale(1.15); }
            66% { transform: translate(-30px, 40px) scale(0.95); }
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes shineSweep {
            0% { transform: translateX(-150%) skewX(-20deg); }
            80%, 100% { transform: translateX(250%) skewX(-20deg); }
        }
        @keyframes iconPulse {
            0%, 100% { transform: scale(1) rotate(0deg); filter: drop-shadow(0 0 15px rgba(168, 85, 247, 0.4)); }
            50% { transform: scale(1.08) rotate(3deg); filter: drop-shadow(0 0 25px rgba(168, 85, 247, 0.7)); }
        }
        .animate-blob { animation: blobFloat 20s infinite ease-in-out; }
        .animate-gradient { background-size: 200% 200%; animation: gradientShift 20s infinite ease-in-out; }
        .shimmer-effect {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shineSweep 5s infinite;
            pointer-events: none;
            z-index: 5;
        }
        .ad-icon-animated {
            animation: iconPulse 5s infinite ease-in-out;
        }
    </style>

    <div x-data="{ 
        show: false,
        dismiss() { 
            this.show = false; 
            localStorage.setItem('wlc_popup_v6', 'true'); 
        }
    }" 
    x-show="show" 
    x-init="setTimeout(() => { show = (localStorage.getItem('wlc_popup_v6') !== 'true'); }, 1500)"
    x-transition:enter="transition ease-out duration-700"
    x-transition:enter-start="opacity-0 translate-y-12 scale-90 rotate-1"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100 rotate-0"
    x-transition:leave="transition ease-in duration-400"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-12 scale-90"
    class="w-full"
    style="position: fixed; bottom: 20px; right: 20px; z-index: 9998; max-width: 320px; pointer-events: auto; display: none;"
    >
        {{-- Background Decorative Icon (Outside Border) --}}
        @if($adIcon)
            <div class="absolute -top-8 -right-5 w-36 h-36 z-20 pointer-events-none select-none ad-icon-animated">
                <img src="{{ $adIcon }}" class="w-full h-full object-contain drop-shadow-[0_0_20px_rgba(124,58,237,0.4)]" alt="" draggable="false">
            </div>
        @endif

        <div class="relative rounded-xl overflow-hidden shadow-[0_20px_50px_-10px_rgba(0,0,0,0.7)] border border-white/20 animate-gradient backdrop-blur-xl" 
             style="background: linear-gradient(135deg, rgba(26,26,46,0.95) 0%, rgba(22,33,62,0.95) 50%, rgba(15,15,35,0.95) 100%);">
            
            {{-- Animated background layers --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-15 -left-15 w-48 h-48 bg-purple-600/25 rounded-full blur-[70px] animate-blob"></div>
                <div class="absolute -bottom-15 -right-15 w-56 h-56 bg-indigo-600/25 rounded-full blur-[70px] animate-blob" style="animation-delay: -7s;"></div>
                <div class="shimmer-effect"></div>
                
                {{-- Noise Texture --}}
                <div class="absolute inset-0 opacity-[0.03] mix-blend-overlay pointer-events-none" style="background-image: url('https://grainy-gradients.vercel.app/noise.svg');"></div>
            </div>

            <div class="relative p-3 pr-20 z-10">
                {{-- Dismiss Button --}}
                <button @click="dismiss()" class="absolute bottom-1 right-1 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white rounded-full hover:bg-white/10 hover:rotate-90 transition-all duration-500 z-30 group" title="Dismiss">
                    <svg class="w-4 h-4 transition-transform group-active:scale-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                {{-- Logo --}}
                @if($adLogo)
                    <div class="mb-1">
                        <img src="{{ $adLogo }}" alt="Ad" class="h-7 w-auto object-contain drop-shadow-[0_4px_10px_rgba(255,255,255,0.25)]">
                    </div>
                @endif

                {{-- Title --}}
                @if($adTitle)
                    <h4 class="font-black italic text-base tracking-tight mb-0.5" style="color: #fbbf24; text-shadow: 0 2px 8px rgba(251,191,36,0.3);">{{ $adTitle }}</h4>
                @endif

                {{-- Description --}}
                @if($adDescription)
                    <p class="text-gray-200/90 text-xs font-bold truncate whitespace-nowrap max-w-[220px]" title="{{ $adDescription }}">{{ $adDescription }}</p>
                @endif

                {{-- CTA Button --}}
                @if($adButtonText && $adButtonLink)
                    <div class="mt-1.5">
                        <a href="{{ $adButtonLink }}" target="_blank" rel="noopener noreferrer" 
                           class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-white text-[10px] font-black uppercase tracking-wider shadow-[0_8px_20px_-5px_rgba(124,58,237,0.5)] hover:shadow-[0_12px_30px_-5px_rgba(124,58,237,0.7)] hover:scale-[1.05] active:scale-[0.98] transition-all duration-300 group overflow-hidden relative"
                           style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
                            <span class="relative z-10">{{ $adButtonText }}</span>
                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:translate-x-1 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

</div>