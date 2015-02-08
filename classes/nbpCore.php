<?php
/**
 * Plugin Core Class. Navigates everything
 * @copyright (c) 2015, NewsBoard Plugin
 * @package WordPress
 * @subpackage NewsBoard Plugin FREE
 */
class nbpCore
{
    public $main, $app, $render, $mainOptName, $themeOptName, $menuName, $menuTitle, $mainName, $mainTitle, $themesName, $themesTitle, $testViewName, $testViewTitle, $uploadsDir, $pluginSlug, $slug, $upgrade;
    public $pluginFolderName, $pluginPath, $pluginPathFull, $logoPath, $errorFlag, $errorData, $hookName, $hookThemes, $hookTestView, $hookUpgrade, $pageUrl, $changeMsgMain, $changeMsgApp, $changeMsgKey, $flag_app_update, $flag_main_update;
    
    /**
     * Class Construct. Includes libraries.
     * @param String $plugin_slug - Plugin Folder Name
     */
    public function __construct($plugin_slug)
    {
        $this->getConstants($plugin_slug);
        
        require_once $this->pluginPathFull . 'classes/nbpUpgrade.php';
        require_once $this->pluginPathFull . 'classes/nbpMain.php';
        require_once $this->pluginPathFull . 'classes/nbpAppearance.php';
        require_once $this->pluginPathFull . 'classes/nbpRender.php';
        require_once $this->pluginPathFull . 'classes/nbpTemplate.php';
        
        $this->errorCheck();
        
        $this->upgrade = new nbpUpgrade($this->pluginOptName, $this->checkActPath, $this->checkUpdPath);
              
    }
    
    /**
     * Gets the current version of the plugin.
     * @uses get_plugins() WP Function which reads Plugin Description
     * @return String - Plugin Version
     */
    public function getPluginVer()
    {
        if ( ! function_exists( 'get_plugins' ) )
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $plugin_folder = get_plugins('/' . $this->slug);
        return $plugin_folder[$this->slug . '.php']['Version'];
    }
    
    /**
     * Gets the URI of the plugin.
     * @uses get_plugins() WP Function which reads Plugin Description
     * @return String - Plugin URI
     */
    public function getPluginUri()
    {
        if ( ! function_exists( 'get_plugins' ) )
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $plugin_folder = get_plugins('/' . $this->slug);
        return $plugin_folder[$this->slug . '.php']['PluginURI'];
    }
    
