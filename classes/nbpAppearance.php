<?php
/**
 * The Class that builds Theme Data
 * @copyright (c) 2015, NewsBoard Plugin
 * @package WordPress
 * @subpackage NewsBoard Plugin FREE
 */
class nbpAppearance
{
    //Arrays with settings of the appearance
    public $nbp_options_appearance = array(), $slider_html = array();
    
    private $theme_dir, $plugin_dir, $option_name;
    
    /**
     * Class construct. Var assigning.
     * @param String $option_name - The option that saves appearance data
     * @param String $plugin_dir - Plugin dir
     */
    public function __construct($option_name, $plugin_dir)
    {
        $this->plugin_dir = $plugin_dir;
        $this->option_name = $option_name;
    }
    
    /**
     * Gets the elements and its values of the current item from DB.
     * @uses get_option() - WP function to determine the value of a given option
     */
    public function getElements()
    {
        $this->nbp_options_appearance = get_option($this->option_name);
        if(!is_array($this->nbp_options_appearance))
            return;
        foreach($this->nbp_options_appearance as $key=>$value)
            $this->nbp_options_appearance[$key] = stripslashes(htmlspecialchars_decode($value));
        $this->adjustCheckboxes();
        $this->adjustRadioboxes();
        $this->nbp_options_appearance['custom_snaphot'] = "<img src=\"" .$this->plugin_dir . "images/custom.png\"/>";
        if($this->nbp_options_appearance['theme_select'] == "Default" && $this->nbp_options_appearance['theme_select'] != "")
            $this->getDefaultTheme();
        
        $this->buildThemeSlider();
    }
    /**
     * Sets values to the appearance option.
     * @global Array $nbp_options_appearance_all - Theme (appearance) options array
     * @uses update_option() - WP function to update an option in DB
     */
    public function setElements()
    {
        global $nbp_options_appearance_all;
        if(!is_array($nbp_options_appearance_all))
            return;
        
        $nbp_app_keys = array_keys($nbp_options_appearance_all);
        for($i=0; $i<count($nbp_app_keys); $i++)
            $array_to_return[$nbp_app_keys[$i]] = htmlspecialchars($_POST[$nbp_app_keys[$i]]);
        update_option($this->option_name, $array_to_return);
    }
    
    /**
     * Converts DB checkboxes values to valid HTML, showing if a checkbox is checked or not.
     * @global Array $nbp_appearance_checkboxes - Theme options checkboxes.
     */
    private function adjustCheckboxes()
    {
        global $nbp_appearance_checkboxes;
        if(!is_array($nbp_appearance_checkboxes) || !is_array($nbp_appearance_checkboxes))
            return;
        
        for($i=0; $i<count($nbp_appearance_checkboxes); $i++)
        {
            if($this->nbp_options_appearance[$nbp_appearance_checkboxes[$i]] == 1)
                $this->nbp_options_appearance[$nbp_appearance_checkboxes[$i]] = 'checked="checked"';
            else
                $this->nbp_options_appearance[$nbp_appearance_checkboxes[$i]] = '';
        }
        
        $this->nbp_options_appearance['luxury_btn_bg'] = $this->plugin_dir . "images/luxury_touch.png";
    }
    
    /**
     * Converts DB radioboxes values to valid HTML, showing if a radiobox is checked or not.
     * @global Array $nbp_appearance_radioboxes - Theme oprions radioboxes
     */
    private function adjustRadioboxes()
    {
        global $nbp_appearance_radioboxes;
        if(!is_array($nbp_appearance_radioboxes))
            return;
        
        for($i=0; $i<count($nbp_appearance_radioboxes); $i++)
        {
            if ( function_exists( 'mb_strtolower' ) )
                $dynamic_element = str_replace(" ", "_", mb_strtolower($this->nbp_options_appearance[$nbp_appearance_radioboxes[$i]], 'UTF-8'));
            else
                $dynamic_element = str_replace(" ", "_", strtolower($this->nbp_options_appearance[$nbp_appearance_radioboxes[$i]]));
                
            $this->nbp_options_appearance[$nbp_appearance_radioboxes[$i].'_'.$dynamic_element] = 'checked="checked"';
        } 
    }
    
