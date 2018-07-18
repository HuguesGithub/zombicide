<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * SpawnDeckActions
 * @since 1.0.00
 * @author Hugues
 */
class SpawnDeckActions extends LocalActions
{
  /**
   * Constructeur
   */
  public function __construct($post)
  {
    $this->LiveServices = FactoryServices::getLiveServices();
    $this->SpawnLiveDeckServices = FactoryServices::getSpawnLiveDeckServices();
    $LiveDecks = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, array(self::CST_DECKKEY=>$post[self::CST_KEYACCESS]));
    $this->Live = array_shift($LiveDecks);
  }
  /**
   * Point d'entrée des méthodes statiques.
   * @param array $post
   * @return string
   **/
  public static function dealWithStatic($post)
  {
    $Act = new SpawnDeckActions($post);
    $returned = '{}';
    if ($_SESSION[self::CST_DECKKEY]==$post[self::CST_KEYACCESS]) {
      switch ($post['ajaxChildAction']) {
        case 'deleteSpawnDeck'    :
          $returned = $Act->deleteSpawnCard();
        break;
        case 'discardSpawnActive' :
          $returned = $Act->discardSpawnCard();
        break;
        case 'drawSpawnCard' :
          $returned = $Act->drawSpawnCard();
        break;
        case 'leaveSpawnDeck' :
          $returned = $Act->leaveSpawnCard();
        break;
        case 'showSpawnDiscard' :
          $returned = $Act->showDiscardSpawnCards();
        break;
        case 'shuffleSpawnDiscard' :
          $returned = $Act->shuffleSpawnCards();
        break;
        default :
        break;
      }
    }
    return $returned;
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
  private function jsonBuild($nbCardInDeck, $nbCardInDiscard, $pageSelectionResult)
  {
    $json  = '{';
    if ($nbCardInDeck!==-1) {
      $json .= '"nbCardInDeck":'.json_encode($nbCardInDeck);
    }
    if ($nbCardInDiscard!==-1) {
      $json .= ($json!='{'?',':'').'"nbCardInDiscard":'.json_encode($nbCardInDiscard);
    }
    return $json.($json!='{'?',':'').'"page-selection-result":'.json_encode($pageSelectionResult).'}';
  }
  /**
   * @return string
   */
  public function drawSpawnCard()
  {
    $Live = $this->Live;
    $arrFilters = array(self::CST_LIVEID=>$Live->getId(), self::CST_STATUS=>'P');
    $SpawnLiveDecks = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters, 'rank', 'DESC');
    // Si $SpawnLiveDecks est vide, il faut remélanger la Pioche.
    if (empty($SpawnLiveDecks)) {
      $this->shuffleSpawnCards();
      $SpawnLiveDecks = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters, 'rank', 'DESC');
    }
    // On prend la première carte retournée par la requête, elle devient active, on met à jour son statut
    $SpawnLiveDeck = array_shift($SpawnLiveDecks);
    $nbInDeck = count($SpawnLiveDecks);
    $SpawnLiveDeck->setStatus('A');
    $this->SpawnLiveDeckServices->update(__FILE__, __LINE__, $SpawnLiveDeck);
    // On met à jour le Live, histoire qu'il soit maintenu en vie.
    $this->touchLive();
    // On récupère toutes les cartes Actives, au cas où plusieurs seraient affichées en même temps.
    $arrFilters[self::CST_STATUS] = 'A';
    $SpawnLiveDecks = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters, 'rank', 'ASC');
    // On retourne les infos à modifier sur l'interface en mode Json.
    $Bean = new WpPageLiveSpawnBean();
    $pageSelectionResult = $Bean->getSpawnCardActives($SpawnLiveDecks);
    return $this->jsonBuild($nbInDeck, -1, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function discardSpawnCard()
  {
    $Live = $this->Live;
    $arrFilters = array(self::CST_LIVEID=>$Live->getId(), self::CST_STATUS=>'A');
    $SpawnLiveDecks = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
    // Si au moins une carte est actuellement révélée, on doit la défausser.
    if (!empty($SpawnLiveDecks)) {
      foreach ($SpawnLiveDecks as $SpawnLiveDeck) {
        $SpawnLiveDeck->setStatus('D');
        $this->SpawnLiveDeckServices->update(__FILE__, __LINE__, $SpawnLiveDeck);
      }
      // On met à jour le Live, histoire qu'il soit maintenu en vie.
      $this->touchLive();
    }
    // Le nombre de cartes défaussées augmente, il faut le mettre à jour.
    $arrFilters[self::CST_STATUS] = 'D';
    $SpawnLiveDecks = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
    // On retourne les infos à modifier sur l'interface en mode Json.
    return $this->jsonBuild(-1, count($SpawnLiveDecks), '');
  }
  /**
   * @return string
   */
  public function shuffleSpawnCards()
  {
    $Live = $this->Live;
    $arrFilters = array(self::CST_LIVEID=>$Live->getId());
    $SpawnLiveDecks = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
    // S'il y a au moins une carte de Spawn (si ce n'est pas le cas, on se demande ce qu'on fait ici...), on remélange ensemble
    // les cartes défaussées et toujours dans la pioche. Les cartes actuellement révélée et celles retirées ne sont pas remélangées.
    if (!empty($SpawnLiveDecks)) {
      shuffle($SpawnLiveDecks);
      $cpt = 0;
      foreach ($SpawnLiveDecks as $SpawnLiveDeck) {
        if ($SpawnLiveDeck->getStatus()=='R' || $SpawnLiveDeck->getStatus()=='A') {
          continue;
        }
        $cpt++;
        $SpawnLiveDeck->setStatus('P');
        $SpawnLiveDeck->setRank($cpt);
        $this->SpawnLiveDeckServices->update(__FILE__, __LINE__, $SpawnLiveDeck);
      }
      // On met à jour le Live, histoire qu'il soit maintenu en vie.
      $this->touchLive();
    }
    return $this->jsonBuild($cpt, 0, '');
  }
  /**
   * @return string
   */
  public function showDiscardSpawnCards()
  {
    $Live = $this->Live;
    $arrFilters = array(self::CST_LIVEID=>$Live->getId(), self::CST_STATUS=>'D');
    $SpawnLiveDecks = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
    // On retourne les infos à modifier sur l'interface en mode Json.
    $pageSelectionResult = SpawnDeckPageBean::getStaticSpawnCardActives($SpawnLiveDecks);
    return $this->jsonBuild(-1, -1, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function leaveSpawnCard()
  {
    unset($_SESSION[self::CST_DECKKEY]);
    return '{}';
  }
  /**
   * @return string
   */
  public function deleteSpawnCard()
  {
    $Live = $this->Live;
    $this->LiveServices->delete(__FILE__, __LINE__, $Live);
    unset($_SESSION[self::CST_DECKKEY]);
    return '{}';
  }
}
