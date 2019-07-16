<?php
/**
 * LifeCycle
**/
include_once( 'newsagg_InstallIndicator.php' );

class newsagg_LifeCycle extends newsagg_InstallIndicator {

  public function install() {
    $this->initOptions();
    $this->saveInstalledVersion();
    $this->markAsInstalled();
    $this->setRandomKey();
  }

  public function uninstall() {
      $this->deleteSavedOptions();
      $this->markAsUnInstalled();
  }

  /**
   * @return void
   */
  public function upgrade() {
  }

  /**
   * @return void
   */
  public function activate() {   
  }

  /**
   * @return void
   */
  public function deactivate() {
  }

  /**
   * @return void
   */
  protected function initOptions() {
  }

  public function addActionsAndFilters() {
  }

  /**
   * @return void
   */
  public function addSettingsSubMenuPage() {
    $this->addSettingsSubMenuPageNav();
  }

  protected function requireExtraPluginFiles() {
      //nada2seehear
  }

  /**
   * @return string Slug name for the URL to the Setting page
   */
  protected function getSettingsSlug() {
    return get_class($this) . '_Settings';
  }

  protected function addSettingsSubMenuPageNav() {
    $this->requireExtraPluginFiles();
    $displayName = $this->getPluginDisplayName();
    add_management_page($displayName,
                     $displayName,
                     'manage_options',
                     $this->getSettingsSlug(),
                     array(&$this, 'settingsPage'));
  }

}
