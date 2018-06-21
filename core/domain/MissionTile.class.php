<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe MissionTile
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionTile extends LocalDomain {
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
	public function __construct($attributes=array()) {
		$services = array('Mission', 'MissionTile', 'Tile');
		parent::__construct($attributes, $services);
	}
	/**
	 * @return int
	 */
	public function getId() { return $this->id; }
	/**
	 * @return int
	 */
	public function getMissionId() { return $this->missionId; }
	/**
	 * @return int
	 */
	public function getTileId() { return $this->tileId; }
	/**
	 * @return string
	 */
	public function getOrientation() { return $this->orientation; }
	/**
	 * @return int
	 */
	public function getCoordX() { return $this->coordX; }
	/**
	 * @return int
	 */
	public function getCoordY() { return $this->coordY; }
	/**
	 * @param int $id
	 */
	public function setId($id) { $this->id=$id; }
	/**
	 * @param int $missionId
	 */
	public function setMissionId($missionId) { $this->missionId=$missionId; }
	/**
	 * @param int $tileId
	 */
	public function setTileId($tileId) { $this->tileId=$tileId; }
	/**
	 * @param string $orientation
	 */
	public function setOrientation($orientation) { $this->orientation=$orientation; }
	/**
	 * @param int $coordX
	 */
	public function setCoordX($coordX) { $this->coordX=$coordX; }
	/**
	 * @param int $coordY
	 */
	public function setCoordY($coordY) { $this->coordY=$coordY; }
	/**
	 * @return array
	 */
	public function getClassVars() { return get_class_vars('MissionTile'); }
	/**
	 * @param array $row
	 * @param string $a
	 * @param string $b
	 * @return MissionTile
	 */
	public static function convertElement($row, $a='', $b='') { return parent::convertElement(new MissionTile(), self::getClassVars(), $row); }
	/**
	 * @return string
	 */
	public function getTileCode() { return $this->getTile()->getCode(); }
	/**
	 * @return string
	 */
	public function getUrlImg() { return '/wp-content/plugins/zomb/web/rsc/img/tiles/'.$this->getTileCode().'-500px.png'; }
	/**
	 * @return string
	 */
	public function getRowForTileTbody() {
		$strTileTable .= '<tr>';
		$strTileTable .= '<th>'.$this->getXCoord().'</th>';
		$strTileTable .= '<th>'.$this->getYCoord().'</th>';
		$strTileTable .= '<th>'.$this->getTileCode().'</th>';
		$strTileTable .= '<th>'.$this->getOrientation().'</th>';
		$strTileTable .= '<th><span data-missiontileid="'.$this->getId().'" class="btn btn-default btn-xs rmvTileRowBtn"><i class="glyphicon glyphicon-minus-sign"></i></span></th>';
		$strTileTable .= '</tr>';
		return $strTileTable;
	}
}
?>