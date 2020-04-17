<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * News_Agg
**/
include_once( 'newsagg_LifeCycle.php' );

class News_Agg extends newsagg_LifeCycle {

  /**
   * @return array of option meta data
   */
  public function getOptionMetaData() {
    return array(
      'apikey'  => array(__('API Key', 'newsagg')),
      'topic'   => array(__('Default Topic', 'newsagg'), 'Trending', 'Astronomy', 'Business', 'Culture', 'Economy', 'Entertainment', 'Environment', 'Food', 'Health', 'Investing', 'Lifestyle', 'Movies', 'Music', 'Personal Finance', 'Politics', 'Science', 'Sports', 'Technology', 'Travel', 'Weird', 'World'),
      'count'   => array(__('Default Count', 'newsagg'), '8', '6', '4', '2'),
      'style'   => array(__('Default Style', 'newsagg'), 'Light', 'Dark'),
      'images'  => array(__('Show Images?', 'newsagg'), 'Show', 'Hide'),
      'columns' => array(__('Number of Columns?', 'newsagg'), '2', '3', '4', '1')
    );
  }

  public function getPluginDisplayName() {
    return 'News Aggregator';
  }

  protected function getMainPluginFileName() {
    return 'news-aggregator.php';
  }

  protected function getPluginDir() {
    $name = dirname(__FILE__);
    if ( strpos($name, '/inc') !== false ) {
      $name = str_replace('/inc', '', $name);
    }
    return $name;
  }

  public function upgrade($upgrade_now=true) {
    $saved_version = $this->getVersionSaved();
    $curr_version = $this->getVersion();
    if ( ($upgrade_now) && ($saved_version != $curr_version) ) {
      $this->saveInstalledVersion();
    }
  }

  public function newsagg_check_for_jquery() {
    if ( ! wp_script_is( 'jquery' ) ) {
      wp_enqueue_script("jquery");
    }
  }

  public function newsagg_styles_and_scripts() {
    wp_enqueue_style( 'newsaggregator-css', plugins_url('/assets/css/newsaggregator.min.css', dirname(__FILE__)), array(), NEWSAGG_VER );
  }

  public function newsagg_admin_styles() {
    wp_enqueue_style( 'newsaggregator-admin-css', plugins_url('/assets/css/newsaggregator-admin.min.css', dirname(__FILE__)), array(), NEWSAGG_VER );
  }

  public function newsagg_add_settings_link( $links ) {
    $settings_link = '<a href="tools.php?page=News_Agg_Settings">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
  }

  protected function addSettingsSubMenuPageNav() {
    $displayName = $this->getPluginDisplayName();
    add_management_page(
      $displayName,
      $displayName,
      'manage_options',
      $this->getSettingsSlug(),
      array(&$this, 'settingsPage')
    );
  }

  public function addActionsAndFilters() {
    add_action( 'admin_menu', array(&$this, 'addSettingsSubMenuPage') );
    add_action( 'init', array(&$this, 'newsagg_check_for_jquery') );
    add_action( 'wp_enqueue_scripts', array(&$this, 'newsagg_styles_and_scripts') );
    add_action( 'admin_enqueue_scripts', array(&$this, 'newsagg_admin_styles') );
    add_action( 'wp_ajax_newsagg_f_f_f', 'newsagg_f_f_f' );
    add_filter( 'plugin_action_links_' . NEWSAGG_BASENAME, array(&$this, 'newsagg_add_settings_link') );
  }

  public function settingsPage() {
    if (!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.', 'newsagg'));
    }

    $optionMetaData = $this->getOptionMetaData();

    // Save Posted Options
    if ($optionMetaData != null) {
      foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
        if (isset($_POST[$aOptionKey])) {
          $this->updateOption( $aOptionKey, sanitize_text_field($_POST[$aOptionKey]) );
        }
      }
    }

