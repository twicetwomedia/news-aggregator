<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * Extras
**/

if ( ! function_exists( 'newsagg_shortcode' ) ) {

  add_shortcode( 'newsaggregator', 'newsagg_shortcode' );

  function newsagg_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'topic'   => '',
        'count'   => '',
        'style'   => '',
        'images'  => '',
        'columns' => ''
    ), $atts );
    $topic = $a['topic'];
    if ('' === $topic) {
      $topic = isset( get_option('News_Agg')['topic'] ) ? get_option('News_Agg')['topic'] : 'trending';
    }
    $topic = strtolower( str_replace(' ', '-', $topic) );
    $count = $a['count'];
    if ('' === $count) {
      $count = isset( get_option('News_Agg')['count'] ) ? get_option('News_Agg')['count'] : intval(4);
    }
    if ( ! is_numeric($count) ) {
      $count = intval(4);
    }
    $style = $a['style'];
    if ('' === $style) {
      $style = isset( get_option('News_Agg')['style'] ) ? get_option('News_Agg')['style'] : 'light';
    }
    $style = strtolower($style);
    $images = $a['images'];
    if ('' === $images) {
      $images = isset( get_option('News_Agg')['images'] ) ? get_option('News_Agg')['images'] : 'show';
    }
    $images = strtolower($images);
    $columns = $a['columns'];
    if ('' === $columns) {
      $columns = isset( get_option('News_Agg')['columns'] ) ? get_option('News_Agg')['columns'] : intval(2);
    }
    if ( ! is_numeric($columns) ) {
      $columns = intval(2);
    }
    $topic_arr = array( 'trending', 'headlines', 'astronomy', 'business', 'culture', 'economy', 'entertainment', 'environment', 'food', 'health', 'investing', 'lifestyle', 'movies', 'music', 'personal-finance', 'politics', 'science', 'sports', 'technology', 'travel', 'weird', 'world' );
    if ( in_array($topic, $topic_arr) ) {
      $newsagg = New NewsAggregator();
      $news = $newsagg->getnews($topic) ?: null;
      $news_display_default = '<!--#newsaggregator--><div id="newsaggregator" class="cols' . esc_html( strtolower($columns) ) . ' ' . esc_html( strtolower($style) ) . '">Sorry, news is unavailable at this time</div>';
      $news_display = $news_display_default;
      if ($news) {
        $decode = json_decode($news);
        if ($decode) {
          $news_display = '<div id="newsaggregator" class="cols' . esc_html( strtolower($columns) ) . ' ' . esc_html( strtolower($style) ) . '"><h3 id="newsagg_h3">' . esc_html( ucwords($topic) ) . ' News</h3>';
          $i = 0;
          $how_many = array();
          foreach ($decode as $n) {
            if ( isset($n->title) ) {
              $i++;
              if ($i <= $count) {
                $img_display = '';
                if ('show' == $images) {
                  $img = plugins_url('/assets/img/news-default.jpg', __FILE__);
                  if ( ($n->image) && ('' != $n->image) ) {
                    $img = $n->image;
                  }
                  $img_display = '<a href="' . esc_url( $n->link ) . '" title="' . esc_html( $n->title ) . '"><div class="news-img" style="background-image:url('. esc_url( $img ) . ');"></div></a>';
                }
                $news_display .= '<div class="news-item">' . $img_display . '<a href="' . esc_url( $n->link ) . '" title="' . esc_html ( $n->title ) . '">' . esc_html( $n->title ) . '</a> <span>[' . esc_html( $n->source ) . ']</span></div>';
                $how_many[] = $n->title;
              }
            }
          }
          $news_display .= '</div><!--..#newsaggregator-->';
          if ( empty($how_many) ) {
            $news_display = $news_display_default;
          }
        }
      }
      return $news_display;
    } else {
      return '<!--news aggregator error-->';     
    }

  }

}
