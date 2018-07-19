<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * EquipmentDeckActions
 * @since 1.0.00
 * @author Hugues
 */
class EquipmentDeckActions extends LocalActions
{
  /**
   * Constructeur
   */
  public function __construct($post)
  {
    $this->LiveServices = new LiveServices();
    $LiveDecks = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, array(self::CST_DECKKEY=>$post[self::CST_KEYACCESS]));
    $this->Live = array_shift($LiveDecks);
    $this->EquipmentServices = new EquipmentServices();
    $this->EquipmentExpansionServices = new EquipmentExpansionServices();
    $this->EquipmentLiveDeckServices = new EquipmentLiveDeckServices();
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
  private function touchLive()
  {
    $this->Live->setDateUpdate(date(self::CST_FORMATDATE));
    $this->LiveServices->update(__FILE__, __LINE__, $this->Live);
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
   * @param string $status
   * @return array
   */
  private function getEquipmentLiveDecksByStatus($status)
  {
    $arrFilters = array(self::CST_LIVEID=>$this->Live->getId(), self::CST_STATUS=>$status);
    return $this->EquipmentLiveDeckServices->getEquipmentLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
  }
  /**
   * @param array $EquipmentLiveDecks
   * @param string $status
   */
  private function updateToStatus($EquipmentLiveDecks, $status)
  {
    if (!empty($EquipmentLiveDecks)) {
      foreach ($EquipmentLiveDecks as $EquipmentLiveDeck) {
        $EquipmentLiveDeck->setStatus($status);
        $this->EquipmentLiveDeckServices->update(__FILE__, __LINE__, $EquipmentLiveDeck);
      }
      // On met à jour le Live, histoire qu'il soit maintenu en vie.
      $this->touchLive();
    }
  }
  /**
   * @param array $post
   */
  public function drawEquipmentCard()
  {
    $EquipmentLiveDecks = $this->getEquipmentLiveDecksByStatus('P');
    // Si $SpawnLiveDecks est vide, il faut remélanger la Pioche.
    if (empty($EquipmentLiveDecks)) {
      //$this->shuffleSpawnCards();
      //$EquipmentLiveDecks = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters, 'rank', 'DESC');
    }
    // On prend la première carte retournée par la requête, elle devient active, on met à jour son statut
    $EquipmentLiveDeck = array_shift($EquipmentLiveDecks);
    $nbInDeck = count($EquipmentLiveDecks);
    $EquipmentLiveDeck->setStatus('A');
    $this->EquipmentLiveDeckServices->update(__FILE__, __LINE__, $EquipmentLiveDeck);
    // On met à jour le Live, histoire qu'il soit maintenu en vie.
    $this->touchLive();
    // On récupère toutes les cartes Actives, au cas où plusieurs seraient affichées en même temps.
    $EquipmentLiveDecks = $this->getEquipmentLiveDecksByStatus('A');
    // On retourne les infos à modifier sur l'interface en mode Json.
    $pageSelectionResult = WpPageLiveEquipmentBean::getStaticEquipmentCardActives($EquipmentLiveDecks);
    return $this->jsonBuild($nbInDeck, -1, -1, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function equipEquipmentCard()
  {
    $EquipmentLiveDecks = $this->getEquipmentLiveDecksByStatus('A');
    // Si au moins une carte est actuellement révélée, on doit l'équiper.
    $this->updateToStatus($EquipmentLiveDecks, 'E');
    // On récupère toutes les cartes Actives, au cas où plusieurs seraient affichées en même temps.
    $EquipmentLiveDecks = $this->getEquipmentLiveDecksByStatus('E');
    // On retourne les infos à modifier sur l'interface en mode Json.
    return $this->jsonBuild(-1, -1, count($EquipmentLiveDecks), '');
  }
  /**
   * @return string
   */
  public function showEquippedEquipmentCards()
  {
    $EquipmentLiveDecks = $this->getEquipmentLiveDecksByStatus('E');
    // On retourne les infos à modifier sur l'interface en mode Json.
    $pageSelectionResult = WpPageLiveEquipmentBean::getStaticEquipmentCardActives($EquipmentLiveDecks, true);
    return $this->jsonBuild(-1, -1, -1, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function discardEquipmentCard($post=null)
  {
    if ($post==null) {
      $EquipmentLiveDecks = $this->getEquipmentLiveDecksByStatus('A');
      // Si au moins une carte est actuellement révélée, on doit la défausser.
      $this->updateToStatus($EquipmentLiveDecks, 'D');
      $pageSelectionResult = '';
      $nbDisplayed = -1;
    } else {
      $EquipmentLiveDeck = $this->EquipmentLiveDeckServices->select(__FILE__, __LINE__, $post['id']);
      $EquipmentLiveDeck->setStatus('D');
      $this->EquipmentLiveDeckServices->update(__FILE__, __LINE__, $EquipmentLiveDeck);
      // On retourne les infos à modifier sur l'interface en mode Json.
      $EquipmentLiveDecks = $this->getEquipmentLiveDecksByStatus('E');
      $pageSelectionResult = WpPageLiveEquipmentBean::getStaticEquipmentCardActives($EquipmentLiveDecks, true);
      $nbDisplayed = count($EquipmentLiveDecks);
    }
    // Le nombre de cartes défaussées augmente, il faut le mettre à jour.
    $EquipmentLiveDecks = $this->getEquipmentLiveDecksByStatus('D');
    // On retourne les infos à modifier sur l'interface en mode Json.
    return $this->jsonBuild(-1, count($EquipmentLiveDecks), $nbDisplayed, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function showDiscardEquipmentCards()
  {
    $EquipmentLiveDecks = $this->getEquipmentLiveDecksByStatus('D');
    // On retourne les infos à modifier sur l'interface en mode Json.
    $pageSelectionResult = WpPageLiveEquipmentBean::getStaticEquipmentCardActives($EquipmentLiveDecks);
    return $this->jsonBuild(-1, -1, -1, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function shuffleEquipmentCards()
  {
    $Live = $this->Live;
    $arrFilters = array(self::CST_LIVEID=>$Live->getId());
    $EquipmentLiveDecks = $this->EquipmentLiveDeckServices->getEquipmentLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
    // S'il y a au moins une carte Equipement (si ce n'est pas le cas, on se demande ce qu'on fait ici...), on remélange ensemble
    // les cartes défaussées et toujours dans la pioche. Les cartes actuellement révélée et celles retirées ne sont pas remélangées,
    // tout comme les cartes équipées.
    if (!empty($EquipmentLiveDecks)) {
      shuffle($EquipmentLiveDecks);
      $cpt = 0;
      foreach ($EquipmentLiveDecks as $EquipmentLiveDeck) {
        if ($EquipmentLiveDeck->getStatus()=='R' || $EquipmentLiveDeck->getStatus()=='A' || $EquipmentLiveDeck->getStatus()=='E') {
          continue;
        }
        $cpt++;
        $EquipmentLiveDeck->setStatus('P');
        $EquipmentLiveDeck->setRank($cpt);
        $this->EquipmentLiveDeckServices->update(__FILE__, __LINE__, $EquipmentLiveDeck);
      }
      // On met à jour le Live, histoire qu'il soit maintenu en vie.
      $this->touchLive();
    }
    return $this->jsonBuild($cpt, 0, -1, '');
  }
  /**
   * @return string
   */
  public function leaveEquipmentCard()
  {
    unset($_SESSION[self::CST_DECKKEY]);
    return '{}';
  }
  /**
   * @return string
   */
  public function deleteEquipmentCard()
  {
    $Live = $this->Live;
    $this->LiveServices->delete(__FILE__, __LINE__, $Live);
    unset($_SESSION[self::CST_DECKKEY]);
    return '{}';
  }
}