    /**
     * Creates instances of the classes, which construct admin menu, and checks for posted elements
     */
    public function init()
    {
        $this->app = new nbpAppearance($this->themeOptName, $this->pluginPath);
        $this->main = new nbpMain($this->mainOptName, $this->pluginPath);
        
        if( isset($_POST['nbp_app_settings_update']) ) 
        {
            $this->flag_app_update = 1;
    		$this->app->setElements();
    		$this->changeMsgApp = '<br /><div id="message" class="updated fade"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
        }
        else
        {
            $this->flag_app_update = 0;
            $this->changeMsgApp = '';
        }
        
        if( isset($_POST['nbp_upgrade_settings']) ) 
        {
    		if(isset($_POST['nbp_license_key']))
            {
                $this->upgrade->setElements('license_key', $_POST['nbp_license_key']);
                $this->upgrade->checkUpgradeKey();
                $this->upgrade->mode = $this->upgrade->getElements('mode'); 
                $this->changeMsgKey = $this->upgrade->upgradeMessage;
                
                if($this->upgrade->mode == 'good_key' || $this->upgrade->mode == 'expired_key')
                {
                    //UPGRADE TO PRO
                    $pluginFile = $this->pluginFolderName . '/' . $this->pluginFolderName . '.php';
            
                    if(is_plugin_active($pluginFile) && current_user_can('activate_plugins'))
                    {
                        //get zip file url
                        $packageUrl = $this->upgrade->getUpgradePackage();
                            
                        //download the temporary file
                        $temp_file = download_url($packageUrl);
                            
                        if ( !is_wp_error($temp_file) )
                        {
                            //Deactivate the plugin
                            deactivate_plugins($pluginFile);
                                
                            $zip_file = $temp_file . '.zip';
                                
                            //rename .tmp to .zip
                            rename($temp_file, $zip_file );
                                
                            //needed by unzip_file()
                            WP_Filesystem();
                                
                            //unzip the file
                            $unzipped = unzip_file($zip_file, WP_PLUGIN_DIR);
                            
                            if ( is_bool( $unzipped ) && $unzipped == true )
                            {   
                                //remove zip file
                                unlink( $zip_file );
                                
                                //rewrite the cache to initialize the pro version
                                $proVersionSlug = $this->slug . '-pro';
                                $proVersionInfo = get_plugins( '/' . $proVersionSlug );
                                $proVersionPath = $proVersionSlug . '/' . $proVersionSlug . '.php';
                                
                                
                                $proVersionInfo = isset( $proVersionInfo[$proVersionSlug . '.php'] ) ? $proVersionInfo[$proVersionSlug . '.php'] : null;
                                    
                                if ( is_null( $proVersionInfo ) )
                                {
                                    wp_redirect( admin_url() . 'plugins.php' );
                                    exit;
                                }
                                
                                $cache_plugins = wp_cache_get( 'plugins', 'plugins' );

                                if ( !empty( $cache_plugins ) )
                                {
                                    $cache_plugins[''][$proVersionPath] = $proVersionInfo;
                                    wp_cache_set( 'plugins', $cache_plugins, 'plugins' );
                                }
                                
                                
                                //activate the plugin
                                $activationResult = activate_plugins( $this->pluginFolderName . '-pro/' . $this->pluginFolderName . '-pro.php' ); 
                                 
                                if ( is_wp_error( $activationResult ) )
                                {
                                    $this->changeMsgKey = 'We couldn\'t activate the plugin, you should do it manually!';
                                } 
                                else
                                {
                                    wp_redirect(admin_url() . 'admin.php?page=nbpCore.php');
                                    exit;
                                }
                                
                            } else {
                                
                                if( is_wp_error( $unzipped ) )
                                    $this->changeMsgKey = 'Error: ' . implode( '<br/>Error: ', $unzipped->get_error_messages() );
                                else
                                    $this->changeMsgKey = 'Error: Unzipping filed.';
                                        
                                activate_plugins( $pluginFile );
                            }
                            
                        } else {
                                $this->changeMsgKey = 'Error: ' . implode('<br/>Error: ', $temp_file->get_error_messages());
                        }
                    }
                }
                
                $this->changeMsgKey = '<br /><div id="message" class="error fade"><p><strong>' . __( $this->changeMsgKey ) . '</strong></p></div>';
            }
        } else
            $this->changeMsgKey = '';
            
        $this->app->getElements();
        
        if( isset($_POST['nbp_main_settings_update']) ) 
        {
            $this->flag_main_update = 1;
    		$this->main->setElements();
    		$this->changeMsgMain = '<div id="message" class="updated fade"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
    	}
        else
        {
            $this->flag_main_update = 0;
            $this->changeMsgMain = '';
        }
            
        $this->main->getElements();
        $this->render = new nbpRender($this->app->nbp_options_appearance + $this->main->nbp_options_main, $this->pluginPath, $this->getPluginVer());
        $this->render->doTheMagic();
        
        if($this->flag_app_update == 1 || $this->flag_main_update == 1)
            $this->updateCss();       
    }
    
