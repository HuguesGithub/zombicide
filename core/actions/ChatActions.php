<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * ChatActions
 * @since 1.0.00
 * @author Hugues
 */
class ChatActions extends LocalActions
{
  /**
   * Constructeur
   */
  public function __construct($post)
  {
    $this->User = wp_get_current_user();
    $Data = $this->User->data;
    $this->userId = $Data->ID;
    $this->displayName = $Data->display_name;
    $arrParams = array(self::CST_LIVEID=>'0', self::CST_TEXTE=>'', self::CST_TIMESTAMP=>'');
    foreach ($arrParams as $key => $param) {
      $this->{$key} = isset($post[$key]) ? $post[$key] : $param;
    }
    parent::__construct();
    $this->ChatServices = new ChatServices();
    $this->LiveServices = new LiveServices();
  }
  /**
   * @param unknown $post
   * @return string
   */
  public static function staticPostChat($post)
  {
    $ChatActions = new ChatActions($post);
    return $ChatActions->dealWithPostChat();
  }
  /**
   * @return string
   */
  public function dealWithPostChat()
  {
    $text = trim($this->text);
    if ($text!='') {
      $arrCmds = explode(' ', $text);
      switch ($arrCmds[0]) {
        case '/join'   :
          $returned = $this->joinNewLive($arrCmds[1]);
        break;
        case '/exit'   :
          $returned = $this->exitLive();
        break;
        case '/help'   :
          $returned = $this->helpLive();
        break;
        case '/invite' :
          $returned = $this->inviteUser($arrCmds[1]);
        break;
        case '/clean'  :
          $returned = $this->cleanChat();
        break;
        default :
          $arr = array(
            self::CST_LIVEID=>$this->liveId,
            'senderId'=>$this->userId,
            self::CST_TEXTE=>stripslashes($text),
            self::CST_TIMESTAMP=>date(self::CST_FORMATDATE));
          $this->postChat($arr);
          $returned = $this->getChatContent();
        break;
      }
    } else {
      $returned = $this->getChatContent();
    }
    return $returned;
  }
  private function cleanChat()
  {
    $arr = array(
      self::CST_SENDTOID=>$this->userId,
      self::CST_TEXTE=>'Vous avez vidé l\'interface.',
      self::CST_TIMESTAMP=>date(FORMATDATE));
    $this->postChat($arr);
    return $this->getChatContent();
  }
  private function inviteUser($displayName='')
  {
    $WpUser = WpUser::getWpUserBy('user_login', $displayName);
    if ($WpUser->getID()=='') {
      $arr = array(
        self::CST_SENDTOID=>$this->userId,
        self::CST_TEXTE=>'Cet utilisateur <b>'.$displayName.'</b> n\'existe pas.',
        self::CST_TIMESTAMP=>date(self::CST_FORMATDATE),
     );
      $this->postChat($arr);
      return $this->getChatContent();
    }
    $Live = $this->LiveServices->select(__FILE__, __LINE__, $this->liveId);
    $deckKey = $Live->getDeckKey();
    $arr = array(
      self::CST_SENDTOID=>$WpUser->getID(),
      'senderId'=>$this->userId,
      self::CST_TEXTE=>'Rejoins moi sur l\'espace <span class="keyDeck" data-keydeck="'.$deckKey.'">'.$deckKey.'</span>',
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE),
   );
    $this->postChat($arr);
    $arr = array(
      self::CST_SENDTOID=>$this->userId,
      self::CST_TEXTE=>'Invitation envoyée à <span class="author" data-displayName="'.$displayName.'">'.$displayName.'</span>',
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE),
   );
    $this->postChat($arr);
    return $this->getChatContent();
  }
  private function helpLive()
  {
    $text = '';
    $text .= '<b>/clean</b> Vide l\'interface de discussion.';
    $text .= '<br><b>/exit</b> Rejoindre l\'espace général.';
    $text .= '<br><b>/help</b> Affiche cette aide.';
    $text .= '<br><b>/invite xxxxx</b> Envoie une invitation à rejoindre l\'espace courant.';
    $text .= '<br><b>/join xxxxx</b> Rejoindre un espace dédié.';
    $arr = array(self::CST_SENDTOID=>$this->userId, self::CST_TEXTE=>$text, self::CST_TIMESTAMP=>date(self::CST_FORMATDATE));
    $this->postChat($arr);
    return $this->getChatContent();
  }
  private function exitLive()
  {
    $arr = array(
      self::CST_LIVEID=>$this->liveId,
      self::CST_TEXTE=>$this->displayName.' a quitté l\'espace de conversation.',
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE),
   );
    $this->postChat($arr);
    unset($_SESSION[self::CST_DECKKEY]);
    $this->liveId = 0;
    $arr = array(
      self::CST_LIVEID=>$this->liveId,
      self::CST_SENDTOID=>$this->userId,
      self::CST_TEXTE=>'Vous êtes de retour sur le canal par défaut',
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE),
   );
    $this->postChat($arr);
    $returned = '{'.$this->getChatContent(false).', "header-ul-chat-saisie":';
    return $returned.json_encode('<li class="nav-item"><a class="nav-link active" href="#" data-liveid="0">Général</a></li>').'}';
  }
  private function joinNewLive($deckKey='')
  {
    $LiveServices = new LiveServices();
    $Lives = $LiveServices->getLivesWithFilters(__FILE__, __LINE__, array(self::CST_DECKKEY=>$deckKey));
    if (empty($Lives)) {
      return $this->getChatContent();
    }
    $Live = array_shift($Lives);
    $this->liveId = $Live->getId();
    $arr = array(
      self::CST_LIVEID=>$this->liveId,
      self::CST_TEXTE=>$this->displayName.' a rejoint l\'espace de conversation.',
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE),
   );
    $this->postChat($arr);
    $_SESSION[self::CST_DECKKEY] = $deckKey;
    $returned  = '{'.$this->getChatContent(false).', "header-ul-chat-saisie":';
    $strJson = '<li class="nav-item"><a class="nav-link active" href="#" data-liveid="'.$this->liveId.'">'.$deckKey.'</a></li>';
    return $returned.json_encode($strJson).'}';
  }
  private function postChat($arr)
  {
    $Chat = new Chat($arr);
    $this->ChatServices->insert(__FILE__, __LINE__, $Chat);
  }
  /**
   * @param unknown $post
   * @return string
   */
  public static function staticChatContent($post)
  {
    $ChatActions = new ChatActions($post);
    return $ChatActions->getChatContent();
  }
  /**
   * @param boolean $directReturn
   * @return string
   */
  public function getChatContent($directReturn=true)
  {
    $arr = array(self::CST_LIVEID=>$this->liveId, self::CST_SENDTOID=>$this->userId, self::CST_TIMESTAMP=>$this->timestamp);
    $Chats = $this->ChatServices->getChatsWithFilters(__FILE__, __LINE__, $arr);
    $strChats = '';
    if (!empty($Chats)) {
      foreach ($Chats as $Chat) {
        $strChats .= $Chat->getChatLine($this->userId);
      }
    }
    return ($directReturn ? '{"online-chat-content":'.json_encode($strChats).'}' : '"online-chat-content":'.json_encode($strChats));
  }
}
