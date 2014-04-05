<?php

/**
 * Upgrade class. Performs checks for the license key and downloads the new version
 * @copyright (c) 2014, NewsBoard Plugin
 * @package WordPress
 * @subpackage NewsBoard Plugin FREE
 */
class nbpUpgrade
{
    public $nbp_settings, $pluginOptName, $check_path, $upgrade_path, $mode, $last_date, $date_format;
    
    /**
     * Class construct. Var assigning and calling checkLicenseKey method if it's necessary
     * @param String $pluginOptName - The option that saves upgrade data
     * @param String $check_path - The URL where license key is checked
     * @uses get_option() - WP function to determine the value of a given option
     */
    public function __construct($pluginOptName, $check_path, $upgrade_path)
    {
        $this->date_format = 'Y-m-d H:i:s';
        $check_time = '1728000'; //20 days in seconds
        $this->check_path = $check_path;
        $this->upgrade_path = $upgrade_path;
        $this->pluginOptName = $pluginOptName;
        
        //Option Array
        $this->nbp_settings = get_option($pluginOptName);  
        
        $this->last_date = $this->getElements('last_date');
        if(strtotime(date($date_format)) - strtotime($this->last_date) > $check_time || $this->last_date == '' || !is_numeric($this->last_date))
            $this->checkLicenseKey();
            
        $this->mode = $this->getElements('mode');                     
    }
    
    /**
     * Getting value from the Option Array
     * @param String $name - The key of Option Array
     * @return String - The value of Option Array
     */
    public function getElements($name)
    {
        return $this->nbp_settings[$name];
    }
    
    /**
     * Setting an option
     * @param String $name - The key of Option Array
     * @param String $value - The value of Option Array
     * @uses update_option() - WP function to update an option in DB
     */
    public function setElements($name, $value)
    {
        $this->nbp_settings[$name] = htmlspecialchars($value);
        update_option($this->pluginOptName, $this->nbp_settings);        
    }
    
    /**
     * Checks the License Key and Updates Option Array from the given result
     * @uses wp_remote_post() - WP function to POST data to Remote Servers
     */
    public function checkLicenseKey()
    {
        $key = $this->getElements('license_key');
        if(!empty($key))
        {
            $request = wp_remote_post($this->check_path, array('body' => array('key' => $key)));
            if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200)
            {
                $this->mode = $request['body'];
                if($this->mode != 'good_key' || $this->mode != 'expired_key')    
                    $this->setElements('last_date', date($this->date_format));
                $this->setElements('mode', $this->mode);
            }
        }
        else
            $this->setElements('mode', 'no_key');
    }
    
    /**
     * Checks the License Key upon Upgrade
     * @uses wp_remote_post() - WP function to POST data to Remote Servers
     * @return Boolean - true if key is valid
     */
    public function checkUpgradeKey()
    {
        $key = $this->getElements('license_key');
        if(!empty($key))
        {
            $request = wp_remote_post($this->check_path, array('body' => array('upgrade_key' => $key)));
            if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200)
            {
                $this->mode = $request['body'];
                if($this->mode == 'good_key' || $this->mode == 'expired_key')
                {
                    return true;
                }else{
                    $this->setElements('mode', 'wrong_key');
                    return false; 
                }          
            }else{
                return false;
            }   
        }
        else
            $this->setElements('mode', 'no_key'); 
    }
    
    /**
     * Gets upgrade package url
     * @uses wp_remote_post() - WP function to POST data to Remote Servers
     * @return String - upgrade package url
     */
    public function getUpgradePackage()
    {
        $key = $this->getElements('license_key');
        return $this->upgrade_path . '?upgrade=' . $key;
    }
}