    /**
     * Updates CSS files.
     */
    public function updateCss()
    {
        $render = new nbpTemplate();
        $render->assign($this->render->tA);
        $renderCSS = $render->displayINRender($this->pluginPathFull . "render/newsboard-render-css.tpl");
        if(!is_writable($this->pluginPathFull . "render"))
            chmod($this->pluginPathFull . "render", 0777);
        $error_check = file_put_contents($this->pluginPathFull . "render/newsboard.css" , utf8_encode($renderCSS));
        if($error_check == false)
        {
            $msg = " The CSS File is not refreshed, probably because of folder or file permission issue. Please change the folder: 'render' and the file: 'newsboard.css' permission for writing!";
            $this->changeMsgMain .= $msg;
            $this->changeMsgApp .= $msg;            
        }
    }
    /**
     * Performs check for errors. If there's any errors switches to default settings
     * @global Array $default_main - Default Settings Array
     * @global Array $default_app - Default Theme (Appearance) Array
     * @uses get_option(), update_option() - Gets options from DB and if it's necessary updates them to default values
     */
    public function errorCheck()
    {
        global $default_main, $default_app;
        
        $counter = 0;
        if( !get_option($this->mainOptName) || !get_option($this->themeOptName) ) 
        {
            $counter++;
            update_option($this->mainOptName, $default_main);
            update_option($this->themeOptName, $default_app);
        } 
        
        if($counter > 0)
        {
            $this->errorFlag = 1;
            $this->errorData['stay'] = 5000;
            $this->errorData['plugin_dir'] = $this->pluginPath;
            $this->errorData['text'] = "Something is worng with the database. <br />Switching to default settings...";
        }
        else
        {
            $this->errorFlag = 0;
            $this->errorData = array();
        }
                    
    }
    
    /**
     * Assigns all properties of the class.
     * @param String $plugin_slug - Plugin folder name
     */
    private function getConstants($plugin_slug)
    {
        $this->pluginSlug = $plugin_slug;
        list ($t1, $t2) = explode('/', $plugin_slug);
        $this->slug = str_replace('.php', '', $t2);
        $this->pluginFolderName = "newsboard";
        $this->pluginPath = plugin_dir_url($this->pluginFolderName) . $this->pluginFolderName . "/";
        $this->pluginPathFull = WP_PLUGIN_DIR . "/" . $this->pluginFolderName . "/";
        $this->uploadsDir = wp_upload_dir();
        $this->pluginOptName = 'newsboard-settings';
        $this->mainOptName = "newsboard-main";
        $this->themeOptName = "newsboard-appearance";
        $this->menuName = "NewsBoard";
        $this->mainName = "Settings";
        $this->themesName = "Themes";
        $this->testViewName = "View";
        $this->menuTitle = $this->menuName . " " . $this->mainName;
        $this->mainTitle = $this->menuTitle;
        $this->themesTitle = $this->menuName . " " . $this->themesName;
        $this->testViewTitle = $this->menuName . " " . $this->testViewName;
        $this->menuCapability = "manage_options";
        $this->logoPath = $this->pluginPath . "images/news-board-icon.png";
        $this->shortCodeName = "NewsBoard";
        $this->checkActPath = $this->getPluginUri() . "/update/keycheck/";
        $this->checkUpdPath = $this->getPluginUri() . "/update/get/";
        $this->checkAutoUpdPath = $this->getPluginUri() . "/update/free/";
        $this->errorFlag = 0;
        $this->errorData = array();
    }
    
    /**
     * Prints the plugin javascript in WP Header
     */
    public function wpHeadJsInit()
    {
        $sign = '$nbp';
        $this->init();
        $output .= "$sign('#newsboard_plugin_holder').nbpAnimate({speed: " . $this->render->tA['transition_time'] . ", stay: " . $this->render->tA['scroll_period'] . ", margin_bottom: " . $this->render->tA['margin_bottom_holder'] . ", max_news: " . $this->render->tA['number_of_news'] . ", board_fit: " . $this->render->tA['board_news_fit'] . ", invisible_news_top: " . $this->render->tA['invisible_news_top'] . "});\n";
        print 
        "\n<script type=\"text/javascript\">
            $sign(document).ready(function () {
                " . $output . "
            });
        </script>";         
    }
    
