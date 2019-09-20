<?php
/*
   Plugin Name: News Aggregator
   Plugin URI: https://twicetwomedia.com/wordpress-plugins/
   Description: Simple news aggregation feeds for your website.
   Version: 0.1.3
   Author: twicetwomedia
   Author URI: https://twicetwomedia.com
   Text Domain: newsagg
   License: GPLv3
  */
$newsagg_minReqPhpV = '5.6';
$newsagg_basename   = plugin_basename( __FILE__ );
$newsagg_path       = plugin_dir_path( __FILE__ );

function newsagg_PhpVerWrong() {
  global $newsagg_minReqPhpV;
  echo '<div class="updated fade">' .
    __('Error: The plugin "News Aggregator" requires a newer version of PHP to be running.',  'newsagg').
          '<br/>' . __('Minimum version of PHP required: ', 'newsagg') . '<strong>' . $newsagg_minReqPhpV . '</strong>' .
          '<br/>' . __('Your server\'s PHP version: ', 'newsagg') . '<strong>' . phpversion() . '</strong>' .
       '</div>';
}
function newsagg_PhpVerCheck() {
  global $newsagg_minReqPhpV;
  if (version_compare(phpversion(), $newsagg_minReqPhpV) < 0) {
    add_action('admin_notices', 'newsagg_PhpVerWrong');
    return false;
  }
  return true;
}

if ( newsagg_PhpVerCheck() ) {
  include_once( $newsagg_path . 'newsagg_init.php' );
  newsagg_init(__FILE__);
}
