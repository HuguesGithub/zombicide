<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * WpPostBean
 * @author Hugues
 */
class WpPostBean extends MainPageBean
{
  /**
   * WpPost affiché
   * @var WpPost $WpPost
   */
  protected $WpPost;
  /**
   * @param string $post
   * @param array $services
   */
  public function __construct($post='', $services=array())
  {
    if ($post=='') {
      $post = get_post();
    }
    if (get_class($post) == 'WpPost') {
      $this->WpPost = $post;
    } else {
      $this->WpPost = WpPost::convertElement($post);
    }
    parent::__construct($services);
  }
  /**
   * @return string|WpPageError404Bean
   */
  public function getContentPage()
  {
    $WpBean = new WpPostMissionBean($this->WpPost);
    $Mission = $WpBean->getMission();
    if ($Mission->getId()!='') {
      $strReturned = $WpBean->getMissionPageContent($Mission);
    } else {
      $WpBean = new WpPostSurvivorBean($this->WpPost);
      $Survivor = $WpBean->getSurvivor();
      if ($Survivor->getId()!='') {
        $strReturned = $WpBean->getSurvivorPageContent($Survivor);
      } else {
        $strReturned = new WpPageError404Bean();
      }
    }
    return $strReturned;
  }
  /**
   * @return string
   */
  public function getShellClass()
  { return ''; }
}
