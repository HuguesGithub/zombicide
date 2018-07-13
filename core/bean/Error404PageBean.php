<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Error404PageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Error404PageBean extends PagePageBean
{
  /**
   * Class Constructor
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
  }
  /**
   * @param WpPage $WpPage
   * @return string
   */
  public static function getStaticPageContent($WpPage='')
  {
    $Bean = new Error404PageBean($WpPage);
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
