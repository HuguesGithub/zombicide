<?php
if (!defined('ABSPATH')) {
die('Forbidden');
}
/**
 * AdminSurvivorsBean
 * @version 1.0.00
 * @since 1.0.00
 * @author Hugues
 */
class AdminSurvivorsBean extends AdminPageBean
{
  /**
   * Class Constructor
   **/
  public function __construct()
  {
    parent::__construct(self::CST_SURVIVOR);
    $this->SurvivorServices = FactoryServices::getSurvivorServices();
    $this->title = 'Survivants';
  }
  /**
   * @param array $urlParams
   * @return $Bean
   */
  public static function getStaticContentPage($urlParams)
  {
    $Bean = new AdminSurvivorsBean();
    if (!isset($urlParams[self::CST_POSTACTION])) {
      return $Bean->getListingPage();
    }
    return $Bean->returnPostActionPage($urlParams);
  }
  /**
   * @return string
   */
  public function getListingPage()
  {
    $strRows = '';
    $nbPerPage = 15;
    $curPage = $this->initVar(self::CST_CURPAGE, 1);
    $orderby = $this->initVar(self::CST_ORDERBY, self::CST_NAME);
    $order = $this->initVar(self::CST_ORDER, 'asc');
    $Survivors = $this->SurvivorServices->getSurvivorsWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
    $nbElements = count($Survivors);
    $nbPages = ceil($nbElements/$nbPerPage);
    $curPage = max(1, min($curPage, $nbPages));
    $DisplayedSurvivors = array_slice($Survivors, ($curPage-1)*$nbPerPage, $nbPerPage);
    if (!empty($DisplayedSurvivors)) {
      foreach ($DisplayedSurvivors as $Survivor) {
        $SurvivorBean = new SurvivorBean($Survivor);
        $strRows .= $SurvivorBean->getRowForAdminPage();
      }
    }
    $queryArg = array(self::CST_ONGLET=>self::CST_SURVIVOR,
      self::CST_ORDERBY=>$orderby,
      self::CST_ORDER=>$order
    );
    // Pagination
    $strPagination = $this->getPagination($queryArg, $post_status, $curPage, $nbPages, $nbElements);
    // Sorts
    $queryArg[self::CST_ORDERBY] = self::CST_NAME;
    $queryArg[self::CST_ORDER] = ($orderby==self::CST_NAME && $order=='asc' ? 'desc' : 'asc');
    $urlSortName = $this->getQueryArg($queryArg);
    $args = array(
      // Liste des Survivants affichés - 1
      $strRows,
      // Filtres - 2
      '',
      // Url pour créer un nouveau Survivant - 3
      $this->getQueryArg(array(self::CST_ONGLET=>self::CST_SURVIVOR, self::CST_POSTACTION=>'add')),
      // Subs - 4
      '',
      // Pagination - 5
      $strPagination,
      // class pour le tri sur title - 8
      ($orderby==self::CST_NAME ? $order : 'desc'),
      // url pour le tri sur title - 9
      $urlSortName,
      '','','','','','','','','','','','','','','','','','','','','','','','','');
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/survivor-listing.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function getAddPage()
  {
    $Survivor = new Survivor();
    if (isset($_POST) && !empty($_POST) && $Survivor->updateWithPost($_POST)) {
      $this->SurvivorServices->insert(__FILE__, __LINE__, $Survivor);
      $Survivor->setId(MySQL::getLastInsertId());
      return $this->getAddEditPage($Survivor);
    }
    return $this->getAddEditPage($Survivor, 'Ajouter un Survivant', 'add');
  }
  /**
   * @param int $survivorId
   * @return string
   */
  public function getClonePage($survivorId)
  {
    $Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $survivorId);
    $Survivor->setId('');
    if (isset($_POST) && !empty($_POST) && $Survivor->updateWithPost($_POST)) {
      $this->SurvivorServices->insert(__FILE__, __LINE__, $Survivor);
      $Survivor->setId(MySQL::getLastInsertId());
      return $this->getAddEditPage($Survivor);
    }
    return $this->getAddEditPage($Survivor, 'Créer un Survivant', 'add');
  }
  /**
   * @param int $survivorId
   * @return string
   */
  public function getEditPage($survivorId)
  {
    $Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $survivorId);
    if (isset($_POST) && !empty($_POST) && $Survivor->updateWithPost($_POST)) {
      $this->SurvivorServices->update(__FILE__, __LINE__, $Survivor);
    }
    return $this->getAddEditPage($Survivor);
  }
  /**
   * @param Survivor $Survivor
   * @param string $title
   * @param string $postAction
   * @return string
   */
  public function getAddEditPage($Survivor, $title='Editer un Survivant', $postAction='edit')
  {
    $args = array(
      // Nom de l'interface - 1
      $title,
      // Url de l'action - 2
      '#',
      // Url pour Annuler - 3
      $this->getQueryArg(array(self::CST_ONGLET=>self::CST_SURVIVOR)),
      // Id de la Compétence - 4
      $Survivor->getId(),
      // Type de l'action - 5
      $postAction,
      // Nom du Survivant - 6
      $Survivor->getName(),
      '','','','','','','','','','','','','','',
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/survivor-edit.php');
    return vsprintf($str, $args);
  }
  /**
   * Affiche l'interface de confirmation de suppression. Supprime le Survivant si suppression confirmée.
   * @param int|null $survivorId Identifiant du Survivant à supprimer.
   * @return string
   */
  public function getTrashPage($survivorId=null)
  {
    // Si on confirme la suppression, on le fait et on affiche la nouvelle liste
    if (isset($_POST) && !empty($_POST) && !empty($_POST['survivorIds'])) {
      foreach ($_POST['survivorIds'] as $survivorId) {
        $Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $survivorId);
        $this->SurvivorServices->delete(__FILE__, __LINE__, $Survivor);
      }
      return $this->getListingPage();
    }
    // On affiche l'interface de confirmation de suppression avant de commettre l'irrémédiable.
    $Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $survivorId);
    // Préparation des variables pour la mutualisation de l'interface de suppression
    $title = 'Suppression de survivants';
    $subTitle = 'le survivant suivant';
    $strLis  = '<li><input type="hidden" name="survivorIds[]" value="'.$survivorId.'"/>'.$Survivor->getName().'</li>';
    $urlCancel = $this->getQueryArg(array(self::CST_ONGLET=>self::CST_SURVIVOR));
    return $this->getConfirmDeletePage($title, $subTitle, $strLis, $urlCancel);
  }
  /**
   * Affiche l'interface de confirmation de suppression. Supprime le Survivant si suppression confirmée.
   * @return string
   */
  public function getBulkTrashPage()
  {
    if (isset($_POST['post']) && !empty($_POST['post'])) {
      if (count($_POST['post'])==1) {
        return $this->getTrashPage($_POST['post'][0]);
      } else {
        $title = 'Suppression de survivants';
        $subTitle = 'les survivants suivants';
        $urlCancel = $this->getQueryArg(array(self::CST_ONGLET=>self::CST_SURVIVOR));
        $strLis  = '';
        foreach ($_POST['post'] as $value) {
          $Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $value);
          $strLis .= '<li><input type="hidden" name="survivorIds[]" value="'.$value.'"/>'.$Survivor->getName().'</li>';
        }
        return $this->getConfirmDeletePage($title, $subTitle, $strLis, $urlCancel);
      }
    } else {
      return $this->getListingPage();
    }
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
