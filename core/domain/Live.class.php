<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Live
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Live extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Code alphanumérique
   * @var string $deckKey
   */
  protected $deckKey;
  /**
   * Date de dernière mise à jour
   * @var datetime $dateUpdate
   */
  protected $dateUpdate;
  /**
   * Class Constructor
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->ExpansionServices         = new ExpansionServices();
    $this->EquipmentLiveDeckServices = new EquipmentLiveDeckServices();
    $this->LiveMissionServices       = new LiveMissionServices();
    $this->SkillServices             = new SkillServices();
    $this->SpawnLiveDeckServices     = new SpawnLiveDeckServices();
    $this->SurvivorSkillServices     = new SurvivorSkillServices();
  }
  /**
   * @return int
   */
  public function getId()
  { return $this->id; }
  /**
   * @return string
   */
  public function getDeckKey()
  { return $this->deckKey; }
  /**
   * @return string
   */
  public function getDateUpdate()
  { return $this->dateUpdate; }
  /**
   * @param int $id
   */
  public function setId($id)
  { $this->id = $id; }
  /**
   * @param string $deckKey
   */
  public function setDeckKey($deckKey)
  { $this->deckKey = $deckKey; }
  /**
   * @param string $dateUpdate
   */
  public function setDateUpdate($dateUpdate)
  { $this->dateUpdate = $dateUpdate; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('Live'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return LiveDeck
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Live(), self::getClassVars(), $row); }
  /**
   * @return int
   */
  public function getNbCardsInDeck($type=self::CST_SPAWN)
  { return $this->getNbCardsByStatus($type, 'P'); }
  /**
   * @return int
   */
  public function getNbCardsInDiscard($type=self::CST_SPAWN)
  { return $this->getNbCardsByStatus($type, 'D'); }
  /**
   * @return int
   */
  public function getNbCardsEquipped()
  { return $this->getNbCardsByStatus(self::CST_EQUIPMENT, 'E'); }
  /**
   */
  public function getNbCardsByStatus($type, $status)
  {
    $args = array(self::CST_LIVEID=>$this->id, self::CST_STATUS=>$status);
    if ($type==self::CST_SPAWN) {
      $Objs = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $args);
    } elseif ($type==self::CST_EQUIPMENT) {
      $Objs = $this->EquipmentLiveDeckServices->getEquipmentLiveDecksWithFilters(__FILE__, __LINE__, $args);
    }
    return count($Objs);
  }
  public function getLiveMission()
  {
    if ($this->LiveMission==null) {
      $args = array(self::CST_LIVEID=>$this->id);
      $LiveMissions = $this->LiveMissionServices->getLiveMissionsWithFilters(__FILE__, __LINE__, $args);
      $this->LiveMission = array_shift($LiveMissions);
    }
    return $this->LiveMission;
  }
}
