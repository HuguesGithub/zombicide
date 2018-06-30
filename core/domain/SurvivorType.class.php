<?php
if (!defined('ABSPATH')) { die('Forbidden'); }
/**
 * Classe SurvivorType
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SurvivorType extends LocalDomain {
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
   * 
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
   * @return string
   */
  public function getName() { return $this->name; }
  /**
   * @param int $id
   */
  public function setId($id) { $this->id=$id; }
  /**
   * @param string $name
   */
  public function setName($name) { $this->name=$name; }
  /**
   * @return array
   */
  public function getClassVars() { return get_class_vars('SurvivorType'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return SurvivorType
   */
  public static function convertElement($row, $a='', $b='') { return parent::convertElement(new SurvivorType(), self::getClassVars(), $row); }
  
}
