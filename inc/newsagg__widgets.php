<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * Widgets
**/

add_action( 'widgets_init', function(){
  register_widget( 'News_Aggregator_Widget' );
});

class News_Aggregator_Widget extends WP_Widget {

  public function __construct() {
    $news_aggregator_widget_ops = array( 
      'classname' => 'news_aggregator_widget',
      'description' => 'Display news by topic.',
    );
    parent::__construct( 'news_aggregator_widget', 'News Aggregator', $news_aggregator_widget_ops );
  }
  
  public function widget( $args, $instance ) {
    $topic = $instance['topic'] ?: 'trending';
    $count = $instance['count'] ?: intval(4);
    $style = $instance['style'] ?: 'light';
    $images = $instance['images'] ?: 'show';
    $columns = $instance['columns'] ?: intval(2);
    $shortcode = '[newsaggregator topic="' . $topic . '" count="' . $count . '" style="' . $style . '" images="' . $images . '" columns="' . $columns . '"]';
    echo do_shortcode( $shortcode );
  }

  public function form( $instance ) {
    $topic = ! empty( $instance['topic'] ) ? $instance['topic'] : esc_html__( 'Topic', 'text_domain' );
?>
    <p>
      <strong>Note:</strong> If you have the free news plan, only Trending News will display here. Other topics will display as "unavailable" unless a <a href="<?php echo esc_url( 'https://www.plnia.com/pricing/' ); ?>" target="_blank">paid plan</a> is purchased.
    </p>
    <p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'topic' ) ); ?>">
    <?php esc_attr_e( 'Topic:', 'text_domain' ); ?>
      <select class='widefat' id="<?php echo $this->get_field_id('topic'); ?>"
              name="<?php echo $this->get_field_name('topic'); ?>" type="text">
        <option value='Trending'<?php echo ($topic=='Trending')?'selected':''; ?>>
          Trending
        </option>
        <option value='Business'<?php echo ($topic=='Business')?'selected':''; ?>>
          Business
        </option>
        <option value='Culture'<?php echo ($topic=='Culture')?'selected':''; ?>>
          Culture
        </option>
        <option value='Economy'<?php echo ($topic=='Economy')?'selected':''; ?>>
          Economy
        </option>
        <option value='Entertainment'<?php echo ($topic=='Entertainment')?'selected':''; ?>>
          Entertainment
        </option>
        <option value='Environment'<?php echo ($topic=='Environment')?'selected':''; ?>>
          Environment
        </option>
        <option value='Food'<?php echo ($topic=='Food')?'selected':''; ?>>
          Food
        </option>
        <option value='Health'<?php echo ($topic=='Health')?'selected':''; ?>>
          Health
        </option>
        <option value='Lifestyle'<?php echo ($topic=='Lifestyle')?'selected':''; ?>>
          Lifestyle
        </option>
        <option value='Movies'<?php echo ($topic=='Movies')?'selected':''; ?>>
          Movies
        </option>
        <option value='Music'<?php echo ($topic=='Music')?'selected':''; ?>>
          Music
        </option>
        <option value='Personal Finance'<?php echo ($topic=='Personal Finance')?'selected':''; ?>>
          Personal Finance
        </option>
        <option value='Politics'<?php echo ($topic=='Politics')?'selected':''; ?>>
          Politics
        </option>
        <option value='Science'<?php echo ($topic=='Science')?'selected':''; ?>>
          Science
        </option>
        <option value='Sports'<?php echo ($topic=='Sports')?'selected':''; ?>>
          Sports
        </option>
        <option value='Technology'<?php echo ($topic=='Technology')?'selected':''; ?>>
          Technology
        </option>
        <option value='Travel'<?php echo ($topic=='Travel')?'selected':''; ?>>
          Travel
        </option>
        <option value='Weird'<?php echo ($topic=='Weird')?'selected':''; ?>>
          Weird
        </option> 
        <option value='World'<?php echo ($topic=='World')?'selected':''; ?>>
          World
        </option> 
      </select>                
    </label>
    </p>
<?php
    $count = ! empty( $instance['count'] ) ? $instance['count'] : esc_html__( 'Count', 'text_domain' );
?>
    <p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
    <?php esc_attr_e( 'Count:', 'text_domain' ); ?>
      <select class='widefat' id="<?php echo $this->get_field_id('count'); ?>"
              name="<?php echo $this->get_field_name('count'); ?>" type="text">
        <option value='2'<?php echo ($count=='2')?'selected':''; ?>>
          2
        </option>
        <option value='4'<?php echo ($count=='4')?'selected':''; ?>>
          4
        </option>
        <option value='6'<?php echo ($count=='6')?'selected':''; ?>>
          6
        </option>
        <option value='8'<?php echo ($count=='8')?'selected':''; ?>>
          8
        </option>
      </select>                
    </label>
    </p>
<?php
    $style = ! empty( $instance['style'] ) ? $instance['style'] : esc_html__( 'Style', 'text_domain' );
?>
    <p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">
    <?php esc_attr_e( 'Style:', 'text_domain' ); ?>
      <select class='widefat' id="<?php echo $this->get_field_id('style'); ?>"
              name="<?php echo $this->get_field_name('style'); ?>" type="text">
        <option value='light'<?php echo ($style=='light')?'selected':''; ?>>
          Light
        </option>
        <option value='dark'<?php echo ($style=='dark')?'selected':''; ?>>
          Dark
        </option>
      </select>                
    </label>
    </p>
<?php
    $images = ! empty( $instance['images'] ) ? $instance['images'] : esc_html__( 'Images', 'text_domain' );
?>
    <p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'images' ) ); ?>">
    <?php esc_attr_e( 'Images:', 'text_domain' ); ?>
      <select class='widefat' id="<?php echo $this->get_field_id('images'); ?>"
              name="<?php echo $this->get_field_name('images'); ?>" type="text">
        <option value='show'<?php echo ($images=='show')?'selected':''; ?>>
          Show
        </option>
        <option value='hide'<?php echo ($images=='hide')?'selected':''; ?>>
          Hide
        </option>
      </select>                
    </label>
    </p>
<?php
    $columns = ! empty( $instance['columns'] ) ? $instance['columns'] : esc_html__( 'Columns', 'text_domain' );
?>
    <p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>">
    <?php esc_attr_e( 'Columns:', 'text_domain' ); ?>
      <select class='widefat' id="<?php echo $this->get_field_id('columns'); ?>"
              name="<?php echo $this->get_field_name('columns'); ?>" type="text">
        <option value='2'<?php echo ($columns=='2')?'selected':''; ?>>
          2
        </option>
        <option value='3'<?php echo ($columns=='3')?'selected':''; ?>>
          3
        </option>
        <option value='4'<?php echo ($columns=='4')?'selected':''; ?>>
          4
        </option>
        <option value='1'<?php echo ($columns=='1')?'selected':''; ?>>
          1
        </option>
      </select>                
    </label>
    </p>
    <?php
  }

  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['topic'] = ( ! empty( $new_instance['topic'] ) ) ? strip_tags( $new_instance['topic'] ) : '';
    $instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
    $instance['style'] = ( ! empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : '';
    $instance['images'] = ( ! empty( $new_instance['images'] ) ) ? strip_tags( $new_instance['images'] ) : '';
    $instance['columns'] = ( ! empty( $new_instance['columns'] ) ) ? strip_tags( $new_instance['columns'] ) : '';
    return $instance;
  }

}
