<?php
/**
 * Upgrade class. Performs checks for the license key and downloads the new version
 * @copyright (c) 2015, NewsBoard Plugin
 * @package WordPress
 * @subpackage NewsBoard Plugin FREE
 */
class nbpUpgrade
{
    public $nbp_settings, $pluginOptName, $check_path, $upgrade_path, $mode, $date_format;
    
    /**
     * Class construct. Var assigning and calling checkLicenseKey method if it's necessary
     * @param String $pluginOptName - The option that saves upgrade data
     * @param String $check_path - The URL where license key is checked
     * @uses get_option() - WP function to determine the value of a given option
     */
    public function __construct($pluginOptName, $check_path, $upgrade_path)
    {
        $this->date_format = 'Y-m-d H:i:s';
        $this->check_path = $check_path;
        $this->upgrade_path = $upgrade_path;
        $this->pluginOptName = $pluginOptName;
        
        //Option Array
        $this->nbp_settings = get_option($pluginOptName);
            
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
     * Checks the License Key upon Upgrade
     * @uses wp_remote_post() - WP function to POST data to Remote Servers
     * @return Boolean - true if key is valid
     */
    public function checkUpgradeKey()
    {
        $key = $this->getElements('license_key');
        
        if(!empty($key))
        {
            $request = wp_remote_post($this->check_path, array('body' => array('key' => $key)));
            
            if(!is_wp_error($request) && (int) $request['response']['code'] === 200)
            {
                $this->mode = $request['body'];
                
                if($this->mode == 'good_key' || $this->mode == 'expired_key')    
                {   
                    $this->setElements('mode', $this->mode);
                    $this->upgradeMessage = sprintf(__('You have successfully activated %s'), 'NewsBoard PRO') . '!<META HTTP-EQUIV="refresh" CONTENT="3; URL=' . $_SERVER['REQUEST_URI'] . '">';
                    
                    return true;                    
                }
                elseif($this->mode == 'wrong_key')    
                {   
                    $this->setElements('mode', $this->mode);
                    $this->upgradeMessage = __('Your key is invalid. Please make sure you have typed it correctly.');
                } else {
                    $this->upgradeMessage = __('No key answer was received.');
                    $this->setElements('mode', 'error');
                }
            }
            elseif(is_wp_error($request))
            {
                $this->upgradeMessage = $request->get_error_message();
                $this->setElements('mode', 'error');
            }
            elseif($request['response']['code'] != 200)
            {
                $this->upgradeMessage = __('Activation server cannot be reached. Please make sure that NewsBoard server (newsboardplugin.com) is not blocked.');
                $this->setElements('mode', 'error');
            }
        } else {
            $this->setElements('mode', 'no_key');
            $this->upgradeMessage = __('Please enter your key');
        } 
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