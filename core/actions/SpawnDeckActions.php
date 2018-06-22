<?php
if ( !defined( 'ABSPATH') ) {
	die( 'Forbidden' );
}
/**
 * SpawnDeckActions
 * @since 1.0.00
 * @author Hugues
 */
class SpawnDeckActions {
	const KEYACCESS = 'keyAccess';
	const LIVEDECKID = 'liveDeckId';
	const FORMATDATE = 'Y-m-d H:i:s';
	const STATUS = 'status';
	
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
        $LiveDeck = array_shift($LiveDecks);
        return $LiveDeck;
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
        $json  = '{"nbCardInDeck":'.json_encode(count($SpawnLiveDecks));
        $json .= ',"nbCardInDiscard":'.json_encode('0');
        $json .= '}';
        return $json;
    }
    /**
     * @param array $post
     */
    public static function staticShowSpawnDiscard($post) {
        $LiveDeckServices = new LiveDeckServices();
        $LiveDeck = self::getLiveDeck($LiveDeckServices, $post[KEYACCESS]);
        $SpawnLiveDeckServices = new SpawnLiveDeckServices();
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array(LIVEDECKID=>$LiveDeck->getId(), STATUS=>'D'));
        $json  = '{';
        if ( !empty($SpawnLiveDecks) ) {
            $json .= '"page-selection-result":'.json_encode(SpawnDeckPageBean::getStaticSpawnCardActives($SpawnLiveDecks));
        }
        $json .= '}';
        return $json;     
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
        $json  = '{"nbCardInDiscard":'.json_encode(count($SpawnLiveDecks));
        if ( !empty($SpawnLiveDecks) ) {
            $json .= ',"page-selection-result":'.json_encode('');
        }
        $json .= '}';
        return $json;                               
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
        $json  = '{"nbCardInDeck":'.json_encode(count($SpawnLiveDecks));
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array(LIVEDECKID=>$LiveDeck->getId(), STATUS=>'A'), 'rank', 'ASC');
        if ( !empty($SpawnLiveDecks) ) {
            $json .= ',"page-selection-result":'.json_encode(SpawnDeckPageBean::getStaticSpawnCardActives($SpawnLiveDecks));
        }
        $json .= '}';
        return $json;                               
    }
    
}
?>