    public function getDefaultTheme()
    {
        global $default_app;
        $this->nbp_options_appearance = $default_app;
        $this->nbp_options_appearance['custom_snapshot'] = "<img src=\"" .$this->plugin_dir . "images/custom.png\"/>";
        $this->adjustCheckboxes();
        $this->adjustRadioboxes();
    }
    
    private function buildThemeSlider()
    {
        $this->slider_html['TP_theme_width'] = 287;
        $this->slider_html['TP_transition_time'] = 800;
        
        $this->slider_html['admin_left_arrow'] = $this->plugin_dir . 'images/admin_left_arrow.png';
        $this->slider_html['admin_right_arrow'] = $this->plugin_dir . 'images/admin_right_arrow.png';
        $this->nbp_options_appearance['custom_snapshot'] = "<img src=\"" .$this->plugin_dir . "images/custom.png\"/>";
        
        if($this->nbp_options_appearance['theme_select'] == 'Default')
        {
            $check = 'checked="checked"';
            $this->nbp_options_appearance['theme_select_custom'] = '';
        }
        else
        {
            $check = '';
            $this->nbp_options_appearance['theme_select_custom'] = 'checked="checked"';
        }
        
        $theme_default = 
        "<div class=\"theme\">
            <div class=\"theme_lable\">
                <input id=\"\" name=\"theme_select\" type=\"radio\" " . $check . " value=\"Default\" /> Default <div id=\"Default\" class=\"override_default\">Override to custom</div>
            </div>
            <div class=\"theme_screenshot\" style=\"background-image: url(" . $this->plugin_dir . "images/Default.png);\"></div> 
        </div>";
        
        $custom_nothemes = 
        "<div class=\"theme\">
            <div class=\"theme_lable\">
                <input id=\"\" name=\"theme_select\" type=\"radio\" disabled=\"disabled\" /> Black Widow <div id=\"Black Widow\" class=\"override_btn\">Override to custom</div>
            </div>
            <div class=\"theme_screenshot\" onclick=\"javascript:window.open('http://newsboardplugin.com/support/faq/#how-to-go-pro')\" style=\"cursor: pointer; background-image: url(" . $this->plugin_dir . "images/Black%20Widow.jpg);\"></div> 
        </div>
        <div class=\"theme\">
            <div class=\"theme_lable\">
                <input id=\"\" name=\"theme_select\" type=\"radio\" disabled=\"disabled\" /> Drops <div id=\"Drops\" class=\"override_btn\">Override to custom</div>
            </div>
            <div class=\"theme_screenshot\" onclick=\"javascript:window.open('http://newsboardplugin.com/support/faq/#how-to-go-pro')\" style=\"cursor: pointer; background-image: url(" . $this->plugin_dir . "images/Drops.jpg);\"></div> 
        </div>
        <div class=\"theme\">
            <div class=\"theme_lable\">
                <input id=\"\" name=\"theme_select\" type=\"radio\" disabled=\"disabled\" /> Shades <div id=\"Shades\" class=\"override_btn\">Override to custom</div>
            </div>
            <div class=\"theme_screenshot\" onclick=\"javascript:window.open('http://newsboardplugin.com/support/faq/#how-to-go-pro')\" style=\"cursor: pointer; background-image: url(" . $this->plugin_dir . "images/Shades.jpg);\"></div> 
        </div>
        <div class=\"theme\">
            <div class=\"theme_lable\">
                <input id=\"\" name=\"theme_select\" type=\"radio\" disabled=\"disabled\" /> Card <div id=\"Card\" class=\"override_btn\">Override to custom</div>
            </div>
            <div class=\"theme_screenshot\" onclick=\"javascript:window.open('http://newsboardplugin.com/support/faq/#how-to-go-pro')\" style=\"cursor: pointer; background-image: url(" . $this->plugin_dir . "images/Card.jpg);\"></div> 
        </div>
        ";
        
        $this->slider_html['pick_theme'] = $theme_default . $custom_nothemes; 
        $this->slider_html['TP_hidden_themes'] = 2;
        $this->slider_html['theme_slider_width'] = 5*$this->slider_html['TP_theme_width']+5;
    }
}