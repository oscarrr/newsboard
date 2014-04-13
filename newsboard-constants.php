<?php
//Hide warnings and errors
error_reporting(0);
//Declare global variables, because hook system of WP requires it
global $nbp_options_appearance_all, $nbp_appearance_checkboxes, $nbp_appearance_radioboxes, $nbp_options_main_all,
$nbp_main_radioboxes, $nbp_transition_types, $nbp_order_types;
//Theme options array with regex and error message
$nbp_options_appearance_all = array(
    'title_cut_after' => array('pattern'=>'^[0-9]+$', 'msg'=>'Please enter numbers only.'),
    'date_format_string' => array('pattern'=>'^.+$', 'msg'=>'Please enter a valid date format string.'),
    'text_cut_after' => array('pattern'=>'^[0-9]+$', 'msg'=>'Please enter numbers only.'),
    'read_more_string' => 'none',
    'new_width' => array('pattern'=>'^[0-9]+$', 'msg'=>'Please enter numbers only.'),
    'new_height' => array('pattern'=>'^[0-9]+$', 'msg'=>'Please enter numbers only.'),
    'thumbnail_width' => array('pattern'=>'^[0-9]+$', 'msg'=>'Please enter numbers only.'),
    'thumbnail_height' => array('pattern'=>'^[0-9]+$', 'msg'=>'Please enter numbers only.'),
    'theme_select' => 'none',
    'text_cutting_rule' =>  'none',
    'title_cutting_rule' =>  'none',
    'show_text' => 'none',
    'show_date' => 'none',
    'show_thumbnails' => 'none',
    'bar_height' => array('pattern'=>'^[0-9]+$', 'msg'=>'Please enter numbers only.'),
    'luxury_touch' => 'none'
);
//Theme options checkboxes
$nbp_appearance_checkboxes = array('show_date', 'show_thumbnails', 'show_text', 'luxury_touch');
//Theme oprions radioboxes
$nbp_appearance_radioboxes = array('text_cutting_rule', 'title_cutting_rule');
//Settings array with regex and error message
$nbp_options_main_all = array(
    'feed' => 'none',
    'pickcategories' => 'none',
    'order_type'    =>  'none',
    'order' =>  'none',
    'rss_link' => array('pattern'=>'^http\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(/\S*)?$', 'msg'=>'Please enter valid URL address.'),
    'board_news_fit' => array('pattern'=>'^[0-9]+$', 'msg'=>'Please enter numbers only.'), 
    'text_from' => 'none',
    'scroll_period' => array('pattern'=>'^[0-9]+$', 'msg'=>'Please enter numbers only.'),
    'transition_time' => array('pattern'=>'^[0-9]+$', 'msg'=>'Please enter numbers only.'),
    'open_links_in' => 'none'
);
//Settings radioboxes
$nbp_main_radioboxes = array('autoscroll_behaviour', 'feed', 'text_from', 'open_links_in', 'order');
//Settings - Transition types
$nbp_transition_types = array('none'=>'linear', 'normal ease'=>'easeOutQuad', 'ease back'=>'easeInOutBack', 'bounce'=>'easeOutBounce');
//Settings - Posts Order By
$nbp_order_types = array('Author' => 'author', 'Date' => 'date', 'Title' => 'title', 'Modified' => 'modified', 'Menu Order' => 'menu_order', 'Chance' => 'rand', 'Comment count' => 'comment_count');
//Validation post to JavaScript
if(isset($_POST['data']))
{
    $valCommand = json_decode($_POST['data'], true);
    if($valCommand['command'] == 'getElements')
    {
        $valArray = $nbp_options_main_all + $nbp_options_appearance_all;
        foreach ($valArray as $key => $value) 
        {
            if($value == 'none')
                unset($valArray[$key]);
        }
        echo json_encode($valArray);
    }
}