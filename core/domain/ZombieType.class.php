<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ZombieType
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class ZombieType extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Nom de la donnée
   * @var string $name
   */
  protected $name;
  /**
   * Nombre d'Actions
   * @var string $pointAction
   */
  protected $pointAction;
  /**
   * Dégâts nécessaires pour être tué
   * @var string $endurance
   */
  protected $endurance;
  /**
   * Nombre de points d'expérience rapportés
   * @var string $pointExperience
   */
  protected $pointExperience;
  /**
   * Nombre de Zones par Action de Déplacement
   * @var string $zoneDeplacement
   */
  protected $zoneDeplacement;
  /**
   * @return int
   */
  public function getId()
  { return $this->id; }
  /**
   * @return string
   */
  public function getName()
  { return $this->name; }
  /**
   * @return int
   */
  public function getPointAction()
  { return $this->pointAction; }
  /**
   * @return int
   */
  public function getEndurance()
  { return $this->endurance; }
  /**
   * @return int
   */
  public function getPointExperience()
  { return $this->pointExperience; }
  /**
   * @return int
   */
  public function getZoneDeplacement()
  { return $this->zoneDeplacement; }
  /**
   * @param int $id
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param string $name
   */
  public function setName($name)
  { $this->name=$name; }
  /**
   * @param int $pointAction
   */
  public function setPointAction($pointAction)
  { $this->pointAction=$pointAction; }
  /**
   * @param int $endurance
   */
  public function setEndurance($endurance)
  { $this->endurance=$endurance; }
  /**
   * @param int $pointExperience
   */
  public function setPointExperience($pointExperience)
  { $this->pointExperience=$pointExperience; }
  /**
   * @param int $zoneDeplacement
   */
  public function setZoneDeplacement($zoneDeplacement)
  { $this->zoneDeplacement=$zoneDeplacement; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('ZombieType'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return ZombieType
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new ZombieType(), self::getClassVars(), $row); }
  
}
