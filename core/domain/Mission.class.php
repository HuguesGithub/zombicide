<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe Mission
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Mission extends LocalDomain {
	/**
	 * Id technique de la donnée
	 * @var int $id
	 */
	protected $id;
	/**
	 * Titre de la Mission
	 * @var string $title
	 */
	protected $title;
	/**
	 * Code de la donnée
	 * @var string $code
	 */
	protected $code;
	/**
	 * Id de la difficulté de la Mission
	 * @var int $levelId
	 */
	protected $levelId;
	/**
	 * Id du nb de joueurs de la Mission
	 * @var int $playerId
	 */
	protected $playerId;
	/**
	 * Id de la durée de la Mission
	 * @var int $durationId
	 */
	protected $durationId;
	/**
	 * Id de l'origine de la Mission
	 * @var int $origineId
	 */
	protected $origineId;
	/**
	 * Nombre de dalles en largeur
	 * @var int $width
	 */
	protected $width;
	/**
	 * Nombre de dalles en hauteur
	 * @var int $height
	 */
	protected $height;
	/**
	 * La mission a-t-elle était publiée ?
	 * @var int $published
	 */
	protected $published;
	
	/*
	protected $description;
	protected $author;
	protected $officielle;
	protected $active;
	protected $missionLiveActive;
  protected $startingXCoord;
  protected $startingYCoord;
	*/

	/**
	 * 
	 * @param array $attributes
	 */
	public function __construct($attributes=array()) {
		$services = array('Duration', 'MissionExpansion', 'Level', 'MissionObjective', 'Origine', 'Player', 'MissionRule', 'MissionTile', 'WpPost');
		parent::__construct($attributes, $services);
	}

	/**
	 * @return int 
	 */
	public function getId() {return $this->id; }
	/**
	 * @return string
	 */
	public function getTitle() { return $this->title; }
	/**
	 * @return string
	 */
	public function getCode() { return $this->code; }
	/**
	 * @return int
	 */
	public function getLevelId() { return $this->levelId; }
	/**
	 * @return int
	 */
	public function getPlayerId() { return $this->playerId; }
	/**
	 * @return int
	 */
	public function getDurationId() { return $this->durationId; }
	/**
	 * @return int
	 */
	public function getOrigineId() { return $this->origineId; }
	/**
	 * @return int
	 */
  public function getWidth() { return $this->width; }
	/**
	 * @return int
	 */
	public function getHeight() { return $this->height; }
	/**
	 * @return boolean
	 */
	public function isPublished() { return ($this->published==1); }
	/*
	public function getDescription() { return $this->description; }
	public function getAuthor() { return $this->author; }
	public function isOfficielle() { return ($this->officielle==1); }
	public function isActive() { return ($this->active==1); }
	public function isMissionLiveActive() { return ($this->missionLiveActive==1); }
  public function getStartingXCoord() { return $this->startingXCoord; }
  public function getStartingYCoord() { return $this->startingYCoord; }
*/
	/**
	 * @param int $id
	 */
	public function setId($id) { $this->id=$id; }
	/**
	 * @param string $title
	 */
	public function setTitle($title) { $this->title=$title; }
	/**
	 * @param string $code
	 */
	public function setCode($code) { $this->code=$code; }
	/**
	 * @param int $levelId
	 */
	public function setLevelId($levelId) { $this->levelId=$levelId; }
	/**
	 * @param int $playerId
	 */
	public function setPlayerId($playerId) { $this->playerId=$playerId; }
	/**
	 * @param int $durationId
	 */
	public function setDurationId($durationId) { $this->durationId=$durationId; }
	/**
	 * @param int $origineId
	 */
	public function setOrigineId($origineId) { $this->origineId=$origineId; }
	/**
	 * @param int $width
	 */
	public function setWidth($width) { $this->width=$width; }
	/**
	 * @param int $height
	 */
	public function setHeight($height) { $this->height=$height; }
	/**
	 * @param boolean $published
	 */
	public function setPublished($published) { $this->published=$published; }
	/*
	public function setDescription($description) { $this->description=$description; }
	public function setAuthor($author) { $this->author=$author; }
	public function setOfficielle($officielle) { $this->officielle=$officielle; }
	public function setActive($active) { $this->active=$active; }
	public function setMissionLiveActive($missionLiveActive) { $this->missionLiveActive=$missionLiveActive; }
  public function setStartingXCoord($startingXCoord) { $this->startingXCoord=$startingXCoord; }
  public function setStartingYCoord($startingYCoord) { $this->startingYCoord=$startingYCoord; }
  */
	/**
	 * @return array
	 */
	public function getClassVars() { return get_class_vars('Mission'); }
	/**
	 * @param array $row
	 * @param string $a
	 * @param string $b
	 * @return Mission
	 */
	public static function convertElement($row, $a='', $b='') { return parent::convertElement(new Mission(), self::getClassVars(), $row); }
	/**
	 * @return string
	 */  
	public function getStrRules() {
		$MissionRules = $this->getMissionRules('title');
		$strList = '';
		if ( !empty($MissionRules) ) {
			foreach ( $MissionRules as $MissionRule ) {
				$strList .= ( $strList!='' ? '<br>' : '' );
				$strList .= '<span class="objRule">'.$MissionRule->getTitle().' <span class="tooltip"><header>'.$MissionRule->getRuleCode().'</header>';
				$strList .= '<content>'.$MissionRule->getRuleDescription().'</content></span></span> ';
			}
		}
		return $strList;
	}
	/**
	 * @return string
	 */
	public function getStrObjectives() {
		$MissionObjectives = $this->getMissionObjectives('title');
		$strList = '';
		if ( !empty($MissionObjectives) ) {
			foreach ( $MissionObjectives as $MissionObjective ) {
				$strList .= ( $strList!='' ? '<br>' : '' );
				$strList .= '<span class="objRule">'.$MissionObjective->getTitle().' <span class="tooltip"><header>'.$MissionObjective->getObjectiveCode().'</header>';
				$strList .= '<content>'.$MissionObjective->getObjectiveDescription().'</content></span></span> ';
			}
		}
		return $strList;
	}
	/**
	 * @return string
	 */
	public function getStrTiles() {
		$MissionTiles = $this->getMissionTiles();
		if ( !empty($MissionTiles) ) {
			foreach ( $MissionTiles as $MissionTile ) {
				if ( $strName!='' ) { $strName .= ', '; }
				$strName .= $MissionTile->getTileCode();
			}
		}
		return $strName;
	}
	/**
	 * @return string
	 */
	public function getStrExpansions() {
		$MissionExpansions = $this->getMissionExpansions();
		if ( !empty($MissionExpansions) ) {
			foreach ( $MissionExpansions as $MissionExpansion ) {
				if ( $strName!='' ) { $strName .= ', '; }
				$strName .= $MissionExpansion->getExpansionName();
			}
		}
		return $strName;
	}
	/**
	 * @return string
	 */
	public function getStrDifPlaDur() { return $this->getStrDifficulty().' / '.$this->getStrNbJoueurs().' / '.$this->getStrDuree(); }
	/**
	 * @return string
	 */
	public function getStrDuree() { return $this->getDuration()->getStrDuree(); }
	/**
	 * @return string
	 */
	public function getStrDifficulty() { return $this->getLevel()->getName(); }
	/**
	 * @return string
	 */
	public function getStrNbJoueurs() { return $this->getPlayer()->getNbJoueurs(); }
	/**
	 * @return string
	 */
	public function getStrOrigine() { return $this->getOrigine()->getName(); }
	/**
	 * @param bool $isHome
	 * @return string
	 */
	public function getStrClassFilters($isHome) {
		$strClassFilters ='';
		$strClassFilters  = 'player-'.$this->playerId.' ';
		$strClassFilters .= 'duration-'.$this->durationId.' ';
		$strClassFilters .= 'level-'.$this->levelId.' ';
		/*
		$MissionExpansions = $this->getMissionExpansions();
		if ( !empty($MissionExpansions) ) {
			foreach ( $MissionExpansions as $MissionExpansion ) {
				$strClassFilters .= $MissionExpansion->getExpansionCode().' ';
			}
		}
		*/
		$strClassFilters .= ' col-12 col-sm-6 col-md-4';
		return $strClassFilters;
	}
	/**
	 * @param int $x
	 * @param int $y
	 * @return MissionTile
	 */
	public function getMissionTile($x, $y) {
		$MissionTiles = $this->getMissionTiles();
		if ( !empty($MissionTiles) ) {
			foreach ( $MissionTiles as $MissionTile ) {
				if ( $MissionTile->getCoordX()==$x && $MissionTile->getCoordY()==$y ) {
					return $MissionTile;
				}
			}
		}
		return new MissionTile();
	}
	/**
	 * @param int $x
	 * @param int $y
	 * @return int
	 */
	public function getTileId($x, $y) { return $this->getMissionTile($x, $y)->getTileId(); }
	/**
	 * @param int $x
	 * @param int $y
	 * @return string
	 */
	public function getTileCode($x, $y) { return $this->getMissionTile($x, $y)->getTileCode(); }
	/**
	 * @param int $x
	 * @param int $y
	 * @return string
	 */
	public function getTileCodeAndOrientation($x, $y) { return $this->getTileCode($x, $y).'-'.$this->getTileOrientation($x, $y); }
	/**
	 * @param int $x
	 * @param int $y
	 * @return string
	 */
	public function getTileOrientation($x, $y) { return $this->getMissionTile($x, $y)->getOrientation(); }
	/**
	 * @return string
	 */
	public function getWpPostUrl() {
		$url = '#';
		$args = array('meta_key'=>'missionId', 'meta_value'=>$this->id);
		$WpPosts = $this->WpPostServices->getArticles(__FILE__, __LINE__, $args);
		if ( !empty($WpPosts) ) {
			$WpPost = array_shift($WpPosts);
			$url = $WpPost->getGuid();
		}
		return $url;
	}
	/**
	 * @param array $MissionExpansions
	 */
	public function setMissionExpansions($MissionExpansions) { $this->MissionExpansions = $MissionExpansions; }
	/**
	 * @param array $post
	 * @return bool
	 */
	public function updateWithPost($post) {
		$doUpdate = FALSE;
		// TODO : Ajouter origineId quand la liste déroulante aura été mise en place.
		$arr = array('title', 'code', 'levelId', 'durationId', 'playerId', 'origineId');
		while ( !empty($arr) ) {
			$key = array_shift($arr);
			$value = stripslashes($post[$key]);
			if ( $this->{$key} != $value ) {
				$doUpdate = TRUE;
				$this->{$key} = $value;
			}
		}
		return $doUpdate;
	}
	/**
	 * @param array $post
	 * @return bool
	 */
	public function initWithPost($post) {
		$doInsert = TRUE;
		$arr = array('title', 'code', 'levelId', 'durationId', 'playerId', 'origineId');
		while ( !empty($arr) ) {
			$key = array_shift($arr);
			if ( $post[$key] == '' ) { $doInsert = FALSE; }
			else { $this->{$key} = stripslashes($post[$key]); }
		}
		return $doInsert;
	}
	/*
	public function getExtractDescription() { return substr($this->description, 0, 150); }
  
  public function getObjectivesList() { return $this->getObjRuleList('Objective'); }
  public function getSetupList() { return $this->getObjRuleList('Setup'); }
  public function getRulesList() { return $this->getObjRuleList('Rule'); }
	private function getObjRuleList($type) {
    $strList = '';
    $MissionRules = $this->getMissionRules();
    if ( !empty($MissionRules) ) {
      foreach ( $MissionRules as $MissionRule ) {
        if ( $MissionRule->getRuleType()==$type ) {
          $strList .= ( $strList!='' ? '<br>' : '' );
	        $strList .= '<span class="objRule">'.$MissionRule->getTitle().' <span class="tooltip"><header>'.$MissionRule->getRuleCode().'</header>';
          $strList .= '<content>'.$MissionRule->getRuleDescription().'</content></span></span> ';
        }
      }
    }
    return $strList;
  }
  public function getAllRules($strObjectives, $strSetups, $strRules) {
  	$strAllRules  = '<li data-missionId="'.$this->getId().'"><h4>Objectifs</h4><ul>';
  	$strAllRules .= ($strObjectives=='' ? '<li>Aucun objectif particulier.</li>' : $strObjectives);
  	$strAllRules .= '</ul>';
  	$strAllRules .= '<h4>Mise en place</h4><ul>';
  	$strAllRules .= ($strSetups=='' ? '<li>Aucune mise en place particulière.</li>' : $strSetups);
  	$strAllRules .= '</ul>';
  	$strAllRules .= '<h4>Règles spéciales</h4><ul>';
  	$strAllRules .= ($strRules=='' ? '<li>Aucune règle spéciale particulière.</li>' : $strRules);
  	$strAllRules .= '</ul></li>';
  	return $strAllRules;
  	}
  
  
  
	public function getWpPost($type='') { return parent::getWpPost('wp_3_z_mission'); }
	*/
	
	/**
	 * Retourne l'url de l'image de la Mission
	 *
	public function getImgUrl() {
		$WpPost = $this->WpPost;
		if ( $WpPost!=null ) {
			$medias = get_attached_media( 'image', $WpPost->getID() );
			if ( !empty($medias) ) {
				$media = array_shift($medias);
				if ( $media->guid!='' ) {
					return $media->guid;
				}
			}
		}
		return '/wp-content/plugins/zomb/web/rsc/img/missions/Mission_'.$this->code.'.jpg';
	}

	public function getMapUrl($type='') { return '/wp-content/plugins/zomb/web/rsc/img/map/'.$this->code.($type==''?'':'_'.$type).'.jpg'; }
	public function getStrDimension() { return $this->width.'x'.$this->height; }

  public function getStartingCanvasDiv() { 
    return '<div id="token_00" class="draggable token start" style="top: '.$this->startingYCoord.'px; left: '.$this->startingXCoord.'px;"></div>';
	}
  

	public function needExpansion($expansionId) {
    $MissionExpansions = $this->getMissionExpansions();
    if ( !empty($MissionExpansions) ) {
      foreach ( $MissionExpansions as $MissionExpansion ) {
        if ( $MissionExpansion->getExpansionId() == $expansionId ) { return TRUE; }
      }
    }
    return FALSE;
  }



  


	/**
	 * @param array $row
	 *
	public static function convertElementFromPost($row) {
		$Obj = new Mission();
		$vars = get_class_vars('Mission');
		if ( !empty($vars) ) {
			foreach ( $vars as $key=>$value ) {
				$Obj->setField($key, str_replace("\\", '', $row[$key]));
			}
			if ( $row['officielle']=='on' ) { $Obj->setField('officielle', 1); }
			if ( $row['active']=='on' ) { $Obj->setField('active', 1); }
			if ( $row['missionliveactive']=='on' ) { $Obj->setField('missionliveactive', 1); }
		}
		return $Obj;
	}
  
	/**
	 * Construit et Affiche le visuel de Mission.
	 * Pour le moment, construit en html et à coup de div et li. Envisager une construction sur canvas.
	 * @since 1.0.00
	 *
	public function displayMissionMap() {
		$width = 250*$this->width;
		$height = 250*$this->height;
		$str  = '';
		$str .= '<ul id="mapTiles" style="width: '.$width.'px; height: '.$height.'px;">';
		foreach ( $this->Tiles as $LinkTile ) {
			$LinkTile->displayMissionTile();
		}
		foreach ( $this->Zones as $LinkZone ) {
			$LinkZone->displayMissionZone();
		}
		$str .= '</ul>';
		return $str;
	}
	*/
}
?>