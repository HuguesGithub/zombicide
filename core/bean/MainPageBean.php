<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe MainPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MainPageBean {
	/**
	 * Template pour afficher le header principal
	 * @var $tplMainHeaderContent
	 */
	public static $tplMainHeaderContent	= 'web/pages/public/public-main-header.php';
	/**
	 * Template pour afficher le footer principal
	 * @var $tplMainFooterContent
	 */
	public static $tplMainFooterContent	= 'web/pages/public/public-main-footer.php';
	/**
	 * Option pour cacher le Header et le footer.
	 * @var $showHeaderAndFooter
	 */
	public $showHeaderAndFooter	= true;
  /**
   * La classe du shell pour montrer plus ou moins le haut de l'image de fond.
   * @var $shellClass
   */
	protected $shellClass;

	/**
	 * @param array $services
	 */
	public function __construct($services=array()) {
		if ( !empty($services) ) {
			foreach ( $services as $service ) {
				switch ( $service ) {
					case 'Duration'			: $this->DurationServices = FactoryServices::getDurationServices(); break;
					case 'Equipment'				: $this->EquipmentServices = FactoryServices::getEquipmentServices(); break;
					case 'EquipmentExpansion'				: $this->EquipmentExpansionServices = FactoryServices::getEquipmentExpansionServices(); break;
					case 'EquipmentWeaponProfile'	: $this->EquipmentWeaponProfileServices = FactoryServices::getEquipmentWeaponProfileServices(); break;
					case 'Expansion' 				: $this->ExpansionServices = FactoryServices::getExpansionServices(); break;
					case 'Invasion' 				: $this->InvasionServices = FactoryServices::getInvasionServices(); break;
					case 'Level'				: $this->LevelServices = FactoryServices::getLevelServices(); break;
					case 'Live' 					: $this->LiveServices = FactoryServices::getLiveServices(); break;
					case 'LiveDeck' 				: $this->LiveDeckServices = FactoryServices::getLiveDeckServices(); break;
					case 'Market'				: $this->MarketServices = FactoryServices::getMarketServices(); break;
					case 'Mission'					: $this->MissionServices = FactoryServices::getMissionServices(); break;
					case 'MissionExpansion'			: $this->MissionExpansionServices = FactoryServices::getMissionExpansionServices(); break;
					case 'MissionLive'				: $this->MissionLiveServices = FactoryServices::getMissionLiveServices(); break;
					case 'MissionObjective'		: $this->MissionObjectiveServices = FactoryServices::getMissionObjectiveServices(); break;
					case 'MissionRule'				: $this->MissionRuleServices = FactoryServices::getMissionRuleServices(); break;
					case 'MissionTile'				: $this->MissionTileServices = FactoryServices::getMissionTileServices(); break;
					case 'MissionToken'				: $this->MissionTokenServices = FactoryServices::getMissionTokenServices(); break;
					case 'MissionZone'				: $this->MissionZoneServices = FactoryServices::getMissionZoneServices(); break;
					case 'Objective'					: $this->ObjectiveServices = FactoryServices::getObjectiveServices(); break;
					case 'Origine'			: $this->OrigineServices = FactoryServices::getOrigineServices(); break;
					case 'Player'			: $this->PlayerServices = FactoryServices::getPlayerServices(); break;
					case 'Rule'						: $this->RuleServices = FactoryServices::getRuleServices(); break;
					case 'Skill'					: $this->SkillServices = FactoryServices::getSkillServices(); break;
					case 'Spawn' 				: $this->SpawnServices = FactoryServices::getSpawnServices(); break;
					case 'SpawnLiveDeck' 				: $this->SpawnLiveDeckServices = FactoryServices::getSpawnLiveDeckServices(); break;
					case 'Survivor'					: $this->SurvivorServices = FactoryServices::getSurvivorServices(); break;
					case 'Tile'						: $this->TileServices = FactoryServices::getTileServices(); break;
					case 'Token'					: $this->TokenServices = FactoryServices::getTokenServices(); break;
					case 'WeaponProfile'			: $this->WeaponProfileServices = FactoryServices::getWeaponProfileServices(); break;
					case 'Zone'						: $this->ZoneServices = FactoryServices::getZoneServices(); break;
					default							: echo "[[Ajouter $service dans MainPageBean.php]]<br>"; break;
				}
			}
		}
		$this->WpPostServices = GlobalFactoryServices::getWpPostServices();
	}
	/**
	 * @return string
	 */
	public function displayPublicFooter() {
		$args = array(admin_url('admin-ajax.php'));
		$str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-main-footer.php');
		return vsprintf($str, $args);
	}
	/**
	 * @return string
	 */
	public function displayPublicHeader() {
		if ( $this->showHeaderAndFooter ) {
			$arrMenuDisplay  = array();
			$children = $this->WpPostServices->getChildPagesByParentId(1022);
			if ( !empty($children) ) {
				foreach ( $children as $WpPost ) {
					$cpt = 0;
					$strMenuDisplay = '<span>'.$WpPost->getPostTitle().'</span><ul>';
					$grandChildren = $this->WpPostServices->getChildPagesByParentId($WpPost->getID());
					if ( !empty($grandChildren) ) {
						foreach ( $grandChildren as $WpPost ) {
							if ( $WpPost->getPostMeta('selected') ) {
								$strMenuDisplay .= '<li><a href="'.$WpPost->getGuid().'">'.$WpPost->getPostTitle().'</a></li>';
								$cpt++;
							}
						}
					}
					$strMenuDisplay .= '</ul>';
					if ( $cpt > 0 ) {
						$arrMenuDisplay[] = $strMenuDisplay;
					}
				}
			}
			$strPages  = '<a href="http://zombicide.jhugues.fr"><span>Accueil</span></a>';
			$strPages .= '<a href="http://zombicide.jhugues.fr/page-competences/"><span>Compétences</span></a>';
			$strPages .= '<a href="http://zombicide.jhugues.fr/page-missions/"><span>Missions</span></a>';
			$strPages .= '<a href="http://zombicide.jhugues.fr/page-survivants/"><span>Survivants</span></a>';
			$strPages .= '<span class="hasDropDown">';
      $strPages .= '<a href="#"><span>Outils</span></a>';
      $strPages .= '<ul>';
      $strPages .= '<li><a href="http://zombicide.jhugues.fr/page-piste-de-des/"><span>Piste de dés</span></a></li>';
      $strPages .= '<li><a href="http://zombicide.jhugues.fr/page-spawncards/"><span>Cartes Invasion</span></a></li>';
      $strPages .= '<li><a href="http://zombicide.jhugues.fr/page-selection-survivants/"><span>Génération équipe</span></a></li>';
      $strPages .= '</ul>';
      $strPages .= '</span>';
			$args = array(
					$arrMenuDisplay[0],
					$arrMenuDisplay[1],
					$strPages
			);
		} else {
			$args = array('', '', '');
		}
		$str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-main-header.php');
		return vsprintf($str, $args);
	}
	/**
	 * @return Bean
	 */
	public static function getPageBean() {
		if ( is_front_page() ) {
			return new HomePageBean();
		} else {
			$post = get_post();
			if ( empty($post) ) {
				// On a un probl�me (ou pas). On pourrait �tre sur une page avec des variables, mais qui n'est pas prise en compte.
				$slug = str_replace('/', '', $_SERVER['REDIRECT_SCRIPT_URL']);
				$args = array(
						'name'=>$slug,
						'post_type'=>'page',
						'numberposts'=>1
				);
				$my_posts = get_posts($args);
				$post = array_shift($my_posts);
			}
			if ( $post->post_type == 'page' ) {
				return new PagePageBean($post);
			} elseif ( $post->post_type == 'post' ) {
				return new PostPageBean($post);
			} else {
				return new Error404PageBean();
			}
		}
	}
	/**
	 * @param array $addArg
	 * @param array $remArg
	 * @return string
	 */
	public function getQueryArg($addArg, $remArg=array()) {
		$addArg['page'] = 'zombicide/admin_zombicide.php';
/*	
		$this->dealWithQueryArg('filter-dimension', $addArg, $remArg);
		$this->dealWithQueryArg('filter-difficulty', $addArg, $remArg);
		$this->dealWithQueryArg('filter-nbPlayers', $addArg, $remArg);
		$this->dealWithQueryArg('filter-duration', $addArg, $remArg);
		$this->dealWithQueryArg('filter-origineId', $addArg, $remArg);
		$this->dealWithQueryArg('filter-expansionId', $addArg, $remArg);
		$this->dealWithQueryArg('filter-type', $addArg, $remArg);
*/	
		$remArg[] = 'form';
		$remArg[] = 'id';
		return add_query_arg($addArg, remove_query_arg($remArg, 'http://zombicide.jhugues.fr/wp-admin/admin.php'));
	}
	/**
	 * @return bool
	 */
	public static function isAdmin() { return current_user_can('manage_options'); }
	/**
	 * @return string
	 */
	public function getShellClass() { return $this->shellClass; }
	/**
	 * @param string $id
	 * @param string $default
	 * @return mixed
	 */
	public function initVar($id, $default='') {
		if ( isset($_POST[$id]) ) { return $_POST[$id]; }
		if ( isset($_GET[$id]) ) { return $_GET[$id]; }
		return $default;
	}
	
	
	/*
	


	/**
	 * 
	 * @param array $addArg
	 * @param array $remArg
	 *
  public function dealWithQueryArg($id, &$addArg, &$remArg) {
    if ( isset($this->urlParams[$id]) && !empty($this->urlParams[$id]) ) { $addArg[$id] = $this->urlParams[$id]; } else { $remArg[] = $id; }
  }
  */
}
?>