    /**
     * Adds the links in admin menu and triggers all necessary events.
     * @uses add_menu_page() - WP Function to add links in admin menu
     * @uses add_action() - Hooks a function on to a specific action
     */
    public function adminMenu()
    {
        $this->init();
        
        add_menu_page(__($this->menuTitle), $this->menuName, $this->menuCapability, basename(__FILE__), array($this, "mainView"), $this->logoPath);
        $this->hookMain = add_submenu_page(basename(__FILE__),  $this->menuName . " " .  __($this->mainName), __($this->mainName), $this->menuCapability, basename(__FILE__) , array($this, "mainView")); 
        $this->hookItems = add_submenu_page(basename(__FILE__),  $this->menuName . " " .  ($this->itemsName), __('Items'), $this->menuCapability, 'NewsBoardItems' , array($this, "itemsView")); 
        $this->hookThemes = add_submenu_page(basename(__FILE__), $this->menuName . " " .  __($this->themesName), __($this->themesName), $this->menuCapability, str_replace(" ", "", $this->themesTitle), array($this, "themesView"));
        $this->hookTestView = add_submenu_page(basename(__FILE__), $this->menuName . " " .  __($this->testViewName), __($this->testViewName), $this->menuCapability, str_replace(" ", "", $this->testViewTitle), array($this, "testView"));
        $this->hookUpgrade = add_submenu_page(basename(__FILE__), "NewsBoard Upgrade", __("Upgrade"), $this->menuCapability, "NewsBoardUpgrade", array($this, "upgradeView"));
        
        add_action('admin_enqueue_scripts', array($this, 'UExtFilesLoadInit'));
        add_action('admin_enqueue_scripts', array($this, 'IExtFilesLoadInit'));
        add_action('admin_enqueue_scripts', array($this, 'MWExtFilesLoadInit'));
        add_action('admin_enqueue_scripts', array($this, 'TWExtFilesLoadInit'));
        add_action('admin_enqueue_scripts', array($this, 'ThWExtFilesLoadInit'));
        add_action('admin_head-' . $this->hookItems, array($this, 'IExtFilesLoadHead'));
        add_action('admin_head-' . $this->hookMain, array($this, 'MWExtFilesLoadHead'));
        add_action('admin_head-' . $this->hookTestView, array($this, 'TWExtFilesLoadHead'));
        add_action('admin_head-' . $this->hookTestView, array($this, 'TWExtFilesLoadHeadCss'));
        add_action('admin_head-' . $this->hookThemes, array($this, 'ThWExtFilesLoadHead'));    
    }
    
    /**
     * Builds Items Page.
     */
    public function itemsView()
    {
        echo '<div class="wrap" id="nbp_wrap">
                <div class="icon32" id="icon-options-general"><br/></div><h2>' . $this->menuName . " " .  __('Items') . '</h2>';        
        echo '<br /><a href="http://newsboardplugin.com/support/faq/#how-to-go-pro" target="_blank"><img src="' . $this->pluginPath . 'images/items.png" /></a>';
        echo '</div>';
    }
    
    /**
     * Builds Settings Page.
     */
    public function mainView()
    {
        echo '<div class="wrap" id="nbp_wrap">
                <div class="icon32" id="icon-options-general"><br/></div><h2>' . $this->menuName . " " .  __($this->mainName) . '</h2>';
        echo $this->changeMsgMain;
        
        $tpl_main = new nbpTemplate();
        $tpl_main->assign($this->main->nbp_options_main + array('go_pro_image' => '<a href="http://newsboardplugin.com/support/faq/#how-to-go-pro" target="_blank"><img src="' . $this->pluginPath . 'images/go_pro.png" class="go_pro_image"/></a>'));
        $tpl_main->display($this->pluginPathFull . "admin/newsboard-main.tpl");
        echo '</div>';
    }
    
    /**
     * Builds Themes Page.
     */
    public function themesView()
    {
        echo '<div class="wrap" id="nbp_wrap">
                <div class="icon32" id="icon-options-general"><br/></div><h2>' . $this->menuName . " " .  __($this->themesName) . '</h2>';
        echo $this->changeMsgApp;
        
        $tpl_themes = new nbpTemplate();
        $tpl_appearance = new nbpTemplate();
        $tpl_appearance->assign($this->app->nbp_options_appearance);
        $tpl_themes->assign($this->app->slider_html + array('appearance_settings' => $tpl_appearance->display_in($this->pluginPathFull . "admin/newsboard-appearance.tpl"), "custom_snapshot" => "<img src=\"" .$this->pluginPath . "images/custom.png\"/>"));
        $tpl_themes->display($this->pluginPathFull . "admin/newsboard-themes.tpl");
        echo '</div>';
    }
    
