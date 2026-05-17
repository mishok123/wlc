<?php

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<div class="cat_bar">
		<h3 class="catbg">Forum Theme Settings</h3>
	</div>
	<div class="windowbg">
		<span class="topslice"><span></span></span>
		<div class="content">
			<form action="', $scripturl, '?action=forumtheme;sa=save" method="post" accept-charset="', $context['character_set'], '">
                <dl class="settings">
                    <dt>
                        <strong>Global Background Color</strong><br>
                        <span class="smalltext">Background color for the entire page body.</span>
                    </dt>
                    <dd>
                        <input type="color" name="forum_bg_color" value="', !empty($modSettings['forum_bg_color']) ? $modSettings['forum_bg_color'] : '#ffffff', '">
                    </dd>

                    <dt>
                        <strong>Navigation Bar Background</strong><br>
                        <span class="smalltext">Background color for the main menu bar.</span>
                    </dt>
                    <dd>
                        <input type="color" name="forum_nav_bg_color" value="', !empty($modSettings['forum_nav_bg_color']) ? $modSettings['forum_nav_bg_color'] : '#3d6e32', '">
                    </dd>

                    <dt>
                        <strong>Navigation Hover Color</strong><br>
                        <span class="smalltext">Color when hovering over menu items.</span>
                    </dt>
                    <dd>
                        <input type="color" name="forum_hover_color" value="', !empty($modSettings['forum_hover_color']) ? $modSettings['forum_hover_color'] : '#558c48', '">
                    </dd>

                    <dt>
                        <strong>Navigation Text Color</strong><br>
                        <span class="smalltext">Color of the text in the navigation bar.</span>
                    </dt>
                    <dd>
                        <input type="color" name="forum_text_color" value="', !empty($modSettings['forum_text_color']) ? $modSettings['forum_text_color'] : '#ffffff', '">
                    </dd>

                    <dt>
                        <strong>Header/Link Color</strong><br>
                        <span class="smalltext">Main accent color for links and headers.</span>
                    </dt>
                    <dd>
                        <input type="color" name="forum_link_color" value="', !empty($modSettings['forum_link_color']) ? $modSettings['forum_link_color'] : '#3d6e32', '">
                    </dd>

                    <dt>
                        <strong>Active Navigation Background</strong><br>
                        <span class="smalltext">Background color for the active menu item.</span>
                    </dt>
                    <dd>
                        <input type="color" name="forum_nav_active_bg_color" value="', !empty($modSettings['forum_nav_active_bg_color']) ? $modSettings['forum_nav_active_bg_color'] : '#2e5c24', '">
                    </dd>

                    <dt>
                        <strong>Active Navigation Border Color</strong><br>
                        <span class="smalltext">Border color for the active menu item.</span>
                    </dt>
                    <dd>
                        <input type="color" name="forum_nav_active_border_color" value="', !empty($modSettings['forum_nav_active_border_color']) ? $modSettings['forum_nav_active_border_color'] : '#ffffff', '">
                    </dd>

                    <dt>
                        <strong>Body Font Size</strong><br>
                        <span class="smalltext">Base font size for the forum (e.g., 14px, 1rem).</span>
                    </dt>
                    <dd>
                        <input type="text" name="forum_body_font_size" value="', !empty($modSettings['forum_body_font_size']) ? $modSettings['forum_body_font_size'] : '14px', '">
                    </dd>

                    <dt>
                        <strong>Navigation Font Size</strong><br>
                        <span class="smalltext">Font size for menu items.</span>
                    </dt>
                    <dd>
                        <input type="text" name="forum_nav_font_size" value="', !empty($modSettings['forum_nav_font_size']) ? $modSettings['forum_nav_font_size'] : '14px', '">
                    </dd>

                    <dt>
                        <strong>Header Font Size</strong><br>
                        <span class="smalltext">Base size for headers (h1-h6 will scale relative to this).</span>
                    </dt>
                    <dd>
                        <input type="text" name="forum_header_font_size" value="', !empty($modSettings['forum_header_font_size']) ? $modSettings['forum_header_font_size'] : '24px', '">
                    </dd>
                </dl>
                <div class="righttext">
                    <input type="submit" value="Save Settings" class="button_submit">
                    <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '">
                </div>
			</form>
		</div>
		<span class="botslice"><span></span></span>
	</div>';
}
?>
