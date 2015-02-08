<?php
/**
 * Template engine
 * @copyright (c) 2015, NewsBoard Plugin
 * @package WordPress
 * @subpackage NewsBoard Plugin FREE
 */
class nbpTemplate
{
    private $tpl_vars = array();
    /**
     * Class construct. Var assigning
     */
    public function __construct()
    {
        $this->tpl_vars = array();
    }
    /**
     * Assign our variables and replacements.
     * @param Array $var_array - Template variables and replacements
     */
    public function assign($var_array)
    {
        // Must be an array...
        if (!is_array($var_array))
        {
            echo '$var_array must be an array!';
            exit();
        }
        $this->tpl_vars = array_merge($this->tpl_vars, $var_array);
    }
    /**
     * Parse the template file.
     * @param String $tpl_file - Template file
     * @param Array $custom_brackets - Brackets for our nests
     * @return String - Parsed template data
    */
    private function parse($tpl_file, $custom_brackets = null)
    {
        // Make sure it's a valid file, and it exists
        if (!is_file($tpl_file))
        {
            echo $tpl_file . ' does not exist or is not a file!';
            exit();
        }
        $tpl_content = file_get_contents($tpl_file);
        if(is_array($custom_brackets) && $custom_brackets != null)
        {
            $open_bracket = $custom_brackets[0];
            $close_bracket = $custom_brackets[1];
        }
        else
        {
            $open_bracket = '{';
            $close_bracket = '}';
        }
        
        foreach ($this->tpl_vars AS $var => $content)
            $tpl_content = str_replace($open_bracket . $var . $close_bracket, $content, $tpl_content);
        
        return $tpl_content;
    }
    /**
     * Parse the template file with other brackets
     * @param String $tpl_file - Template file
     * @return String - Parsed template data
     */
    private function parseRender($tpl_file)
    {
        return $this->parse($tpl_file, array('[', ']'));
    }
    /**
     * Prints the HTML
     * @param String - Template file
     */
    public function display($tpl_file)
    {
        echo $this->parse($tpl_file);
    }
    
    /**
     * Prints the HTML with recognizing custom brackets
     * @param String - Template file
     */
    public function displayRender($tpl_file)
    {
        echo $this->parseRender($tpl_file);
    }
    
    /**
     * Builds the HTML with recognizing custom brackets
     * @param String - Template file
     * @return String - Parsed HTML
     */
    public function displayINRender($tpl_file)
    {
        return $this->parseRender($tpl_file);
    }
    
    /**
     * Builds the HTML
     * @param String - Template file
     * @return String - Parsed HTML
     */
    public function display_in($tpl_file)
    {
        return $this->parse($tpl_file);
    }
}