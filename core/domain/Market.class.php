<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe Market
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Market extends LocalDomain {
	/**
	 * Id technique de la donnée
	 * @var int $id
	 */
	protected $id;
	/**
	 * Nom de l'objet
	 * @var string $name
	 */
	protected $name;
	/**
	 * Description de l'objet
	 * @var string $description
	 */
	protected $description;
	/**
	 * Nombre disponibles
	 * @var int $quantity
	 */
	protected $quantity;
	/**
	 * Prix de l'objet
	 * @var int $price
	 */
	protected $price;
	/**
	 * Url partielle de l'image du produit
	 * @var string $imgProduct
	 */
	protected $imgProduct;
	/**
	 * Univers auquel est rattaché le produit
	 * @var int $universId
	 */
	protected $universId;
	/**
	 * Langue du produit
	 * @var string $lang
	 */
	protected $lang;
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
	 * @return string
	 */
	public function getDescription() { return $this->description; }
	/**
	 * @return int
	 */
	public function getQuantity() { return $this->quantity; }
	/**
	 * @return int
	 */
	public function getPrice() { return $this->price; }
	/**
	 * @return string
	 */
	public function getImgProduct() { return $this->imgProduct; }
	/**
	 * @return int
	 */
	public function getUniversId() { return $this->universId; }
	/**
	 * @return string
	 */
	public function getLang() { return $this->lang; }
	/**
	 * @param int $id
	 */
	public function setId($id) { $this->id = $id; }
	/**
	 * @param string $name
	 */
	public function setName($name) { $this->name = $name; }
	/**
	 * @param string $description
	 */
	public function setDescription($description) { $this->description = $description; }
	/**
	 * @param int $quantity
	 */
	public function setQuantity($quantity) { $this->quantity = $quantity; }
	/**
	 * @param int $price
	 */
	public function setPrice($price) { $this->price = $price; }
	/**
	 * @param string $imgProduct
	 */
	public function setImgProduct($imgProduct) { $this->imgProduct = $imgProduct; }
	/**
	 * @param int $universId
	 */
	public function setUniversId($universId) { $this->universId = $universId; }
	/**
	 * @param string $lang
	 */
	public function setLang($lang) { $this->lang = $lang; }
	/**
	 * @return array
	 */
	public function getClassVars() { return get_class_vars('Market'); }
	/**
	 * @param array $row
	 * @param string $a
	 * @param string $b
	 * @return Market
	 */
	public static function convertElement($row, $a='', $b='') { return parent::convertElement(new Market(), self::getClassVars(), $row); }

	/**
	 * 
	 * @param unknown $WpPost
	 */
	public static function convertWpPost($WpPost) {
		$Market = new Market();
		print_r($WpPost);
		return $Market;
	}
}
?>