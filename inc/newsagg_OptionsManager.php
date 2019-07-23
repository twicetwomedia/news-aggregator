<?php
/**
 * OptionsManager
**/

class newsagg_OptionsManager {

  public function getOptionName() {
    return get_class( $this );
  }

  /**
   * Define your options meta data here as an array, where each element in the array
   * @return array of key=>display-name and/or key=>array(display-name, choice1, choice2, ...)
   */
  public function getOptionMetaData() {
    return array();
  }

  /**
   * @return array of string name of options
   */
  public function getOptionNames() {
    return array_keys($this->getOptionMetaData());
  }

  /**
   * Cleanup: remove all known options from the DB
   * @return void
   */
  protected function deleteSavedOptions() {
    $optionMetaData = $this->getOptionMetaData();
    if ( is_array( $optionMetaData ) ) {
      $options = get_option( $this->getOptionName() );
      if ( ! is_array( $options ) )
        $options = array();
      foreach ( $optionMetaData as $aOptionKey => $aOptionMeta ) {
        if ( isset( $options[$aOptionKey] ) ) {
          unset( $options[$aOptionKey] );
        }
      }
      update_option( $this->getOptionName(), $options );
    }
  }

  /**
   * @return string display name of the plugin to show as a name/title in HTML.
   */
  public function getPluginDisplayName() {
    return get_class($this);
  }

  /**
   * @return string the value from delegated call to get_option(), or optional default value
   * if option is not set.
   */
  public function getOption( $optionName, $default = null ) {
    $options = get_option( $this->getOptionName() );
    if ( ! is_array( $options ) )
      $options = array();

    if ( isset( $options[$optionName] ) ) {
      $retVal = $options[$optionName];
    } elseif ( $default ) {
      $retVal = $default;
    } else {
      $retVal = '';
    }

    return $retVal;
  }

  /**
   * @return bool indicating whether wp_options was changed or not
   */
  public function deleteOption( $optionName ) {
    $options = get_option( $this->getOptionName() );
    if ( ! is_array( $options ) ) {
      $options = array();
    }
    if ( isset( $options[$optionName] ) ) {
      unset( $options[$optionName] );
      return update_option( $this->getOptionName(), $options );
    } else {
      return true;
    }
  }

  /**
   * @return bool from delegated call to updateOption()
   */
  public function addOption( $optionName, $value ) {
    if (strpos($optionName, 'key') !== false) {
      return $this->updateOption( $optionName, base64_encode($value) );
    } else {
      return $this->updateOption( $optionName, $value );
    }
  }

  /**
   * @return bool from delegated call to update_option()
   */
  public function updateOption( $optionName, $value ) {
    $options = get_option( $this->getOptionName() );
    if ( ! is_array( $options ) )
      $options = array();
    if (strpos($optionName, 'key') !== false) {
      $options[$optionName] = base64_encode($value);
    } else {
      $options[$optionName] = $value;
    }
    return update_option( $this->getOptionName(), $options );
  }

  /**
   * @param  $optionName string name of a Role option (see comments in getRoleOption())
   * @return bool indicates if the user has adequate permissions
   */
  public function canUserDoRoleOption($optionName) {
    $roleAllowed = $this->getRoleOption($optionName);
    if ('Anyone' == $roleAllowed) {
      return true;
    }
    return $this->isUserRoleEqualOrBetterThan($roleAllowed);
  }