    /**
     * Builds the HTML of the plugin ticker.
     * @return String - The HTML of the plugin
     */
    public function renderView()
    {
        $render = new nbpTemplate();
        $render->assign($this->render->tA);
        $output = $render->displayINRender($this->pluginPathFull . "render/newsboard-render.tpl");
        return $output;
    }
    
    /**
     * Builds Test View Page.
     */
    public function testView()
    {
        echo '<div class="wrap" id="nbp_wrap">
                <div class="icon32" id="icon-options-general"><br/></div><h2>' . $this->menuName . " " . __($this->testViewName) . '</h2>';
        echo '<div class="testview_holder">';
        echo $this->renderView();
        echo '</div></div>';
    }
    
    /**
     * Builds Upgrade Page.
     */
    public function upgradeView()
    {
        echo '<div class="wrap" id="nbp_wrap">
                <div class="icon32" id="icon-ms-admin"><br/></div><h2>NewsBoard Upgrade</h2>';
        echo $this->changeMsgKey;
        $tpl_upgrade = new nbpTemplate();
        
        switch($this->upgrade->mode)
        {
            case 'good_key':
            case 'expired_key':
                $licenseFieldClass = '';
                $licenseModeLink = '<a href="http://newsboardplugin.com/support/faq/#how-to-get-a-key" target="_blank">I don\'t have a key. How to get one?</a><br /><br><a href="http://newsboardplugin.com/support/faq/#why-to-go-pro" target="_blank">Why to go PRO?</a>';
                break;
            case 'no_key':
                $licenseFieldClass = '';
                $licenseModeLink = '<a href="http://newsboardplugin.com/support/faq/#how-to-get-a-key" target="_blank">I don\'t have a key. How to get one?</a><br /><br><a href="http://newsboardplugin.com/support/faq/#why-to-go-pro" target="_blank">Why to go PRO?</a>';
                break;
            case 'wrong_key':
                $licenseFieldClass = 'invalid';
                $licenseModeLink = '<a href="http://newsboardplugin.com/support/faq/#where-is-my-key" target="_blank">Where is my key?</a><br><a href="https://newsboardplugin.com/support/" target="_blank">Get support</a>';
                break;
        }
        
        $tpl_upgrade->assign(array('license_key' => $this->upgrade->getElements('license_key'), 'license_key_class' => $licenseFieldClass, 'license_mode_link' => $licenseModeLink));
        $tpl_upgrade->display($this->pluginPathFull . "admin/newsboard-upgrade.tpl");
        
        echo '</div>';
    }
    
    /**
     * Prints the CSS for Test View Page.
     */
    public function TWExtFilesLoadHeadCss()
    {
        $render = new nbpTemplate();
        $render->assign($this->render->tA);
        $renderCSS = "<style type=\"text/css\">";
        $renderCSS .= $render->displayINRender($this->pluginPathFull . "render/newsboard-render-css.tpl");
        print $renderCSS . "</style>";
    }
    
    /**
     * Prints the JavaScript for Test View Page
     */
    public function TWExtFilesLoadHead()
    {
        $sign = '$nbp';
        if($this->errorFlag == 1)
        {
            $error_handle = "$sign('.newsboard_plugin_holder').nbpMsg(" . $this->errorData['stay'] . ", '" . $this->errorData['text'] . "', '" . $this->errorData['plugin_dir']. "');";
        }
        else
            $error_handle = "";
        print 
        "\n<script type=\"text/javascript\">
            $sign(document).ready(function () {
                " . $error_handle . "
                $sign('#newsboard_plugin_holder').nbpAnimate({speed: " . $this->render->tA['transition_time'] . ", stay: " . $this->render->tA['scroll_period'] . ", margin_bottom: " . $this->render->tA['margin_bottom_holder'] . ", max_news: " . $this->render->tA['number_of_news'] . ", board_fit: " . $this->render->tA['board_news_fit'] . ", invisible_news_top: " . $this->render->tA['invisible_news_top'] . "});
            });
        </script>"; 
    }
    
