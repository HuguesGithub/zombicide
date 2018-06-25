<?php
if (!defined('ABSPATH') ) { die('Forbidden' ); }
/**
 * Classe WpPostMission
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WpPostMission extends WpPost {
  
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array()) {
    parent::__construct($attributes);
  }
  /**
   * @return int
   */
  public function getIdMission() {
    return $this->getPostMeta('missionId');
  }
  
}
?>