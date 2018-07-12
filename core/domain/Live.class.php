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
    $this->ExpansionServices = FactoryServices::getExpansionServices();
    $this->SkillServices = FactoryServices::getSkillServices();
    $this->SurvivorSkillServices = FactoryServices::getSurvivorSkillServices();
    $this->SpawnLiveDeckServices = FactoryServices::getSpawnLiveDeckServices();
    $this->EquipmentLiveDeckServices = FactoryServices::getEquipmentLiveDeckServices();
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
  public function getNbCardsInDeck($type='spawn')
  { return $this->getNbCardsByStatus($type, 'P'); }
  /**
   * @return int
   */
  public function getNbCardsInDiscard($type='spawn')
  { return $this->getNbCardsByStatus($type, 'D'); }
  /**
   */
  public function getNbCardsByStatus($type, $status)
  {
    if ($type=='spawn') {
      $args = array('liveId'=>$this->id, 'status'=>$status);
      $Objs = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $args);
    } elseif ($type=='equipment') {
      $args = array('liveId'=>$this->id, 'status'=>$status);
      $Objs = $this->EquipmentLiveDeckServices->getEquipmentLiveDecksWithFilters(__FILE__, __LINE__, $args);
    }
    return count($Objs);
  }
}
