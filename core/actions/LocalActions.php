<?php
if ( !defined( 'ABSPATH') ) {
  die( 'Forbidden' );
}
/**
 * LocalActions
 * @since 1.0.00
 * @author Hugues
 */
class LocalActions extends GlobalActions implements iConstants {

  /**
   * @param array $services
   */
  public function __construct($services=array()) {
    if ( !empty($services) ) {
      foreach ( $services as $service ) {
        switch ( $service ) {
          case 'Chat'        : $this->ChatServices = FactoryServices::getChatServices(); break;
          case 'Live'        : $this->LiveServices = FactoryServices::getLiveServices(); break;
          default          : break;
        }
      }
    }
  }
  /**
   * @param string $form
   * @param array $arrIgnore
   * @return array
   *
  public static function initAttributes($form, $arrIgnore=array()) {
    $attributes = array();
    $params = explode('&', $form);
    foreach ( $params as $param ) {
      list($key, $value) = explode('=', $param);
      if ( $value=='' && !in_array($key, $arrIgnore) ) { return -1; }
      $attributes[$key] = $value;
    }
    return $attributes;
  }
  */
  

}
?>