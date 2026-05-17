<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Settings extends Component
{
    use WithFileUploads;

    public $activeTab = 'general';

    public $scam_warning_message;
    public $risk_warning_message;
    public $pending_status_message;
    public $approved_status_message;
    public $rejected_status_message;
    public $verified_status_message;
    public $admin_xmpp_contact;
    public $footer_copyright;
    public $min_bankroll_amount;

    public $favicon;
    public $existingFavicon;
    public $logo;
    public $existingLogo;
    public $site_title;
    public $font_family = 'Inter'; // Default
    public $font_url;
    public $base_font_size = '16px';
    public $primary_color = '#3b82f6'; // blue-500
    public $secondary_color = '#64748b'; // slate-500
    public $background_color = '#f9fafb'; // gray-50
    public $meta_title;
    public $meta_description;

    // Design Settings - Directory Global
    public $dir_bg_color = '#111827'; // gray-900
    public $dir_text_color = '#f3f4f6'; // gray-100
    public $dir_accent_color = '#22c55e'; // green-500

    // Design Settings - Sidebar
    public $sidebar_bg_color = 'transparent'; 
    public $sidebar_filter_bg = 'rgba(0,0,0,0.4)'; // black/40
    public $sidebar_filter_border = '#374151'; // gray-700
    public $sidebar_header_color = '#22c55e'; // green-500

    // Design Settings - Cards
    public $card_bg_color = '#111111'; // #111
    public $card_border_color = '#1f2937'; // gray-800
    public $card_title_color = '#ffffff'; // white
    public $card_text_color = '#9ca3af'; // gray-400

    // Design Settings - Badges (Conditions)
    public $badge_safe_text = '#4ade80'; // green-400
    public $badge_safe_bg = 'rgba(20, 83, 45, 0.4)'; // green-900/40
    public $badge_safe_border = 'rgba(22, 101, 52, 0.5)'; // green-800/50

    public $badge_danger_text = '#f87171'; // red-400
    public $badge_danger_bg = 'rgba(127, 29, 29, 0.4)'; // red-900/40
    public $badge_danger_border = 'rgba(153, 27, 27, 0.5)'; // red-800/50
    
    public $badge_info_text = '#60a5fa'; // blue-400
    public $badge_info_bg = 'rgba(30, 58, 138, 0.3)'; // blue-900/30
    public $badge_info_border = 'rgba(30, 64, 175, 0.5)'; // blue-800/50

    public $badge_warning_text = '#facc15'; // yellow-400
    public $badge_warning_bg = 'rgba(113, 63, 18, 0.3)'; // yellow-900/30
    public $badge_warning_border = 'rgba(133, 77, 14, 0.5)'; // yellow-800/50

    // Card Customization
    public $card_cat_bg = 'rgba(30, 58, 138, 0.3)';
    public $card_cat_text = '#93c5fd';
    public $card_cat_border = 'rgba(30, 64, 175, 0.5)';
    
    public $card_wlc_poor_bg = '#dc2626';
    public $card_wlc_fair_bg = '#FFBF00';
    public $card_wlc_good_bg = '#dcfce7';
    public $card_wlc_vgood_bg = '#7cff7c';
    public $card_wlc_excellent_bg = '#16a34a';
    
    public $card_status_verified = '#3b82f6';
    public $card_status_approved = '#22c55e';
    public $card_status_pending = '#eab308';
    public $card_status_scam = '#dc2626';

    public $card_escrow_bg = '#15803d';
    public $card_ownership_text = '#00ff00';
    public $card_risk_bg = '#7f1d1d';
    public $card_risk_text = '#fee2e2';

    public $card_coins_bg = '#1a442a';
    public $card_coins_text = '#86e2a2';
    public $card_coins_border = '#2b6540';

    public $card_fee_bg = '#293247';
    public $card_fee_text = '#a1b0cd';
    public $card_fee_border = '#3b4764';

    public $card_tor_true_bg = '#1a442a';
    public $card_tor_true_text = '#86e2a2';
    public $card_tor_true_border = '#16a34a'; // NEW
    public $card_tor_false_bg = '#1a1c23';
    public $card_tor_false_text = '#ef4444';
    public $card_tor_false_border = '#dc2626'; // NEW

    public $card_attr_bg = '#1a1c23'; // Base for Score/Support
    public $card_attr_text = '#a0a5b1';
    public $card_attr_border = '#2a3041';

    public $card_score_poor = '#ef4444';
    public $card_score_poor_border = 'rgba(239, 68, 68, 0.5)'; // NEW
    public $card_score_fair = '#FFBF00';
    public $card_score_fair_border = 'rgba(255, 191, 0, 0.5)'; // NEW
    public $card_score_good = '#7cff7c';
    public $card_score_good_border = 'rgba(124, 255, 124, 0.5)'; // NEW
    public $card_score_excellent = '#22c55e';
    public $card_score_excellent_border = 'rgba(34, 197, 94, 0.5)'; // NEW

    public $card_review_pos = '#22c55e';
    public $card_review_neu = '#d1d5db';
    public $card_review_neg = '#ef4444';

    public $card_kyc_bg = '#18211b';
    public $card_kyc_text = '#ffffff';
    public $card_kyc_border = '#22c55e'; // NEW

    public $card_comm_true_bg = '#1d1b31';
    public $card_comm_true_text = '#716cd5';
    public $card_comm_true_border = '#716cd5'; // NEW
    public $card_comm_false_bg = 'rgba(31, 41, 55, 0.3)';
    public $card_comm_false_text = '#4b5563';
    public $card_comm_false_border = 'rgba(31, 41, 55, 0.5)'; // NEW

    public $card_item_age = '#6b7280';
    public $card_online_dot = '#22c55e';
    public $card_offline_dot = '#ef4444';

    public $card_log_true_bg = '#ffffff';
    public $card_log_true_text = '#15803d';
    public $card_log_true_border = '#15803d'; // NEW
    public $card_log_false_bg = 'rgba(31, 41, 55, 0.3)';
    public $card_log_false_text = '#4b5563';
    public $card_log_false_border = 'rgba(31, 41, 55, 0.5)'; // NEW

    // Policy Indicators (Registration, Log, Liquidity, Audit)
    public $card_policy_true_bg = '#1a442a';
    public $card_policy_true_text = '#86e2a2';
    public $card_policy_true_border = '#16a34a';
    public $card_policy_false_bg = '#441a1a';
    public $card_policy_false_text = '#e28686';
    public $card_policy_false_border = '#dc2626';
    public $card_policy_check_true = '#22c55e'; // Checkmark color
    public $card_policy_check_false = '#ef4444'; // Cross color

    // Popup Ad Settings
    public $popup_ad_enabled = false;
    public $popup_ad_title = '';
    public $popup_ad_description = '';
    public $popup_ad_button_text = '';
    public $popup_ad_button_link = '';
    public $popup_ad_logo;
    public $existingPopupAdLogo;
    public $popup_ad_icon;
    public $existingPopupAdIcon;


    public function mount()
    {
        $settings = Setting::all()->pluck('value', 'key');

        $this->site_title = $settings['site_title'] ?? config('app.name', 'WeLiveCrypto Directory');

        $this->scam_warning_message = $settings['scam_warning_message'] ?? '';
        $this->risk_warning_message = $settings['risk_warning_message'] ?? '';
        $this->pending_status_message = $settings['pending_status_message'] ?? '';
        $this->approved_status_message = $settings['approved_status_message'] ?? '';
        $this->rejected_status_message = $settings['rejected_status_message'] ?? '';
        $this->verified_status_message = $settings['verified_status_message'] ?? '';
        $this->admin_xmpp_contact = $settings['admin_xmpp_contact'] ?? $settings['admin_telegram_contact'] ?? '';
        $this->footer_copyright = $settings['footer_copyright'] ?? '';

        $faviconPath = $settings['favicon'] ?? null;
        $this->existingFavicon = $faviconPath ? Storage::disk('public')->url($faviconPath) : null;
        $logoPath = $settings['logo'] ?? null;
        $this->existingLogo = $logoPath ? Storage::disk('public')->url($logoPath) : null;
        $this->font_family = $settings['font_family'] ?? 'Inter';
        $this->font_url = $settings['font_url'] ?? '';
        $this->base_font_size = $settings['base_font_size'] ?? '16px';
        $this->primary_color = $settings['primary_color'] ?? '#3b82f6';
        $this->secondary_color = $settings['secondary_color'] ?? '#64748b';
        $this->background_color = $settings['background_color'] ?? '#f9fafb';

        // Load Design Settings
        $this->dir_bg_color = $settings['dir_bg_color'] ?? '#111827';
        $this->dir_text_color = $settings['dir_text_color'] ?? '#f3f4f6';
        $this->dir_accent_color = $settings['dir_accent_color'] ?? '#22c55e';

        $this->sidebar_bg_color = $settings['sidebar_bg_color'] ?? 'transparent';
        $this->sidebar_filter_bg = $settings['sidebar_filter_bg'] ?? 'rgba(0,0,0,0.4)';
        $this->sidebar_filter_border = $settings['sidebar_filter_border'] ?? '#374151';
        $this->sidebar_header_color = $settings['sidebar_header_color'] ?? '#22c55e';

        $this->card_bg_color = $settings['card_bg_color'] ?? '#111111';
        $this->card_border_color = $settings['card_border_color'] ?? '#1f2937';
        $this->card_title_color = $settings['card_title_color'] ?? '#ffffff';
        $this->card_text_color = $settings['card_text_color'] ?? '#9ca3af';

        $this->badge_safe_text = $settings['badge_safe_text'] ?? '#4ade80';
        $this->badge_safe_bg = $settings['badge_safe_bg'] ?? 'rgba(20, 83, 45, 0.4)';
        $this->badge_safe_border = $settings['badge_safe_border'] ?? 'rgba(22, 101, 52, 0.5)';

        $this->badge_danger_text = $settings['badge_danger_text'] ?? '#f87171';
        $this->badge_danger_bg = $settings['badge_danger_bg'] ?? 'rgba(127, 29, 29, 0.4)';
        $this->badge_danger_border = $settings['badge_danger_border'] ?? 'rgba(153, 27, 27, 0.5)';

        $this->badge_info_text = $settings['badge_info_text'] ?? '#60a5fa';
        $this->badge_info_bg = $settings['badge_info_bg'] ?? 'rgba(30, 58, 138, 0.3)';
        $this->badge_info_border = $settings['badge_info_border'] ?? 'rgba(30, 64, 175, 0.5)';

        $this->badge_warning_text = $settings['badge_warning_text'] ?? '#facc15';
        $this->badge_warning_bg = $settings['badge_warning_bg'] ?? 'rgba(113, 63, 18, 0.3)';
        $this->badge_warning_border = $settings['badge_warning_border'] ?? 'rgba(133, 77, 14, 0.5)';

        $this->card_cat_bg = $settings['card_cat_bg'] ?? 'rgba(30, 58, 138, 0.3)';
        $this->card_cat_text = $settings['card_cat_text'] ?? '#93c5fd';
        $this->card_cat_border = $settings['card_cat_border'] ?? 'rgba(30, 64, 175, 0.5)';
        $this->card_wlc_poor_bg = $settings['card_wlc_poor_bg'] ?? '#dc2626';
        $this->card_wlc_fair_bg = $settings['card_wlc_fair_bg'] ?? '#FFBF00';
        $this->card_wlc_good_bg = $settings['card_wlc_good_bg'] ?? '#dcfce7';
        $this->card_wlc_vgood_bg = $settings['card_wlc_vgood_bg'] ?? '#7cff7c';
        $this->card_wlc_excellent_bg = $settings['card_wlc_excellent_bg'] ?? '#16a34a';

        // Load Exhaustive Card Settings
        $this->card_kyc_border = $settings['card_kyc_border'] ?? '#22c55e';
        $this->card_comm_true_border = $settings['card_comm_true_border'] ?? '#716cd5';
        $this->card_comm_false_border = $settings['card_comm_false_border'] ?? 'rgba(31, 41, 55, 0.5)';
        $this->card_log_true_border = $settings['card_log_true_border'] ?? '#15803d';
        $this->card_log_false_border = $settings['card_log_false_border'] ?? 'rgba(31, 41, 55, 0.5)';
        
        $this->card_policy_true_bg = $settings['card_policy_true_bg'] ?? '#1a442a';
        $this->card_policy_true_text = $settings['card_policy_true_text'] ?? '#86e2a2';
        $this->card_policy_true_border = $settings['card_policy_true_border'] ?? '#16a34a';
        $this->card_policy_false_bg = $settings['card_policy_false_bg'] ?? '#441a1a';
        $this->card_policy_false_text = $settings['card_policy_false_text'] ?? '#e28686';
        $this->card_policy_false_border = $settings['card_policy_false_border'] ?? '#dc2626';
        $this->card_policy_check_true = $settings['card_policy_check_true'] ?? '#22c55e';
        $this->card_policy_check_false = $settings['card_policy_check_false'] ?? '#ef4444';
        $this->card_status_verified = $settings['card_status_verified'] ?? '#3b82f6';
        $this->card_status_approved = $settings['card_status_approved'] ?? '#22c55e';
        $this->card_status_pending = $settings['card_status_pending'] ?? '#eab308';
        $this->card_status_scam = $settings['card_status_scam'] ?? '#dc2626';
        $this->card_escrow_bg = $settings['card_escrow_bg'] ?? '#15803d';
        $this->card_ownership_text = $settings['card_ownership_text'] ?? '#00ff00';
        $this->card_risk_bg = $settings['card_risk_bg'] ?? '#7f1d1d';
        $this->card_risk_text = $settings['card_risk_text'] ?? '#fee2e2';
        $this->card_coins_bg = $settings['card_coins_bg'] ?? '#1a442a';
        $this->card_coins_text = $settings['card_coins_text'] ?? '#86e2a2';
        $this->card_coins_border = $settings['card_coins_border'] ?? '#2b6540';
        $this->card_fee_bg = $settings['card_fee_bg'] ?? '#293247';
        $this->card_fee_text = $settings['card_fee_text'] ?? '#a1b0cd';
        $this->card_fee_border = $settings['card_fee_border'] ?? '#3b4764';
        $this->card_tor_true_bg = $settings['card_tor_true_bg'] ?? '#1a442a';
        $this->card_tor_true_text = $settings['card_tor_true_text'] ?? '#86e2a2';
        $this->card_tor_true_border = $settings['card_tor_true_border'] ?? '#16a34a'; // NEW
        $this->card_tor_false_bg = $settings['card_tor_false_bg'] ?? '#1a1c23';
        $this->card_tor_false_text = $settings['card_tor_false_text'] ?? '#ef4444';
        $this->card_tor_false_border = $settings['card_tor_false_border'] ?? '#dc2626'; // NEW
        
        $this->card_score_poor = $settings['card_score_poor'] ?? '#ef4444';
        $this->card_score_poor_border = $settings['card_score_poor_border'] ?? 'rgba(239, 68, 68, 0.5)';
        $this->card_score_fair = $settings['card_score_fair'] ?? '#FFBF00';
        $this->card_score_fair_border = $settings['card_score_fair_border'] ?? 'rgba(255, 191, 0, 0.5)';
        $this->card_score_good = $settings['card_score_good'] ?? '#7cff7c';
        $this->card_score_good_border = $settings['card_score_good_border'] ?? 'rgba(124, 255, 124, 0.5)';
        $this->card_score_excellent = $settings['card_score_excellent'] ?? '#22c55e';
        $this->card_score_excellent_border = $settings['card_score_excellent_border'] ?? 'rgba(34, 197, 94, 0.5)';
        $this->card_review_pos = $settings['card_review_pos'] ?? '#22c55e';
        $this->card_review_neu = $settings['card_review_neu'] ?? '#d1d5db';
        $this->card_review_neg = $settings['card_review_neg'] ?? '#ef4444';
        $this->card_kyc_bg = $settings['card_kyc_bg'] ?? '#18211b';
        $this->card_kyc_text = $settings['card_kyc_text'] ?? '#ffffff';
        $this->card_comm_true_bg = $settings['card_comm_true_bg'] ?? '#1d1b31';
        $this->card_comm_true_text = $settings['card_comm_true_text'] ?? '#716cd5';
        $this->card_comm_false_bg = $settings['card_comm_false_bg'] ?? 'rgba(31, 41, 55, 0.3)';
        $this->card_comm_false_text = $settings['card_comm_false_text'] ?? '#4b5563';
        $this->card_item_age = $settings['card_item_age'] ?? '#6b7280';
        $this->card_online_dot = $settings['card_online_dot'] ?? '#22c55e';
        $this->card_offline_dot = $settings['card_offline_dot'] ?? '#ef4444';
        $this->card_log_true_bg = $settings['card_log_true_bg'] ?? '#ffffff';
        $this->card_log_true_text = $settings['card_log_true_text'] ?? '#15803d';
        $this->card_log_false_bg = $settings['card_log_false_bg'] ?? 'rgba(31, 41, 55, 0.3)';
        $this->card_log_false_text = $settings['card_log_false_text'] ?? '#4b5563';

        $this->meta_title = $settings['meta_title'] ?? '';
        $this->meta_description = $settings['meta_description'] ?? '';

        // Load Popup Ad Settings
        $this->popup_ad_enabled = ($settings['popup_ad_enabled'] ?? '0') === '1';
        $this->popup_ad_title = $settings['popup_ad_title'] ?? '';
        $this->popup_ad_description = $settings['popup_ad_description'] ?? '';
        $this->popup_ad_button_text = $settings['popup_ad_button_text'] ?? '';
        $this->popup_ad_button_link = $settings['popup_ad_button_link'] ?? '';
        $popupLogoPath = $settings['popup_ad_logo'] ?? null;
        $this->existingPopupAdLogo = $popupLogoPath ? Storage::disk('public')->url($popupLogoPath) : null;
        $popupIconPath = $settings['popup_ad_icon'] ?? null;
        $this->existingPopupAdIcon = $popupIconPath ? Storage::disk('public')->url($popupIconPath) : null;
        $this->min_bankroll_amount = $settings['min_bankroll_amount'] ?? 0;
    }

    public function save()
    {
        $this->validate([
            'site_title' => 'required|string|max:255',
            'scam_warning_message' => 'nullable|string',
            'risk_warning_message' => 'nullable|string',
            'admin_xmpp_contact' => 'nullable|string|max:255',
            'favicon' => 'nullable|image|max:512', // 512KB Max
            'logo' => 'nullable|image|max:1024', // 1MB Max
            'min_bankroll_amount' => 'nullable|numeric|min:0',
            'font_family' => 'required|string',
            'base_font_size' => 'required|string',
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'background_color' => 'required|string',
            
            // Allow mostly any string for colors (hex, rgb, rgba)
            'dir_bg_color' => 'required|string',
            'dir_text_color' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
             // Others are nullable or strings, let's keep it loose to avoid validation hell on colors
        ]);

        if ($this->favicon) {
            $path = $this->favicon->store('favicons', 'public');
            // Save the relative path in the database
            Setting::updateOrCreate(['key' => 'favicon'], ['value' => $path]);
            // Update the preview with the full URL
            $this->existingFavicon = Storage::disk('public')->url($path);
        }

        if ($this->logo) {
            $path = $this->logo->store('logos', 'public');
            // Save the relative path in the database
            Setting::updateOrCreate(['key' => 'logo'], ['value' => $path]);
            // Update the preview with the full URL
            $this->existingLogo = Storage::disk('public')->url($path);
        }

        if ($this->popup_ad_logo) {
            $path = $this->popup_ad_logo->store('popup_ads', 'public');
            Setting::updateOrCreate(['key' => 'popup_ad_logo'], ['value' => $path]);
            $this->existingPopupAdLogo = Storage::disk('public')->url($path);
        }

        if ($this->popup_ad_icon) {
            $path = $this->popup_ad_icon->store('popup_ads', 'public');
            Setting::updateOrCreate(['key' => 'popup_ad_icon'], ['value' => $path]);
            $this->existingPopupAdIcon = Storage::disk('public')->url($path);
        }

        $fields = [
            'font_family', 'font_url', 'base_font_size', 'primary_color', 'secondary_color', 'background_color',
            'dir_bg_color', 'dir_text_color', 'dir_accent_color',
            'sidebar_bg_color', 'sidebar_filter_bg', 'sidebar_filter_border', 'sidebar_header_color',
            'card_bg_color', 'card_border_color', 'card_title_color', 'card_text_color',
            'badge_safe_text', 'badge_safe_bg', 'badge_safe_border',
            'badge_danger_text', 'badge_danger_bg', 'badge_danger_border',
            'badge_info_text', 'badge_info_bg', 'badge_info_border',
            'badge_warning_text', 'badge_warning_bg', 'badge_warning_border',
            'card_cat_bg', 'card_cat_text', 'card_cat_border',
            'card_wlc_poor_bg', 'card_wlc_fair_bg', 'card_wlc_good_bg', 'card_wlc_vgood_bg', 'card_wlc_excellent_bg',
            'card_status_verified', 'card_status_approved', 'card_status_pending', 'card_status_scam',
            'card_escrow_bg', 'card_ownership_text', 'card_risk_bg', 'card_risk_text',
            'card_coins_bg', 'card_coins_text', 'card_coins_border',
            'card_fee_bg', 'card_fee_text', 'card_fee_border',
            'card_tor_true_bg', 'card_tor_true_text', 'card_tor_true_border', 'card_tor_false_bg', 'card_tor_false_text', 'card_tor_false_border',
            'card_score_poor', 'card_score_poor_border', 'card_score_fair', 'card_score_fair_border', 'card_score_good', 'card_score_good_border', 'card_score_excellent', 'card_score_excellent_border',
            'card_review_pos', 'card_review_neu', 'card_review_neg',
            'card_kyc_bg', 'card_kyc_text', 'card_kyc_border',
            'card_comm_true_bg', 'card_comm_true_text', 'card_comm_true_border', 'card_comm_false_bg', 'card_comm_false_text', 'card_comm_false_border',
            'card_item_age', 'card_online_dot', 'card_offline_dot',
            'card_log_true_bg', 'card_log_true_text', 'card_log_true_border', 'card_log_false_bg', 'card_log_false_text', 'card_log_false_border',
            'card_policy_true_bg', 'card_policy_true_text', 'card_policy_true_border',
            'card_policy_false_bg', 'card_policy_false_text', 'card_policy_false_border',
            'card_policy_check_true', 'card_policy_check_false',
            'scam_warning_message', 'risk_warning_message',
            'pending_status_message', 'approved_status_message', 'rejected_status_message', 'verified_status_message',
            'admin_xmpp_contact',
            'site_title', 'meta_title', 'meta_description', 'footer_copyright',
            'popup_ad_title', 'popup_ad_description', 'popup_ad_button_text', 'popup_ad_button_link',
            'min_bankroll_amount'
        ];

        foreach ($fields as $field) {
            Setting::updateOrCreate(['key' => $field], ['value' => $this->$field]);
        }

        // Save popup_ad_enabled as '1' or '0'
        Setting::updateOrCreate(['key' => 'popup_ad_enabled'], ['value' => $this->popup_ad_enabled ? '1' : '0']);

        session()->flash('message', 'Settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('components.admin.layout', ['title' => 'System Settings']);
    }
}
