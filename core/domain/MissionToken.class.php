<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionToken
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionToken extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Identifiant technique de la Mission
   * @var int $missionId
   */
  protected $missionId;
  /**
   * Identifiant technique du Token
   * @var int $tokenId
   */
  protected $tokenId;
  /**
   * Position en abscisses
   * @var int $coordX
   */
  protected $coordX;
  /**
   * Position en ordonnées
   * @var int $coordY
   */
  protected $coordY;
  /**
   * Couleur du Token
   * @var string $color
   */
  protected $color;
  /**
   * Statut du Token
   * @var string $status
   */
  protected $status;
  /**
   * Orientation du Token
   * @var string $orientation
   */
  protected $orientation;
  /**
   * @return int
   */
  public function getId()
  { return $this->id; }
  /**
   * @return int
   */
  public function getMissionId()
  { return $this->missionId; }
  /**
   * @return int
   */
  public function getTokenId()
  { return $this->tokenId; }
  /**
   * @return int
   */
  public function getCoordX()
  { return $this->coordX; }
  /**
   * @return int
   */
  public function getCoordY()
  { return $this->coordY; }
  /**
   * @return string
   */
  public function getColor()
  { return $this->color; }
  /**
   * @return string
   */
  public function getStatus()
  { return $this->status; }
    /**
   * @return string
   */
  public function getOrientation()
  { return $this->orientation; }
/**
   * @param int $id
   */
  public function setId($id)
  { $this->id = $id; }
  /**
   * @param int $missionId
   */
  public function setMissionId($missionId)
  { $this->missionId = $missionId; }
  /**
   * @param int $tokenId
   */
  public function setTokenId($tokenId)
  { $this->tokenId = $tokenId; }
  /**
   * @param int $coordX
   */
  public function setCoordX($coordX)
  { $this->coordX = $coordX; }
  /**
   * @param int $coordY
   */
  public function setCoordY($coordY)
  { $this->coordY = $coordY; }
  /**
   * @param string $color
   */
  public function setColor($color)
  { $this->color = $color; }
  /**
   * @param string $status
   */
  public function setStatus($status)
  { $this->status = $status; }
  /**
   * @param string $orientation
   */
  public function setOrientation($orientation)
  { $this->orientation = $orientation; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('MissionToken'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return MissionToken
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new MissionToken(), self::getClassVars(), $row); }

}
