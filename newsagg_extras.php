<?php
/**
 * extras
**/
function newsagg_add_settings_link( $links ) {
    $settings_link = '<a href="tools.php?page=News_Agg_Settings">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
add_filter( 'plugin_action_links_' . $newsagg_basename, 'newsagg_add_settings_link' );

add_shortcode( 'newsaggregator', 'newsagg_shortcode' );
function newsagg_shortcode( $atts ) {
  $a = shortcode_atts( array(
      'topic'  => 'Trending',
      'count'  => 4,
      'style'  => 'Light',
      'images' => 'Show'
  ), $atts );
  $topic = isset( get_option('News_Agg')['topic'] ) ? get_option('News_Agg')['topic'] : "{$a['topic']}";
  $topic = strtolower( str_replace(' ', '-', $topic) );
  $count = isset( get_option('News_Agg')['count'] ) ? get_option('News_Agg')['count'] : "{$a['count']}";
  if ( ! is_numeric($count) ) {
    $count = 4;
  }
  $style = isset( get_option('News_Agg')['style'] ) ? get_option('News_Agg')['style'] : "{$a['style']}";
  $style = strtolower($style);
  $images = isset( get_option('News_Agg')['images'] ) ? get_option('News_Agg')['images'] : "{$a['images']}";
  $images = strtolower($images);
  $topic_arr = array( 'trending', 'headlines', 'business', 'entertainment', 'health', 'politics', 'science', 'sports', 'technology' );
  if ( in_array($topic, $topic_arr) ) {
    $newsagg = New NewsAggregator();
    $news = $newsagg->getnews($topic) ?: null;
    $news_display_default = '<!--#newsaggregator--><div id="newsaggregator" class="' . esc_html( strtolower($style) ) . '">Sorry, news is unavailable at this time</div>';
    $news_display = $news_display_default;
    if ($news) {
      $decode = json_decode($news);
      if ($decode) {
        $news_display = '<div id="newsaggregator" class="' . esc_html( strtolower($style) ) . '"><h3 id="newsagg_h3">' . esc_html( ucwords($topic) ) . ' News</h3>';
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