    /**
     * Prints the JavaScript for Themes Page
     */
    public function ThWExtFilesLoadHead()
    {
        $sign = '$nbp';
        if($this->errorFlag == 1)
        {
            $error_handle = "$sign('.newsboard_plugin_holder').nbpMsg(" . $this->errorData['stay'] . ", '" . $this->errorData['text'] . "', '" . $this->errorData['plugin_dir']. "');";
        }
        else
            $error_handle = "";
        print 
        "\n<script type=\"text/javascript\">
            $sign(document).ready(function () {
                " . $error_handle . "
                $sign('.nbp_settings_holder .theme_picker').nbpThemePick({plugin_dir: '" . $this->pluginPath . "', theme_width: " . $this->app->slider_html['TP_theme_width'] . ", speed: " . $this->app->slider_html['TP_transition_time'] . ", hiddenThemes: " . $this->app->slider_html['TP_hidden_themes'] . "});
                $sign('.nbp_settings_holder input:text').nbpValidate({pluginDir:'" . $this->pluginPath . "', curr_el:'.nbp_settings_holder input:text'});
                });
        </script>";
    }
    
    /**
     * Prints the JavaScript for Settings Page
     */
    public function MWExtFilesLoadHead()
    {
        $sign = '$nbp';
        if($this->errorFlag == 1)
        {
            $error_handle = "$sign('.newsboard_plugin_holder').nbpMsg(" . $this->errorData['stay'] . ", '" . $this->errorData['text'] . "', '" . $this->errorData['plugin_dir']. "');";
        }
        else
            $error_handle = "";
        print 
        "\n<script type=\"text/javascript\">
            $sign(document).ready(function () {
                " . $error_handle . "
                $sign('.nbp_settings_holder input:text').nbpValidate({pluginDir:'" . $this->pluginPath . "', curr_el:'.nbp_settings_holder input:text'});
            });
        </script>";
    }
    
    /**
     * Prints the JavaScript for Items View Page
     */
    public function IExtFilesLoadHead()
    {
        $sign = '$nbp';
        if($this->errorFlag == 1)
        {
            $error_handle = "$sign('.newsboard_plugin_holder').nbpMsg(" . $this->errorData['stay'] . ", '" . $this->errorData['text'] . "', '" . $this->errorData['plugin_dir']. "');";
        }
        else
            $error_handle = "";
        print 
        "\n<script type=\"text/javascript\">
            $sign(document).ready(function () {
                " . $error_handle . "
                $sign('#nbp_items_holder input:text').nbpValidate({pluginDir:'" . $this->pluginPath . "', curr_el:'#nbp_items_holder input:text'});
            });
        </script>";
    }
    
