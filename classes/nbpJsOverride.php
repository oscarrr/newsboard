<?php
error_reporting(0); 
$ov = json_decode($_POST['data'], true);

//post appearance options to JavaScript
if($ov['command'] == 'jsOverride')
{
    require_once '../newsboard-plugin-constants.php';
    require_once '../default-settings.php';
    require_once 'nbpAppearance.php';  
     
    $new_app = new nbpAppearance(null, $ov['plugin_dir']);
    $new_app->getDefaultTheme();
    $new_app->nbp_options_appearance['theme_select_custom'] = 'checked="checked"';
    $new_app->nbp_options_appearance['theme_select_default'] = '';
    echo json_encode($new_app->nbp_options_appearance);
}