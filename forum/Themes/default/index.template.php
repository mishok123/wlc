<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines https://www.simplemachines.org
 * @copyright 2022 Simple Machines and individual contributors
 * @license https://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.1.3
 */

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	https://www.simplemachines.org/
*/

/**
 * Initialize the template... mainly little settings.
 */
function template_init()
{
	global $settings, $txt;

	/* $context, $options and $txt may be available for use, but may not be fully populated yet. */

	// The version this template/theme is for. This should probably be the version of SMF it was created for.
	$settings['theme_version'] = '2.1';

	// Set the following variable to true if this theme requires the optional theme strings file to be loaded.
	$settings['require_theme_strings'] = false;

	// Set the following variable to true if this theme wants to display the avatar of the user that posted the last and the first post on the message index and recent pages.
	$settings['avatars_on_indexes'] = false;

	// Set the following variable to true if this theme wants to display the avatar of the user that posted the last post on the board index.
	$settings['avatars_on_boardIndex'] = false;

	// Set the following variable to true if this theme wants to display the login and register buttons in the main forum menu.
	$settings['login_main_menu'] = false;

	// This defines the formatting for the page indexes used throughout the forum.
	$settings['page_index'] = array(
		'extra_before' => '<span class="pages">' . $txt['pages'] . '</span>',
		'previous_page' => '<span class="main_icons previous_page"></span>',
		'current_page' => '<span class="current_page">%1$d</span> ',
		'page' => '<a class="nav_page" href="{URL}">%2$s</a> ',
		'expand_pages' => '<span class="expand_pages" onclick="expandPages(this, {LINK}, {FIRST_PAGE}, {LAST_PAGE}, {PER_PAGE});"> ... </span>',
		'next_page' => '<span class="main_icons next_page"></span>',
		'extra_after' => '',
	);

	// Allow css/js files to be disabled for this specific theme.
	// Add the identifier as an array key. IE array('smf_script'); Some external files might not add identifiers, on those cases SMF uses its filename as reference.
	if (!isset($settings['disable_files']))
		$settings['disable_files'] = array();
}

/**
 * The main sub template above the content.
 */
