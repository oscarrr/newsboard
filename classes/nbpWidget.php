<?php
/**
 * Widget Class
 * @copyright (c) 2015, NewsBoard Plugin
 * @package WordPress
 * @subpackage NewsBoard Plugin FREE
 */
class nbpWidget extends WP_Widget
{
    public $nbp_app;
    
    public function __construct()
    {   
        global $nbp_app;
        $this->nbp_app = $nbp_app;
        $widget_ops = array('classname' => 'nbpWidget', 'description' => 'Shows your posts or rss feed in a nice ticker');
        parent::__construct('nbpWidget', 'NewsBoard ticker', $widget_ops);
    }
    
    public function form($instance)
    {
        $instance = wp_parse_args((array) $instance, array( 'title' => '' ));
        $title = $instance['title'];
        echo "<p><label for=\"" . $this->get_field_id('title') . "\">Title: <input class=\"widefat\" id=\"" . $this->get_field_id('title') . "\" name=\"" . $this->get_field_name('title') . "\" type=\"text\" value=\"" . attribute_escape($title) . "\" /></label></p>";
    }
    
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }
    
    public function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);
        
        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        
        if (!empty($title))
          echo $before_title . $title . $after_title;
        
        echo $this->nbp_app->widgetShortcodeInit();
        echo $after_widget;
    }
}