<?php
/**
 * //class__NewsAggregator
**/
class NewsAggregator {

  public function __construct() { 
    //news-aggregator-contstruct
  }

  public function getnews($topic=null) {

    $apiKey = base64_decode( get_option('News_Agg')['apikey'] ) ?: null;

    if ( ($apiKey) && ($topic) ) {

      $t_name = 'newsagg_cache_' . $topic;
      $t_timeout = (60*10);
      $news = get_transient( $t_name );
    
      if ( $news === false ) {

        $api_url = 'https://api.plnia.com/v1/news/';
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
