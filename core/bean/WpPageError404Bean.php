<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageError404Bean
 * @author Hugues
 * @since 1.0.00
 * @version 1.0.00
 */
class WpPageError404Bean extends WpPageBean
{
  /**
   * @param WpPage $WpPage
   * @return string
   */
  public static function getStaticPageContent($WpPage='')
  {
    $Bean = new WpPageError404Bean($WpPage);
    return $Bean->getContentPage();
  }
  /**
   * @return string
   */
  public function getContentPage()
  {
    return '<section id="page-live-spawn">Oops</section>';
  }
}
