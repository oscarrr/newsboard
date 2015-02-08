<?php
/**
 * Plugin Initialisation Class. Filters actions
 * @copyright (c) 2015, NewsBoard Plugin
 * @package WordPress
 * @subpackage NewsBoard Plugin FREE
 */
class nbpInit extends nbpCore
{
    
    /**
     * Class Construct. Filters the actions depending on license key.
     * @param String $plugin_basename - The Plugin Basename
     * @uses add_filter() - Hooks a function to a specific filter action
     * @uses add_action() - Hooks a function on to a specific action
     * @uses add_shortcode() - Adds a hook for a shortcode tag
     */
    public function __construct($main_file)
    {
        $plugin_basename = plugin_basename($main_file);
        parent::__construct($plugin_basename);
        add_filter('plugin_action_links_' . $plugin_basename, array($this, 'addSettingsLink')); 
        add_action('init', array($this, 'registerTinymce'));
        add_action('admin_menu', array($this, 'adminInit'));
        add_action('wp_enqueue_scripts', array($this, 'RenderCssJs')); 
        add_action('wp_head', array($this, 'wpHead'));
        add_action('widgets_init', array($this, 'widgetDo'));
        add_shortcode('NewsBoard', array($this, 'widgetShortcodeInit'));
        register_activation_hook($main_file, array( $this, 'onPluginActivate'));
    }
    
    /**
     * This function is triggered when the plugin activates.
     */
    public function onPluginActivate()
    {
		$this->init();
    	$this->updateCSS();
    }
    
    /**
     * Registers TinyMCE on init by filters.
     * @uses add_filter() - Hooks a function to a specific filter action
     */
    public function registerTinymce() 
    { 
        if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
            return;
        if ( get_user_option('rich_editing') == 'true') 
        {
            add_filter('mce_buttons', array($this, 'filterTinymceButton'));
            add_filter('mce_external_plugins', array($this, 'filterTinymcePlugin'));
        }
    }
    
    /**
     * Adds our tinyMCE button.
     * @param Array $buttons - Current tinyMCE buttons
     * @return Array - All buttons, including our
     */
    public function filterTinymceButton($buttons) 
    {
        array_push($buttons, '|', 'NewsBoard' );
        return $buttons;
    }
    
    /**
     * Adds necessary JavaScript for our tinyMCE button.
     * @param Array $plugin_array
     * @return Array
     */
    public function filterTinymcePlugin($plugin_array) 
    {
        $news_board_path_mce = trailingslashit(rtrim(WP_PLUGIN_URL, '/') . '/newsboard');
        $plugin_array['contextmenu'] = $news_board_path_mce . 'mce/contextmenu/editor_plugin.js';
        $plugin_array['NewsBoard'] = $news_board_path_mce . 'js/editor_plugin.js';
        return $plugin_array;
    }
    
    /**
     * Triggers when we are in admin panel.
     */
    public function adminInit()
    {
        $this->adminMenu();
    }
    
    /**
     * Loads JavaScript and CSS files for the ticker.
     * @uses wp_enqueue_script - The safe and recommended method of adding JavaScript to a WordPress generated page
     * @uses wp_enqueue_style - The safe and recommended method of adding Style to a WordPress generated page
     */
    public function renderCssJs()
    {
        global $wp_version;
        
        $wp_native_scripts = new WP_Scripts();
        $jquery_src = (version_compare('3.6', $wp_version) === 1) ? $wp_native_scripts->registered['jquery']->src : $wp_native_scripts->registered['jquery-core']->src;

        wp_register_script( 'newsboard-jquery', $jquery_src );
        wp_register_script( 'newsboard-jquery-ui-core', $wp_native_scripts->registered['jquery-ui-core']->src, array('newsboard-jquery') );
        wp_register_script( 'newsboard-jquery-effects-core', $wp_native_scripts->registered['jquery-effects-core']->src, array('newsboard-jquery', 'newsboard-jquery-ui-core') );
        
        wp_enqueue_script( 'newsboard-jquery' );
        wp_enqueue_script( 'newsboard-jquery-ui-core' );
        wp_enqueue_script( 'newsboard-jquery-effects-core' );
        wp_enqueue_script( 'nbpFrontEnd-js', plugin_dir_url("newsboard") . "newsboard/js/nbpFrontEnd.js", array('newsboard-jquery', 'newsboard-jquery-ui-core', 'newsboard-jquery-effects-core') );
        wp_enqueue_script( 'nbpAnimate-js', plugin_dir_url("newsboard") . "newsboard/js/nbpAnimate.js", array('newsboard-jquery', 'newsboard-jquery-ui-core', 'newsboard-jquery-effects-core', 'nbpFrontEnd-js') );
        
        $file_path_css = $this->pluginPathFull . "render/newsboard.css";
        wp_enqueue_style( "NewsBoardPlugin", plugin_dir_url("newsboard") . "newsboard/render/newsboard.css?" . md5_file($file_path_css), false, 'free' );
    }
    
    /**
     * Calls wpHeadJsInit() to print our custom JavaScript.
     */
    public function wpHead()
    {
        $this->wpHeadJsInit();
    }
    
    /**
     * Registers widgets for the active items.
     * @uses register_widget() - Registers a widget
     */
    public function widgetDo()
    {
        register_widget('nbpWidget');
    } 
    
    /**
     * Adds links to our plugin in plugin page.
     * @param Array $links - All links
     * @return Array - All links with our custom one
     */
    public function addSettingsLink($links) 
    { 
        $settings_link = '<a href="admin.php?page=nbpCore.php" title="NewsBoard Plugin Settings">Settings</a>'; 
        array_unshift($links, $settings_link); 
        return $links; 
    }
    
}