<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * WpPageBean
 * @author Hugues
 */
class WpPageBean extends MainPageBean
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
  public function __construct($post='')
  {
    if ($post=='') {
      $post = get_post();
    }
    if (get_class($post) == 'WpPost') {
      $this->WpPage = $post;
    } else {
      $this->WpPage = WpPost::convertElement($post);
    }
    parent::__construct();
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
      case 'page-live-pioche-equipment' :
        $strReturned = WpPageLiveEquipmentBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-live-pioche-invasion' :
        $strReturned = WpPageLiveSpawnBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-market'               :
        $strReturned = WpPageMarketBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-missions'             :
        $strReturned = WpPageMissionsBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-partie-online'        :
        $strReturned = WpPageOnlineBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-piste-de-des'         :
        $strReturned = WpPageToolsBean::getStaticPisteContent($this->WpPage);
      break;
      case 'page-selection-survivants' :
        $strReturned = WpPageToolsBean::getStaticSurvivorsContent($this->WpPage);
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
        $strReturned = new WpPageError404Bean();
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
