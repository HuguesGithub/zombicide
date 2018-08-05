<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class AdminPageBean extends MainPageBean
{
  /**
   * @var string WP_DB_BACKUP_CRON
   */
  const WP_DB_BACKUP_CRON = 'wp_db_backup_cron';
  /**
   * @param string $tag
   */
  public function __construct($tag='')
  {
    parent::__construct();
    $this->analyzeUri();
    $this->tableName = 'wp_11_zombicide_'.$tag;
    $this->tplAdminerUrl  = 'http://zombicide.jhugues.fr/wp-content/plugins/adminer/inc/adminer/loader.php';
    $this->tplAdminerUrl .= '?username=dbo507551204&db=db507551204&table='.$this->tableName;
  }

  /**
   * @return string
   */
  public function analyzeUri()
  {
    $uri = $_SERVER['REQUEST_URI'];
    $pos = strpos($uri, '?');
    if ($pos!==false) {
      $arrParams = explode('&', substr($uri, $pos+1, strlen($uri)));
      if (!empty($arrParams)) {
        foreach ($arrParams as $param) {
          list($key, $value) = explode('=', $param);
          $this->urlParams[$key] = $value;
        }
      }
      $uri = substr($uri, 0, $pos-1);
    }
    $pos = strpos($uri, '#');
    if ($pos!==false) {
      $this->anchor = substr($uri, $pos+1, strlen($uri));
    }
    if (isset($_POST)) {
      foreach ($_POST as $key => $value) {
        $this->urlParams[$key] = $value;
      }
    }
    return $uri;
  }
  /**
   * @return string
   */
  public function getContentPage()
  {
    if (self::isAdmin()) {
      switch ($this->urlParams['onglet']) {
        case 'mission'    :
          $returned = AdminPageMissionsBean::getStaticContentPage($this->urlParams);
        break;
        case 'parametre'  :
          $returned = AdminPageParametresBean::getStaticContentPage($this->urlParams);
        break;
        case 'skill'  :
          $returned = AdminPageSkillsBean::getStaticContentPage($this->urlParams);
        break;
        case 'survivor'  :
          $returned = AdminPageSurvivorsBean::getStaticContentPage($this->urlParams);
        break;
        case ''       :
          $returned = $this->getHomeContentPage();
        break;
        default       :
          $returned = "Need to add <b>".$this->urlParams['onglet']."</b> to AdminPageBean > getContentPage().";
        break;
      }
    }
    return $returned;
  }
  /**
   * @return string
   */
  public function getHomeContentPage()
  {
    $reset = $this->initVar('reset', '');
    $doReset = !empty($reset);
    if ($doReset) {
      $ts = time();
      list($N, $d, $m, $y) = explode(' ', date('N d m y', $ts));
      $nd = $d + ($N==1 ? 1 : 9-$N);
      $resetTs = mktime(1, 0, 0, $m, $nd, $y);
    }
    $request = "SELECT option_value FROM wp_11_options WHERE option_name='cron';";
    $row = MySQL::wpdbSelect($request);
    $Obj = array_shift($row);
    $arrOptions = unserialize($Obj->option_value);
    foreach ($arrOptions as $key => $value) {
      if (isset($value[WP_DB_BACKUP_CRON])) {
        $nextTs = $key;
        $arrOptions[$resetTs][WP_DB_BACKUP_CRON] = $value[WP_DB_BACKUP_CRON];
        unset($arrOptions[$key]);
      }
    }
    if ($doReset) {
      $serialized = serialize($arrOptions);
      $request = "UPDATE wp_11_options SET option_value='$serialized' WHERE option_name='cron';";
    }
    $args = array(
    // Date de la prochaine sauvegarde - 1
      date('d/m/Y h:i:00', $nextTs),
   );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/home-admin-board.php');
    return vsprintf($str, $args);
  }

  /**
   * @param array $queryArg
   * @param string $post_status
   * @param int $curPage
   * @param int $nbPages
   * @param int $nbElements
   */
  protected function getPagination($queryArg, $post_status, $curPage, $nbPages, $nbElements)
  {
    $queryArg[self::CST_POSTSTATUS] = $post_status;
    $queryArg[self::CST_CURPAGE] = 1;
    $hrefFirst = $this->getQueryArg($queryArg);
    $queryArg[self::CST_CURPAGE] = max(1, $curPage-1);
    $hrefPrev = $this->getQueryArg($queryArg);
    $queryArg[self::CST_CURPAGE] = min($nbPages, $curPage+1);
    $hrefNext = $this->getQueryArg($queryArg);
    $queryArg[self::CST_CURPAGE] = $nbPages;
    $hrefLast = $this->getQueryArg($queryArg);
    $args = array(
      // Nombre d'éléments
         $nbElements,
      // Disable First/Prev Page
      $curPage==1 ? ' disabled' : '',
      // URL First Page
      $hrefFirst,
      // URL Prev Page
      $hrefPrev,
      // Page courante
      $curPage,
      // Nombre de pages
      $nbPages,
      // Disable Next/Last Page
      $curPage==$nbPages ? ' disabled' : '',
      // URL Next Page
      $hrefNext,
      // URL Last Page
      $hrefLast,
  );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/fragment-pagination.php');
    return vsprintf($str, $args);
  }
  /**
   * @param array $urlParams
   * @return string
   */
  public function returnPostActionPage($urlParams)
  {
    switch ($urlParams[self::CST_POSTACTION]) {
      case 'add'   :
        $returned = $this->getAddPage();
      break;
      case 'edit'  :
        $returned = $this->getEditPage($urlParams['id']);
      break;
      case self::CST_TRASH :
        $returned = $this->getTrashPage($urlParams['id']);
      break;
      case 'clone' :
        $returned = $this->getClonePage($urlParams['id']);
      break;
      case 'Appliquer' :
        // On est dans le cas du bulkAction. On doit donc vérifier l'action.
        if ($urlParams['action']==self::CST_TRASH) {
          $returned = $this->getBulkTrashPage();
        } else {
          $returned = $this->getListingPage();
        }
      break;
      default      :
        $returned = $this->getListingPage();
      break;
    }
    return $returned;
  }
  
  /**
   * Retourne l'interface commune de confirmation de suppression d'éléments
   * @param string $title Titre de la page
   * @param string $subTitle Libellé spécifique à la suppression
   * @param string $strLis Liste des lis des éléments à supprimer
   * @param string $urlCancel Url de rollback si on annule la suppression.
   * @return string
   */
  public function getConfirmDeletePage($title, $subTitle, $strLis, $urlCancel)
  {
    // Les données de l'interface.
    $args = array(
      // Titre de l'opération - 1
      $title,
      // Url de l'action - 2
      '#',
      // - 3
      $subTitle,
      // Liste des éléments qui vont être supprimés - 4
      $strLis,
      // Url pour Annuler - 5
      $urlCancel,
      // Postaction - 6
      self::CST_TRASH,
      '','','','','','','','','','','','','','',
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/delete-common-elements.php');
    return vsprintf($str, $args);
  }
}
