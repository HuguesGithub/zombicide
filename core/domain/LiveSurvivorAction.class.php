<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivorAction
 * @author Hugues
 * @since 1.0.01
 * @version 1.0.01
 */
class LiveSurvivorAction extends LocalDomain
{
  /**
   * Id technique de la jointure
   * @var int $id
   */
  protected $id;
  /**
   * Id technique du LiveSurvivor
   * @var int $liveSurvivorId
   */
  protected $liveSurvivorId;
  /**
   * Id technique de l'Action
   * @var int $actionId
   */
  protected $actionId;
  /**
   * @return int
   */
  public function getId()
  { return $this->id; }
  /**
   * @ return int
   */
  public function getLiveSurvivorId()
  { return $this->liveSurvivorId; }
  /**
   * @ return int
   */
  public function getActionId()
  { return $this->actionId; }
  /**
   * @param int $id
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param int $liveSurvivorId
   */
  public function setLiveSurvivorId($liveSurvivorId)
  { $this->liveSurvivorId = $liveSurvivorId; }
  /**
   * @param int $actionId
   */
  public function setActionId($actionId)
  { $this->actionId = $actionId; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('LiveSurvivorAction'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return LiveSurvivorAction
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new LiveSurvivorAction(), self::getClassVars(), $row); }
  /**
   * @return LiveSurvivorActionBean
   */
  public function getBean()
  { return new LiveSurvivorActionBean($this); }
}
