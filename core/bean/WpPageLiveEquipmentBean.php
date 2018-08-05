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
class WpPageLiveEquipmentBean extends WpPageBean
{
  private $labelDraw = 'Piocher une carte (<span id="nbCardInDeck">%1$s</span>)';
  private $labelDisplay = 'Afficher les cartes équipées (<span id="nbCardEquipped">%1$s</span>)';
  private $labelShuffle = 'Remélanger la défausse (<span id="nbCardInDiscard">%1$s</span>)';

  /**
   * Class Constructor
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->EquipmentServices          = new EquipmentServices();
    $this->EquipmentExpansionServices = new EquipmentExpansionServices();
    $this->EquipmentLiveDeckServices  = new EquipmentLiveDeckServices();
    $this->ExpansionServices          = new ExpansionServices();
    $this->LiveServices               = new LiveServices();
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
      $EquipmentLiveDeck = new EquipmentLiveDeck(array(self::CST_LIVEID=>$Live->getId(), self::CST_STATUS=>'P'));
      $this->EquipmentLiveDeckServices->createDeck($EquipmentLiveDeck, $arrEE);
    }
    return $Live;
  }
  private function getDeckButtons($Live)
  {
    $deckKey = $Live->getDeckKey();
    $str  = $this->getButtonDiv('btnDisabled1', $deckKey, '', 'Actions disponibles :', '', 'btn-dark disabled');
    $label = vsprintf($this->labelDraw, $Live->getNbCardsInDeck(self::CST_EQUIPMENT));
    $str .= $this->getButtonDiv('btnDrawEquipmentCard', $deckKey, 'drawEquipmentCard', $label);
    $str .= $this->getButtonDiv('btnEquipEquipmentActive', $deckKey, 'equipEquipmentActive', 'Equiper les cartes piochées');
    $label = vsprintf($this->labelDisplay, $Live->getNbCardsEquipped());
    $str .= $this->getButtonDiv('btnShowEquipEquipment', $deckKey, 'showEquipmentEquip', $label);
    $str .= $this->getButtonDiv('btnDiscardEquipmentActive', $deckKey, 'discardEquipmentActive', 'Défausser les cartes piochées');
    $str .= $this->getButtonDiv('btnShowDiscardEquipment', $deckKey, 'showEquipmentDiscard', 'Afficher la défausse');
    $label = vsprintf($this->labelShuffle, $Live->getNbCardsInDiscard(self::CST_EQUIPMENT));
    $str .= $this->getButtonDiv('btnShuffleDiscardEquipment', $deckKey, 'shuffleEquipmentDiscard', $label);
    $str .= $this->getButtonDiv('btnLeaveEquipmentDeck', $deckKey, 'leaveEquipmentDeck', 'Quitter cette pioche', 'reload');
    $str .= $this->getButtonDiv('btnDisabled2', $deckKey, '', 'Attention, action irréversible :', '', 'btn-dark disabled');
    $label = 'Supprimer cette pioche';
    $str .= $this->getButtonDiv('btnDeleteEquipmentDeck', $deckKey, 'deleteEquipmentDeck', $label, 'reload', 'btn-danger');
    $args = array(
      $str,
      'live-equipment-selection',
    );
    $strFile = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/dynamic-div.php');
    return vsprintf($strFile, $args);
  }
  private function getButtonDiv($id, $deckKey, $action, $label, $type='insert', $classe='btn-dark')
  {
    $str = '<div type="button" id="'.$id.'" class="btn '.$classe.' withEquipmentAction" data-type="'.$type.'" data-action="';
    return $str.$action.'" data-keyaccess="'.$deckKey.'">'.$label.'</div>';
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
      $args = array(self::CST_DECKKEY=>$deckKey);
      $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
      $Live = array_shift($Lives);
      if ($Live==null) {
        unset($_SESSION[self::CST_DECKKEY]);
        $blocExpansions = $this->nonLoggedInterface();
      } else {
        if ($Live->getNbCardsInDeck(self::CST_EQUIPMENT)+$Live->getNbCardsInDiscard(self::CST_EQUIPMENT)==0) {
          $this->buildInterface($args, $deckKey, $blocExpansions, $showSelection);
        } else {
          // On a une Session et des cartes.
          $blocExpansions = $this->getDeckButtons($Live);
          $showSelection = self::CST_HIDDEN;
        }
      }
    } else {
      // On n'a pas de Session, en a-t-on un en POST ?
      $deckKey = $this->initVar(self::CST_KEYACCESS);
      $this->buildInterface($args, $deckKey, $blocExpansions, $showSelection);
    }
    if ($showSelection==self::CST_HIDDEN) {
      $deckKey = $_SESSION[self::CST_DECKKEY];
      $args = array(self::CST_DECKKEY=>$deckKey);
      $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
      $Live = array_shift($Lives);
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
  private function buildInterface($args, $deckKey, &$blocExpansions, &$showSelection)
  {
    if ($deckKey!='') {
      // On a un KeyAccess en Formulaire
      $args = array(self::CST_DECKKEY=>$deckKey);
      $Live = $this->createEquipmentLiveDeck($args);
      $blocExpansions = $this->getDeckButtons($Live);
      $showSelection = self::CST_HIDDEN;
      $_SESSION[self::CST_DECKKEY] = $deckKey;
    } else {
      // On n'a rien
      $blocExpansions = $this->nonLoggedInterface();
    }
  }
  /**
   * Retourne l'interface pour créer une pioche Equipement
   * @return string
   */
  private function nonLoggedInterface()
  {
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array(), self::CST_DISPLAYRANK, 'ASC');
    $strFile = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/dynamic-div.php');
    $str = '';
    while (!empty($Expansions)) {
      $strTmp = '';
      $Expansion = array_shift($Expansions);
      $id = $Expansion->getId();
      $arrF = array(self::CST_EXPANSIONID=>$id);
      $EquipmentCards = $this->EquipmentExpansionServices->getEquipmentExpansionsWithFilters(__FILE__, __LINE__, $arrF);
      if (!empty($EquipmentCards)) {
        $ExpansionBean = $Expansion->getBean();
        $strTmp = $ExpansionBean->getMenuButtonLive($id);
        $args = array(
          $strTmp,
          'live-equipment-selection',
        );
        $str .= vsprintf($strFile, $args);
      }
    }
    return $str;
  }
  /**
   * @param array $EquipmentLiveDecks
   * @param boolean $showDiscardButton
   * @return string
   */
  public static function getStaticEquipmentCardActives($EquipmentLiveDecks, $showDiscardButton)
  {
    $Bean = new WpPageLiveEquipmentBean();
    return $Bean->getEquipmentCardActives($EquipmentLiveDecks, $showDiscardButton);
  }
  /**
   * @param array $EquipmentLiveDecks
   * @param boolean $showDiscardButton
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
        $id = ($showDiscardButton?$EquipmentLiveDeck->getId():-1);
        $strEquipments .= $EquipmentBean->displayCard($EquipmentExpansion->getExpansionId(), $id);
      }
    }
    return $strEquipments;
  }
}
