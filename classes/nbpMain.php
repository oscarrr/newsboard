<?php
/**
 * The Class that builds Settings Data
 * @copyright (c) 2015, NewsBoard Plugin
 * @package WordPress
 * @subpackage NewsBoard Plugin FREE
 */
class nbpMain
{
    //Arrays with settings of the plugin settings
    public $nbp_options_main = array(), $nbp_catlist = array(), $plugin_dir;
    
    private $option_name;
    
    /**
     * Class construct. Var assigning
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
        $this->nbp_options_main = get_option($this->option_name);
        if(!is_array($this->nbp_options_main))
            return;
        $this->typeOptions();
        $this->adjustRadioboxes();
        $this->getCategoriesList(0,0);
        $this->adjustCategoriesList();
    }
    
    /**
     * Sets values to the settings option.
     * @global Array $nbp_options_main_all - Settings options array
     * @uses update_option() - WP function to update an option in DB
     */
    public function setElements()
    {
        global $nbp_options_main_all;
        if(!is_array($nbp_options_main_all))
            return;
        
        $nbp_options_keys = array_keys($nbp_options_main_all);
        for($i=0; $i<count($nbp_options_keys); $i++)
            $array_to_return[$nbp_options_keys[$i]] = $_POST[$nbp_options_keys[$i]];
        update_option($this->option_name, $array_to_return);
    }
    
    /**
     * Converts DB radioboxes values to valid HTML, showing if a radiobox is checked or not.
     * @global Array $nbp_main_radioboxes - Settings radioboxes
     */
    private function adjustRadioboxes()
    {
        global $nbp_main_radioboxes;
        if(!is_array($nbp_main_radioboxes))
            return;
        
        for($i=0; $i<count($nbp_main_radioboxes); $i++)
            $this->nbp_options_main[$nbp_main_radioboxes[$i].'_'.$this->nbp_options_main[$nbp_main_radioboxes[$i]]] = 'checked="checked"'; 
    }
    
    /**
     * Builds options for selects.
     * @global Array $nbp_transition_types - Settings: Transition types
     * @global Array $nbp_order_types - //Settings: Posts Order By
     */
    private function typeOptions()
    {
        global $nbp_transition_types, $nbp_order_types;
        if(!is_array($nbp_transition_types))
            return;
        if(!is_array($nbp_order_types))
            return;
            
        $nbp_transition_types_keys = array_keys($nbp_transition_types);
        
        for($i=0; $i<count($nbp_transition_types_keys); $i++)
        {
            if($nbp_transition_types_keys[$i]!=$this->nbp_options_main['transition_type'])
                $flag_selected = "";
            else
                $flag_selected = " selected=\"selected\"";
            if($nbp_transition_types_keys[$i] != 'normal ease')
                $disabled = 'disabled="disabled"';
            else
                $disabled = '';
            $this->nbp_options_main['transition_type_options'] .= "<option " . $disabled. " value=\"" . $nbp_transition_types_keys[$i] . "\"" . $flag_selected . ">" . $nbp_transition_types_keys[$i]. "</option>";
        }
        foreach($nbp_order_types as $key=>$value)
        {
            if($value!=$this->nbp_options_main['order_type'])
                $flag_selected = "";
            else
                $flag_selected = " selected=\"selected\"";
            $this->nbp_options_main['order_type_options'] .= "<option value=\"" . $value . "\"" . $flag_selected . ">" . $key. "</option>";           
        }
        
    }
    
    /**
     * Recursive Call. Builds multiple select options HTML.
     * @param Integer $id - Category ID
     * @param Integer $h - Subcategory offset
     */
    private function getCategoriesList($id, $h)
    {
        $categories = get_categories( array('hide_empty' => 0, 'parent' => $id, 'taxonomy' => get_taxonomies()) );
        
        $pre = '';
        for($i = $h; $i > 0; $i--)
            $pre .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        
        $h++;
        foreach($categories as $cat)
        {
            $this->nbp_catlist[$cat->cat_ID] = $pre.$cat->name;
            $this->getCategoriesList($cat->cat_ID, $h);
        }
    }
    
    /**
     * Converts DB option values to valid HTML, showing if an option is checked or not.
     */
    private function adjustCategoriesList()
    {
        if(!is_array($this->nbp_catlist))
            return;
        foreach($this->nbp_catlist as $key1=>$value1)
        {
            foreach($this->nbp_options_main['pickcategories'] as $key2=>$value2)
            {
                if($key1!=$value2)
                    $flag_selected = "";
                else
                {
                    $flag_selected = " selected=\"selected\"";
                    break;
                }
            }
            $this->nbp_options_main['pickcategories_options'] .= "<option value=\"" . $key1 . "\"" . $flag_selected . ">" . $value1. "</option>";
        }
    }
}