<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * OnlineActions
 * @since 1.0.00
 * @version 1.0.01
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
    $this->LiveZombieServices         = new LiveZombieServices();
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
      $this->errorMsg = '{'.$this->getErrorPanel('Ce Survivant a déjà agit ce tour. Il ne peut pas le faire de nouveau.').'}';
      return false;
    }
    // On peut sauvegarder ce LiveSurvivor, il va servir !
    $this->LiveSurvivor = $LiveSurvivor;
    $this->Live = $LiveSurvivor->getLive();
    // Faudrait vérifier si c'est bien le Survivor actif...
    // On ne vérifie pas si le LiveSurvivor sélectionné est l'actif quand on veut qu'il le devienne...
    if ($post['ajaxChildAction']!='startSurvivorTurn') {
      $LiveMission = $LiveSurvivor->getLiveMission();
      if ($LiveMission->getActiveLiveSurvivorId()!=$LiveSurvivor->getId()) {
        $this->errorMsg = '{'.$this->getErrorPanel('Ce Survivant ne peut pas effectuer cette Action, il n\'est pas le Survivant actif.').'}';
        return false;
      }
      $this->LiveMission = $LiveMission;
    }
    return true;
  }
  private function actionAvailable($actionId)
  {
    $args = array(self::CST_LIVESURVIVORID=>$this->LiveSurvivor->getId(), self::CST_ACTIONID=>$actionId);
    $LiveSurvivorActions = $this->LiveSurvivorActionServices->getLiveSurvivorActionsWithFilters(__FILE__, __LINE__, $args);
    if (empty($LiveSurvivorActions)) {
      $this->errorMsg = '{'.$this->getErrorPanel('Ce Survivant ne peut pas effectuer cette Action.').'}';
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
  public function activateZombies($missionZoneId='', $Live='')
  {
    if ($this->Live=='') {
      $this->Live = $Live;
    }
    // Si missionZoneId n'est pas '', on n'active que les Zombies de cette Zone.
    // Sinon, on active tous les Zombies du jeu.
    $args = array(self::CST_LIVEID=>$this->Live->getId());
    if ($missionZoneId!='') {
      $args['missionZoneId'] = $missionZoneId;
    }
    $LiveZombies = $this->LiveZombieServices->getLiveZombiesWithFilters(__FILE__, __LINE__, $args);
    $this->postChat('Les Zombies se déplacent');
    
    $ZombiePath = new ZombiePath();
    while (!empty($LiveZombies)) {
      $LiveZombie = array_shift($LiveZombies);
      $MissionZone = $LiveZombie->getMissionZone();
      // TODO : Faudrait tester s'ils ont un visuel...
      // TODO : Faudrait gérer les cas à plusieurs Zones les plus bruyantes.
      // Sans visuel, et avec une seule Zone la plus bruyante, an récupère la missionZoneId de destination
      // On construit l'Arbre des déplacements.
			$ZombiePath->buildZombiePathToLouderZone();
      $targetMissionZoneId = $ZombiePath->searchTreeForFather($MissionZone->getZoneNum());
      $str .= "[[$targetMissionZoneId]]";
      // TODO : Faudrait envisager de gérer la gestion des portes fermées / ouvertes...
      // Voire comment gérer pour les Zombies ayant plusieurs Actions par Activations
      $LiveZombie->setMissionZoneId($targetMissionZoneId);
      $this->LiveZombieServices->update(__FILE__, __LINE__, $LiveZombie);
    }
    return '{"online-popup-modal": '.json_encode($str).'}';
  }
  public function endTurn()
  {
    // On voudrait récupérer le LiveSurvivor qui doit jouer après.
    // On a plusieurs situations :
    $NextLiveSurvivor = $this->LiveMission->getNextLiveSurvivor();
    if ($NextLiveSurvivor===false) {
      // On vient de jouer le dernier Survivant du tour, il faut jouer les Zombies maintenant.
      // Mais avant, il faut que le LiveSurvivorActif soit taggué comme ayant joué.
      // Et on supprime ses éventuelles actions restantes.
      $this->endActiveLiveSurvivorTurn();
      // Maintenant, on gère le tour des Zombies..
      $this->activateZombies();
      $this->postChat('Les Zombies spawnent');
      // TODO : Et c'est pas gagné..
      // Ensuite, on doit gére le nouveau tour.
      // On incrémente le tour courant de LiveMission.
      $this->LiveMission->setTurn($this->LiveMission->getTurn()+1);
      $this->LiveMissionServices->update(__FILE__, __LINE__, $this->LiveMission);
      $this->postChat('Le Tour '.$this->LiveMission->getTurn().' débute.');
      // On prend tous les LiveSurvivors et on remet playedThisTurn à 0.
      $LiveSurvivors = $this->LiveMission->getLiveSurvivors();
      while (!empty($LiveSurvivors)) {
        $LiveSurvivor = array_shift($LiveSurvivors);
        $LiveSurvivor->setPlayedThisTurn(0);
        $this->LiveSurvivorServices->update(__FILE__, __LINE__, $LiveSurvivor);
      }
      // Puis on définit le nouveau Premier Joueur que l'on met en Survivant Actif.
      $NextLiveSurvivor = $this->LiveMission->getFirstLiveSurvivor();
      $this->startNextActiveLiveSurvivorTurn($NextLiveSurvivor);
      
      // On peut retourner la toolBar mise à jour.
      $WpPageOnlineBean = new WpPageOnlineBean();
      return '{'.$this->getOnlineBtnActions($WpPageOnlineBean->getActionButtons($this->Live)).'}';
    } elseif ($NextLiveSurvivor->getId()=='') {
      // On est dans le premier tour, on ne sait pas qui sera le prochain.
      $args = array(self::CST_LIVEID=>$this->Live->getId(), 'playedThisTurn'=>0);
      $LiveSurvivors = $this->LiveSurvivorServices->getLiveSurvivorsWithFilters(__FILE__, __LINE__, $args);
      $strDivs = '';
      while (!empty($LiveSurvivors)) {
        $LiveSurvivor = array_shift($LiveSurvivors);
        if ($LiveSurvivor->getId()==$this->LiveMission->getActiveLiveSurvivorId()) {
          continue;
        }
        $strDivs .= '<div type="button" class="btn btn-dark btn-choose-survivor" data-livesurvivor-id="'.$LiveSurvivor->getId().'">';
        $strDivs .= $LiveSurvivor->getSurvivor()->getName().'</div>';
      }
      $msg = '<div id="canvas-content" class="popup-like">'.$strDivs.'</div>';      
      return '{"online-popup-modal": '.json_encode($msg).'}';
    } else {
      // On a récupéré le prochain Survivant à jouer.
      // On met fin au tour du précédent Survivant
      $this->endActiveLiveSurvivorTurn();
      // On démarre le tour du suivant
      $this->startNextActiveLiveSurvivorTurn($NextLiveSurvivor);
      // On peut retourner la toolBar mise à jour.
      $WpPageOnlineBean = new WpPageOnlineBean();
      return '{'.$this->getOnlineBtnActions($WpPageOnlineBean->getActionButtons($this->Live)).'}';
    }
  }
  private function endActiveLiveSurvivorTurn()
  {
    $ActiveLiveSurvivor = $this->LiveSurvivor;
    // On marque que l'Active LiveSurvivor a joué ce tour.
    $ActiveLiveSurvivor->setPlayedThisTurn(1);
    $this->LiveSurvivorServices->update(__FILE__, __LINE__, $ActiveLiveSurvivor);
    // On récupère les actions restantes en base et on les supprime.
    $args = array(self::CST_LIVESURVIVORID=>$ActiveLiveSurvivor->getId());
    $LiveSurvivorActions = $this->LiveSurvivorActionServices->getLiveSurvivorActionsWithFilters(__FILE__, __LINE__, $args);
    while (!empty($LiveSurvivorActions)) {
      $LiveSurvivorAction = array_shift($LiveSurvivorActions);
      $this->LiveSurvivorActionServices->delete(__FILE__, __LINE__, $LiveSurvivorAction);
    }
  }
  private function startNextActiveLiveSurvivorTurn($LiveSurvivor)
  {
    $this->LiveMission = $LiveSurvivor->getLiveMission();
    // On enregistre l'id du Survivant actif
    $this->LiveMission->setActiveLiveSurvivorId($LiveSurvivor->getId());
    $this->LiveMissionServices->update(__FILE__, __LINE__, $this->LiveMission);
    // On créé les LiveSurvivorActions du nouveau $LiveSurvivor actif
    $LiveSurvivorActions = $this->LiveSurvivorActionServices->initLiveSurvivorActions($LiveSurvivor);
    while (!empty($LiveSurvivorActions)) {
      $LiveSurvivorAction = array_shift($LiveSurvivorActions);
      $this->LiveSurvivorActionServices->insert(__FILE__, __LINE__, $LiveSurvivorAction);
    }
    $this->postChat($LiveSurvivor->getSurvivor()->getName().' débute son tour.');
  }
  public function makeNoise()
  {
    // On vérifie que le LiveSurvivor a bien accès à au moins une compétence diverse.
    if ($this->actionAvailable(1)) {
      $this->postChat($this->LiveSurvivor->getSurvivor()->getName().' fait du bruit.');
      
      // Pour le moment, on va juste supprimer l'action liée,
      return $this->deleteAndMajToolbar();
    }
  }
  
  public function moveTo()
  {
    // On vérifie que le LiveSurvivor a bien accès à au moins une compétence grauite de déplacement ou une compétence diverse.
    if ($this->actionAvailable(12) || $this->actionAvailable(1)) {
      $this->postChat($this->LiveSurvivor->getSurvivor()->getName().' se déplace.');
      
      // Pour le moment, on va juste supprimer l'action liée,
      return $this->deleteAndMajToolbar();
    }
  }
  
  public function search()
  {
    // On vérifie que le LiveSurvivor a bien accès à au moins une compétence grauite de fouille ou une compétence diverse.
    if ($this->actionAvailable(13)) {
    } elseif ($this->actionAvailable(1)) {
    } else {
      return $this->getErrorMsg();
    }
    // Faudrait aussi vérifier qu'il n'a pas déjà fouillé ce tour-ci...
    // Faudrait aussi vérifier qu'il peut fouiller à cet endroit...
    $this->postChat($this->LiveSurvivor->getSurvivor()->getName().' effectue une Fouille.');
     
    // Pour le moment, on va juste supprimer l'action liée,
    return $this->deleteAndMajToolbar();
  }
  
  public function openDoor()
  {
    // On vérifie que le LiveSurvivor a bien accès à au moins une compétence grauite de fouille ou une compétence diverse.
    if ($this->actionAvailable(41) || $this->actionAvailable(1)) {
      // Bon pour le coup, faudrait aussi vérifier qu'on a un objet qui peut ouvrir les portes en main
      $this->postChat($this->LiveSurvivor->getSurvivor()->getName().' ouvre une porte.');
      
      // Pour le moment, on va juste supprimer l'action liée,
      return $this->deleteAndMajToolbar();
    }
  }
  
  public function trade()
  {
    // On vérifie que le LiveSurvivor a bien accès à au moins une compétence grauite de fouille ou une compétence diverse.
    if ($this->actionAvailable(1)) {
      $this->postChat($this->LiveSurvivor->getSurvivor()->getName().' effectue un échange.');
      
      // Pour le moment, on va juste supprimer l'action liée,
      return $this->deleteAndMajToolbar();
    }
  }
  
  public function startSurvivorTurn()
  {
    $NextActiveLiveSurvivor = $this->LiveSurvivor;
    $this->LiveSurvivor = $NextActiveLiveSurvivor->getLiveMission()->getActiveLiveSurvivor();
    if ($NextActiveLiveSurvivor->getTurnRank()==0) {
      $NextActiveLiveSurvivor->setTurnRank($this->LiveSurvivor->getTurnRank()+1);
      $this->LiveSurvivorServices->update(__FILE__, __LINE__, $NextActiveLiveSurvivor);
    }
    // Pour qu'un Survivant puisse démarrer son tour, il faut :
    // Finir le tour du LiveSurvivor actif, s'il y en a un. Et marqué que celui-ci a déjé joué ce tour
    // Purger la table des Actions du précédent LiveSurvivor actif
    $this->endActiveLiveSurvivorTurn();
    // Créer les Actions du nouveau LiveSurvivor actif et les représentées autant que possible.
    $this->startNextActiveLiveSurvivorTurn($NextActiveLiveSurvivor);
    // Retourner une toolbar agrémentée des boutons qui vont bien selon le LiveSurvivor actif.
    $WpPageOnlineBean = new WpPageOnlineBean();
    return '{"online-popup-modal": '.json_encode('').', '.$this->getOnlineBtnActions($WpPageOnlineBean->getActionButtons($this->Live)).'}';
  }
  public function getOnlineBtnActions($content)
  { return '"online-btn-actions": '.json_encode($content); }
  
  public function postChat($msg)
  {
    $args = array(
      self::CST_TEXTE=>$msg,
      self::CST_LIVEID=>$this->Live->getId(),
      'userId'=>0,
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE)
    );
    ChatActions::staticPostChat($args);
  }
}
