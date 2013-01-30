<?php
/** 
    Plugin Name: NewsBoard Plugin FREE
    Plugin URI: http://newsboardplugin.com 
    Description: Posts and RSS ticker.
    Version: 1.0 
    Author: NewsBoard Plugin 
    Author URI: http://newsboardplugin.com 
*/
/*
    Copyright 2013 NewsBoard Plugin (email : office@newsboardplugin.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once WP_PLUGIN_DIR . '/newsboard/newsboard-constants.php';
require_once WP_PLUGIN_DIR . '/newsboard/default-settings.php';
require_once WP_PLUGIN_DIR . '/newsboard/classes/nbpWidget.php';
require_once WP_PLUGIN_DIR . '/newsboard/classes/nbpCore.php';
require_once WP_PLUGIN_DIR . '/newsboard/classes/nbpAutoUpdate.php';
require_once WP_PLUGIN_DIR . '/newsboard/classes/nbpInit.php';

$plugin_basename = plugin_basename(__FILE__);
$nbp_app = new nbpInit($plugin_basename);