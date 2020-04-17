<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * init
**/
function newsagg_init($file) {

  require_once( NEWSAGG_PATH_INC . 'News_Agg.php' );
  $newsagg_plugin = new News_Agg();

  if ( ! $newsagg_plugin->isInstalled() ) {
    $newsagg_plugin->install();
  } else {
    $newsagg_plugin->upgrade();
  }

  $newsagg_plugin->addActionsAndFilters();

  if (!$file) {
    $file = __FILE__;
  }
  
  register_activation_hook($file, array(&$newsagg_plugin, 'activate'));
  register_deactivation_hook($file, array(&$newsagg_plugin, 'deactivate'));

  require_once( NEWSAGG_PATH_INC . 'newsagg_Get.php' );
  require_once( NEWSAGG_PATH_INC . 'newsagg_extras.php' );

}
