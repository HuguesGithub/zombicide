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
    $tag = self::CST_SURVIVOR;
    parent::__construct($tag);
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
    switch ($urlParams[self::CST_POSTACTION]) {
      case 'add'   :
        $returned = $Bean->getAddPage();
      break;
      case 'edit'  :
        $returned = $Bean->getEditPage($urlParams['id']);
      break;
      case 'trash' :
        $returned = $Bean->getTrashPage($urlParams['id']);
      break;
      case 'clone' :
        $returned = $Bean->getClonePage($urlParams['id']);
      break;
      case 'Appliquer' :
        // On est dans le cas du bulkAction. On doit donc vérifier l'action.
        if ($urlParams['action']=='trash') {
          $returned = $Bean->getBulkTrashPage();
        } else {
          $returned = $Bean->getListingPage();
        }
      break;
      default      :
        $returned = $Bean->getListingPage();
      break;
    }
    return $returned;
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
    $order = $this->initVar(self::CST_ORDER, 'ASC');
//    $Skills = $this->SkillServices->getSkillsWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
//    $nbElements = count($Skills);
    $nbPages = ceil($nbElements/$nbPerPage);
    $curPage = max(1, min($curPage, $nbPages));
//    $DisplayedSkills = array_slice($Skills, ($curPage-1)*$nbPerPage, $nbPerPage);
//    if (!empty($DisplayedSkills)) {
//      foreach ($DisplayedSkills as $Skill) {
//        $SkillBean = new SkillBean($Skill);
//        $strRows .= $SkillBean->getRowForAdminPage();
//      }
//    }
    $queryArg = array(self::CST_ONGLET=>self::CST_SURVIVOR,
      self::CST_ORDERBY=>$orderby,
      self::CST_ORDER=>$order
    );
    // Pagination
    $strPagination = $this->getPagination($queryArg, $post_status, $curPage, $nbPages, $nbElements);
    // Sorts
    $queryArg[self::CST_ORDERBY] = self::CST_NAME;
    $queryArg[self::CST_ORDER] = ($orderby==self::CST_NAME && $order=='asc' ? 'desc' : 'asc');
    $urlSortTitle = $this->getQueryArg($queryArg);
    $args = array(
	/*
      // Liste des compétences affichées - 1
      $strRows,
      // Filtres - 2
      '',
      // Url pour créer une nouvelle Compétence - 3
      $this->getQueryArg(array(self::CST_ONGLET=>self::CST_SKILL, self::CST_POSTACTION=>'add')),
      // Subs - 4
      '',
      // Pagination - 5
      $strPagination,
      // class pour le tri sur code - 6
      ($orderby=='code' ? $order : 'desc'),
      // url pour le tri sur code - 7
      $urlSortCode,
      // class pour le tri sur title - 8
      ($orderby==self::CST_NAME ? $order : 'desc'),
      // url pour le tri sur title - 9
      $urlSortTitle,
	  */
      '','','','','','','','','','','','','','','','','','','','','','','','','');
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/survivor-listing.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function getAddPage()
  {
  /*
    $Skill = new Skill();
    if (isset($_POST) && !empty($_POST)) {
      $doInsert = $Skill->updateWithPost($_POST);
      if ($doInsert) {
        $this->SkillServices->insert(__FILE__, __LINE__, $Skill);
        $Skill->setId(MySQL::getLastInsertId());
        return $this->getAddEditPage($Skill, 'Editer une Compétence', 'edit');
      }
    }
    return $this->getAddEditPage($Skill, 'Ajouter une Compétence', 'add');
	*/
  }
  /**
   * @return string
   */
  public function getClonePage($skillId)
  {
  /*
    $Skill = $this->SkillServices->select(__FILE__, __LINE__, $skillId);
    $Skill->setId('');
    if (isset($_POST) && !empty($_POST)) {
      $doUpdate = $Skill->updateWithPost($_POST);
      if ($doUpdate) {
        $this->SkillServices->insert(__FILE__, __LINE__, $Skill);
        $Skill->setId(MySQL::getLastInsertId());
        return $this->getAddEditPage($Skill, 'Editer une Compétence', 'edit');
      }
    }
    return $this->getAddEditPage($Skill, 'Créer une Compétence', 'add');
	*/
  }
  /**
   * @return string
   */
  public function getEditPage($skillId)
  {
  /*
    $Skill = $this->SkillServices->select(__FILE__, __LINE__, $skillId);
    if (isset($_POST) && !empty($_POST)) {
      $doUpdate = $Skill->updateWithPost($_POST);
      if ($doUpdate) {
        $this->SkillServices->update(__FILE__, __LINE__, $Skill);
      }
    }
    return $this->getAddEditPage($Skill, 'Editer une Compétence', 'edit');
	*/
  }
  /**
   * @param Survivor $Survivor
   * @param string $title
   * @param string $postAction
   * @return string
   */
  public function getAddEditPage($Survivor, $title, $postAction)
  {
  /*
    $args = array(
      // Nom de l'interface - 1
      $title,
      // Url de l'action - 2
      '#',
      // Url pour Annuler - 3
      $this->getQueryArg(array(self::CST_ONGLET=>self::CST_SKILL)),
      // Id de la Compétence - 4
      $Skill->getId(),
      // Type de l'action - 5
      $postAction,
      // Nom de la Compétence - 6
      $Skill->getName(),
      // Code de la Compétence - 7
      $Skill->getCode(),
      // Description de la Compétence - 8
      $Skill->getDescription(),
      '','','','','','','','','','','','','','',
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/skill-edit.php');
    return vsprintf($str, $args);
	*/
  }
  /**
   * Affiche l'interface de confirmation de suppression. Supprime le Survivant si suppression confirmée.
   * @param int|null $survivorId Identifiant du Survivant à supprimer.
   * @return string
   */
  public function getTrashPage($survivorId=null)
  {
  /*
    // Si on confirme la suppression, on le fait et on affiche la nouvelle liste
    if (isset($_POST) && !empty($_POST) && !empty($_POST['skillIds'])) {
      foreach ($_POST['skillIds'] as $skillId) {
        $Skill = $this->SkillServices->select(__FILE__, __LINE__, $skillId);
        $this->SkillServices->delete(__FILE__, __LINE__, $Skill);
      }
      return $this->getListingPage();
    }
    // On affiche l'interface de confirmation de suppression avant de commettre l'irrémédiable.
    $Skill = $this->SkillServices->select(__FILE__, __LINE__, $skillId);
    // Préparation des variables pour la mutualisation de l'interface de suppression
    $title = 'Suppression de compétences';
    $subTitle = 'la compétence suivante';
    $strLis  = '<li><input type="hidden" name="skillIds[]" value="'.$skillId.'"/>'.$Skill->getName().'</li>';
    $urlCancel = $this->getQueryArg(array(self::CST_ONGLET=>self::CST_SKILL));
    return $this->getConfirmDeletePage($title, $subTitle, $strLis, $urlCancel);
	*/
  }
  /**
   * Affiche l'interface de confirmation de suppression. Supprime le Survivant si suppression confirmée.
   * @param int|null $skillId Identifiant du Survivant à supprimer.
   * @return string
   */
  public function getBulkTrashPage()
  {
  /*
    if (isset($_POST['post']) && !empty($_POST['post'])) {
      if (count($_POST['post'])==1) {
        return $this->getTrashPage($_POST['post'][0]);
      } else {
        $title = 'Suppression de compétences';
        $subTitle = 'les compétences suivantes';
        $urlCancel = $this->getQueryArg(array(self::CST_ONGLET=>self::CST_SKILL));
        $strLis  = '';
        foreach ($_POST['post'] as $value) {
          $Skill = $this->SkillServices->select(__FILE__, __LINE__, $value);
          $strLis .= '<li><input type="hidden" name="skillIds[]" value="'.$value.'"/>'.$Skill->getName().'</li>';
        }
        return $this->getConfirmDeletePage($title, $subTitle, $strLis, $urlCancel);
      }
    } else {
      return $this->getListingPage();
    }
	*/
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
  /*
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
      'trash',
      '','','','','','','','','','','','','','',
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/delete-common-elements.php');
    return vsprintf($str, $args);
	*/
  }





















  
  
  
  
  

}
