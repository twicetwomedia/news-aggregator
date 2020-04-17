<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * //class__NewsAggregator
**/
class NewsAggregator {

  public function __construct() {}

  public function getnews($topic=null) {

    $apiKey = isset( get_option('News_Agg')['apikey'] ) ? base64_decode( get_option('News_Agg')['apikey'] ) : null;

    if ( ($apiKey) && ($topic) ) {

      $t_name = 'newsagg_cache_' . $topic;
      $t_timeout = 20 * MINUTE_IN_SECONDS;
      $news = get_transient( $t_name );
    
      if ( $news === false ) {

        $host = $_SERVER['SERVER_NAME'] ?: $_SERVER['HTTP_HOST'];
        $api_url = 'https://' . NEWSAGG_THE_API . '/v1/news/';
        $args = array(
          'headers' => array( 
            'Authorization' => $apiKey
          ),
          'body' => array(
            'topic' => urlencode($topic)
          )
        );
        $response = wp_remote_get( $api_url, $args );

        if ($response) {

          $api_result = wp_remote_retrieve_body( $response );

          if ($api_result) {

            set_transient( $t_name, $api_result, $t_timeout );
            $news = $api_result;

          }
          
        }

      }

      return $news;

    }

  }

}
