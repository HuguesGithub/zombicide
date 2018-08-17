<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageMarketBean
 * @author Hugues
 * @since 1.0.00
 * @version 1.0.00
 */
class WpPageMarketBean extends WpPageBean
{
  /**
   * Class Constructor
   * @param WpPage $WpPage
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->MarketServices = new MarketServices();
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPageContent($WpPage)
  {
    $Bean = new WpPageMarketBean($WpPage);
    return $Bean->getContentPage();
  }
  /**
   * {@inheritDoc}
   * @see PagePageBean::getContentPage()
   */
  public function getContentPage()
  {
    $strBody = '';
    $argsSearch = array('orderby'=> 'rand', 'posts_per_page'=>-1, 'post_status'=>'private');
    $WpPosts = $this->WpPostServices->getArticles(__FILE__, __LINE__, $argsSearch);
    if (!empty($WpPosts)) {
      foreach ($WpPosts as $WpPost) {
        $Market = Market::convertWpPost($WpPost);
        $MarketBean = new MarketBean($Market);
        $strBody .= $MarketBean->getVisitCard();
      }
    }
    $args = array(
      $strBody,
   );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-market.php');
    return vsprintf($str, $args);
  }
  
}
