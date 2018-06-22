<?php
if ( !defined( 'ABSPATH') ) {
	die( 'Forbidden' );
}
/**
 * ChatActions
 * @since 1.0.00
 * @author Hugues
 */
class ChatActions extends LocalActions {
	const TIMESTAMP = 'timestamp';
	const LIVEID = 'liveId';
	const TEXTE = 'texte';
	const FORMATDATE = 'Y-m-d H:i:s';
	const SENDTOID = 'sendToId';
	const DECKKEY = 'deckKey';
	
    /**
     * Constructeur
     */
    public function __construct($post) {
        $this->User = wp_get_current_user();
        $Data = $this->User->data;
        $this->userId = $Data->ID;
        $this->displayName = $Data->display_name;
        
        $arrParams = array(LIVEID=>'0', 'text'=>'', TIMESTAMP=>'');
        foreach ( $arrParams as $key => $param ) {
            $this->{$key} = isset($post[$key]) ? $post[$key] : $param;
        }
        $services = array('Chat', 'Live');
        parent::__construct($services);
    }
    
    /**
     * 
     * @param unknown $post
     * @return string
     */
    public static function staticPostChat($post) {
        $ChatActions = new ChatActions($post);
        return $ChatActions->dealWithPostChat();
    }
    /**
     * @return string
     */
    public function dealWithPostChat() {
        $text = trim($this->text);
        if ( $text!='' ) {
            $arrCmds = explode(' ', $text);
            switch ( $arrCmds[0] ) {
                case '/join'   : $returned = $this->joinNewLive($arrCmds[1]); break;
                case '/exit'   : $returned = $this->exitLive(); break;
                case '/help'   : $returned = $this->helpLive(); break;
                case '/invite' : $returned = $this->inviteUser($arrCmds[1]); break;
                case '/clean'  : $returned = $this->cleanChat(); break;
                default :
                    $arr = array(LIVEID=>$this->liveId, 'senderId'=>$this->userId, TEXTE=>stripslashes($text), TIMESTAMP=>date(FORMATDATE));
                    $this->postChat($arr);
                    $returned = $this->getChatContent(); 
                break;
            }
        } else {
        	$returned = $this->getChatContent();
        }
        return $returned;
    }
    private function cleanChat() {
        $arr = array(SENDTOID=>$this->userId, TEXTE=>'Vous avez vidé l\'interface.', TIMESTAMP=>date(FORMATDATE));
        $this->postChat($arr);
        return $this->getChatContent();
    }
    private function inviteUser($displayName='') {
      $WpUser = WpUser::getWpUserBy('user_login', $displayName);
      if ( $WpUser->getID()=='' ) {
        $arr = array(SENDTOID=>$this->userId, TEXTE=>'Cet utilisateur <b>'.$displayName.'</b> n\'existe pas.', TIMESTAMP=>date(FORMATDATE));
        $this->postChat($arr);
        return $this->getChatContent();
      }
      $Live = $this->LiveServices->select(__FILE__, __LINE__, $this->liveId);
      $arr = array(SENDTOID=>$WpUser->getID(), 'senderId'=>$this->userId, TEXTE=>'Rejoins moi sur l\'espace <span class="keyDeck" data-keydeck="'.$Live->getDeckKey().'">'.$Live->getDeckKey().'</span>', TIMESTAMP=>date(FORMATDATE));
      $this->postChat($arr);
      $arr = array(SENDTOID=>$this->userId, TEXTE=>'Invitation envoyée à <span class="author" data-displayName="'.$displayName.'">'.$displayName.'</span>', TIMESTAMP=>date(FORMATDATE));
      $this->postChat($arr);
      return $this->getChatContent();
    }
    private function helpLive() {
      $text = '';
      $text .= '<b>/clean</b> Vide l\'interface de discussion.';
      $text .= '<br><b>/exit</b> Rejoindre l\'espace général.';
      $text .= '<br><b>/help</b> Affiche cette aide.';
      $text .= '<br><b>/invite xxxxx</b> Envoie une invitation à rejoindre l\'espace courant.';
      $text .= '<br><b>/join xxxxx</b> Rejoindre un espace dédié.';
        $arr = array(SENDTOID=>$this->userId, TEXTE=>$text, TIMESTAMP=>date(FORMATDATE));
        $this->postChat($arr);
        return $this->getChatContent();
    }
    private function exitLive() {
        $arr = array(LIVEID=>$this->liveId, TEXTE=>$this->displayName.' a quitté l\'espace de conversation.', TIMESTAMP=>date(FORMATDATE));
        $this->postChat($arr);
        unset($_SESSION[DECKKEY]);
        $this->liveId = 0;
        $arr = array(LIVEID=>$this->liveId, SENDTOID=>$this->userId, TEXTE=>'Vous êtes de retour sur le canal par défaut', TIMESTAMP=>date(FORMATDATE));
        $this->postChat($arr);
        $json = '{';
        $json .= $this->getChatContent(FALSE).', ';
        $json .= '"header-ul-chat-saisie":'.json_encode('<li class="nav-item"><a class="nav-link active" href="#" data-liveid="0">Général</a></li>');
        $json .= '}';
        return $json;
    }
    private function joinNewLive($deckKey='') {
        $LiveServices = FactoryServices::getLiveServices();
        $Lives = $LiveServices->getLivesWithFilters(__FILE__, __LINE__, array(DECKKEY=>$deckKey));
        if ( empty($Lives) ) { return $this->getChatContent(); }
        $Live = array_shift($Lives);
        $this->liveId = $Live->getId();
        $arr = array(LIVEID=>$this->liveId, TEXTE=>$this->displayName.' a rejoint l\'espace de conversation.', TIMESTAMP=>date(FORMATDATE));
        $this->postChat($arr);
        $_SESSION[DECKKEY] = $deckKey;
        $json = '{';
        $json .= $this->getChatContent(FALSE).', ';
        $json .= '"header-ul-chat-saisie":'.json_encode('<li class="nav-item"><a class="nav-link active" href="#" data-liveid="'.$this->liveId.'">'.$deckKey.'</a></li>');
        $json .= '}';
        return $json;
    }

