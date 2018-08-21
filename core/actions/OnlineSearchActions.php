<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * OnlineSearchActions
 * @author Hugues
 * @since 1.0.02
 * @version 1.0.02
 */
class OnlineSearchActions extends OnlineActions
{
  /**
   * Class Constructor
   */
  public function __construct($post)
  {
    parent::__construct($post);
    $this->EquipmentLiveDeckServices = new EquipmentLiveDeckServices();
    $this->LiveSurvivor = $this->LiveSurvivorServices->select(__FILE__, __LINE__, $post[self::CST_LIVESURVIVORID]);
    $this->Live = $this->LiveSurvivor->getLive();
  }
  /**
   * Point d'entrée des méthodes statiques.
   * @param array $post
   * @return string
   **/
  public static function staticSearch($post)
  {
    $OSA = new OnlineSearchActions($post);
    switch ($post['ajaxChildAction']) {
      case 'search' :
      	$returned = $OSA->search();
      break;
      case 'searchConfirm' :
        $returned = $OSA->searchConfirm();
      break;
    }
    return $returned;
  }

  /**
   * @return string
   */
  public function search()
  {
    // On vérifie que le LiveSurvivor a bien accès à au moins une compétence grauite de fouille ou une compétence diverse.
    if (!$this->actionAvailable(13) && !$this->actionAvailable(1)) {
      return $this->getErrorMsg();
    }
    // Faudrait aussi vérifier qu'il n'a pas déjà fouillé ce tour-ci...
    if ($this->LiveSurvivor->hasPlayedThisTurn()) {
      $this->errorMsg = 'Ce Survivant a déjà Fouillé pendant ce tour.';
      return $this->getErrorMsg();
    }
    // Faudrait aussi vérifier qu'il peut fouiller à cet endroit...
    // TODO : Bon, par contre là c'est basique. En effet, il peut Fouiller s'il y a une voiture, ou s'il a certaines compétences...
    if (!$this->LiveSurvivor->getMissionZone()->isSearchable()) {
      $this->errorMsg = 'Ce Survivant ne peut pas Fouillé dans cette Zone.';
      return $this->getErrorMsg();
    }
    // Faudrait vérifier la présence éventuelle de Zombies...
    
    // Une fois qu'on a tout vérifier, on affiche le message dans le Chat
    $this->postChat($this->LiveSurvivor->getSurvivor()->getName().' effectue une Fouille.');
    // Pour le moment, on va juste supprimer l'action liée,
//    $this->LiveSurvivorActionServices->delete(__FILE__, __LINE__, $this->LiveSurvivorAction);
    $post = array(self::CST_KEYACCESS=>$_SESSION[self::CST_DECKKEY]);
    $DeckActions = new DeckActions($post);
    // On pioche une carte...
    // TODO : Faudrait voir pour en piocher plusieurs...
    $DeckActions->drawCardDeck($this->EquipmentLiveDeckServices);
    // On récupère toutes les cartes Actives, au cas où plusieurs seraient affichées en même temps.
    //$EquipmentLiveDecks = $DeckActions->getLiveDecksByStatus('A');
    // On retourne les infos à modifier sur l'interface en mode Json.
    //$pageSelectionResult = WpPageLiveEquipmentBean::getStaticEquipmentCardActives($EquipmentLiveDecks);
    
    $str = '<div id="canvas-content" class="popup-like">
    <form action="#" method="post" class="row">
    <div class="col-12">'.$pageSelectionResult.'
    <div class="input-group mb-3">
    <button class="btn btn-lg btn-outline-secondary" type="button">Equiper</button>
    <button class="btn btn-lg btn-outline-secondary" type="button">Défausser</button>
    </div>
    </div>
    </form>
    </div>';
    
    // On veut retourner une popup pour afficher la carte piochée, et savoir ce que le Survivant veut en faire.
    // Il doit entre autre pouvoir réorganiser son inventaire.
    // Mais dans un premier temps, on va juste équiper en dernière position ou défausser.
    // Lorsqu'on aura valider l'action, on pourra passer à la "confirmation"
    return '{'.$this->getOnlinePopupModal($str).'}';
  }
  public function searchConfirm()
  {
    // On doit donc réagencer l'inventaire, défausser d'éventuelles cartes...
    
    // Et on retourne la toolbar mise à jour.
    $WpPageOnlineBean = new WpPageOnlineBean();
    return '{'.$this->getOnlineBtnActions($WpPageOnlineBean->getActionButtons($this->Live)).'}';
  }
}
