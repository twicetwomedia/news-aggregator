<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * OptionsManager
**/

class newsagg_OptionsManager {

  public function getOptionName() {
    return get_class( $this );
  }

  public function getOptionMetaData() {
    return array();
  }

  public function getOptionNames() {
    return array_keys($this->getOptionMetaData());
  }

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

  public function getPluginDisplayName() {
    return get_class($this);
  }

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

  public function addOption( $optionName, $value ) {
    if (strpos($optionName, 'key') !== false) {
      return $this->updateOption( $optionName, base64_encode($value) );
    } else {
      return $this->updateOption( $optionName, $value );
    }
  }

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

  public function canUserDoRoleOption($optionName) {
    $roleAllowed = $this->getRoleOption($optionName);
    if ('Anyone' == $roleAllowed) {
      return true;
    }
    return $this->isUserRoleEqualOrBetterThan($roleAllowed);
  }

  protected function createFormControl($aOptionKey, $aOptionMeta, $savedOptionValue, $disabled=false) {
    if (is_array($aOptionMeta) && count($aOptionMeta) >= 2) { // Drop-down list
        $choices = array_slice($aOptionMeta, 1);
        ?>
        <p><select name="<?php echo esc_html( $aOptionKey ); ?>" id="<?php echo esc_html( $aOptionKey ); ?>">
        <?php
          $i = 0;
          foreach ($choices as $aChoice) {
            $i++;
            $selectedd = ($aChoice == $savedOptionValue) ? 'selected' : '';
            $disabledd = ( ($disabled) && ($i > 1) ) ? 'disabled' : '';
            ?>
                <option value="<?php echo esc_html( $aChoice ); ?>" <?php echo $selectedd; ?> <?php echo $disabledd; ?>><?php echo esc_html( $this->getOptionValueI18nString($aChoice) ); ?></option>
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

  public function registerSettings() {
    $settingsGroup = get_class($this) . '-settings-group';
    $optionMetaData = $this->getOptionMetaData();
    foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
        register_setting($settingsGroup, $aOptionMeta);
    }
  }

  public function settingsPage() {
  }

}
