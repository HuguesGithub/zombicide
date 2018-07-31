<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * OnlineActions
 * @version 1.0.01
 * @since 1.0.00
 * @author Hugues
 */
class OnlineActions extends LocalActions
{
  /**
   * Constructeur
   */
  public function __construct($post)
  {
    $this->post = $post;
    $this->LiveMissionServices        = new LiveMissionServices();
    $this->LiveSurvivorServices       = new LiveSurvivorServices();
    $this->LiveSurvivorActionServices = new LiveSurvivorActionServices();
  }
  /**
   * Point d'entrée des méthodes statiques.
   * @param array $post
   * @return string
   **/
  public static function dealWithStatic($post)
  {
    $Act = new OnlineActions($post);
    if ($Act->isLiveSurvivorValidAction()) {
      switch ($post['ajaxChildAction']) {
        case 'startSurvivorTurn' :
          $returned = $Act->startSurvivorTurn();
        break;
        case 'medic' :
          $returned = $Act->medicPopup();
        break;
        case 'endTurn' :
          $returned = $Act->endTurn();
        break;
        case 'makeNoise' :
          $returned = $Act->makeNoise();
        break;
        case 'move' :
          $returned = $Act->moveTo();
        break;
        case 'search' :
          $returned = $Act->search();
        break;
        case 'openDoor' :
          $returned = $Act->openDoor();
        break;
        case 'trade' :
          $returned = $Act->trade();
        break;
        default :
        break;
      }
      // On a préparé le rendu de l'action entreprise.
      // On doit rafraîchier la toolbar, même si ça n'est pas encore nécessaire...
      // Ou alors, on ne le fait que si on a un flag qui dit de le faire... ?
      return $returned;
    } else {
      return $Act->getErrorMsg();
    }
  }
  public function getErrorMsg()
  { return $this->errorMsg; }
  public function getErrorPanel($msg)
  { return '"error-panel":'.json_encode($msg); }
  public function isLiveSurvivorValidAction()
  {
    // On récupère le LiveSurvivor à partir des données du Post.
    $post = $this->post;
    $LiveSurvivor = $this->LiveSurvivorServices->select(__FILE__, __LINE__, $post[self::CST_LIVESURVIVORID]);
    // S'il a déjà joué ce tour, on ne peut pas être là !
    // Enfin, sauf dans le cas d'un Lien Zombie j'imagine, mais on verra ça le moment venu...
    if ($LiveSurvivor->hasPlayedThisTurn()) {
      $this->errorMsg = '{'.getErrorPanel('Ce Survivant a déjà agit ce tour. Il ne peut pas le faire de nouveau.').'}';
      return false;
    }
    // Faudrait vérifier si c'est bien le Survivor actif...
    $LiveMission = $LiveSurvivor->getLiveMission();
    if ($LiveMission->getActiveLiveSurvivorId()!=$LiveSurvivor->getId()) {
      $this->errorMsg = '{'.getErrorPanel('Ce Survivant ne peut pas effectuer cette Action, il n\'est pas le Survivant actif.').'}';
      return false;
    }
    // On peut sauvegarder ce LiveSurvivor, il va servir !
    $this->LiveSurvivor = $LiveSurvivor;
    $this->Live = $LiveSurvivor->getLive();
    return true;
  }
  private function actionAvailable($actionId)
  {
    $args = array(self::CST_LIVESURVIVORID=>$this->LiveSurvivor->getId(), self::CST_ACTIONID=>$actionId);
    $LiveSurvivorActions = $this->LiveSurvivorActionServices->getLiveSurvivorActionsWithFilters(__FILE__, __LINE__, $args);
    if (empty($LiveSurvivorActions)) {
      $this->errorMsg = '{'.getErrorPanel('Ce Survivant ne peut pas effectuer cette Action.').'}';
      return false;
    }
    $this->LiveSurvivorAction = array_shift($LiveSurvivorActions);
    return true;
  }
  private function deleteAndMajToolbar()
  {
    $this->LiveSurvivorActionServices->delete(__FILE__, __LINE__, $this->LiveSurvivorAction);
    // Et on retourne la toolbar mise à jour.
    $WpPageOnlineBean = new WpPageOnlineBean();
    return '{'.$this->getOnlineBtnActions($WpPageOnlineBean->getActionButtons($this->Live)).'}';
  }
  public function medicPopup()
  {
    // On vérifie que le LiveSurvivor a bien accès à Médic.
    if ($this->actionAvailable(33)) {
      // Ensuite, on affiche une popup de confirmation, en renvoyant la dite popup.
      // Si on valide la confirmation, on supprime la LiveSurvivorAction correspondant.
      // On doit cacher la popup.
      
      // Pour le moment, on va juste supprimer l'action liée,
      return $this->deleteAndMajToolbar();
    }
  }
  
