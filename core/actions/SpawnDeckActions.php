<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * SpawnDeckActions
 * @since 1.0.00
 * @author Hugues
 */
class SpawnDeckActions {
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
        $LiveDeck = self::getLiveDeck($LiveDeckServices, $post['keyAccess']);
        $LiveDeckServices->delete(__FILE__, __LINE__, $LiveDeck);
        unset($_SESSION['wp_11_keyAccess']);
    /*
        $json  = '{"page-live-spawn":';
    $json .= json_encode(SpawnDeckPageBean::getStaticSpawnDeckContent());
        $json .= '}';
        return $json;     
    */
    }
    /**
     * @param array $post
     */
    public static function staticShuffleSpawnDiscard($post) {
        $LiveDeckServices = new LiveDeckServices();
        $LiveDeck = self::getLiveDeck($LiveDeckServices, $post['keyAccess']);
        $SpawnLiveDeckServices = new SpawnLiveDeckServices();
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array('liveDeckId'=>$LiveDeck->getId()));
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
            $LiveDeck->setDateUpdate(date('Y-m-d H:i:s', time()));
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
        $LiveDeck = self::getLiveDeck($LiveDeckServices, $post['keyAccess']);
        $SpawnLiveDeckServices = new SpawnLiveDeckServices();
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array('liveDeckId'=>$LiveDeck->getId(), 'status'=>'D'));
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
        $LiveDeck = self::getLiveDeck($LiveDeckServices, $post['keyAccess']);
        $SpawnLiveDeckServices = new SpawnLiveDeckServices();
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array('liveDeckId'=>$LiveDeck->getId(), 'status'=>'A'));
        if ( !empty($SpawnLiveDecks) ) {
            foreach ( $SpawnLiveDecks as $SpawnLiveDeck ) {
                $SpawnLiveDeck->setStatus('D');
                $SpawnLiveDeckServices->update(__FILE__, __LINE__, $SpawnLiveDeck);
            }
            $LiveDeck->setDateUpdate(date('Y-m-d H:i:s', time()));
            $LiveDeckServices->update(__FILE__, __LINE__, $LiveDeck);
        }
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array('liveDeckId'=>$LiveDeck->getId(), 'status'=>'D'));
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
        $LiveDeck = self::getLiveDeck($LiveDeckServices, $post['keyAccess']);
        $SpawnLiveDeckServices = new SpawnLiveDeckServices();
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array('liveDeckId'=>$LiveDeck->getId(), 'status'=>'P'), 'rank', 'DESC');
        if ( !empty($SpawnLiveDecks) ) {
            $SpawnLiveDeck = array_shift($SpawnLiveDecks);
            $SpawnLiveDeck->setStatus('A');
            $SpawnLiveDeckServices->update(__FILE__, __LINE__, $SpawnLiveDeck);
            $LiveDeck->setDateUpdate(date('Y-m-d H:i:s', time()));
            $LiveDeckServices->update(__FILE__, __LINE__, $LiveDeck);
        }
        $json  = '{"nbCardInDeck":'.json_encode(count($SpawnLiveDecks));
        $SpawnLiveDecks = $SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array('liveDeckId'=>$LiveDeck->getId(), 'status'=>'A'), 'rank', 'ASC');
        if ( !empty($SpawnLiveDecks) ) {
            $json .= ',"page-selection-result":'.json_encode(SpawnDeckPageBean::getStaticSpawnCardActives($SpawnLiveDecks));
        }
        $json .= '}';
        return $json;                               
    }
    
}
?>
