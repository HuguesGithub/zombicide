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
    $this->ExpansionServices = FactoryServices::getExpansionServices();
    $this->EquipmentExpansionServices = FactoryServices::getEquipmentExpansionServices();
    $this->LiveServices = FactoryServices::getLiveServices();
    $this->EquipmentLiveDeckServices = FactoryServices::getEquipmentLiveDeckServices();
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
    $args['dateUpdate'] = date(self::CST_FORMATDATE);
    $Live = new Live($args);
    $this->LiveServices->insert(__FILE__, __LINE__, $Live);
    $Live->setId(MySQL::getLastInsertId());
    $arrEE = array();
    // Maintenant que le Live est créé, on va créer les cartes associées.
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
    $str .= $this->getButtonDiv('btnDisabled1', $deckKey, 'Actions disponibles :', 'btn-dark disabled');
    $label = 'Piocher une carte (<span id="nbCardInDeck">'.$Live->getNbCardsInDeck('equipment').'</span>)';
    $str .= $this->getButtonDiv('btnDrawEquipmentCard', $deckKey, $label);
    $str .= $this->getButtonDiv('btnDiscardEquipmentActive', $deckKey, 'Défausser les cartes piochées');
    $str .= $this->getButtonDiv('btnShowDiscardEquipment', $deckKey, 'Afficher la défausse');
    $label = 'Remélanger la défausse (<span id="nbCardInDiscard">'.$Live->getNbCardsInDiscard('equipment').'</span>)';
    $str .= $this->getButtonDiv('btnShuffleDiscardEquipment', $deckKey, $label);
    $str .= $this->getButtonDiv('btnLeaveEquipmentDeck', $deckKey, 'Quitter cette pioche');
    $str .= $this->getButtonDiv('btnDisabled2', $deckKey, 'Attention, action irréversible :', 'btn-dark disabled');
    $str .= $this->getButtonDiv('btnDeleteEquipmentDeck', $deckKey, 'Supprimer cette pioche', 'btn-danger');
    return $str.'</div>';
  }
  private function getButtonDiv($id, $deckKey, $label, $classe='btn-dark')
  {
    return '<div type="button" id="'.$id.'" class="btn '.$classe.'" data-keyaccess="'.$deckKey.'">'.$label.'</div>';
  }
  /**
   * @return string
   */
  public function getEquipmentDeckContent()
  {
    $blocExpansions = '';
    $showSelection = '';
    $strSpawns = '';
    $deckKey = $this->initVar('keyAccess');
    $Lives = array();
    if ($deckKey == '' && isset($_SESSION[self::CST_DECKKEY]) && $_SESSION[self::CST_DECKKEY]!='') {
      $deckKey = $_SESSION[self::CST_DECKKEY];
      $args = array('deckKey'=>$deckKey);
      $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
      if (empty($Lives)) {
        unset($_SESSION[self::CST_DECKKEY]);
        $deckKey = '';
      }
    }
    if ($deckKey=='') {
      $blocExpansions = $this->nonLoggedInterface();
    } else {
      if (empty($Lives)) {
        $args = array('deckKey'=>$deckKey);
        $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
      }
      $_SESSION[self::CST_DECKKEY] = $deckKey;
      $showSelection = 'hidden';
      // deckKey est renseigné. On doit vérifier que cette clef existe ou non.
      // Si elle existe, on va reprendre les données en cours.
      // Sinon, on va devoir créer la pioche complète
      if (empty($Lives)) {
        $Live = $this->createEquipmentLiveDeck($args);
      } else {
        $Live = array_shift($Lives);
      }
      $blocExpansions = $this->getDeckButtons($Live);
    }
    $args = array(
      $blocExpansions,
      $showSelection,
      $strSpawns
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
}