  public function endTurn()
  {
    $args = array(self::CST_LIVESURVIVORID=>$this->LiveSurvivor->getId());
    $LiveSurvivorActions = $this->LiveSurvivorActionServices->getLiveSurvivorActionsWithFilters(__FILE__, __LINE__, $args);
    while (!empty($LiveSurvivorActions)) {
      $LiveSurvivorAction = array_shift($LiveSurvivorActions);
      $this->LiveSurvivorActionServices->delete(__FILE__, __LINE__, $LiveSurvivorAction);
    }
    // Et on retourne la toolbar mise à jour.
    $WpPageOnlineBean = new WpPageOnlineBean();
    return '{'.$this->getOnlineBtnActions($WpPageOnlineBean->getActionButtons($this->Live)).'}';
  }
  
  public function makeNoise()
  {
    // On vérifie que le LiveSurvivor a bien accès à au moins une compétence diverse.
    if ($this->actionAvailable(1)) {
      
      // Pour le moment, on va juste supprimer l'action liée,
      return $this->deleteAndMajToolbar();
    }
  }
  
  public function moveTo()
  {
    // On vérifie que le LiveSurvivor a bien accès à au moins une compétence grauite de déplacement ou une compétence diverse.
    if ($this->actionAvailable(12) || $this->actionAvailable(1)) {
      
      // Pour le moment, on va juste supprimer l'action liée,
      return $this->deleteAndMajToolbar();
    }
  }
  
  public function search()
  {
    // On vérifie que le LiveSurvivor a bien accès à au moins une compétence grauite de fouille ou une compétence diverse.
    if ($this->actionAvailable(13) || $this->actionAvailable(1)) {
      
      // Pour le moment, on va juste supprimer l'action liée,
      return $this->deleteAndMajToolbar();
    }
  }
  
  public function openDoor()
  {
    // On vérifie que le LiveSurvivor a bien accès à au moins une compétence grauite de fouille ou une compétence diverse.
    if ($this->actionAvailable(41) || $this->actionAvailable(1)) {
      // Bon pour le coup, faudrait aussi vérifier qu'on a un objet qui peut ouvrir les portes en main
      
      // Pour le moment, on va juste supprimer l'action liée,
      return $this->deleteAndMajToolbar();
    }
  }
  
  public function trade()
  {
  }
  
  public function startSurvivorTurn()
  {
    // Pour qu'un Survivant puisse démarrer son tour, il faut :
    // Vérifier que ce LiveSurvivor peut être actif.
    // Finir le tour du LiveSurvivor actif, s'il y en a un. Et marqué que celui-ci a déjé joué ce tour
    // Purger la table des Actions du précédent LiveSurvivor actif
    // Créer les Actions du nouveau LiveSurvivor actif et les représentées autant que possible.
    // Retourner une toolbar agrémentée des boutons qui vont bien selon le LiveSurvivor actif.
    // Et sans doute d'autres choses...
    $post = $this->post;
    // Le LiveSurvivor qu'on veut voir devenir actif
    // La LiveMission concernée
    $LiveMission = $LiveSurvivor->getLiveMission();
    // Le LiveSurvivor actif, qui si il existe doit être passé en ayant agit ce tour.
    $ActiveLiveSurvivor = $LiveMission->getActiveLiveSurvivor();
    if ($ActiveLiveSurvivor->getId()!='') {
      $ActiveLiveSurvivor->setPlayedThisTurn(1);
      $this->LiveSurvivorServices->update(__FILE__, __LINE__, $ActiveLiveSurvivor);
      // On récupère les actions restantes en base et on les supprime.
      $args = array(self::CST_LIVESURVIVORID=>$LiveSurvivor->getId());
      $LiveSurvivorActions = $this->LiveSurvivorActionServices->getLiveSurvivorActionsWithFilters(__FILE__, __LINE__, $args);
      while (!empty($LiveSurvivorActions)) {
        $LiveSurvivorAction = array_shift($LiveSurvivorActions);
        $this->LiveSurvivorActionServices->delete(__FILE__, __LINE__, $LiveSurvivorAction);
      }
    }
    $LiveMission->setActiveLiveSurvivorId($LiveSurvivor->getId());
    $this->LiveMissionServices->update(__FILE__, __LINE__, $LiveMission);
    // On créé les LiveSurvivorActions du nouveau $LiveSurvivor actif
    $LiveSurvivorActions = $this->LiveSurvivorActionServices->initLiveSurvivorActions($LiveSurvivor);
    while (!empty($LiveSurvivorActions)) {
      $LiveSurvivorAction = array_shift($LiveSurvivorActions);
      $this->LiveSurvivorActionServices->insert(__FILE__, __LINE__, $LiveSurvivorAction);
    }
    $WpPageOnlineBean = new WpPageOnlineBean();
    $Live = $LiveSurvivor->getLive();
    return '{'.$this->getOnlineBtnActions($WpPageOnlineBean->getActionButtons($Live)).'}';
  }
  public function getOnlineBtnActions($content)
  { return '"online-btn-actions": '.json_encode($content); }
}
