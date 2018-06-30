<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ZombieCategory
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class ZombieCategory extends LocalDomain
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
   * Description de la donnée
   * @var string $description
   */
  protected $description;
  /**
   * 
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
  }
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
   * @return string
   */
  public function getDescription()
  { return $this->description; }
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
   * @param string $description
   */
  public function setDescription($description)
  { $this->description=$description; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('ZombieCategory'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return ZombieCategory
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new ZombieCategory(), self::getClassVars(), $row); }
  
}
