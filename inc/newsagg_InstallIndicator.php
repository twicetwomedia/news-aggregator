<?php
/**
 * InstallIndicator
**/
include_once( 'newsagg_OptionsManager.php' );

class newsagg_InstallIndicator extends newsagg_OptionsManager {

  const optionInstalled = '_installed';
  const optionVersion = '_version';
  const optionKey = '_key';

  /**
   * @return bool indicating if the plugin is installed already
   */
  public function isInstalled() {
    return $this->getOption(self::optionInstalled) == true;
  }

  /**
   * Note in DB that the plugin is installed
   * @return null
   */
  protected function markAsInstalled() {
    return $this->updateOption(self::optionInstalled, true);
  }

  /**
   * Note in DB that the plugin is uninstalled
   * @return bool
   */
  protected function markAsUnInstalled() {
    return $this->deleteOption(self::optionInstalled);
  }

  /**
   * Check if an older version was installed
   * @return null
   */
  protected function getVersionSaved() {
    return $this->getOption(self::optionVersion);
  }

  /**
   * Set a key on install
   * @return null
   */
  protected function generateRandomString($length=18) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
  }
  protected function setRandomKey() {
    return $this->updateOption(self::optionKey, $this->generateRandomString());
  }

  /**
   * Set a version string in the options
   */
  protected function setVersionSaved($version) {
    return $this->updateOption(self::optionVersion, $version);
  }

  /**
   * @return string
   */
  protected function getMainPluginFileName() {
    return basename(dirname(__FILE__)) . 'php';
  }

  /**
   * @return string if found, otherwise null
   */
  public function getPluginHeaderValue($key) {
    // Read the string from the comment header of the main plugin file
    $data = file_get_contents($this->getPluginDir() . DIRECTORY_SEPARATOR . $this->getMainPluginFileName());
    $match = array();
    preg_match('/' . $key . ':\s*(\S+)/', $data, $match);
    if (count($match) >= 1) {
      return $match[1];
    }
    return null;
  }

  /**
   * @return string
   */
  protected function getPluginDir() {
    return dirname(__FILE__);
  }

  /**
   * current version of this plugin
   * @return string
   */
  public function getVersion() {
    return $this->getPluginHeaderValue('Version');
  }


  /**
   * @return bool true if the version saved in the options is earlier than the version declared in getVersion().
   */
  public function isInstalledCodeAnUpgrade() {
    return $this->isSavedVersionLessThan($this->getVersion());
  }

  /**
   * Is the installed code an earlier version than the input version
   * @param  $aVersion string
   * @return bool true if the saved version is earlier (by natural order) than the input version
   */
  public function isSavedVersionLessThan($aVersion) {
    return $this->isVersionLessThan($this->getVersionSaved(), $aVersion);
  }

  /**
   * @param  $aVersion string
   * @return bool true if the saved version is earlier (by natural order) than the input version
   */
  public function isSavedVersionLessThanEqual($aVersion) {
    return $this->isVersionLessThanEqual($this->getVersionSaved(), $aVersion);
  }

  /**
   * @return bool true if version_compare of $versions1 and $version2 shows $version1 as the same or earlier
   */
  public function isVersionLessThanEqual($version1, $version2) {
    return (version_compare($version1, $version2) <= 0);
  }

  /**
   * @return bool true if version_compare of $versions1 and $version2 shows $version1 as earlier
   */
  public function isVersionLessThan($version1, $version2) {
    return (version_compare($version1, $version2) < 0);
  }

  /**
   * Record the installed version to options.
   */
  protected function saveInstalledVersion() {
    $this->setVersionSaved($this->getVersion());
  }

}
