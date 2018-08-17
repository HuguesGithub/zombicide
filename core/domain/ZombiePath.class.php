<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ZombiePath
 * @author Hugues.
 * @version 1.0.01
 * @since 1.0.01
 */
class ZombiePath extends LocalDomain
{
  /**
   * Liste des MissionZones de la Map
   * @var array $MissionZones
   */
  protected $MissionZones;
  /**
   * Noeud de départ du chemin
   * @var NodeZombiePath $LouderNodeZombiePath
   */
  protected $LouderNodeZombiePath;

  /**
   */
  public function __construct()
  {
    parent::__construct();
    $this->LiveServices = new LiveServices();
    
    $args = array(self::CST_DECKKEY=>$_SESSION[self::CST_DECKKEY]);
    $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
    $Live = array_shift($Lives);
    $LiveMission = $Live->getLiveMission();
    $Mission = $LiveMission->getMission();
    $this->MissionZones = $Mission->getMissionZones();
  }
  /**
   * Construit le chemin en partant de la Zone la plus bruyante
   */
  public function buildZombiePathToLouderZone()
  {
    // Faudrait définir la Zone la plus bruyante pour initialiser le Path.
    // Pour le moment, on va initialiser avec la 13 qu'on sait être la plus bruyante.
    $MissionZone = $this->getMissionZoneByNum(13);
    $this->LouderNodeZombiePath = new NodeZombiePath($MissionZone);
    $this->buildZombiePath($this->LouderNodeZombiePath, 0);
  }
  /**
   * @param int $num
   * @return MissionZone
   */
  public function getMissionZoneByNum($num)
  {
    foreach ($this->MissionZones as $MissionZone) {
      if ($MissionZone->getZoneNum()==$num) {
        return $MissionZone;
      }
    }
  }
  /**
   * @param NodeZombiePath $NodeZombiePath
   * @return string
   */
  public function displayZombiePath($NodeZombiePath=null)
  {
    if ($NodeZombiePath==null) {
      $NodeZombiePath = $this->LouderNodeZombiePath;
    }
    $str = $NodeZombiePath->displayNodeZombiePath();
    if ($NodeZombiePath->hasChildren()) {
      foreach ($NodeZombiePath->getChildren() as $Child) {
        $tmp = $this->displayZombiePath($Child);
        $str .= $tmp;
      }
    }
    return $str; 
  }
  /**
   * @param NodeZombiePath $NodeZombiePath
   * @param int $depth
   */
  public function buildZombiePath($NodeZombiePath, $depth=0)
  {
    $arrOrientation = array(1=>'N', 'E', 'S', 'W');
    // On récupère les ReachZones de la MissionZone du NodeZombiePath
    $reachZone = $NodeZombiePath->getMissionZone()->getReachZone();
    // On les splitte. Dans cet ordre : N, E, S, W.
    $arrReachZone = explode('-', $reachZone);
    $cpt = 0;
    foreach ($arrReachZone as $reachZone) {
      $cpt++;
      if ($reachZone=='.') {
        continue;
      }
      // On parcourt chaque reachZone, qu'on splitte au cas où il y en aurait plusieurs dans cette direction.
      $arrZones = explode(':', $reachZone);
      // Pour chaque numéro rencontré
      foreach ($arrZones as $num) {
        // S'il n'est pas présent dans l'arbre
        if($this->isNumInTree($num)===false) {
          $MissionZone = $this->getMissionZoneByNum($num);
          // On l'y ajoute
          $NodeZombiePath->addChild(new NodeZombiePath($MissionZone, $depth+1, $arrOrientation[$cpt]));
        }
      }
      // Pour l'instant, il sert à rien, mais c'est pour quand j'aurais besoin de stocker la direction entre deux Zones. TODO
    }
    // A ce niveau, on a peut-être attaché des enfants. Si c'est le cas, il faut aller les traiter pour trouver les petits-enfants.
    if ($NodeZombiePath->hasChildren()) {
      foreach ($NodeZombiePath->getChildren() as $Child) {
        $this->buildZombiePath($Child, $depth+1);
      }
    }
  }
  /**
   * Permet de définir si une Zone, identifiée par son numéro est déjà dans l'arbre.
   * @param int $num
   * @param NodeZombiePath $NodeZombiePath
   * @return int
   */
  public function isNumInTree($num, $NodeZombiePath='')
  {
    // Si on n'a pas passé de NodeZombiePath, on commence par la racine.
    if ($NodeZombiePath=='') {
      $NodeZombiePath = $this->LouderNodeZombiePath;
    }
    // Si $num correspond à la zoneNum de la MissionZone de NodeZombiePath, on retourne sa profondeur
    if ($NodeZombiePath->isNum($num)) {
      return $NodeZombiePath->getDepth();
    }
    // On part du principe qu'il n'y est pas.
    $isNumInTree = false;
    // On parcourt les enfants de NodeZombiePath
    if ($NodeZombiePath->hasChildren()) {
      // Pour chaque enfant, on lance de façon récursive la recherche.
      foreach ($NodeZombiePath->getChildren() as $Child) {
        $isNumInTree = $this->isNumInTree($num, $Child);
        // Si on le trouve, on retourne la profondeur
        if ($isNumInTree!==false) {
         return $isNumInTree;
        }
      }
    }
    return $isNumInTree;
  }
  
  /**
   * L'arbre des déplacements est construit. On le parcourt. Quand on trouve la cible, on renvoie le Père.
   * @param int $num
   * @param NodeZombiePath $NodeZombiePath
   * @param NodeZombiePath $Father
   * @return int|false
   */
  public function searchTreeForFather($num, $NodeZombiePath='', $Father='')
  {
    // Si on n'a pas passé de NodeZombiePath, on commence par la racine.
    if ($NodeZombiePath=='') {
      $NodeZombiePath = $this->LouderNodeZombiePath;
    }
    // Si $num correspond à la zoneNum de la MissionZone de NodeZombiePath, on retourne sa profondeur
    if ($NodeZombiePath->isNum($num)) {
      return $Father->getMissionZone()->getZoneNum();
    }
    // On parcourt les enfants de NodeZombiePath
    if ($NodeZombiePath->hasChildren()) {
      // Pour chaque enfant, on lance de façon récursive la recherche.
      foreach ($NodeZombiePath->getChildren() as $Child) {
        $fatherNum = $this->searchTreeForFather($num, $Child, $NodeZombiePath);
        // Si on le trouve, on retourne le num du Père
        if ($fatherNum!==false) {
         return $fatherNum;
        }
      }
    }
    return false;
  }
}
