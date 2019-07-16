<?php
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
      'apikey' => array(__('API Key', 'newsagg')),
      'topic' => array(__('Default Topic', 'newsagg'), 'Trending', 'Astronomy', 'Business', 'Culture', 'Economy', 'Entertainment', 'Environment', 'Food', 'Health', 'Investing', 'Lifestyle', 'Movies', 'Music', 'Personal Finance', 'Politics', 'Science', 'Sports', 'Technology', 'Travel', 'Weird', 'World'),
      'count' => array(__('Default Count', 'newsagg'), '8', '6', '4', '2'),
      'style' => array(__('Default Style', 'newsagg'), 'Light', 'Dark'),
      'images' => array(__('Show Images?', 'newsagg'), 'Show', 'Hide')
    );
  }

  protected function initOptions() {
    $options = $this->getOptionMetaData();
    if (!empty($options)) {
      foreach ($options as $key => $arr) {
        if (is_array($arr) && count($arr > 1)) {
          $this->addOption($key, $arr[1]);
        }
      }
    }
  }

  public function getPluginDisplayName() {
    return 'News Aggregator';
  }

  protected function getMainPluginFileName() {
    return 'news-aggregator.php';
  }

  public function upgrade() {
  }

  public function newsagg_check_for_jquery() {
    if ( ! wp_script_is( 'jquery' ) ) {
      wp_enqueue_script("jquery");
    }
  }

  public function newsagg_styles_and_scripts() {
    wp_enqueue_style( 'newsaggregator-css', plugins_url('/assets/css/newsaggregator.css', dirname(__FILE__) ));
  }

  public function newsagg_admin_styles() {
    wp_enqueue_style( 'newsaggregator-css', plugins_url('/assets/css/newsaggregator-admin.css', dirname(__FILE__) ));
  }

  public function addActionsAndFilters() {
    add_action( 'admin_menu', array(&$this, 'addSettingsSubMenuPage') );
    add_action( 'init', array(&$this, 'newsagg_check_for_jquery') );
    add_action( 'wp_enqueue_scripts', array(&$this, 'newsagg_styles_and_scripts') );
    add_action( 'admin_enqueue_scripts', array(&$this, 'newsagg_admin_styles') );
  } 

}

include_once( 'newsagg_Get.php' );
