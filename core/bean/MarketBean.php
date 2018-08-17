<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MarketBean
 * @author Hugues
 * @since 1.0.00
 * @version 1.0.00
 */
class MarketBean extends LocalBean
{
  /**
   * Class Constructor
   * @param Market $Market
   */
  public function __construct($Market='')
  {
    parent::__construct();
    $this->Market = ($Market=='' ? new Market() : $Market);
    $this->MarketServices = new MarketServices();
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