function template_html_above()
{
	global $context, $scripturl, $txt, $modSettings;

	// Show right to left, the language code, and the character set for ease of translating.
	echo '<!DOCTYPE html>
<html', $context['right_to_left'] ? ' dir="rtl"' : '', !empty($txt['lang_locale']) ? ' lang="' . str_replace("_", "-", substr($txt['lang_locale'], 0, strcspn($txt['lang_locale'], "."))) . '"' : '', '>
<head>
	<meta charset="', $context['character_set'], '">';

	/*
		You don't need to manually load index.css, this will be set up for you.
		Note that RTL will also be loaded for you.
		To load other CSS and JS files you should use the functions
		loadCSSFile() and loadJavaScriptFile() respectively.
		This approach will let you take advantage of SMF's automatic CSS
		minimization and other benefits. You can, of course, manually add any
		other files you want after template_css() has been run.

	*	Short example:
			- CSS: loadCSSFile('filename.css', array('minimize' => true));
			- JS:  loadJavaScriptFile('filename.js', array('minimize' => true));
			You can also read more detailed usages of the parameters for these
			functions on the SMF wiki.

	*	Themes:
			The most efficient way of writing multi themes is to use a master
			index.css plus variant.css files. If you've set them up properly
			(through $settings['theme_variants']), the variant files will be loaded
			for you automatically.
			Additionally, tweaking the CSS for the editor requires you to include
			a custom 'jquery.sceditor.theme.css' file in the css folder if you need it.

	*	MODs:
			If you want to load CSS or JS files in here, the best way is to use the
			'integrate_load_theme' hook for adding multiple files, or using
			'integrate_pre_css_output', 'integrate_pre_javascript_output' for a single file.
	*/

	// load in any css from mods or themes so they can overwrite if wanted
	template_css();

	// load in any javascript files from mods and themes
	template_javascript();

	echo '
	<title>', $context['page_title_html_safe'], '</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">';

	global $smcFunc;
	$request = $smcFunc['db_query'](
		'',
		'
		SELECT value
		FROM settings
		WHERE `key` = {string:favicon}',
		array('favicon' => 'favicon')
	);
	if ($row = $smcFunc['db_fetch_assoc']($request)) {
		if (!empty($row['value'])) {
			echo '
	<link rel="icon" href="/wlc/directory/public/storage/', $row['value'], '">';
		}
	}
	$smcFunc['db_free_result']($request);

	// Content related meta tags, like description, keywords, Open Graph stuff, etc...
	foreach ($context['meta_tags'] as $meta_tag) {
		echo '
	<meta';

		foreach ($meta_tag as $meta_key => $meta_value)
			echo ' ', $meta_key, '="', $meta_value, '"';

		echo '>';
	}

	/*	What is your Lollipop's color?
		Theme Authors, you can change the color here to make sure your theme's main color gets visible on tab */
	echo '
	<meta name="theme-color" content="#557EA0">';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex">';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '">';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help">
	<link rel="contents" href="', $scripturl, '">', ($context['allow_search'] ? '
	<link rel="search" href="' . $scripturl . '?action=search">' : '');

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?action=.xml;type=rss2', !empty($context['current_board']) ? ';board=' . $context['current_board'] : '', '">
	<link rel="alternate" type="application/atom+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['atom'], '" href="', $scripturl, '?action=.xml;type=atom', !empty($context['current_board']) ? ';board=' . $context['current_board'] : '', '">';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['links']['next']))
		echo '
	<link rel="next" href="', $context['links']['next'], '">';

	if (!empty($context['links']['prev']))
		echo '
	<link rel="prev" href="', $context['links']['prev'], '">';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0">';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
	<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --forum-bg: ', !empty($modSettings['forum_bg_color']) ? $modSettings['forum_bg_color'] : '#ffffff', ';
            --forum-nav-bg: ', !empty($modSettings['forum_nav_bg_color']) ? $modSettings['forum_nav_bg_color'] : '#3d6e32', ';
            --forum-nav-active-bg: ', !empty($modSettings['forum_nav_active_bg_color']) ? $modSettings['forum_nav_active_bg_color'] : '#2e5c24', ';
            --forum-nav-active-border: ', !empty($modSettings['forum_nav_active_border_color']) ? $modSettings['forum_nav_active_border_color'] : '#ffffff', ';
            --forum-hover: ', !empty($modSettings['forum_hover_color']) ? $modSettings['forum_hover_color'] : '#558c48', ';
            --forum-text: ', !empty($modSettings['forum_text_color']) ? $modSettings['forum_text_color'] : '#ffffff', ';
            --forum-link: ', !empty($modSettings['forum_link_color']) ? $modSettings['forum_link_color'] : '#3d6e32', ';
            --forum-body-font-size: ', !empty($modSettings['forum_body_font_size']) ? $modSettings['forum_body_font_size'] : '14px', ';
            --forum-nav-font-size: ', !empty($modSettings['forum_nav_font_size']) ? $modSettings['forum_nav_font_size'] : '14px', ';
            --forum-header-font-size: ', !empty($modSettings['forum_header_font_size']) ? $modSettings['forum_header_font_size'] : '24px', ';
        }
        body { background-color: var(--forum-bg) !important; font-size: var(--forum-body-font-size) !important; }
        .main_menu, .dropmenu, .dropmenu li ul { background: var(--forum-nav-bg) !important; }
        .dropmenu li a:hover, .dropmenu li:hover > a { background: var(--forum-hover) !important; text-decoration: none; }
        
        /* Aggressive override for active menu item */
        .dropmenu a.active, .dropmenu li a.active, .dropmenu li a.active:hover, .dropmenu li:hover a.active {
            background: var(--forum-nav-active-bg) !important;
            border: none !important;
            border-bottom: 3px solid var(--forum-nav-active-border) !important;
            box-shadow: none !important;
            text-shadow: none !important;
            color: var(--forum-text) !important;
        }
        
        .dropmenu li a { color: var(--forum-text) !important; font-size: var(--forum-nav-font-size) !important; }
        .catbg, .cat_bar { background: var(--forum-nav-bg) !important; }
        h1, h2, h3, h4, h5, h6 { color: var(--forum-link); }
        h1 { font-size: calc(var(--forum-header-font-size) * 1.5) !important; }
        h2 { font-size: calc(var(--forum-header-font-size) * 1.25) !important; }
        h3 { font-size: var(--forum-header-font-size) !important; }
        a { color: var(--forum-link); }

        /* --- Mini-Tailwind Utilities for Header --- */
        .flex { display: flex !important; }
        .flex-col { flex-direction: column !important; }
        .justify-between { justify-content: space-between !important; }
        .items-center { align-items: center !important; }
        .h-16 { height: 4rem !important; }
        .h-6 { height: 1.5rem !important; }
        .w-6 { width: 1.5rem !important; }
        .shrink { flex-shrink: 1 !important; }
        .overflow-hidden { overflow: hidden !important; }
        .pr-2 { padding-right: 0.5rem !important; }
        .px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
        @media (min-width: 640px) { .sm\:px-6 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; } }
        @media (min-width: 1024px) { .lg\:px-8 { padding-left: 2rem !important; padding-right: 2rem !important; } }
        .bg-white { background-color: #ffffff !important; }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05) !important; }
        .border-b { border-bottom: 1px solid #e5e7eb !important; }
        .border-gray-200 { border-color: #e5e7eb !important; }
        .font-bold { font-weight: 700 !important; }
        .font-medium { font-weight: 500 !important; }
        .text-sm { font-size: 0.875rem !important; line-height: 1.25rem !important; }
        .text-lg { font-size: 1.125rem !important; line-height: 1.75rem !important; }
        .text-xl { font-size: 1.25rem !important; line-height: 1.75rem !important; }
        .text-2xl { font-size: 1.5rem !important; line-height: 2rem !important; }
        .text-blue-600 { color: #2563eb !important; }
        .text-red-600 { color: #dc2626 !important; }
        .text-gray-500 { color: #6b7280 !important; }
        .text-gray-600 { color: #4b5563 !important; }
        .text-gray-900 { color: #111827 !important; }
        .uppercase { text-transform: uppercase !important; }
        .tracking-wider { letter-spacing: 0.05em !important; }
        .gap-4 { gap: 1rem !important; }
        .space-x-8 > * + * { margin-left: 2rem !important; }
        .space-x-4 > * + * { margin-left: 1rem !important; }
        .space-y-4 > * + * { margin-top: 1rem !important; }
        .ml-10 { margin-left: 2.5rem !important; }
        .pt-1 { padding-top: 0.25rem !important; }
        .border-b-2 { border-bottom-width: 2px !important; }
        .border-transparent { border-color: transparent !important; }
        .border-blue-500 { border-color: #3b82f6 !important; }
        .transition { transition: all 0.15s ease-in-out !important; }
        
        /* Mobile Drawer Utilities */
        .fixed { position: fixed !important; }
        .inset-0 { top: 0; right: 0; bottom: 0; left: 0 !important; }
        .inset-y-0 { top: 0; bottom: 0 !important; }
        .right-0 { right: 0 !important; }
        .z-50 { z-index: 9999 !important; }
        .bg-black\/50 { background-color: rgba(0,0,0,0.5) !important; }
        .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04) !important; }
        .max-w-xs { max-width: 20rem !important; }
        .w-full { width: 100% !important; }
        .mb-10 { margin-bottom: 2.5rem !important; }
        .p-2 { padding: 0.5rem !important; }
        .-mr-2 { margin-right: -0.5rem !important; }
        .border-t { border-top: 1px solid #e5e7eb !important; }
        .border-gray-100 { border-color: #f3f4f6 !important; }
        .rounded-full { border-radius: 9999px !important; }
        .bg-blue-100 { background-color: #dbeafe !important; }
        .px-1\.5 { padding-left: 0.375rem; padding-right: 0.375rem !important; }
        .py-0.5 { padding-top: 0.125rem; padding-bottom: 0.125rem !important; }
        .text-xs { font-size: 0.75rem !important; }

        /* Custom Nav Responsive Fixes */
        .wlc-logo-img { height: 40px !important; width: auto !important; }
        .wlc-nav-desktop-links { display: flex !important; align-items: center !important; }
        .wlc-nav-desktop-links a { text-decoration: none !important; }
        .wlc-nav-mobile-toggle { display: none !important; }

        @media (max-width: 1024px) {
            .wlc-nav-desktop-links { display: none !important; }
            .wlc-nav-mobile-toggle { display: flex !important; }
            #top_section, #inner_wrap { 
                position: absolute !important;
                left: -9999px !important;
                visibility: hidden !important; 
            }
            #upper_section { display: block !important; }
            #content_section { padding-top: 5px !important; }
            .wlc-logo-img { height: 32px !important; }
            .mobile-drawer-content {
                padding: 40px 20px !important;
            }
        }
    </style>
</head>';

	echo '
<body id="', $context['browser_body_id'], '" class="action_', !empty($context['current_action']) ? $context['current_action'] : (!empty($context['current_board']) ?
			'messageindex' : (!empty($context['current_topic']) ? 'display' : 'home')), !empty($context['current_board']) ? ' board_' . $context['current_board'] : '', '">
<div id="footerfix">';
}

/**
 * The upper part of the main template layer. This is the stuff that shows above the main forum content.
 */
function template_body_above()
{
	global $context, $settings, $scripturl, $txt, $modSettings, $maintenance;

	// Directory Header HTML adapted for SMF with Alpine.js
	echo '
    <nav x-data="{ mobileMenuOpen: false }" class="bg-white shadow-sm border-b border-gray-200" style="margin-bottom: 20px;">
        <div class="px-4 sm:px-6 lg:px-8" style="max-width: 98%; margin: 0 auto;">
            <div class="flex justify-between h-16">
                <!-- Left Side: Logo & Desktop Links -->
                <div class="flex items-center">
                    <div class="shrink overflow-hidden flex items-center pr-2">
                        <a href="/wlc/directory/public/" class="font-bold text-xl text-blue-600">
                            <img src="/wlc/directory/public/storage/logos/a8wBzkJNoFZcPHQSSfBDG2fgBJs5ApwWEorfH5r0.png" alt="WeLiveCrypto" class="wlc-logo-img">
                        </a>
                    </div>
                    <div class="wlc-nav-desktop-links space-x-8 ml-10">
                        <a href="/wlc/directory/public/" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Catalog
                        </a>
                        <a href="/wlc/forum/" class="inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-blue-700 transition duration-150 ease-in-out">
                            Forum
                        </a>
                    </div>
                </div>

                <!-- Right Side: Desktop Links & Mobile Menu Toggle -->
                <div class="flex items-center gap-4">
                    <div class="wlc-nav-desktop-links items-center space-x-4">';

	if ($context['user']['is_logged']) {
		echo '
                        <a href="/wlc/directory/public/submit" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                            + Submit Project
                        </a>
                        
                        <a href="/wlc/directory/public/admin" class="text-sm font-medium text-red-600 hover:text-red-800">
                            Admin Dashboard
                        </a>

                        <div class="text-sm text-gray-500">
                            Hello, ', $context['user']['name'], '
                        </div>

                        <a href="', $scripturl, '?action=logout;', $context['session_var'], '=', $context['session_id'], '" class="text-sm text-gray-600 hover:text-gray-900">
                            Logout
                        </a>';
	} else {
		echo '
                        <a href="', $scripturl, '?action=login" class="text-sm text-gray-600 hover:text-gray-900 uppercase font-bold tracking-wider">Login / Register</a>';
	}

	echo '
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="wlc-nav-mobile-toggle">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                <path x-show="mobileMenuOpen" style="display:none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay/Drawer -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-full"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-full"
             class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true" style="display:none">
            <!-- Backdrop -->
            <div @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/50"></div>
            
            <!-- Menu Content -->
            <div class="fixed inset-y-0 right-0 w-full max-w-xs bg-white shadow-xl flex flex-col mobile-drawer-content" style="display: flex !important; z-index: 9999 !important;">
                <div id="wlc-drawer-inner" style="flex: 1; display: flex; flex-direction: column; overflow-y: auto;">
                    <div class="flex items-center justify-between mb-10">
                        <span class="font-bold text-2xl text-blue-600">WLC Menu</span>
                        <button @click="mobileMenuOpen = false" class="text-gray-500 hover:text-gray-800 p-2 -mr-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col space-y-4">
                    <a href="/wlc/directory/public/" class="text-lg font-medium text-gray-900 hover:text-blue-600">Catalog</a>
                    <a href="/wlc/forum/" class="text-lg font-medium text-gray-900 hover:text-blue-600">Forum</a>
                    
                    <hr class="border-gray-100">';

	if ($context['user']['is_logged']) {
		echo '
                    <a href="', $scripturl, '?action=profile" class="text-lg font-medium text-gray-900 hover:text-blue-600">Profile</a>';

		if ($context['allow_pm']) {
			echo '
                    <a href="', $scripturl, '?action=pm" class="text-lg font-medium text-gray-900 hover:text-blue-600">
                        Messages ', !empty($context['user']['unread_messages']) ? ' <span class="bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full text-xs">' . $context['user']['unread_messages'] . '</span>' : '', '
                    </a>';
		}

		echo '
                    <a href="/wlc/directory/public/submit" class="text-lg font-medium text-blue-600">+ Submit Project</a>
                    <a href="/wlc/directory/public/admin" class="text-lg font-medium text-red-600">Admin Dashboard</a>
                    <div class="pt-4 text-sm text-gray-500 border-t border-gray-100 pt-4">Logged in as ', $context['user']['name'], '</div>
                    <a href="', $scripturl, '?action=logout;', $context['session_var'], '=', $context['session_id'], '" class="text-lg font-medium text-gray-600 hover:text-gray-900 pb-8">Logout</a>';
	} else {
		echo '
                    <a href="', $scripturl, '?action=login" class="text-lg font-medium text-gray-900 hover:text-blue-600 uppercase font-bold tracking-wider">Login / Register</a>';
	}

	echo '
                    </div>
                </div>
            </div>
        </div>
    </nav>';

	// Wrapper div now echoes permanently for better layout options. h1 a is now target for "Go up" links.
	echo '
	<div id="top_section">
		<div class="inner_wrap">';

	// If the user is logged in, display some things that might be useful.
	if ($context['user']['is_logged']) {
		// Firstly, the user's menu
		echo '
			<ul class="floatleft" id="top_info">
				<li>
					<a href="', $scripturl, '?action=profile"', !empty($context['self_profile']) ? ' class="active"' : '', ' id="profile_menu_top">';

		if (!empty($context['user']['avatar']))
			echo $context['user']['avatar']['image'];

		echo '<span class="textmenu">', $context['user']['name'], '</span></a>
					<div id="profile_menu" class="top_menu"></div>
				</li>';

		// Secondly, PMs if we're doing them
		if ($context['allow_pm'])
			echo '
				<li>
					<a href="', $scripturl, '?action=pm"', !empty($context['self_pm']) ? ' class="active"' : '', ' id="pm_menu_top">
						<span class="main_icons inbox"></span>
						<span class="textmenu">', $txt['pm_short'], '</span>', !empty($context['user']['unread_messages']) ? '
						<span class="amt">' . $context['user']['unread_messages'] . '</span>' : '', '
					</a>
					<div id="pm_menu" class="top_menu scrollable"></div>
				</li>';

		// Thirdly, alerts
		echo '
				<li>
					<a href="', $scripturl, '?action=profile;area=showalerts;u=', $context['user']['id'], '"', !empty($context['self_alerts']) ? ' class="active"' : '', ' id="alerts_menu_top">
						<span class="main_icons alerts"></span>
						<span class="textmenu">', $txt['alerts'], '</span>', !empty($context['user']['alerts']) ? '
						<span class="amt">' . $context['user']['alerts'] . '</span>' : '', '
					</a>
					<div id="alerts_menu" class="top_menu scrollable"></div>
				</li>';

		// A logout button for people without JavaScript.
		if (empty($settings['login_main_menu']))
			echo '
				<li id="nojs_logout">
					<a href="', $scripturl, '?action=logout;', $context['session_var'], '=', $context['session_id'], '">', $txt['logout'], '</a>
					<script>document.getElementById("nojs_logout").style.display = "none";</script>
				</li>';

		// And now we're done.
		echo '
			</ul>';
	}
	// Otherwise they're a guest. Ask them to either register or login.
	elseif (empty($maintenance)) {
		// Some people like to do things the old-fashioned way.
		if (!empty($settings['login_main_menu'])) {
			echo '
			<ul class="floatleft">
				<li class="welcome">', sprintf($txt[$context['can_register'] ? 'welcome_guest_register' : 'welcome_guest'], $context['forum_name_html_safe'], $scripturl . '?action=login', 'return reqOverlayDiv(this.href, ' . JavaScriptEscape($txt['login']) . ', \'login\');', $scripturl . '?action=signup'), '</li>
			</ul>';
		} else {
			echo '
			<ul class="floatleft" id="top_info">
				<li class="welcome">
					', sprintf($txt['welcome_to_forum'], $context['forum_name_html_safe']), '
				</li>
				<li class="button_login">
					<a href="', $scripturl, '?action=login" class="', $context['current_action'] == 'login' ? 'active' : 'open', '" onclick="return reqOverlayDiv(this.href, ' . JavaScriptEscape($txt['login']) . ', \'login\');">
						<span class="main_icons login"></span>
						<span class="textmenu">', $txt['login'], '</span>
					</a>
				</li>';

			if ($context['can_register'])
				echo '
				<li class="button_signup">
					<a href="', $scripturl, '?action=signup" class="', $context['current_action'] == 'signup' ? 'active' : 'open', '">
						<span class="main_icons regcenter"></span>
						<span class="textmenu">', $txt['register'], '</span>
					</a>
				</li>';

			echo '
			</ul>';
		}
	} else
		// In maintenance mode, only login is allowed and don't show OverlayDiv
		echo '
			<ul class="floatleft welcome">
				<li>', sprintf($txt['welcome_guest'], $context['forum_name_html_safe'], $scripturl . '?action=login', 'return true;'), '</li>
			</ul>';

	if (!empty($modSettings['userLanguage']) && !empty($context['languages']) && count($context['languages']) > 1) {
		echo '
			<form id="languages_form" method="get" class="floatright">
				<select id="language_select" name="language" onchange="this.form.submit()">';

		foreach ($context['languages'] as $language)
			echo '
					<option value="', $language['filename'], '"', isset($context['user']['language']) && $context['user']['language'] == $language['filename'] ? ' selected="selected"' : '', '>', str_replace('-utf8', '', $language['name']), '</option>';

		echo '
				</select>
				<noscript>
					<input type="submit" value="', $txt['quick_mod_go'], '">
				</noscript>
			</form>';
	}

	if ($context['allow_search']) {
		echo '
			<form id="search_form" class="floatright" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
				<input type="search" name="search" value="">&nbsp;';

		// Using the quick search dropdown?
		$selected = !empty($context['current_topic']) ? 'current_topic' : (!empty($context['current_board']) ? 'current_board' : 'all');

		echo '
				<select name="search_selection">
					<option value="all"', ($selected == 'all' ? ' selected' : ''), '>', $txt['search_entireforum'], ' </option>';

		// Can't limit it to a specific topic if we are not in one
		if (!empty($context['current_topic']))
			echo '
					<option value="topic"', ($selected == 'current_topic' ? ' selected' : ''), '>', $txt['search_thistopic'], '</option>';

		// Can't limit it to a specific board if we are not in one
		if (!empty($context['current_board']))
			echo '
					<option value="board"', ($selected == 'current_board' ? ' selected' : ''), '>', $txt['search_thisboard'], '</option>';

		// Can't search for members if we can't see the memberlist
		if (!empty($context['allow_memberlist']))
			echo '
					<option value="members"', ($selected == 'members' ? ' selected' : ''), '>', $txt['search_members'], ' </option>';

		echo '
				</select>';

		// Search within current topic?
		if (!empty($context['current_topic']))
			echo '
				<input type="hidden" name="sd_topic" value="', $context['current_topic'], '">';

		// If we're on a certain board, limit it to this board ;).
		elseif (!empty($context['current_board']))
			echo '
				<input type="hidden" name="sd_brd" value="', $context['current_board'], '">';

		echo '
				<input type="submit" name="search2" value="', $txt['search'], '" class="button">
				<input type="hidden" name="advanced" value="0">
			</form>';
	}

	echo '
		</div><!-- .inner_wrap -->
	</div><!-- #top_section -->';

	echo '
	<div id="wrapper">
		<div id="upper_section">
			<div id="inner_section">
				<div id="inner_wrap"', !$context['user']['is_logged'] ? ' class="hide_720"' : '', '>
					<div class="user">
						<time datetime="', smf_gmstrftime('%FT%TZ'), '">', $context['current_time'], '</time>';

	if ($context['user']['is_logged'])
		echo '
						<ul class="unread_links">
							<li>
								<a href="', $scripturl, '?action=unread" title="', $txt['unread_since_visit'], '">', $txt['view_unread_category'], '</a>
							</li>
							<li>
								<a href="', $scripturl, '?action=unreadreplies" title="', $txt['show_unread_replies'], '">', $txt['unread_replies'], '</a>
							</li>
						</ul>';

	echo '
					</div>';

	// Show a random news item? (or you could pick one from news_lines...)
	if (!empty($settings['enable_news']) && !empty($context['random_news_line']))
		echo '
					<div class="news">
						<h2>', $txt['news'], ': </h2>
						<p>', $context['random_news_line'], '</p>
					</div>';

	echo '
				</div>';

	// Show the menu here, according to the menu sub template, followed by the navigation tree.
	// Load mobile menu here
	echo '
				<a class="mobile_user_menu">
					<span class="menu_icon"></span>
					<span class="text_menu">', $txt['mobile_user_menu'], '</span>
				</a>
				<div id="main_menu">
					<div id="mobile_user_menu" class="popup_container">
						<div class="popup_window description">
							<div class="popup_heading">', $txt['mobile_user_menu'], '
								<a href="javascript:void(0);" class="main_icons hide_popup"></a>
							</div>
							', template_menu(), '
						</div>
					</div>
				</div>';

	theme_linktree();

	echo '
			</div><!-- #inner_section -->
		</div><!-- #upper_section -->';

	// The main content should go here.
	echo '
		<div id="content_section">
			<div id="main_content_section">';
}

/**
 * The stuff shown immediately below the main content, including the footer
 */
function template_body_below()
{
	global $context, $txt, $scripturl, $modSettings;

	echo '
			</div><!-- #main_content_section -->
		</div><!-- #content_section -->
	</div><!-- #wrapper -->
</div><!-- #footerfix -->';

	// Show the footer with copyright, terms and help links.
	echo '
	<div id="footer">
		<div class="inner_wrap">';

	// There is now a global "Go to top" link at the right.
	echo '
		<ul>
			<li class="floatright"><a href="', $scripturl, '?action=help">', $txt['help'], '</a> ', (!empty($modSettings['requireAgreement'])) ? '| <a href="' . $scripturl . '?action=agreement">' . $txt['terms_and_rules'] . '</a>' : '', ' | <a href="#top_section">', $txt['go_up'], ' &#9650;</a></li>
			<li class="copyright">', theme_copyright(), '</li>
		</ul>';

	// Show the load time?
	if ($context['show_load_time'])
		echo '
		<p>', sprintf($txt['page_created_full'], $context['load_time'], $context['load_queries']), '</p>';

	echo '
		</div>
	</div><!-- #footer -->';

}

/**
 * This shows any deferred JavaScript and closes out the HTML
 */
function template_html_below()
{
	// Load in any javascipt that could be deferred to the end of the page
	template_javascript(true);

	echo '
</body>
</html>';
}

/**
 * Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
 *
 * @param bool $force_show Whether to force showing it even if settings say otherwise
 */
function theme_linktree($force_show = false)
{
	global $context, $shown_linktree, $scripturl, $txt;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
				<div class="navigate_section">
					<ul>';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree) {
		echo '
						<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Don't show a separator for the first one.
		// Better here. Always points to the next level when the linktree breaks to a second line.
		// Picked a better looking HTML entity, and added support for RTL plus a span for styling.
		if ($link_num != 0)
			echo '
							<span class="dividers">', $context['right_to_left'] ? ' &#9668; ' : ' &#9658; ', '</span>';

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'], ' ';

		// Show the link, including a URL if it should have one.
		if (isset($tree['url']))
			echo '
							<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>';
		else
			echo '
							<span>' . $tree['name'] . '</span>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo ' ', $tree['extra_after'];

		echo '
						</li>';
	}

	echo '
					</ul>
				</div><!-- .navigate_section -->';

	$shown_linktree = true;
}

/**
 * Show the menu up top. Something like [home] [help] [profile] [logout]...
 */
function template_menu()
{
	global $context;

	echo '
					<ul class="dropmenu menu_nav">';

	// Note: Menu markup has been cleaned up to remove unnecessary spans and classes.
	foreach ($context['menu_buttons'] as $act => $button) {
		echo '
						<li class="button_', $act, '', !empty($button['sub_buttons']) ? ' subsections"' : '"', '>
							<a', $button['active_button'] ? ' class="active"' : '', ' href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', isset($button['onclick']) ? ' onclick="' . $button['onclick'] . '"' : '', '>
								', $button['icon'], '<span class="textmenu">', $button['title'], !empty($button['amt']) ? ' <span class="amt">' . $button['amt'] . '</span>' : '', '</span>
							</a>';

		// 2nd level menus
		if (!empty($button['sub_buttons'])) {
			echo '
							<ul>';

			foreach ($button['sub_buttons'] as $childbutton) {
				echo '
								<li', !empty($childbutton['sub_buttons']) ? ' class="subsections"' : '', '>
									<a href="', $childbutton['href'], '"', isset($childbutton['target']) ? ' target="' . $childbutton['target'] . '"' : '', isset($childbutton['onclick']) ? ' onclick="' . $childbutton['onclick'] . '"' : '', '>
										', $childbutton['title'], !empty($childbutton['amt']) ? ' <span class="amt">' . $childbutton['amt'] . '</span>' : '', '
									</a>';
				// 3rd level menus :)
				if (!empty($childbutton['sub_buttons'])) {
					echo '
									<ul>';

					foreach ($childbutton['sub_buttons'] as $grandchildbutton)
						echo '
										<li>
											<a href="', $grandchildbutton['href'], '"', isset($grandchildbutton['target']) ? ' target="' . $grandchildbutton['target'] . '"' : '', isset($grandchildbutton['onclick']) ? ' onclick="' . $grandchildbutton['onclick'] . '"' : '', '>
												', $grandchildbutton['title'], !empty($grandchildbutton['amt']) ? ' <span class="amt">' . $grandchildbutton['amt'] . '</span>' : '', '
											</a>
										</li>';

					echo '
									</ul>';
				}

				echo '
								</li>';
			}
			echo '
							</ul>';
		}
		echo '
						</li>';
	}

	echo '
					</ul><!-- .menu_nav -->';
}

/**
 * Generate a strip of buttons.
 *
 * @param array $button_strip An array with info for displaying the strip
 * @param string $direction The direction
 * @param array $strip_options Options for the button strip
 */
function template_button_strip($button_strip, $direction = '', $strip_options = array())
{
	global $context, $txt;

	if (!is_array($strip_options))
		$strip_options = array();

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value) {
		// As of 2.1, the 'test' for each button happens while the array is being generated. The extra 'test' check here is deprecated but kept for backward compatibility (update your mods, folks!)
		if (!isset($value['test']) || !empty($context[$value['test']])) {
			if (!isset($value['id']))
				$value['id'] = $key;

			$button = '
				<a class="button button_strip_' . $key . (!empty($value['active']) ? ' active' : '') . (isset($value['class']) ? ' ' . $value['class'] : '') . '" ' . (!empty($value['url']) ? 'href="' . $value['url'] . '"' : '') . ' ' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '>' . (!empty($value['icon']) ? '<span class="main_icons ' . $value['icon'] . '"></span>' : '') . '' . $txt[$value['text']] . '</a>';

			if (!empty($value['sub_buttons'])) {
				$button .= '
					<div class="top_menu dropmenu ' . $key . '_dropdown">
						<div class="viewport">
							<div class="overview">';
				foreach ($value['sub_buttons'] as $element) {
					if (isset($element['test']) && empty($context[$element['test']]))
						continue;

					$button .= '
								<a href="' . $element['url'] . '"><strong>' . $txt[$element['text']] . '</strong>';
					if (isset($txt[$element['text'] . '_desc']))
						$button .= '<br><span>' . $txt[$element['text'] . '_desc'] . '</span>';
					$button .= '</a>';
				}
				$button .= '
							</div><!-- .overview -->
						</div><!-- .viewport -->
					</div><!-- .top_menu -->';
			}

			$buttons[] = $button;
		}
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"' : ''), '>
			', implode('', $buttons), '
		</div>';
}

/**
 * Generate a list of quickbuttons.
 *
 * @param array $list_items An array with info for displaying the strip
 * @param string $list_class Used for integration hooks and as a class name
 * @param string $output_method The output method. If 'echo', simply displays the buttons, otherwise returns the HTML for them
 * @return void|string Returns nothing unless output_method is something other than 'echo'
 */
function template_quickbuttons($list_items, $list_class = null, $output_method = 'echo')
{
	global $txt;

	// Enable manipulation with hooks
	if (!empty($list_class))
		call_integration_hook('integrate_' . $list_class . '_quickbuttons', array(&$list_items));

	// Make sure the list has at least one shown item
	foreach ($list_items as $key => $li) {
		// Is there a sublist, and does it have any shown items
		if ($key == 'more') {
			foreach ($li as $subkey => $subli)
				if (isset($subli['show']) && !$subli['show'])
					unset($list_items[$key][$subkey]);

			if (empty($list_items[$key]))
				unset($list_items[$key]);
		}
		// A normal list item
		elseif (isset($li['show']) && !$li['show'])
			unset($list_items[$key]);
	}

	// Now check if there are any items left
	if (empty($list_items))
		return;

	// Print the quickbuttons
	$output = '
		<ul class="quickbuttons' . (!empty($list_class) ? ' quickbuttons_' . $list_class : '') . '">';

	// This is used for a list item or a sublist item
	$list_item_format = function ($li) {
		$html = '
			<li' . (!empty($li['class']) ? ' class="' . $li['class'] . '"' : '') . (!empty($li['id']) ? ' id="' . $li['id'] . '"' : '') . (!empty($li['custom']) ? ' ' . $li['custom'] : '') . '>';

		if (isset($li['content']))
			$html .= $li['content'];
		else
			$html .= '
				<a href="' . (!empty($li['href']) ? $li['href'] : 'javascript:void(0);') . '"' . (!empty($li['javascript']) ? ' ' . $li['javascript'] : '') . '>
					' . (!empty($li['icon']) ? '<span class="main_icons ' . $li['icon'] . '"></span>' : '') . (!empty($li['label']) ? $li['label'] : '') . '
				</a>';

		$html .= '
			</li>';

		return $html;
	};

	foreach ($list_items as $key => $li) {
		// Handle the sublist
		if ($key == 'more') {
			$output .= '
			<li class="post_options">
				<a href="javascript:void(0);">' . $txt['post_options'] . '</a>
				<ul>';

			foreach ($li as $subli)
				$output .= $list_item_format($subli);

			$output .= '
				</ul>
			</li>';
		}
		// Ordinary list item
		else
			$output .= $list_item_format($li);
	}

	$output .= '
		</ul><!-- .quickbuttons -->';

	// There are a few spots where the result needs to be returned
	if ($output_method == 'echo')
		echo $output;
	else
		return $output;
}

/**
 * The upper part of the maintenance warning box
 */
function template_maint_warning_above()
{
	global $txt, $context, $scripturl;

	echo '
	<div class="errorbox" id="errors">
		<dl>
			<dt>
				<strong id="error_serious">', $txt['forum_in_maintenance'], '</strong>
			</dt>
			<dd class="error" id="error_list">
				', sprintf($txt['maintenance_page'], $scripturl . '?action=admin;area=serversettings;' . $context['session_var'] . '=' . $context['session_id']), '
			</dd>
		</dl>
	</div>';
}

/**
 * The lower part of the maintenance warning box.
 */
function template_maint_warning_below()
{

}

?>