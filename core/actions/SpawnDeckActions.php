<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * SpawnDeckActions
 * @since 1.0.00
 * @author Hugues
 */
class SpawnDeckActions extends LocalActions {  
  /**
   * Constructeur
   */
  public function __construct() {}
  /**
   * @param LiveDeckServices $LiveDeckServices
   * @param string $deckKey
   */
  public static function getLiveDeck($LiveDeckServices, $deckKey) {
    $LiveDecks = $LiveDeckServices->getLiveDecksWithFilters(__FILE__, __LINE__, array('deckKey'=>$deckKey));
    return array_shift($LiveDecks);
  }
  /**
   * @param array $post
   */
  public static function staticDeleteSpawnDeck($post) {
    $LiveDeckServices = new LiveDeckServices();
    $LiveDeck = self::getLiveDeck($LiveDeckServices, $post[self::CST_KEYACCESS]);
    $LiveDeckServices->delete(__FILE__, __LINE__, $LiveDeck);
    unset($_SESSION['wp_11_keyAccess']);
  }
  /**
   * @param array $post
   */
  public static function staticShuffleSpawnDiscard($post) {
    $LiveDeckServices = new LiveDeckServices();
    $LiveDeck = self::getLiveDeck($LiveDeckServices, $post[self::CST_KEYACCESS]);
    $SpawnLiveDeckServices = new SpawnLiveDeckServices();
    $arrFilters = array(self::CST_LIVEDECKID=>$LiveDeck->getId());
    $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
    if (!empty($SpawnLiveDecks)) {
      shuffle($SpawnLiveDecks);
      $cpt = 1;
      foreach ($SpawnLiveDecks as $SpawnLiveDeck) {
        if ($SpawnLiveDeck->getStatus()=='R' || $SpawnLiveDeck->getStatus()=='A') { continue; }
        $SpawnLiveDeck->setStatus('P');
        $SpawnLiveDeck->setRank($cpt);
        $SpawnLiveDeckServices->update(__FILE__, __LINE__, $SpawnLiveDeck);
        $cpt++;
      }
      $LiveDeck->setDateUpdate(date(self::CST_FORMATDATE, time()));
      $LiveDeckServices->update(__FILE__, __LINE__, $LiveDeck);
    }
    return '{"nbCardInDeck":'.json_encode(count($SpawnLiveDecks)).',"nbCardInDiscard":'.json_encode('0').'}';
  }
  /**
   * @param array $post
   */
  public static function staticShowSpawnDiscard($post) {
    $LiveDeckServices = new LiveDeckServices();
    $LiveDeck = self::getLiveDeck($LiveDeckServices, $post[self::CST_KEYACCESS]);
    $SpawnLiveDeckServices = new SpawnLiveDeckServices();
    $arrFilters = array(self::CST_LIVEDECKID=>$LiveDeck->getId(), self::CST_STATUS=>'D');
    $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
    $pageSelectionResult = (!empty($SpawnLiveDecks) ? json_encode(SpawnDeckPageBean::getStaticSpawnCardActives($SpawnLiveDecks)) : '');
    return '{'.(!empty($SpawnLiveDecks) ? '"page-selection-result":'.$pageSelectionResult : '').'}';
  }
  /**
   * @param array $post
   */
  public static function staticDiscardSpawnCard($post) {
    $LiveDeckServices = new LiveDeckServices();
    $LiveDeck = self::getLiveDeck($LiveDeckServices, $post[KEYACCESS]);
    $SpawnLiveDeckServices = new SpawnLiveDeckServices();
    $arrFilters = array(self::CST_LIVEDECKID=>$LiveDeck->getId(), self::CST_STATUS=>'A');
    $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
    if (!empty($SpawnLiveDecks)) {
      foreach ($SpawnLiveDecks as $SpawnLiveDeck) {
        $SpawnLiveDeck->setStatus('D');
        $SpawnLiveDeckServices->update(__FILE__, __LINE__, $SpawnLiveDeck);
      }
      $LiveDeck->setDateUpdate(date(FORMATDATE, time()));
      $LiveDeckServices->update(__FILE__, __LINE__, $LiveDeck);
    }
    $arrFilters = array(self::CST_LIVEDECKID=>$LiveDeck->getId(), self::CST_STATUS=>'D');
    $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
    $json = '{"nbCardInDiscard":'.json_encode(count($SpawnLiveDecks));
    return $json.(!empty($SpawnLiveDecks) ? ',"page-selection-result":'.json_encode('') : '').'}';
  }
  /**
   * @param array $post
   */
  public static function staticDrawSpawnCard($post) {
    $LiveDeckServices = new LiveDeckServices();
    $LiveDeck = self::getLiveDeck($LiveDeckServices, $post[self::CST_KEYACCESS]);
    $SpawnLiveDeckServices = new SpawnLiveDeckServices();
    $arrFilters = array(self::CST_LIVEDECKID=>$LiveDeck->getId(), self::CST_STATUS=>'P');
    $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters, 'rank', 'DESC');
    if (!empty($SpawnLiveDecks)) {
      $SpawnLiveDeck = array_shift($SpawnLiveDecks);
      $SpawnLiveDeck->setStatus('A');
      $SpawnLiveDeckServices->update(__FILE__, __LINE__, $SpawnLiveDeck);
      $LiveDeck->setDateUpdate(date(self::CST_FORMATDATE, time()));
      $LiveDeckServices->update(__FILE__, __LINE__, $LiveDeck);
    }
    $arrFilters = array(self::CST_LIVEDECKID=>$LiveDeck->getId(), self::CST_STATUS=>'A');
    $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters, 'rank', 'ASC');
    $json = '{"nbCardInDeck":'.json_encode(count($SpawnLiveDecks));
    $pageSelectionResult = (!empty($SpawnLiveDecks) ? json_encode(SpawnDeckPageBean::getStaticSpawnCardActives($SpawnLiveDecks)) : '');
     return $json.(!empty($SpawnLiveDecks) ? ',"page-selection-result":'.$pageSelectionResult : '').'}';
  }
  
}
