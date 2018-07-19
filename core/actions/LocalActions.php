<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * LocalActions
 * @since 1.0.00
 * @author Hugues
 */
class LocalActions extends GlobalActions implements ConstantsInterface
{
  /**
   * @param array $services
   */
  public function __construct($services=array())
  {
    if (!empty($services)) {
      foreach ($services as $service) {
        switch ($service) {
          case 'Chat'      :
            $this->ChatServices = FactoryServices::getChatServices();
          break;
          case 'Live'      :
            $this->LiveServices = FactoryServices::getLiveServices();
          break;
          default          :
          break;
        }
      }
    }
  }
}
