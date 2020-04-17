<?php
/*
   Plugin Name: News Aggregator
   Plugin URI: https://twicetwomedia.com/wordpress-plugins/
   Description: Simple news aggregation feeds for your website.
   Version: 0.1.5
   Author: twicetwomedia
   Author URI: https://twicetwomedia.com
   Text Domain: newsagg
   License: GPLv3
  */
$newsagg_version     = '0.1.5';
$newsagg_name        = 'News Aggregator';
$newsagg_min_php_v   = '5.6';
$newsagg_file        = __FILE__;
$newsagg_basename    = plugin_basename( $newsagg_file );
$newsagg_path_base   = plugin_dir_path( $newsagg_file );
$newsagg_path_inc    = $newsagg_path_base . 'inc/';
$newsagg_path_assets = $newsagg_path_base . 'assets/';

defined( 'NEWSAGG_VER' ) or define( 'NEWSAGG_VER', $newsagg_version );
defined( 'NEWSAGG_NAME' ) or define( 'NEWSAGG_NAME', $newsagg_name );
defined( 'NEWSAGG_PHPV' ) or define( 'NEWSAGG_PHPV', $newsagg_min_php_v );
defined( 'NEWSAGG_THE_API' ) or define( 'NEWSAGG_THE_API', 'api.plnia.com' );
defined( 'NEWSAGG_FILE' ) or define( 'NEWSAGG_FILE', $newsagg_file );
defined( 'NEWSAGG_BASENAME' ) or define( 'NEWSAGG_BASENAME', $newsagg_basename );
defined( 'NEWSAGG_PATH_BASE' ) or define( 'NEWSAGG_PATH_BASE', $newsagg_path_base );
defined( 'NEWSAGG_PATH_INC' ) or define( 'NEWSAGG_PATH_INC', $newsagg_path_inc );
defined( 'NEWSAGG_PATH_ASSETS' ) or define( 'NEWSAGG_PATH_ASSETS', $newsagg_path_assets );

require_once( NEWSAGG_PATH_INC . 'newsagg__version_check.php' );
if ( newsagg_php_ver_check() ) {
  include_once( NEWSAGG_PATH_BASE . 'newsagg_init.php' );
  newsagg_init(__FILE__);
}
