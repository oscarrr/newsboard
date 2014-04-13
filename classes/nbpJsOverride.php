<?php
error_reporting(0);
$data = $_POST['data'];
if(get_magic_quotes_gpc() === 1)
    $data = stripslashes($_POST['data']);
$ov = json_decode($data, true);
//post appearance options to JavaScript
if($ov['command'] == 'jsOverride')
{
    require_once '../newsboard-constants.php';
    require_once '../default-settings.php';
    require_once 'nbpAppearance.php';  
     
    $new_app = new nbpAppearance(null, $ov['plugin_dir']);
    $new_app->getDefaultTheme();
    $new_app->nbp_options_appearance['theme_select_custom'] = 'checked="checked"';
    $new_app->nbp_options_appearance['theme_select_default'] = '';
    echo json_encode($new_app->nbp_options_appearance);
}