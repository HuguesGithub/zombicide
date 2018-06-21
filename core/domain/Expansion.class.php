<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe Expansion
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Expansion extends LocalDomain {
	/**
	 * Id technique de la donnée
	 * @var int $id
	 */
	protected $id;
	/**
	 * Code de la donnée
	 * @var string $code
	 */
	protected $code;
	/**
	 * Nom de la donnée
	 * @var string $name
	 */
	protected $name;
	/**
	 * Rang d'affichage
	 * @var int $displayRank
	 */
	protected $displayRank;
	/*
	protected $imageSuffixe;
	protected $officielle;
	protected $active;
  protected $invasionSpan;
  protected $nbSurvivants;
  protected $nbDalles;
  protected $nbEquipmentCards;
  protected $nbInvasionCards;
	*/

	/**
	 * 
	 * @param array $attributes
	 */
	public function __construct($attributes=array()) {
		$services = array(/*'EquipmentExpansion'*/);
		parent::__construct($attributes, $services);
	}

	/**
	 * Getter Id
	 * @return int
	 */
	public function getId() {return $this->id; }
	/**
	 * Getter Code
	 * @return string
	 */
	public function getCode() { return $this->code; }
	/**
	 * Getter Name
	 * @return string
	 */
	public function getName() { return $this->name; }
	/**
	 * Getter displayRank
	 * @return int
	 */
	public function getDisplayRank() { return $this->displayRank; }
	/*
	public function getImageSuffixe() { return $this->imageSuffixe; }
	public function isOfficielle() { return ($this->officielle==1); }
	public function isActive() { return ($this->active==1); }
	public function getInvasionspan() { return $this->invasionSpan; }
  public function getNbSurvivants() { return $this->nbSurvivants; }
  public function getNbDalles() { return $this->nbDalles; }
  public function getNbEquipmentCards() { return $this->nbEquipmentCards; }
  public function getNbInvasionCards() { return $this->nbInvasionCards; }
	*/
	/**
	 * 
	 * @param int $id
	 */
	public function setId($id) { $this->id=$id; }
	/**
	 * 
	 * @param string $code
	 */
	public function setCode($code) { $this->code=$code; }
	/**
	 * 
	 * @param string $name
	 */
	public function setName($name) { $this->name=$name; }
	/**
	 * 
	 * @param int $displayRank
	 */
	public function setDisplayRank($displayRank) { $this->displayRank=$displayRank; }
	/*
	public function setImageSuffixe($imageSuffixe) { $this->imageSuffixe=$imageSuffixe; }
	public function setOfficielle($officielle) { $this->officielle=$officielle; }
	public function setActive($active) { $this->active=$active; }
	public function setInvasionspan($invasionSpan) { $this->invasionSpan=$invasionSpan; }
  public function setNbSurvivants($nbSurvivants) { $this->nbSurvivants=$nbSurvivants; }
  public function setNbDalles($nbDalles) { $this->nbDalles=$nbDalles; }
  public function setNbEquipmentCards($nbEquipmentCards) { $this->nbEquipmentCards=$nbEquipmentCards; }
  public function setNbInvasionCards($nbInvasionCards) { $this->nbInvasionCards=$nbInvasionCards; }
	*/
	/**
	 * Retourne les attributs de la classe
	 * @return array
	 */
	public function getClassVars() { return get_class_vars('Expansion'); }
	/**
	 * 
	 * @param array $row
	 * @param string $a
	 * @param string $b
	 */
	public static function convertElement($row, $a='', $b='') { return parent::convertElement(new Expansion(), self::getClassVars(), $row); }
	/**
	 * 
	 * @param array $row
	 */
	public static function convertElementFromPost($row) {
		$Obj = new Expansion();
		$vars = get_class_vars('Expansion');
		if ( !empty($vars) ) {
			foreach ( $vars as $key=>$value ) {
				$Obj->setField($key, $row[$key]);
			}
			if ( $row['officielle']=='on' ) { $Obj->setField('officielle', 1); }
			if ( $row['active']=='on' ) { $Obj->setField('active', 1); }
		}
		return $Obj;
	}

}
?>