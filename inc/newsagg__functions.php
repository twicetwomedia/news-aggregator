<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * Functions
**/

if ( ! function_exists( 'newsagg_f_f_f' ) ) {

  function newsagg_f_f_f() {

    check_ajax_referer( 'newsagg_ajax_nonce', 'security' );

    if ( isset($_GET['akey']) && ('' != isset($_GET['akey'])) ) {
      $apiKey = sanitize_text_field( $_GET['akey'] ) ?: null;
    } else {
      $apiKey = isset( get_option('News_Agg')['apikey'] ) ? base64_decode( get_option('News_Agg')['apikey'] ) : null;
    }

    $fff = false;

    if ($apiKey) {
    
      $api_url = 'https://api.plnia.com/v1/f__f__f/';
      $args = array(
        'headers' => array( 
          'Authorization' => $apiKey
        ),
        'body' => array(
          'akey' => $apiKey
        )
      );
      $response = wp_remote_get( $api_url, $args );

      if ($response) {
        $fff = json_decode( wp_remote_retrieve_body( $response ) ) ?: null;

        if ($fff) {
          $options = get_option('News_Agg');
          $options['fff'] = $fff;
          update_option( 'News_Agg', $options );
        }
      }

    }

    wp_die();

  }

}
