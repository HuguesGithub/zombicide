<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe Chat
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Chat extends LocalDomain {
    /**
     * Id technique de la donnée
     * @var int $id
     */
    protected $id;
    /**
     * Eventuel Identifiant de Partie. Par défaut 0, onglet Général
     * @var int $liveId
     */
    protected $liveId;
    /**
     * Eventuel Identifiant de Joueur. Par défaut 0, adressé à tout le monde
     * @var int $sendToId
     */
    protected $sendToId;
    /**
     * Identifiant de l'expéditeur.
     * @var int $senderId
     */
    protected $senderId;
    /**
     * Timestamp du message
     * @var int $timestamp
     */
    protected $timestamp;
    /**
     * Message posté
     * @var string $texte
     */
    protected $texte;
    /**
     * @param array $attributes
     */
    public function __construct($attributes=array()) {
        parent::__construct($attributes);
    }
    /**
     * @return int
     */
    public function getId() {return $this->id; }
    /**
     * @return int
     */
    public function getLiveId() {return $this->liveId; }
    /**
     * @return int
     */
    public function getSendToId() {return $this->sendToId; }
    /**
     * @return int
     */
    public function getSenderId() {return $this->senderId; }
    /**
     * @return int
     */
    public function getTimestamp() {return $this->timestamp; }
    /**
     * @return string
     */
    public function getTexte() { return $this->texte; }
    /**
     * @param int $id
     */
    public function setId($id) { $this->id=$id; }
    /**
     * @param int $liveId
     */
    public function setLiveId($liveId) { $this->liveId=$liveId; }
    /**
     * @param int $sendToId
     */
    public function setSendToId($sendToId) { $this->sendToId=$sendToId; }
    /**
     * @param int $senderId
     */
    public function setSenderId($senderId) { $this->senderId=$senderId; }
    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp) { $this->timestamp=$timestamp; }
    /**
     * @param string $texte
     */
    public function setTexte($texte) { $this->texte=$texte; }
    /**
     * @return array
     */
    public function getClassVars() { return get_class_vars('Chat'); }
    /**
     * @param array $row
     * @param string $a
     * @param string $b
     * @return Chat
     */
    public static function convertElement($row, $a='', $b='') { return parent::convertElement(new Chat(), self::getClassVars(), $row); }
    /**
     * @return string
     */
    public function getSenderDisplayName() {
        $WpUser = get_user_by('ID', $this->senderId);
        return $WpUser->display_name;
    }
}
?>