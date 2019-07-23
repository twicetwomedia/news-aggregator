<?php
/**
 * //class__NewsAggregator
**/
class NewsAggregator {

  public function __construct() { 
    //news-aggregator-construct
  }

  public function getnews($topic=null) {

    $apiKey = isset( get_option('News_Agg')['apikey'] ) ? base64_decode( get_option('News_Agg')['apikey'] ) : null;

    if ( ($apiKey) && ($topic) ) {

      $t_name = 'newsagg_cache_' . $topic;
      $t_timeout = (60*10);
      $news = get_transient( $t_name );
    
      if ( $news === false ) {

        $host = $_SERVER['SERVER_NAME'] ?: $_SERVER['HTTP_HOST'];
        $api_url = 'https://api.plnia.com/v1/news/';
        $args = array(
          'headers' => array( 
            'Host' => $host,
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
