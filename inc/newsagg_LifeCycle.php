<?php
if ( ! defined('ABSPATH') ) { exit; }
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
    $this->setTopic();
    $this->setCount();
    $this->setStyle();
    $this->setImages();
    $this->setColumns();
  }

  public function uninstall() {
    $this->deleteSavedOptions();
    $this->markAsUnInstalled();
  }

  public function upgrade() {
  }

  public function activate() {  
    $this->saveInstalledVersion(); 
  }

  public function deactivate() {
    $this->saveInstalledVersion(); 
  }

  protected function initOptions() {
  }

  public function addActionsAndFilters() {
  }

  protected function requireExtraPluginFiles() {
  }

  protected function getSettingsSlug() {
    return get_class($this) . '_Settings';
  }

  public function addSettingsSubMenuPage() {
    $this->addSettingsSubMenuPageNav();
  }

  protected function addSettingsSubMenuPageNav() {
  }

}