  public function registerSettings() {
      $settingsGroup = get_class($this) . '-settings-group';
      $optionMetaData = $this->getOptionMetaData();
      foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
          register_setting($settingsGroup, $aOptionMeta);
      }
  }

  /**
   * Creates HTML for the Administration page to set options for this plugin.
   * @return void
   */
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
        <h1 class="news-aggregator-h1"><?php echo esc_html ( $this->getPluginDisplayName() ) . ' '; _e('Settings', 'newsagg'); ?></h1>
        <br />
        <hr />
        <br />
        <p><a href="<?php echo esc_url( 'https://www.plnia.com/get-news-apikey/' ); ?>" target="_blank"><?php echo esc_html( 'Need an API key?' ); ?></a></p>
        <form id="news-aggregator-settings" method="post" action="">
        <?php settings_fields($settingsGroup); ?>
            <table class="custom-plugin form-table"><tbody>
            <?php
            if ($optionMetaData != null) {
                foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                    $displayText = is_array($aOptionMeta) ? $aOptionMeta[0] : $aOptionMeta;
                    ?>
                        <tr valign="top">
                            <th scope="row"><p><label for="<?php echo esc_html( $aOptionKey ); ?>"><?php echo esc_html( $displayText ); ?></label></p></th>
                            <td>
                            <?php $this->createFormControl($aOptionKey, $aOptionMeta, $this->getOption($aOptionKey)); ?>
                            </td>
                        </tr>
                    <?php
                }
            }
            ?>
            <!--<tr><td style="min-height:33px;">&nbsp;</td></tr>-->
            </tbody></table>
            <p class="submit">
              <input type="submit" class="button-primary"
                     value="<?php _e('Save Changes', 'newsagg') ?>"/>
            </p>
        </form>
        <br />
        <hr />
        <div id="news-aggregator-implementation">
          <br />
          <h2>About our free News API</h2>
          <p>If you opt to utilize our plugin and news aggregation for free by utilizing a <a href="<?php echo esc_url( 'https://www.plnia.com/get-news-apikey/' ); ?>" target="_blank">free API key</a>, you will have free, unlimited access to our Trending News feed. If you wish to add news to your site from one of our other 20+ categories, consider purchasing one of our affordable <a href="<?php echo esc_url( 'https://www.plnia.com/pricing/' ); ?>" target="_blank">paid plans</a>.</p>
          <br />
          <h2>Implementation</h2>
          <p>Implementation of News is accomplished via a WordPress shortcode. The defaults set above will be used unless any of the settings are defined within the shortcode itself.</p>
          <br />
          <h3>Attributes</h3>
          <p>All four attributes listed above can also be specified case-by-case when using the shortcode: count, images, topic &amp; style.</p>
          <h4>Available options</h4>
          <p>
            Topic: Trending, Business, Entertainment, Politics, Science, Sports<br />
            Count: Any number between 2 &amp; 8<br />
            Style: light or dark<br />
            Images: show or hide
          </p>
          <br />
          <h3>Example 1</h3>
          <h4>Default implementation using settings above</h4>
          <p><code>[newsaggregator]</code></p>
          <br />
          <h3>Example 2</h3>
          <h4>Implementation with all four attributes defined.</h4>
          <p><code>[newsaggregator topic="technology" count="2" style="dark" images="show"]</code></p>
          <br />
          <h3>Example 3</h3>
          <h4>Implementation only specifying some attributes, and using above defaults for the rest.</h4>
          <p><code>[newsaggregator topic="business" images="hide"]</code></p>
          <br />
          <h3>Example 4</h3>
          <h4>Implementation within a WordPress theme file.</h4>
          <p><code>echo do_shortcode('[newsaggregator topic="sports" count="4"]');</code></p>
          <br />
          <p>
            Powered by <a href="<?php echo esc_url( 'https://www.plnia.com' ); ?>" target="_blank" title="plnia"><img src="<?php echo esc_url( plugins_url('/assets/img/plnia-logo.png', dirname(__FILE__)) ); ?>" alt="plnia" style="width:67px;height:auto;margin-left:2px;vertical-align:bottom" /></a>
          </p>
        </div>
        <br />
        <hr />
        <br />
    <?php

  }

  /**
   * Helper-function outputs the correct form element (input tag, select tag) for the given item
   * @return void
   */
  protected function createFormControl($aOptionKey, $aOptionMeta, $savedOptionValue) {
    if (is_array($aOptionMeta) && count($aOptionMeta) >= 2) { // Drop-down list
        $choices = array_slice($aOptionMeta, 1);
        ?>
        <p><select name="<?php echo esc_html( $aOptionKey ); ?>" id="<?php echo esc_html( $aOptionKey ); ?>">
        <?php
                        foreach ($choices as $aChoice) {
            $selected = ($aChoice == $savedOptionValue) ? 'selected' : '';
            ?>
                <option value="<?php echo esc_html( $aChoice ); ?>" <?php echo $selected ?>><?php echo esc_html( $this->getOptionValueI18nString($aChoice) ); ?></option>
            <?php
        }
        ?>
        </select></p>
        <?php

    }
    elseif (strpos($aOptionKey, 'key') !== false) {
        ?>
        <p><input type="password" name="<?php echo esc_html( $aOptionKey ); ?>" id="<?php echo esc_html( $aOptionKey ); ?>"
                  value="<?php echo esc_attr( base64_decode($savedOptionValue) ); ?>" size="50"/></p>
        <?php
    }
    else { // Simple input field
        ?>
        <p><input type="text" name="<?php echo esc_html( $aOptionKey ); ?>" id="<?php echo esc_html( $aOptionKey ); ?>"
                  value="<?php echo esc_attr( $savedOptionValue ); ?>" size="50"/></p>
        <?php

    }
  }

  /**
   * @param  $optionValue string
   * @return string __($optionValue) if it is listed in this method, otherwise just returns $optionValue
   */
  protected function getOptionValueI18nString($optionValue) {
    switch ($optionValue) {
      case 'true':
        return __('true', '1');
      case 'false':
        return __('false', '0');
      case 'on':
        return __('on', '1');
      case 'off':
        return __('off', '0');
    }
    return $optionValue;
  }

}