    /**
     * Loads the JavaScript for Test View Page
     * @uses wp_enqueue_script - The safe and recommended method of adding JavaScript to a WordPress generated page
     */
    public function wp_TWExtFilesLoadInit()
    {
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-effects-core' );
        wp_enqueue_script( 'nbpBackEnd-js', $this->pluginPath . "js/nbpBackEnd.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core') );
        wp_enqueue_script( 'nbpMsg-js', $this->pluginPath . "js/nbpMsg.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'nbpBackEnd-js') );
        wp_enqueue_script( 'nbpAnimate-js', $this->pluginPath . "js/nbpAnimate.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'nbpBackEnd-js') );
    }
    
    /**
     * Loads the JavaScript for Test View Page
     * @uses wp_enqueue_script - The safe and recommended method of adding JavaScript to a WordPress generated page
     * @uses wp_enqueue_style - The safe and recommended method of adding Style to a WordPress generated page
     */
    public function TWExtFilesLoadInit($hook)
    {
        if( $this->hookTestView != $hook )
            return;
            
        wp_enqueue_style( 'nbp-admin-css', $this->pluginPath . "admin/style/admin.css", false, 'free' );    
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-effects-core' );
        wp_enqueue_script( 'nbpBackEnd-js', $this->pluginPath . "js/nbpBackEnd.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core') );
        wp_enqueue_script( 'nbpMsg-js', $this->pluginPath . "js/nbpMsg.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'nbpBackEnd-js') );
        wp_enqueue_script( 'nbpAnimate-js', $this->pluginPath . "js/nbpAnimate.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'nbpBackEnd-js') );
    }
    
    /**
     * Loads the JavaScript for Themes Page
     * @uses wp_enqueue_script - The safe and recommended method of adding JavaScript to a WordPress generated page
     * @uses wp_enqueue_style - The safe and recommended method of adding Style to a WordPress generated page
     */
    public function ThWExtFilesLoadInit($hook)
    {
        if( $this->hookThemes != $hook )
            return;
        
        wp_enqueue_style( 'nbp-admin-css', $this->pluginPath . "admin/style/admin.css", false, 'free' );
        wp_enqueue_style( 'farbtastic' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-effects-core' );
        wp_enqueue_script( 'farbtastic' );
        wp_enqueue_script( 'nbpBackEnd-js', $this->pluginPath . "js/nbpBackEnd.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'farbtastic') );
        wp_enqueue_script( 'nbpMsg-js', $this->pluginPath . "js/nbpMsg.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'farbtastic', 'nbpBackEnd-js') );
        wp_enqueue_script( 'nbpValidate-js', $this->pluginPath . "js/nbpValidate.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'farbtastic', 'nbpBackEnd-js') );
        wp_enqueue_script( 'nbpThemePick-js', $this->pluginPath . "js/nbpThemePick.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'farbtastic','nbpBackEnd-js') );
    }
    
    /**
     * Loads the JavaScript for Items Page
     * @uses wp_enqueue_script - The safe and recommended method of adding JavaScript to a WordPress generated page
     * @uses wp_enqueue_style - The safe and recommended method of adding Style to a WordPress generated page
     */
    public function IExtFilesLoadInit($hook)
    {
        if( $this->hookItems != $hook )
            return;
        
        wp_enqueue_style( 'nbp-admin-css', $this->pluginPath . "admin/style/admin.css", false, 'free' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-effects-core' );
        wp_enqueue_script( 'nbpBackEnd-js', $this->pluginPath . "js/nbpBackEnd.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'farbtastic') );
        wp_enqueue_script( 'nbpMsg-js', $this->pluginPath . "js/nbpMsg.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'nbpBackEnd-js') );
        wp_enqueue_script( 'nbpValidate-js', $this->pluginPath . "js/nbpValidate.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core','nbpBackEnd-js') );
    }
    
    /**
     * Loads the JavaScript for Upgrade Page
     * @uses wp_enqueue_style - The safe and recommended method of adding Style to a WordPress generated page
     */
    public function UExtFilesLoadInit($hook)
    {
        if( $this->hookUpgrade != $hook )
            return;
        
        wp_enqueue_style( 'nbp-admin-css', $this->pluginPath . "admin/style/admin.css", false, 'free' );
    }
    
    /**
     * Loads the JavaScript for Settings Page
     * @uses wp_enqueue_script - The safe and recommended method of adding JavaScript to a WordPress generated page
     * @uses wp_enqueue_style - The safe and recommended method of adding Style to a WordPress generated page
     */
    public function MWExtFilesLoadInit($hook)
    {
        if( $this->hookMain != $hook )
            return;
        
        wp_enqueue_style( 'nbp-admin-css', $this->pluginPath . "admin/style/admin.css", false, 'free' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-effects-core' );
        wp_enqueue_script( 'nbpBackEnd-js', $this->pluginPath . "js/nbpBackEnd.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'farbtastic') );
        wp_enqueue_script( 'nbpMsg-js', $this->pluginPath . "js/nbpMsg.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'nbpBackEnd-js') );
        wp_enqueue_script( 'nbpValidate-js', $this->pluginPath . "js/nbpValidate.js", array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'nbpBackEnd-js') );
    }
    
    /**
     * Assigning Registered Widgets to the Items
     * @param Integer $currentItem - Current Item ID
     */
    public function widgetShortcodeInit()
    {
        $this->init();
        return $this->renderView();     
    } 
    
}