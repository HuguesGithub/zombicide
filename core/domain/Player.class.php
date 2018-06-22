<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe Player
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Player extends LocalDomain {
	/**
	 * Id technique de la donnée
	 * @var int $id
	 */
	protected $id;
	/**
	 * nom du niveau de Difficulté
	 * @var string $name
	 */
	protected $name;
	/**
	 * @param array $attributes
	 */
	public function __construct($attributes=array()) {
		parent::__construct($attributes);
	}
	/**
	 * @return int
	 */
	public function getId() { return $this->id; }
	/**
	 * @return string
	 */
	public function getName() { return $this->name; }
	/**
	 * @param int $id
	 */
	public function setId($id) { $this->id = $id; }
	/**
	 * @param string $name
	 */
	public function setName($name) { $this->name = $name; }
	/**
	 * @return array
	 */
	public function getClassVars() { return get_class_vars('Player'); }
	/**
	 * @param array $row
	 * @param string $a
	 * @param string $b
	 * @return Player
	 */
	public static function convertElement($row, $a='', $b='') { return parent::convertElement(new Player(), self::getClassVars(), $row); }
	/**
	 * @return string
	 */
	public function getNbJoueurs() {
		$pos = strpos($this->name, '+');
		if ( $pos!==FALSE ) { return substr($this->name, 0, $pos).' Survivants et +'; }
		$pos = strpos($this->name, '-');
		if ( $pos!==FALSE ) {
			list($min, $max) = explode('-', $this->name);
			return $min.' à '.$max.' Survivants';
		}
		return $this->name.' Survivants';
	}
}
?>