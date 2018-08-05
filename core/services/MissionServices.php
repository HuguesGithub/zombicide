<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionServices
 * @since 1.0.00
 * @version 1.0.01
 * @author Hugues
 */
class MissionServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var MissionDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new MissionDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, ($this->isNonEmptyAndNoArray($arrFilters, self::CST_LEVELID) ? $arrFilters[self::CST_LEVELID] : '%'));
    if ($arrFilters[self::CST_DURATIONID]!='' && !is_array($arrFilters[self::CST_DURATIONID])) {
      array_push($arrParams, $arrFilters[self::CST_DURATIONID]);
    } else {
      array_push($arrParams, '%');
    }
    array_push($arrParams, ($this->isNonEmptyAndNoArray($arrFilters, self::CST_PLAYERID) ? $arrFilters[self::CST_PLAYERID] : '%'));
    array_push($arrParams, ($this->isNonEmptyAndNoArray($arrFilters, self::CST_ORIGINEID) ? $arrFilters[self::CST_ORIGINEID] : '%'));
    $bPublished = isset($arrFilters[self::CST_PUBLISHED]) && !is_array($arrFilters[self::CST_PUBLISHED]);
    array_push($arrParams, $bPublished ? $arrFilters[self::CST_PUBLISHED] : '%');
    $bLiveAble = isset($arrFilters[self::CST_LIVEABLE]) && !is_array($arrFilters[self::CST_LIVEABLE]);
    array_push($arrParams, $bLiveAble ? $arrFilters[self::CST_LIVEABLE] : '%');
    return $arrParams;
  }
  /**
   * @param string $file
   * @param string $line
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getMissionsWithFilters($file, $line, $arrFilters=array(), $orderby='title', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  /**
   * @param string $file
   * @param string $line
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getMissionsWithFiltersIn($file, $line, $arrFilters=array(), $orderby='title', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFiltersIn($file, $line, $arrParams, $arrFilters);
  }
  /**
   * @param string $file
   * @param string $line
   * @param string $value
   * @param string $prefix
   * @return string
   */
  public function getDifficultySelect($file, $line, $value='', $prefix='')
  {
    $arrDifficulties = array('TUTO'=>'Tutoriel', 'EASY'=>'Facile', 'MED'=>'Moyenne', 'HARD'=>'Difficile',
      'VHARD'=>'Très Difficile', 'PVP'=>'Compétitive', 'BLUE'=>'Bleue', 'YELLOW'=>'Jaune', 'ORANGE'=>'Orange', 'RED'=>'Rouge');
    $arrSetValues = $this->getSetValues($file, $line, 'difficulty', false);
    $arrSetLabels = array();
    foreach ($arrSetValues as $setValue) {
      $arrSetLabels[$setValue] = $arrDifficulties[$setValue];
    }
    $this->labelDefault = 'Difficultés';
    return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'difficulty', $value);
  }
  /**
   * @param string $file
   * @param string $line
   * @param string $field
   * @param string $isSet
   * @return array
   */
  public function getSetValues($file, $line, $field, $isSet=true)
  { return $this->Dao->getSetValues($file, $line, $field, $isSet); }
  /**
   * @param string $file
   * @param string $line
   * @param string $value
   * @param string $prefix
   * @return string
   */
  public function getNbPlayersSelect($file, $line, $value='', $prefix='')
  {
    $arrSetValues = $this->getSetValues($file, $line, 'nbPlayers', false);
    $arrSetLabels = array();
    foreach ($arrSetValues as $setValue) {
      if (strpos($setValue, '+')!==false) {
        $arrSetLabels[$setValue] = $setValue[0].' Survivants et +';
      } else {
        list($min, $max) = explode('-', $setValue);
        $arrSetLabels[$setValue] = $min.' à '.$max.' Survivants';
      }
    }
    $this->labelDefault = 'Survivants';
    return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'nbPlayers', $value);
  }
  /**
   * @param string $file
   * @param string $line
   * @param string $field
   * @return array
   */
  public function getDistinctValues($file, $line, $field)
  { return $this->Dao->getDistinctValues($file, $line, $field); }
  /**
   * @param string $file
   * @param string $line
   * @param string $value
   * @param string $prefix
   * @return string
   */
  public function getDimensionsSelect($file, $line, $value='', $prefix='')
  {
    $arrParams = $this->buildOrderAndLimit(array('width', 'height'), array('ASC', 'ASC'));
    $arrSetValues = $this->Dao->selectDistinctDimensions($file, $line, $arrParams);
    $arrSetLabels = array();
    foreach ($arrSetValues as $setValue) {
      $arrSetLabels[$setValue->label] = $setValue->label;
    }
    $this->labelDefault = 'Dimensions';
    return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'dimension', $value);
  }
  /**
   * @param string $file
   * @param string $line
   * @param string $value
   * @param string $prefix
   * @return string
   */
  public function getDurationSelect($file, $line, $value='', $prefix='')
  {
    $arrSetValues = $this->getDistinctValues($file, $line, 'duration');
    $arrSetLabels = array();
    foreach ($arrSetValues as $setValue) {
      $arrSetLabels[$setValue] = $setValue.' minutes';
    }
    $this->labelDefault = 'Durées';
    return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'duration', $value);
  }
  /**
   * @param int $width
   * @return string
   */
  public function getWidthSelect($width)
  {
    $widthSelect  = '<select name="width">';
    $widthSelect .= '<option value="0">0</option>';
    for ($i=1; $i<=6; $i++) {
      $widthSelect .= '<option value="'.$i.'"'.($width==$i?' selected="selected"':'').'>'.$i.'</option>';
    }
    return $widthSelect.'</select>';
  }
  /**
   * @param int $height
   */
  public function getHeightSelect($height)
  {
    $heightSelect  = '<select name="height">';
    $heightSelect .= '<option value="0">0</option>';
    for ($i=1; $i<=6; $i++) {
      $heightSelect .= '<option value="'.$i.'"'.($height==$i?' selected="selected"':'').'>'.$i.'</option>';
    }
    return $heightSelect.'</select>';
  }
  private function addLiveZombie(&$LiveZombies, $Live, $missionZoneId, $zombieTypeId, $zombieCategoryId, $quantity)
  {
    $args = array(
      'liveId'=>$Live->getId(),
      'missionZoneId'=>$missionZoneId,
      'zombieTypeId'=>$zombieTypeId,
      'zombieCategoryId'=>$zombieCategoryId,
      'quantity'=>$quantity,
    );
    array_push($LiveZombies, new LiveZombie($args));
  }
  public function getStartingZombies($Live, $Mission)
  {
    $LiveZombies = array();
    if ($Mission->hasRule(11)) {
      switch ($Mission->getId()) {
        case 1 :
          $this->addLiveZombie($LiveZombies, $Live, 4, 1, 1, 1);
          $this->addLiveZombie($LiveZombies, $Live, 12, 1, 1, 1);
        break;
        case 8 :
          $arrIds = array(1, 2, 3, 4, 6, 7, 8, 16, 17, 18, 19, 21, 22, 23, 24);
          while (!empty($arrIds)) {
            $id = array_shift($arrIds);
            $this->addLiveZombie($LiveZombies, $Live, $id, 1, 1, 1);
          }
        break;
        default :
          // Une Mission a des Zombies à mettre en place...
        break;
      }
    }
    return $LiveZombies;
  }
  
  public function getStartingEquipmentDeck($Mission)
  {
    $arrEE = array();
    // On récupère les Extensions rattachées à la Mission.
    $MissionExpansions = $Mission->getMissionExpansions();
    while (!empty($MissionExpansions)) {
      $MissionExpansion = array_shift($MissionExpansions);
      // On récupère les Equipements rattachés aux Extensions
      $EquipmentExpansions = $MissionExpansion->getEquipmentExpansions();
      while (!empty($EquipmentExpansions)) {
        $EquipmentExpansion = array_shift($EquipmentExpansions);
        $EquipmentCard = $EquipmentExpansion->getEquipment();
        // On ne doit pas prendre les cartes suivantes :
        // Starter / Pimp / TODO : gérer les cartes comme le Molotov, la Batte Cloutée...
        if ($EquipmentCard->isStarter() || $EquipmentCard->isPimp()) {
          continue;
        }
        // On ajoute autant de fois la carte que requis.
        for ($i=0; $i<$EquipmentExpansion->getQuantity(); $i++) {
          array_push($arrEE, $EquipmentExpansion->getId());
        }
      }
    }
    shuffle($arrEE);
    // Certaines règles peuvent demander un traitement spécifique pour certaines cartes.
    if ($Mission->hasRule(2)) {
      // On rajoute le Pistolet, le Pied-de-biche et la Hache Starters en début de pioche.
      $arrAdd = array(13, 23, 25);
      shuffle($arrAdd);
      $arrEE = array_merge($arrAdd, $arrEE);
    }
    return $arrEE;
  }
  
  public function getSpawnDeck($Mission)
  {
    // Certaines règles peuvent demander un traitement spécifique pour certaines cartes.
    if ($Mission->hasRule(1)) {
      // On ne joue qu'avec les cartes #1, #2, #3, #4 et #41.
      $arrNumbers = array(1, 2, 3, 4, 41);
      shuffle($arrNumbers);
    }
    return $arrNumbers;
  }
  
}