    // HTML for the page
    $settingsGroup = get_class($this) . '-settings-group';
    ?>
        <br />
        <img src="<?php echo esc_url( plugins_url('/assets/img/icon-256x256.png', dirname(__FILE__)) ); ?>" alt="<?php echo esc_html ( $this->getPluginDisplayName() ); ?>" class="newsagg-h1-img" /><h1 class="news-aggregator-h1"><?php echo esc_html ( $this->getPluginDisplayName() ) . ' '; _e('Settings', 'newsagg'); ?></h1>
        <br />
        <hr />
        <br />
        <form id="news-aggregator-settings" method="post" action="">
        <p><a href="<?php echo esc_url( 'https://www.plnia.com/get-news-apikey/' ); ?>" target="_blank"><?php echo esc_html( 'Need an API key?' ); ?></a></p>
        <?php settings_fields($settingsGroup); ?>
            <table class="custom-plugin form-table"><tbody>
            <?php
            if ($optionMetaData != null) {
              $disabled = false;

              foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                $displayText = is_array($aOptionMeta) ? $aOptionMeta[0] : $aOptionMeta;
                if ('topic' == $aOptionKey) {
                  $fff = isset( get_option('News_Agg')['fff'] ) ? get_option('News_Agg')['fff'] : null;
                  if ( ! $fff ) {
                    $disabled = true;
                  }
                  if ( ($fff) && ('nwz_' == $fff) ) {
                    $disabled = true;
                  }
                }
        ?>
                      <tr valign="top">
                          <th scope="row"><p><label for="<?php echo esc_html( $aOptionKey ); ?>"><?php echo esc_html( $displayText ); ?></label></p></th>
                          <td>
                          <?php 
                            if ('topic' == $aOptionKey) {
                              $this->createFormControl($aOptionKey, $aOptionMeta, $this->getOption($aOptionKey), $disabled); 
                            } else {
                              $this->createFormControl($aOptionKey, $aOptionMeta, $this->getOption($aOptionKey), false); 
                            }
                            ?>
                          </td>
                      </tr>
        <?php
              }
            }
            ?>
            <!--<tr><td style="min-height:33px;">&nbsp;</td></tr>-->
            </tbody></table>
            <p class="submit">
              <input type="submit" id="submit-newsagg-options" class="button-primary"
                     value="<?php _e('Save Changes', 'newsagg') ?>"/>
            </p>
        </form>
        <br />
        <hr />
        <div id="news-aggregator-implementation">
          <a name="about-free"></a>
          <br />
          <h2>How to Use</h2>
          <p>You'll need a <a href="<?php echo esc_url( 'https://www.plnia.com/get-news-apikey/' ); ?>" target="_blank" rel="noopener">free API key</a> for (unlimited) access to our <strong>Trending News</strong> feed.</p> 
          <p><strong>To add news from any of our other 20+ categories, consider one of our <a href="<?php echo esc_url( 'https://www.plnia.com/pricing/' ); ?>" target="_blank" rel="noopener">paid plans</a></strong>.</p>
          <br />
          <h2>Implementation</h2>
          <p>Implementation of News is accomplished via a <strong>WordPress shortcode</strong> or with our <strong>News Aggregator widget</strong>. The defaults set on this page above will be used unless any of the settings are defined within the shortcode or the widget itself.</p>
          <br />
          <h3>Attributes</h3>
          <p>All five attributes listed above can also be specified case-by-case when using the shortcode: count, images, topic, style, &amp; columns. Columns specifies how many columns you want to use to display the news items. For example, if you choose 1 for columns, the news items will be stacked in one column (possibly useful in a sidebar).</p>
          <h4>Available options (<a href="<?php echo esc_url( 'https://www.plnia.com/get-news-apikey/' ); ?>" target="_blank" rel="noopener">free plan</a>)</h4>
          <p>
            Topic: <em>Trending News only</em><br />
            Count: Any number between 2 &amp; 8<br />
            Style: light or dark<br />
            Images: show or hide<br />
            Columns: 1, 2, 3, or 4<br />
          </p>
          <h4>Available options (<a href="<?php echo esc_url( 'https://www.plnia.com/pricing/' ); ?>" target="_blank" rel="noopener">any other plan</a>)</h4>
          <p>
            Topic: Trending, Astronomy, Business, Culture, Economy, Entertainment, Environment, Food, Health, Investing, Lifestyle, Movies, Music, Personal Finance, Politics, Science, Sports, Technology, Travel, Weird, World<br />
            Count: Any number between 2 &amp; 8<br />
            Style: light or dark<br />
            Images: show or hide<br />
            Columns: 1, 2, 3, or 4<br />
          </p>
          <br />
          <h2>Widget</h2>
          <h3>Example</h3>
          <p>
            1) Navigate to Appearance -> Widgets.<br />
            2) Find the News Aggregator widget &amp; drag or add it to the sidebar/area of choice.<br />
            3) Select options in the News Aggregator widget &amp; click save.
          </p>
          <br />
          <h2>Shortcode</h2>
          <p>Learn more: <a href="<?php echo esc_url( 'https://en.support.wordpress.com/shortcodes/' ); ?>" target="_blank" rel="noopener">What are WordPress Shortcodes?</a></p>
          <br />
          <h3>Example 1</h3>
          <h4>Default display using the default settings specified above.</h4>
          <p><code>[newsaggregator]</code></p>
          <br />
          <h3>Example 2</h3>
          <h4>Display with all five attributes defined.</h4>
          <p><code>[newsaggregator topic="technology" count="2" style="dark" images="show" columns="3"]</code></p>
          <br />
          <h3>Example 3</h3>
          <h4>Display only specifying some attributes, and using above defaults for the rest.</h4>
          <p><code>[newsaggregator topic="business" images="hide"]</code></p>
          <br />
          <h3>Example 4</h3>
          <h4>Display of eight Sports news items in a single column in the light theme.</h4>
          <p><code>[newsaggregator topic="sports" count="8" style="light" colums="1"]</code></p>
          <br />
          <h3>Example 5</h3>
          <h4>Implementation within a WordPress theme file.</h4>
          <p><code>&lt;?php echo do_shortcode('[newsaggregator topic="sports" count="4"]'); ?&gt;</code></p>
          <br />
          <p>
            Powered by <a href="<?php echo esc_url( 'https://www.plnia.com/products/news-aggregation-api/' ); ?>" target="_blank" title="plnia" rel="noopener"><img src="<?php echo esc_url( plugins_url('/assets/img/plnia-logo.png', dirname(__FILE__)) ); ?>" alt="plnia" class="plnia-logo" /></a>
          </p>
        </div>
        <br />
        <hr />
        <script>
          jQuery(document).on( "click", "#submit-newsagg-options", function(e) {
            e.preventDefault();
            var akey = jQuery("#apikey").val();
            var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';   
            var newsagg_nonce = '<?php echo wp_create_nonce( "newsagg_ajax_nonce" ); ?>';  
            var the_ajax_action = 'newsagg_f_f_f';
            var kdata = {
              'action'   : the_ajax_action,
              'security' : newsagg_nonce,
              'akey'     : akey
            };
            jQuery.get(ajaxurl, kdata, function(res){ 
              jQuery("#news-aggregator-settings").submit();
            });
          });
        </script>
    <?php

  }

}
