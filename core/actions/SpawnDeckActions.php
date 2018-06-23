<?php
if ( !defined( 'ABSPATH') ) {
	die( 'Forbidden' );
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
        $LiveDeck = self::getLiveDeck($LiveDeckServices, $post[KEYACCESS]);
        $LiveDeckServices->delete(__FILE__, __LINE__, $LiveDeck);
        unset($_SESSION['wp_11_keyAccess']);
    }
    /**
     * @param array $post
     */
    public static function staticShuffleSpawnDiscard($post) {
        $LiveDeckServices = new LiveDeckServices();
        $LiveDeck = self::getLiveDeck($LiveDeckServices, $post[KEYACCESS]);
        $SpawnLiveDeckServices = new SpawnLiveDeckServices();
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array(LIVEDECKID=>$LiveDeck->getId()));
        if ( !empty($SpawnLiveDecks) ) {
            shuffle($SpawnLiveDecks);
            $cpt = 1;
            foreach ( $SpawnLiveDecks as $SpawnLiveDeck ) {
                if ( $SpawnLiveDeck->getStatus()=='R' || $SpawnLiveDeck->getStatus()=='A' ) { continue; }
                $SpawnLiveDeck->setStatus('P');
                $SpawnLiveDeck->setRank($cpt);
                $SpawnLiveDeckServices->update(__FILE__, __LINE__, $SpawnLiveDeck);
                $cpt++;
            }
            $LiveDeck->setDateUpdate(date(FORMATDATE, time()));
            $LiveDeckServices->update(__FILE__, __LINE__, $LiveDeck);
        }
        return '{"nbCardInDeck":'.json_encode(count($SpawnLiveDecks)).',"nbCardInDiscard":'.json_encode('0').'}';
    }
    /**
     * @param array $post
     */
    public static function staticShowSpawnDiscard($post) {
        $LiveDeckServices = new LiveDeckServices();
        $LiveDeck = self::getLiveDeck($LiveDeckServices, $post[KEYACCESS]);
        $SpawnLiveDeckServices = new SpawnLiveDeckServices();
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array(LIVEDECKID=>$LiveDeck->getId(), STATUS=>'D'));
        return '{'.( !empty($SpawnLiveDecks) ? '"page-selection-result":'.json_encode(SpawnDeckPageBean::getStaticSpawnCardActives($SpawnLiveDecks)) : '').'}';
    }
    /**
     * @param array $post
     */
    public static function staticDiscardSpawnCard($post) {
        $LiveDeckServices = new LiveDeckServices();
        $LiveDeck = self::getLiveDeck($LiveDeckServices, $post[KEYACCESS]);
        $SpawnLiveDeckServices = new SpawnLiveDeckServices();
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array(LIVEDECKID=>$LiveDeck->getId(), STATUS=>'A'));
        if ( !empty($SpawnLiveDecks) ) {
            foreach ( $SpawnLiveDecks as $SpawnLiveDeck ) {
                $SpawnLiveDeck->setStatus('D');
                $SpawnLiveDeckServices->update(__FILE__, __LINE__, $SpawnLiveDeck);
            }
            $LiveDeck->setDateUpdate(date(FORMATDATE, time()));
            $LiveDeckServices->update(__FILE__, __LINE__, $LiveDeck);
        }
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array(LIVEDECKID=>$LiveDeck->getId(), STATUS=>'D'));
        return '{"nbCardInDiscard":'.json_encode(count($SpawnLiveDecks)).(!empty($SpawnLiveDecks) ? ',"page-selection-result":'.json_encode('') : '').'}';
    }
    /**
     * @param array $post
     */
    public static function staticDrawSpawnCard($post) {
        $LiveDeckServices = new LiveDeckServices();
        $LiveDeck = self::getLiveDeck($LiveDeckServices, $post[KEYACCESS]);
        $SpawnLiveDeckServices = new SpawnLiveDeckServices();
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array(LIVEDECKID=>$LiveDeck->getId(), STATUS=>'P'), 'rank', 'DESC');
        if ( !empty($SpawnLiveDecks) ) {
            $SpawnLiveDeck = array_shift($SpawnLiveDecks);
            $SpawnLiveDeck->setStatus('A');
            $SpawnLiveDeckServices->update(__FILE__, __LINE__, $SpawnLiveDeck);
            $LiveDeck->setDateUpdate(date(FORMATDATE, time()));
            $LiveDeckServices->update(__FILE__, __LINE__, $LiveDeck);
        }
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array(LIVEDECKID=>$LiveDeck->getId(), STATUS=>'A'), 'rank', 'ASC');
        return '{"nbCardInDeck":'.json_encode(count($SpawnLiveDecks)).(!empty($SpawnLiveDecks) ? ',"page-selection-result":'.json_encode(SpawnDeckPageBean::getStaticSpawnCardActives($SpawnLiveDecks)) : '').'}';
    }
    
}
?>
