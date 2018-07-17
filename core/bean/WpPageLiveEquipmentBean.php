<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageLiveEquipmentBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WpPageLiveEquipmentBean extends PagePageBean
{
  /**
   * Class Constructor
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->EquipmentServices = FactoryServices::getEquipmentServices();
    $this->EquipmentExpansionServices = FactoryServices::getEquipmentExpansionServices();
    $this->EquipmentLiveDeckServices = FactoryServices::getEquipmentLiveDeckServices();
    $this->ExpansionServices = FactoryServices::getExpansionServices();
    $this->LiveServices = FactoryServices::getLiveServices();
  }
  /**
   * @param WpPage $WpPage
   * @return string
   */
  public static function getStaticPageContent($WpPage='')
  {
    $Bean = new WpPageLiveEquipmentBean($WpPage);
    return $Bean->getEquipmentDeckContent();
  }
  private function createEquipmentLiveDeck($args)
  {
    // On vérifie l'existence éventuelle d'un Live avec cette clé.
    $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
    if (empty($Lives)) {
      // S'il n'en existe pas, on en créé un.
      $args['dateUpdate'] = date(self::CST_FORMATDATE);
      $Live = new Live($args);
      $this->LiveServices->insert(__FILE__, __LINE__, $Live);
      $Live->setId(MySQL::getLastInsertId());
    } else {
      // Sinon, on récupère celui existant.
      $Live = array_shift($Lives);
    }
    $arrEE = array();
    // Maintenant on va créer les cartes associées.
    if (!empty($_POST)) {
      foreach ($_POST as $key => $value) {
        list($t, $id) = explode('-', $key);
        if ($t!='ec') {
          continue;
        }
        // On va devoir stocker les identifiants des cartes.
        for ($i=0; $i<$value; $i++) {
          $arrEE[] = $id;
        }
      }
      // On mélange ces cartes
      shuffle($arrEE);
      // On prépare l'insertion en base
      $EquipmentLiveDeck = new EquipmentLiveDeck(array('liveId'=>$Live->getId(), 'status'=>'P'));
      $cpt = 1;
      while (!empty($arrEE)) {
        $id = array_shift($arrEE);
        $EquipmentLiveDeck->setEquipmentCardId($id);
        $EquipmentLiveDeck->setRank($cpt);
        $cpt++;
        $this->EquipmentLiveDeckServices->insert(__FILE__, __LINE__, $EquipmentLiveDeck);
      }
      return $Live;
    } else {
      return new Live();
    }
  }
  private function getDeckButtons($Live)
  {
    $str  = '';
    $str .= '<div class="btn-group-vertical live-spawn-selection" role="group">';
    $deckKey = $Live->getDeckKey();
    $str .= $this->getButtonDiv('btnDisabled1', $deckKey, 'Actions disponibles :', '', 'btn-dark disabled');
    $label = 'Piocher une carte (<span id="nbCardInDeck">'.$Live->getNbCardsInDeck('equipment').'</span>)';
    $str .= $this->getButtonDiv('btnDrawEquipmentCard', $deckKey, 'drawEquipmentCard', $label);
    $str .= $this->getButtonDiv('btnEquipEquipmentActive', $deckKey, 'equipEquipmentActive', 'Equiper les cartes piochées');
    $label = 'Afficher les cartes équipées (<span id="nbCardEquipped">'.$Live->getNbCardsEquipped().'</span>)';
    $str .= $this->getButtonDiv('btnShowEquipEquipment', $deckKey, 'showEquipmentEquip', $label);
    $str .= $this->getButtonDiv('btnDiscardEquipmentActive', $deckKey, 'discardEquipmentActive', 'Défausser les cartes piochées');
    $str .= $this->getButtonDiv('btnShowDiscardEquipment', $deckKey, 'showEquipmentDiscard', 'Afficher la défausse');
    $label = 'Remélanger la défausse (<span id="nbCardInDiscard">'.$Live->getNbCardsInDiscard('equipment').'</span>)';
    $str .= $this->getButtonDiv('btnShuffleDiscardEquipment', $deckKey, 'shuffleEquipmentDiscard', $label);
    $str .= $this->getButtonDiv('btnLeaveEquipmentDeck', $deckKey, 'leaveEquipmentDeck', 'Quitter cette pioche', 'reload');
    $str .= $this->getButtonDiv('btnDisabled2', $deckKey, 'Attention, action irréversible :', '', 'btn-dark disabled');
    $str .= $this->getButtonDiv('btnDeleteEquipmentDeck', $deckKey, 'deleteEquipmentDeck', 'Supprimer cette pioche', 'reload', 'btn-danger');
    return $str.'</div>';
  }
  private function getButtonDiv($id, $deckKey, $action, $label, $type='insert', $classe='btn-dark')
  {
    return '<div type="button" id="'.$id.'" class="btn '.$classe.' withSpawnAction" data-type="'.$type.'" data-action="'.$action.'" data-keyaccess="'.$deckKey.'">'.$label.'</div>';
  }
  /**
   * @return string
   */
  public function getEquipmentDeckContent()
  {
    $blocExpansions = '';
    $showSelection = '';
    $strEquipments = '';
    if (isset($_SESSION[self::CST_DECKKEY]) && $_SESSION[self::CST_DECKKEY]!='') {
      // On a un KeyAccess en Session
      $deckKey = $_SESSION[self::CST_DECKKEY];
      $args = array('deckKey'=>$deckKey);
      $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
      $Live = array_shift($Lives);
      if ($Live==null) {
        unset($_SESSION[self::CST_DECKKEY]);
        $blocExpansions = $this->nonLoggedInterface();
      } else {
        if ($Live->getNbCardsInDeck('equipment')+$Live->getNbCardsInDiscard('equipment')==0) {
          if ($deckKey!='') {
            // On a un KeyAccess en Formulaire
            $args = array('deckKey'=>$deckKey);
            $Live = $this->createEquipmentLiveDeck($args);
            $blocExpansions = $this->getDeckButtons($Live);
            $showSelection = 'hidden';
            $_SESSION[self::CST_DECKKEY] = $deckKey;
          } else {
            // On n'a rien
            $blocExpansions = $this->nonLoggedInterface();
          }
        } else {
          // On a une Session et des cartes.
          $blocExpansions = $this->getDeckButtons($Live);
          $showSelection = 'hidden';
        }
      }
    } else {
      // On n'a pas de Session, en a-t-on un en POST ?
      $deckKey = $this->initVar('keyAccess');
      if ($deckKey!='') {
        // On a un KeyAccess en Formulaire
        $args = array('deckKey'=>$deckKey);
        $Live = $this->createEquipmentLiveDeck($args);
        $blocExpansions = $this->getDeckButtons($Live);
        $showSelection = 'hidden';
        $_SESSION[self::CST_DECKKEY] = $deckKey;
      } else {
        // On n'a rien
        $blocExpansions = $this->nonLoggedInterface();
      }
    }
    if ($showSelection=='hidden') {
      // On a des cartes, par défaut, on affiche les cartes "actives".
      $arrFilters = array(self::CST_LIVEID=>$Live->getId(), self::CST_STATUS=>'A');
      $EquipmentLiveDecks = $this->EquipmentLiveDeckServices->getEquipmentLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
      $strEquipments = $this->getEquipmentCardActives($EquipmentLiveDecks);
    }
    $args = array(
      $blocExpansions,
      $showSelection,
      $strEquipments,
      $deckKey,
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-live-equipment-deck.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  private function nonLoggedInterface()
  {
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array(), array('displayRank'), array('ASC'));
    $str = '';
    if (!empty($Expansions)) {
      $str .= '<div class="btn-group-vertical live-equipment-selection" role="group">';
      foreach ($Expansions as $Expansion) {
        $id = $Expansion->getId();
        $EquipmentCards = $this->EquipmentExpansionServices->getEquipmentExpansionsWithFilters(__FILE__, __LINE__, array('expansionId'=>$id));
        if (!empty($EquipmentCards)) {
          $str .= '<div type="button" class="btn btn-dark btn-expansion" data-expansion-id="'.$id.'"><span class="';
          $str .= '"><i class="far fa-square"></i></span> '.$Expansion->getName().'</div>';
        }
      }
      $str .= '</div>';
    }
    return $str;
  }
  /**
   * @param array $EquipmentLiveDecks
   * @return string
   */
  public static function getStaticEquipmentCardActives($EquipmentLiveDecks, $showDiscardButton)
  {
    $Bean = new WpPageLiveEquipmentBean();
    return $Bean->getEquipmentCardActives($EquipmentLiveDecks, $showDiscardButton);
  }
  /**
   * @param array $EquipmentLiveDecks
   * @return string
   */
  public function getEquipmentCardActives($EquipmentLiveDecks, $showDiscardButton=false)
  {
    $strEquipments = '';
    if (!empty($EquipmentLiveDecks)) {
      foreach ($EquipmentLiveDecks as $EquipmentLiveDeck) {
        $EquipmentExpansion = $this->EquipmentExpansionServices->select(__FILE__, __LINE__, $EquipmentLiveDeck->getEquipmentCardId());
        $Equipment = $this->EquipmentServices->select(__FILE__, __LINE__, $EquipmentExpansion->getEquipmentCardId());
        $EquipmentBean = new EquipmentBean($Equipment);
        $strEquipments .= $EquipmentBean->displayCard($EquipmentExpansion->getExpansionId(), ($showDiscardButton?$EquipmentLiveDeck->getId():-1));
      }
    }
    return $strEquipments;
  }
}
