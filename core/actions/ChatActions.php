<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * ChatActions
 * @since 1.0.00
 * @version 1.0.01
 * @author Hugues
 */
class ChatActions extends LocalActions
{
  /**
   * Constructeur
   */
  public function __construct($post)
  {
    parent::__construct();
    $this->User = wp_get_current_user();
    $Data = $this->User->data;
    $this->userId = $Data->ID;
    $this->displayName = $Data->display_name;
    $arrParams = array(
      self::CST_LIVEID=>'0',
      self::CST_TEXTE=>'',
      self::CST_TIMESTAMP=>''
    );
    foreach ($arrParams as $key => $param) {
      $this->{$key} = isset($post[$key]) ? $post[$key] : $param;
    }
    $this->ChatServices        = new ChatServices();
    $this->LiveServices        = new LiveServices();
    $this->LiveMissionServices = new LiveMissionServices();
  }
  
  /**
   * Point d'entrée des méthodes statiques.
   * @param array $post
   * @return string
   **/
  public static function dealWithStatic($post)
  {
    $returned = '';
    $Act = new ChatActions($post);
    switch ($post['ajaxChildAction']) {
      case 'postChat'    :
        $returned = $Act->dealWithPostChat();
      break;
      case 'refreshChat' :
        $returned = $Act->dealWithRefreshChat();
      break;
      default :
        $returned = '';
      break;
    }
    return $returned;
  }
  /**
   * @return string
   */
  public function dealWithPostChat()
  {
    $this->purgeOldChat();
    $text = trim($this->texte);
    if ($text!='') {
      $arrCmds = explode(' ', $text);
      switch ($arrCmds[0]) {
        case '/clean'  :
          $returned = $this->cleanChat();
        break;
        case '/exit'   :
          $returned = $this->exitLive();
        break;
        case '/games'  :
          $returned = $this->listGames();
        break;
        case '/help'   :
          $returned = $this->helpLive();
        break;
        case '/invite' :
          $returned = $this->inviteUser($arrCmds[1]);
        break;
        case '/join'   :
          $returned = $this->joinLive($arrCmds[1]);
        break;
        case '/users'  :
          $returned = $this->listUsers();
        break;
        case '/activateZombies' :
          // TODO : à ne pas garder en PROD, ou à protéger par droits d'admin.
          $OnlineActions = new OnlineActions();
          $args = array(self::CST_DECKKEY=>$_SESSION[self::CST_DECKKEY]);
          $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
          $Live = array_shift($Lives);
          $returned = $OnlineActions->activateZombies((isset($arrCmds[1])?$arrCmds[1]:''), $Live);
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
  /**
   * @return string
   */
  private function cleanChat()
  {
    $arr = array(
      self::CST_SENDTOID=>$this->userId,
      self::CST_TEXTE=>'Vous avez vidé l\'interface.',
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE)
    );
    $this->postChat($arr);
    return $this->getChatContent();
  }
  /**
   * @return string
   */
  public function dealWithRefreshChat()
  {
    $this->purgeOldChat();
    return $this->getChatContent();
  }
  /**
   * @return string
   */
  private function exitLive()
  {
    // On affiche un message de départ dans l'espace courant.
    $arr = array(
      self::CST_LIVEID=>$this->liveId,
      self::CST_TEXTE=>$this->displayName.' a quitté l\'espace de conversation.',
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE),
    );
    $this->postChat($arr);
    // On quitte l'espace en cours, pour retourner dans l'espace général.
    unset($_SESSION[self::CST_DECKKEY]);
    $this->liveId = 0;
    // On affiche un message de bienvenue dans le nouveau canal.
    $arr = array(
      self::CST_LIVEID=>$this->liveId,
      self::CST_SENDTOID=>$this->userId,
      self::CST_TEXTE=>'Vous êtes de retour sur le canal par défaut',
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE),
    );
    $this->postChat($arr);
    // On retourne le contenu du nouveau canal et on modifie l'onglet avec le nom qui convient.
    return '{'.$this->getChatContent(false).', '.$this->getHeaderChatSaisie('Général', 0, false).'}';
  }
  /**
   * @param boolean $directReturn
   * @return string
   */
  public function getChatContent($directReturn=true)
  {
    $arr = array(
      self::CST_LIVEID=>$this->liveId,
      self::CST_SENDTOID=>$this->userId,
      self::CST_TIMESTAMP=>$this->timestamp
    );
    $Chats = $this->ChatServices->getChatsWithFilters(__FILE__, __LINE__, $arr);
    $strChats = '';
    if (!empty($Chats)) {
      foreach ($Chats as $Chat) {
        $strChats .= $Chat->getChatLine($this->userId);
      }
    }
    return ($directReturn ? '{"online-chat-content":'.json_encode($strChats).'}' : '"online-chat-content":'.json_encode($strChats));
  }
  /**
   * @param string $label
   * @param int $liveId
   * @param string $directReturn
   */
  public function getHeaderChatSaisie($label, $liveId, $directReturn=true)
  {
    $strChats = '<li class="nav-item"><a class="nav-link active" href="#" data-liveid="'.$liveId.'">'.$label.'</a></li>';
    return ($directReturn ? '{"header-ul-chat-saisie":'.json_encode($strChats).'}' : '"header-ul-chat-saisie":'.json_encode($strChats));
  }
  /**
   * @return string
   */
  private function helpLive()
  {
    $text  = '';
    $text .= '<b>/clean</b> Vide l\'interface de discussion.';
    $text .= '<br><b>/exit</b> Rejoindre l\'espace général.';
    $text .= '<br><b>/games</b> Affiche la liste des Missions en cours.';
    $text .= '<br><b>/help</b> Affiche cette aide.';
    $text .= '<br><b>/invite xxxxx</b> Envoie une invitation à rejoindre l\'espace courant.';
    $text .= '<br><b>/join xxxxx</b> Rejoindre un espace dédié.';
    $text .= '<br><b>/users</b> Affiche la liste des Utilisateurs connectés.';
    $arr = array(
      self::CST_SENDTOID=>$this->userId,
      self::CST_TEXTE=>$text,
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE)
    );
    $this->postChat($arr);
    return $this->getChatContent();
  }
  /**
   * @param string $displayName
   * @return string
   */
  private function inviteUser($displayName='')
  {
    // On récupère l'utilisateur WordPress passé en paramètre. S'il n'existe pas, on ne donne pas suite.
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
    // S'il existe, on fabrique un Message contenant un lien vers l'espace de discussion du Live courant.
    $Live = $this->LiveServices->select(__FILE__, __LINE__, $this->liveId);
    $deckKey = $Live->getDeckKey();
    $arr = array(
      self::CST_SENDTOID=>$WpUser->getID(),
      'senderId'=>$this->userId,
      self::CST_TEXTE=>'Rejoins moi sur l\'espace '.$this->getSpecialSpan('keyDeck', $deckKey),
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE),
    );
    $this->postChat($arr);
    // Et on affiche un message pour confirmer l'envoi.
    $arr = array(
      self::CST_SENDTOID=>$this->userId,
      self::CST_TEXTE=>'Invitation envoyée à '.$this->getSpecialSpan('author', $displayName),
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE),
    );
    $this->postChat($arr);
    return $this->getChatContent();
  }
  /**
   * @param string $deckKey
   * @return string
   */
  private function joinLive($deckKey='')
  {
    // on vérifie l'existence d'un Live avec cette clé.
    $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, array(self::CST_DECKKEY=>$deckKey));
    if (empty($Lives)) {
      return $this->getChatContent();
    }
    // On rejoint le Live, puis on poste un message de bienvenue.
    $_SESSION[self::CST_DECKKEY] = $deckKey;
    $Live = array_shift($Lives);
    $this->liveId = $Live->getId();
    $arr = array(
      self::CST_LIVEID=>$this->liveId,
      self::CST_TEXTE=>$this->displayName.' a rejoint l\'espace de conversation.',
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE),
    );
    $this->postChat($arr);
    // On retourne le contenu du nouveau canal et on modifie l'onglet avec le nom qui convient.
    return '{'.$this->getChatContent(false).', '.$this->getHeaderChatSaisie($deckKey, $this->liveId, false).'}';
  }
  /**
   * @return string
   */
  private function listGames()
  {
    $text  = '';
    $text .= '<b>Liste des Missions accessibles</b>';
    $LiveMissions = $this->LiveMissionServices->getLiveMissionsWithFilters(__FILE__, __LINE__);
    while (!empty($LiveMissions)) {
      $LiveMission = array_shift($LiveMissions);
      $deckKey = $LiveMission->getLive()->getDeckKey();
      $text .= '<br>'.$this->getSpecialSpan('keyDeck', $deckKey, 'Rejoindre cette Mission');
    }
    $arr = array(
      self::CST_SENDTOID=>$this->userId,
      self::CST_TEXTE=>$text,
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE)
    );
    $this->postChat($arr);
    return $this->getChatContent();
  }
  /**
   * @return string
   */
  private function listUsers()
  {
    $text  = '';
    $text .= '<b>Liste des Utilisateurs connectés</b>';
    $Chats = $this->ChatServices->getDistinctUsersOnline(__FILE__, __LINE__);
    while (!empty($Chats)) {
      $Chat = array_shift($Chats);
      $WpUser = get_user_by('ID', $Chat->getSenderId());
      $displayName = $WpUser->display_name;
      $text .= '<br>'.$this->getSpecialSpan('userInvite', $displayName, 'Ecrit à cet utilisateur');
    }
    $arr = array(
      self::CST_SENDTOID=>$this->userId,
      self::CST_TEXTE=>$text,
      self::CST_TIMESTAMP=>date(self::CST_FORMATDATE)
    );
    $this->postChat($arr);
    return $this->getChatContent();
  }
  private function getSpecialSpan($classe, $deckKey, $title='')
  { return '<span class="'.$classe.'" data-keydeck="'.$deckKey.'" title="'.$title.'">'.$deckKey.'</span>'; }
  /**
   * @param array $arr
   */
  public function postChat($arr)
  {
    $Chat = new Chat($arr);
    $this->ChatServices->insert(__FILE__, __LINE__, $Chat);
  }
  /**
   */
  public function purgeOldChat()
  {
    // On récupère tous les Chats pouvant être effacés. Les conditions étant un peu complexes, on ne le fait pas directement via la requête
    $Chats = $this->ChatServices->getPurgeableChats(__FILE__, __LINE__);
    while (!empty($Chats)) {
      $Chat = array_shift($Chats);
      // Si le Chat peut être purgé, on le fait.
      if ($Chat->isPurgeable()) {
        $this->ChatServices->delete(__FILE__, __LINE__, $Chat);
      }
    }
  }
  /**
   * @param array $args
   */
  public static function staticPostChat($args)
  {
    $ChatServices = new ChatServices();
    $Chat = new Chat($args);
    $ChatServices->insert(__FILE__, __LINE__, $Chat);
  }
}
