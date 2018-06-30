<?php
if (!defined('ABSPATH')) { die('Forbidden'); }
/**
 * Classe HomePageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class HomePageBean extends MainPageBean {

  public function __construct() {
    $services = array('Mission');
    parent::__construct($services);
  }
  
  /**
   * {@inheritDoc}
   * @see MainPageBean::getContentPage()
   */
  public function getContentPage() {
    $strContent  = '<section id="homeSectionArticles" class="batchArticles missions survivors show-survivor">';
    $strContent .= $this->addMoreNews(0, FALSE);
    $strContent .= '</section>';
    $strContent .= '<section class="col-xs-4 col-xs-offset-4">';
    $strContent .= '<div class="text-center"><div id="more_news" class="special_buttons">Plus de news</div></div>';
    $strContent .= '</section>';
    return $strContent.'<div class="clearfix"></div>';
  }
  /**
   * @param number $offset
   * @return string
   */
  public static function staticAddMoreNews($offset=0) {
    $Bean = new HomePageBean();
    return $Bean->addMoreNews($offset);
  }
  /**
   * @param number $offset
   * @param string $isAjax
   * @return string
   */
  public function addMoreNews($offset=0, $isAjax=TRUE) {
  $postStatus = ($this->isAdmin() ? 'publish, private, future' : 'publish');
    $WpPosts = $this->WpPostServices->getArticles(__FILE__, __LINE__, array('orderby'=> 'post_date', 'order'=>'DESC', 'posts_per_page'=>6, 'offset'=>$offset, 'post_status'=>$postStatus));
    $strContent = '';
    if (!empty($WpPosts)) {
      foreach ($WpPosts as $WpPost) {
    $WpBean = $WpPost->getBean();
        $strContent .= $WpBean->displayWpPost(TRUE);
      }
      $strContent .= '<div class="clearfix"></div>';
    }
  
    return ($isAjax ?  '{"homeSectionArticles":'.json_encode($strContent).'}' : $strContent);
  }
  
}
?>
