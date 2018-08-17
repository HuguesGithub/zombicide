<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * EquipmentDeckActions
 * @author Hugues
 * @since 1.0.00
 * @version 1.0.02
 */
class EquipmentDeckActions extends DeckActions
{
  /**
   * Constructeur
   */
  public function __construct($post)
  {
    $this->EquipmentServices          = new EquipmentServices();
    $this->EquipmentExpansionServices = new EquipmentExpansionServices();
    $this->EquipmentLiveDeckServices  = new EquipmentLiveDeckServices();
  }
  /**
   * Point d'entrée des méthodes statiques.
   * @param array $post
   * @return string
   **/
  public static function dealWithStatic($post)
  {
    $Act = new EquipmentDeckActions($post);
    if ($post['ajaxChildAction']=='pregenEquipmentCard') {
      $returned = $Act->getPreGen($post);
    } else {
      $returned = '{}';
    }
    if ($_SESSION[self::CST_DECKKEY]==$post[self::CST_KEYACCESS]) {
      switch ($post['ajaxChildAction']) {
        case 'deleteEquipmentDeck'     :
          $returned = $Act->deleteEquipmentCard();
        break;
        case 'discardEquipmentActive'  :
          $returned = $Act->discardEquipmentCard();
        break;
        case 'discardEquippedCard'     :
          $returned = $Act->discardEquipmentCard($post);
        break;
        case 'drawEquipmentCard'       :
          $returned = $Act->drawEquipmentCard();
        break;
        case 'equipEquipmentActive'    :
          $returned = $Act->equipEquipmentCard();
        break;
        case 'leaveEquipmentDeck'      :
          $returned = $Act->leaveEquipmentCard();
        break;
        case 'showEquipmentDiscard'    :
          $returned = $Act->showDiscardEquipmentCards();
        break;
        case 'showEquipmentEquip'      :
          $returned = $Act->showEquippedEquipmentCards();
        break;
        case 'shuffleEquipmentDiscard' :
          $returned = $Act->shuffleEquipmentCards();
        break;
        default :
        break;
      }
    }
    return $returned;
  }
  /**
   * Retourne la chaîne json mutualisée pour l'ensemble des méthodes.
   * @param int $nbCardInDeck Nombre de cartes dans la pioche
   * @param int $nbCardInDiscard Nombre de cartes dans la défausse
   * @param string $pageSelectionResult Le contenu à afficher
   * @return string
   */
  private function jsonBuild($nbCardInDeck, $nbCardInDiscard, $nbCardsEquipped, $pageSelectionResult)
  {
    $json  = '{';
    if ($nbCardInDeck!==-1) {
      $json .= '"nbCardInDeck":'.json_encode($nbCardInDeck);
    }
    if ($nbCardInDiscard!==-1) {
      $json .= ($json!='{'?',':'').'"nbCardInDiscard":'.json_encode($nbCardInDiscard);
    }
    if ($nbCardsEquipped!==-1) {
      $json .= ($json!='{'?',':'').'"nbCardEquipped":'.json_encode($nbCardsEquipped);
    }
    return $json.($json!='{'?',':'').'"page-selection-result":'.json_encode($pageSelectionResult).'}';
  }
  /**
   * @return string
   */
  public function deleteEquipmentCard()
  { return parent::deleteDeckCard(); }
  /**
   * @return string
   */
  public function discardEquipmentCard($post=null)
  {
    if ($post==null) {
      $EquipmentLiveDecks = $this->getLiveDecksByStatus('A');
      // Si au moins une carte est actuellement révélée, on doit la défausser.
      $this->updateToStatus($EquipmentLiveDecks, $this->EquipmentLiveDeckServices, 'D');
      $pageSelectionResult = '';
      $nbDisplayed = -1;
    } else {
      $EquipmentLiveDeck = $this->EquipmentLiveDeckServices->select(__FILE__, __LINE__, $post['id']);
      $EquipmentLiveDeck->setStatus('D');
      $this->EquipmentLiveDeckServices->update(__FILE__, __LINE__, $EquipmentLiveDeck);
      // On retourne les infos à modifier sur l'interface en mode Json.
      $EquipmentLiveDecks = $this->getLiveDecksByStatus('E');
      $pageSelectionResult = WpPageLiveEquipmentBean::getStaticEquipmentCardActives($EquipmentLiveDecks, true);
      $nbDisplayed = count($EquipmentLiveDecks);
    }
    // Le nombre de cartes défaussées augmente, il faut le mettre à jour.
    $EquipmentLiveDecks = $this->getLiveDecksByStatus('D');
    // On retourne les infos à modifier sur l'interface en mode Json.
    return $this->jsonBuild(-1, count($EquipmentLiveDecks), $nbDisplayed, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function drawEquipmentCard()
  {
    $nbInDeck = $this->drawCardDeck($this->EquipmentLiveDeckServices);
    // On récupère toutes les cartes Actives, au cas où plusieurs seraient affichées en même temps.
    $EquipmentLiveDecks = $this->getLiveDecksByStatus('A');
    // On retourne les infos à modifier sur l'interface en mode Json.
    $pageSelectionResult = WpPageLiveEquipmentBean::getStaticEquipmentCardActives($EquipmentLiveDecks);
    return $this->jsonBuild($nbInDeck, -1, -1, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function equipEquipmentCard()
  {
    $EquipmentLiveDecks = $this->getLiveDecksByStatus('A');
    // Si au moins une carte est actuellement révélée, on doit l'équiper.
    $this->updateToStatus($EquipmentLiveDecks, $this->EquipmentLiveDeckServices, 'E');
    // On récupère toutes les cartes Actives, au cas où plusieurs seraient affichées en même temps.
    $EquipmentLiveDecks = $this->getLiveDecksByStatus('E');
    // On retourne les infos à modifier sur l'interface en mode Json.
    return $this->jsonBuild(-1, -1, count($EquipmentLiveDecks), '');
  }
  /**
   * @return string
   */
  public function leaveEquipmentCard()
  { return parent::leaveDeckCard(); }
  /**
   * @param array $post
   * @return string
   */
  public function getPreGen($post)
  {
    $strReturned = '';
    $expansionIds = explode(',', $post['expansionIds']);
    $arrEquipment = array();
    while (!empty($expansionIds)) {
      $expansionId = array_shift($expansionIds);
      $arrFilters = array('expansionId'=>$expansionId);
      $EquipmentExpansions = $this->EquipmentExpansionServices->getEquipmentExpansionsWithFilters(__FILE__, __LINE__, $arrFilters);
      if (!empty($EquipmentExpansions)) {
        foreach ($EquipmentExpansions as $EquipmentExpansion) {
          $Equipment = $this->EquipmentServices->select(__FILE__, __LINE__, $EquipmentExpansion->getEquipmentCardId());
          $EquipmentExpansion->setEquipment($Equipment);
          $arrEquipment[$Equipment->getNiceName().'-'.$Equipment->getId().'-'.$EquipmentExpansion->getExpansionId()] = $EquipmentExpansion;
        }
      }
    }
    if (!empty($arrEquipment)) {
      ksort($arrEquipment);
      while (!empty($arrEquipment)) {
        $EquipmentExpansion = array_shift($arrEquipment);
        $Equipment = $this->EquipmentServices->select(__FILE__, __LINE__, $EquipmentExpansion->getEquipmentCardId());
        $strReturned .= '<div class="input-group input-group-sm mb-3 col-12 col-md-6 col-xl-4 float-left">';
        $strReturned .= '<div class="input-group-prepend">';
        $strReturned .= '<span class="input-group-text">'.$EquipmentExpansion->getExpansionId().' / ';
        $strReturned .= ($Equipment->isStarter()?'*':'').$Equipment->getName().'</span>';
        $strReturned .= '</div>';
        $strReturned .= '<div class="input-group-prepend"><span class="input-group-text">&nbsp;';
        $strReturned .= '<i class="fa fa-question-circle"></i></span></div>';
        $nb = $EquipmentExpansion->getQuantity();
        $strReturned .= '<input class="form-control" type="text" name="ec-'.$EquipmentExpansion->getId();
        $strReturned .= '" data-default="'.$nb.'" value="'.$nb.'">';
        $strReturned .= '</div>';
      }
    }
    return '{"equipment-container":'.json_encode($strReturned).'}';
  }
  /**
   * @return string
   */
  public function showDiscardEquipmentCards()
  {
    $EquipmentLiveDecks = $this->getLiveDecksByStatus('D');
    // On retourne les infos à modifier sur l'interface en mode Json.
    $pageSelectionResult = WpPageLiveEquipmentBean::getStaticEquipmentCardActives($EquipmentLiveDecks);
    return $this->jsonBuild(-1, -1, -1, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function showEquippedEquipmentCards()
  {
    $EquipmentLiveDecks = $this->getLiveDecksByStatus('E');
    // On retourne les infos à modifier sur l'interface en mode Json.
    $pageSelectionResult = WpPageLiveEquipmentBean::getStaticEquipmentCardActives($EquipmentLiveDecks, true);
    return $this->jsonBuild(-1, -1, -1, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function shuffleEquipmentCards()
  {
    $EquipmentLiveDecks = $this->getLiveDecksByStatus();
    // S'il y a au moins une carte Equipement (si ce n'est pas le cas, on se demande ce qu'on fait ici...), on remélange
    $cpt = 0;
    if (!empty($EquipmentLiveDecks)) {
      $cpt = $this->shuffleDeckCards($EquipmentLiveDecks, $this->EquipmentLiveDeckServices);
    }
    return $this->jsonBuild($cpt, 0, -1, '');
  }
}
