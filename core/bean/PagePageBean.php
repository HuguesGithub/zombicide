<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * PagePageBean
 * @author Hugues
 */
class PagePageBean extends MainPageBean
{
  /**
   * WpPost affiché
   * @var WpPost $WpPage
   */
  protected $WpPage;
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
      $this->WpPage = $post;
    } else {
      $this->WpPage = WpPost::convertElement($post);
    }
    parent::__construct($services);
  }
  /**
   * @return string|Error404PageBean
   */
  public function getContentPage()
  {
    switch ($this->WpPage->getPostName()) {
      case 'page-competences'          :
        $strReturned = WpPageSkillsBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-live-pioche-invasion' :
        $strReturned = WpPageLiveSpawnBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-market'               :
        $strReturned = MarketPageBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-missions'             :
        $strReturned = WpPageMissionsBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-partie-online'        :
        $strReturned = OnlinePageBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-piste-de-des'         :
        $strReturned = ToolsPageBean::getStaticPisteContent($this->WpPage);
      break;
      case 'page-selection-survivants' :
        $strReturned = ToolsPageBean::getStaticSurvivorsContent($this->WpPage);
      break;
      case 'page-equipmentcards'       :
        $strReturned = WpPageEquipmentsBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-spawncards'           :
        $strReturned = WpPageSpawnsBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-survivants'           :
        $strReturned = WpPageSurvivorsBean::getStaticPageContent($this->WpPage);
      break;
      default                          :
        $strReturned = new Error404PageBean();
      break;
    }
    return $strReturned;
  }
  /**
   * {@inheritDoc}
   * @see MainPageBean::getShellClass()
   */
  public function getShellClass()
  { return ''; }

  /**
   * Retourne la liste des liens numérotés d'une pagination
   * @param int $curPage Page courante
   * @param int $nbPages Nombre de pages
   * @return string
   */
  protected function getPaginateLis($curPage, $nbPages)
  {
    $strPagination = '';
    for ($i=1; $i<=$nbPages; $i++) {
      $strPagination .= '<li class="page-item'.($i==$curPage?' disabled':'').'"><a class="page-link ';
      $strPagination .= 'ajaxAction" href="#" data-paged="'.$i.'" data-ajaxaction="paged">'.$i.'</a></li>';
    }
    return $strPagination;
  }
}
