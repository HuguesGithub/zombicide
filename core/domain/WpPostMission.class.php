<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPostMission
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WpPostMission extends WpPost
{
  /**
   * @return int
   */
  public function getIdMission()
  {
    return $this->getPostMeta('missionId');
  }
}
