<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MarketBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MarketBean extends MainPageBean
{
  /**
   * Class Constructor
   * @param Market $Market
   */
  public function __construct($Market='')
  {
    $services = array('Market');
    parent::__construct($services);
    if ($Market=='') {
      $Market = new Market();
    }
    $this->Market = $Market;
  }
  /**
   * @return string
   */  
  public function getVisitCard()
  {
    $Market = $this->Market;
    $args = array(
      $Market->getImgProduct(),
      $Market->getName(),
      $Market->getQuantity(),
      $Market->getPrice(),
      $Market->getDescription(),
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/article-market-cardvisit.php');
    return vsprintf($str, $args);
  }
  
}