    private function postChat($arr) {
        $Chat = new Chat($arr);
        $this->ChatServices->insert(__FILE__, __LINE__, $Chat);
    }
    /**
     * 
     * @param unknown $post
     * @return string
     */
    public static function staticChatContent($post) {
            $ChatActions = new ChatActions($post);
            return $ChatActions->getChatContent();
    }
    /**
     * @param boolean $directReturn
     * @return string
     */
    public function getChatContent($directReturn=TRUE) {
        $arr = array(LIVEID=>$this->liveId, SENDTOID=>$this->userId, TIMESTAMP=>$this->timestamp);
        $Chats = $this->ChatServices->getChatsWithFilters(__FILE__, __LINE__, $arr);
        $strChats = '';
        if ( !empty($Chats) ) {
            foreach ( $Chats as $Chat ) {
                $timestamp = $Chat->getTimestamp();
                $strChats .= '<li class="msg-';
                  if ( $Chat->getSenderId()==$this->userId ) { $strChats .= 'right'; }
                elseif ( $Chat->getSenderId()==0 ) { $strChats .= 'technique'; }
                else { $strChats .= 'left'; }
                $strChats .= '" data-timestamp="'.$timestamp.'"><div>';
                if ( $Chat->getSenderId()!=$this->userId ) {
                    $strChats .= '<span class="author" data-displayname="'.$Chat->getSenderDisplayName().'">'.$Chat->getSenderDisplayName().'</span> ';
                }
                $arr1 = explode(' ', $timestamp);
                list($Y, $m, $d) = explode('-', $arr1[0]);
                list($H, $i, $s) = explode(':', $arr1[1]);
                list($cY, $cm, $cd) = explode('-', date('Y-m-d'));
                if ( $Y!=$cY ) { $strTimestamp = $d.'/'.$m.'/'.$Y.' '; }
                elseif ( $m!=$cm || $d!=$cd ) { $strTimestamp = $d.'/'.$m.' '; }
                $strTimestamp .= $H.':'.$i;
                $strChats .= '<span class="timestamp">'.$strTimestamp.'</span></div>'.$Chat->getTexte().'</li>';
            }
        }
        if ( $directReturn ) {
            return '{"online-chat-content":'.json_encode($strChats).'}';
        } else {
            return '"online-chat-content":'.json_encode($strChats);
        }
    }
    
}
?>
