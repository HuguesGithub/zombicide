<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * DeckActions
 * @author Hugues
 * @since 1.0.02
 * @version 1.0.02
 */
class DeckActions extends LocalActions
{
  /**
   * Constructeur
   */
  public function __construct($post)
  {
    $this->LiveServices = new LiveServices();
    $LiveDecks = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, array(self::CST_DECKKEY=>$post[self::CST_KEYACCESS]));
    $this->Live = array_shift($LiveDecks);
  }
  /**
   * @return string
   */
  protected function deleteDeckCard()
  {
    $Live = $this->Live;
    $this->LiveServices->delete(__FILE__, __LINE__, $Live);
    unset($_SESSION[self::CST_DECKKEY]);
    return '{}';
  }
  /**
   * @param Service $Services
   * @return int
   */
  protected function drawCardDeck($Services)
  {
    $CardDecks = $this->getLiveDecksByStatus('P');
    // Si $SpawnLiveDecks est vide, il faut remélanger la Pioche.
    if (empty($CardDecks)) {
      $this->shuffleDeckCards($CardDecks, $Services);
      $CardDecks = $this->getLiveDecksByStatus('P');
    }
    // On prend la première carte retournée par la requête, elle devient active, on met à jour son statut
    $CardDeck = array_shift($CardDecks);
    $nbInDeck = count($CardDecks);
    $CardDeck->setStatus('A');
    $Services->update(__FILE__, __LINE__, $CardDeck);
    // On met à jour le Live, histoire qu'il soit maintenu en vie.
    $this->touchLive();
    return $nbInDeck;
  }
  /**
   * @param Service $Services
   * @param string $status
   * @return array
   */
  protected function getLiveDecksByStatus($Services, $status='')
  {
    $arrFilters = array(self::CST_LIVEID=>$this->Live->getId());
    if ($status!='') {
      $arrFilters[self::CST_STATUS]=$status;
    }
    return $Services->getLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters, 'rank', 'DESC');
  }
  /**
   * @return string
   */
  protected function leaveDeckCard()
  {
    unset($_SESSION[self::CST_DECKKEY]);
    return '{}';
  }
  /**
   * @param array $CardDecks
   * @param Service $Services
   * @return int
   */
  protected function shuffleDeckCards($CardDecks, $Services)
  {
    // On remélange, les cartes défaussées et toujours dans la pioche. Les cartes actuellement révélées et celles retirées
    // ne sont pas remélangées, tout comme les cartes équipées, pour les cartes Equipement
    shuffle($CardDecks);
    $cpt = 0;
    while (!empty($CardDecks)) {
      $CardDeck = array_shift($CardDecks);
      if ($CardDeck->getStatus()=='R' || $CardDeck->getStatus()=='A' || $CardDeck->getStatus()=='E') {
        continue;
      }
      $cpt++;
      $CardDeck->setStatus('P');
      $CardDeck->setRank($cpt);
      $Services->update(__FILE__, __LINE__, $CardDeck);
    }
    // On met à jour le Live, histoire qu'il soit maintenu en vie.
    $this->touchLive();
    return $cpt;
  }
  /**
   * Met à jour le Live relatif au Deck
   */
  protected function touchLive()
  {
    $this->Live->setDateUpdate(date(self::CST_FORMATDATE));
    $this->LiveServices->update(__FILE__, __LINE__, $this->Live);
  }
  /**
   * @param array $CardLiveDecks
   * @param Service $Services
   * @param string $status
   */
  protected function updateToStatus($CardLiveDecks, $Services, $status)
  {
    while (!empty($CardLiveDecks)) {
      $CardLiveDeck = array_shift($CardLiveDecks);
      $CardLiveDeck->setStatus($status);
      $Services->update(__FILE__, __LINE__, $CardLiveDeck);
    }
    // On met à jour le Live, histoire qu'il soit maintenu en vie.
    $this->touchLive();
  }
}
