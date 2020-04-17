<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * version check
**/

if ( ! function_exists( 'newsagg_php_ver_wrong' ) ) {

  function newsagg_php_ver_wrong() {
    echo '<div class="updated fade">' .
      __('Error: The plugin "' . NEWSAGG_NAME . '" requires a newer version of PHP.',  'newsagg').
            '<br/>' . __('Minimum version of PHP required: ', 'newsagg') . '<strong>' . NEWSAGG_PHPV . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'newsagg') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
  }

}

if ( ! function_exists( 'newsagg_php_ver_check' ) ) {

  function newsagg_php_ver_check() {
    if ( version_compare(phpversion(), NEWSAGG_PHPV) < 0 ) {
      add_action('admin_notices', 'newsagg_php_ver_wrong');
      return false;
    }
    return true;
  }

}
