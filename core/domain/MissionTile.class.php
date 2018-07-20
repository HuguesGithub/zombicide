<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionTile
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionTile extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Id technique de la Mission
   * @var int $missionId
   */
  protected $missionId;
  /**
   * Id technique de la Dalle
   * @var int $tileId
   */
  protected $tileId;
  /**
   * Orientation de la Dalle sur la Mission
   * @var string $orientation
   */
  protected $orientation;
  /**
   * Coordonnées en abscisses
   * @var int $coordX
   */
  protected $coordX;
  /**
   * Coordonnée en ordonnées
   * @var int $coordY
   */
  protected $coordY;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->MissionServices       = new MissionServices();
    $this->MissionTileServices   = new MissionTileServices();
    $this->TileServices          = new TileServices();
  }
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
  public function getTileId()
  { return $this->tileId; }
  /**
   * @return string
   */
  public function getOrientation()
  { return $this->orientation; }
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
   * @param int $id
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param int $missionId
   */
  public function setMissionId($missionId)
  { $this->missionId=$missionId; }
  /**
   * @param int $tileId
   */
  public function setTileId($tileId)
  { $this->tileId=$tileId; }
  /**
   * @param string $orientation
   */
  public function setOrientation($orientation)
  { $this->orientation=$orientation; }
  /**
   * @param int $coordX
   */
  public function setCoordX($coordX)
  { $this->coordX=$coordX; }
  /**
   * @param int $coordY
   */
  public function setCoordY($coordY)
  { $this->coordY=$coordY; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('MissionTile'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return MissionTile
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new MissionTile(), self::getClassVars(), $row); }
  /**
   * @return Tile
   */
  public function getTile()
  {
    if ($this->Tile==null) {
      $this->Tile = $this->TileServices->select(__FILE__, __LINE__, $this->tileId);
    }
    return $this->Tile;
  }
  /**
   * @return string
   */
  public function getTileCode()
  { return $this->getTile()->getCode(); }
  /**
   * @return string
   */
  public function getUrlImg()
  { return '/wp-content/plugins/zomb/web/rsc/img/tiles/'.$this->getTileCode().'-500px.png'; }
  /**
   * @return string
   */
  public function getRowForTileTbody()
  {
    $args = array(
      $this->getXCoord(),
      $this->getYCoord(),
      $this->getTileCode(),
      $this->getOrientation(),
      $this->getId(),
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/tile-row-public.php');
    return vsprintf($str, $args);
  }
}
