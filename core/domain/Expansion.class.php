<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
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