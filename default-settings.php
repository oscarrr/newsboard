<?php
//Declare global variables, because hook system of WP requires it
global $default_main, $default_app;
//Default settings
$default_main = array(
'feed'	=>	'rss',
'pickcategories'	=>	array('0'	=>	'1'),
'order_type'    =>  'date',
'order' =>  'DESC',
'rss_link'	=>	'http://newsboardplugin.com/feed/sample.rss',
'board_news_fit'	=>	'5',
'text_from'	=>	'excerpt',
'scroll_period'	=>	'3500',
'transition_time'	=>	'700',
'open_links_in'	=>	'self'
);
$default_app = array(
'title_cut_after'	=>	'42',
'date_format_string'	=>	'l, F j',
'text_cut_after'	=>	'50',
'read_more_string'	=>	'[read more]',
'new_width'	=>	'250',
'new_height'	=>	'76',
'thumbnail_width'	=>	'64',
'thumbnail_height'	=>	'64',
'title_cutting_rule'	=>	'symbols',
'text_cutting_rule'	=>	'symbols',
'theme_select' => 'Default',
'show_text'	=>	'',
'show_date'	=>	'1',
'show_thumbnails'	=>	'1',
'bar_height'	=>	'25',
'luxury_touch'	=>	'1',
);