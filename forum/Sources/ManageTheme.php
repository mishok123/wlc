<?php

if (!defined('SMF'))
	die('Hacking attempt...');

function ManageTheme()
{
	global $context, $txt, $scripturl, $modSettings, $smcFunc;

	isAllowedTo('admin_forum');

	loadTemplate('ManageTheme');

	$context['page_title'] = 'Forum Theme Settings';
	$context['sub_template'] = 'main';

	// Save Settings
	if (isset($_GET['sa']) && $_GET['sa'] == 'save')
	{
		checkSession();

        // Prepare settings array
        $newSettings = array(
            'forum_bg_color' => !empty($_POST['forum_bg_color']) ? $_POST['forum_bg_color'] : '',
            'forum_nav_bg_color' => !empty($_POST['forum_nav_bg_color']) ? $_POST['forum_nav_bg_color'] : '',
            'forum_hover_color' => !empty($_POST['forum_hover_color']) ? $_POST['forum_hover_color'] : '',
            'forum_text_color' => !empty($_POST['forum_text_color']) ? $_POST['forum_text_color'] : '',
            'forum_header_text_color' => !empty($_POST['forum_header_text_color']) ? $_POST['forum_header_text_color'] : '',
            'forum_link_color' => !empty($_POST['forum_link_color']) ? $_POST['forum_link_color'] : '',
            'forum_nav_active_bg_color' => !empty($_POST['forum_nav_active_bg_color']) ? $_POST['forum_nav_active_bg_color'] : '',
            'forum_nav_active_border_color' => !empty($_POST['forum_nav_active_border_color']) ? $_POST['forum_nav_active_border_color'] : '',
            'forum_body_font_size' => !empty($_POST['forum_body_font_size']) ? $_POST['forum_body_font_size'] : '',
            'forum_nav_font_size' => !empty($_POST['forum_nav_font_size']) ? $_POST['forum_nav_font_size'] : '',
            'forum_header_font_size' => !empty($_POST['forum_header_font_size']) ? $_POST['forum_header_font_size'] : '',
        );

        updateSettings($newSettings);
        redirectexit('action=forumtheme');
	}
}
?>
