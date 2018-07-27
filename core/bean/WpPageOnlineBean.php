<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageOnlineBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WpPageOnlineBean extends WpPageBean
{
  /**
   * Class Constructor
   * @param WpPage $WpPage
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->EquipmentLiveDeckServices = new EquipmentLiveDeckServices();
    $this->LiveServices              = new LiveServices();
    $this->LiveMissionServices       = new LiveMissionServices();
    $this->LiveMissionTokenServices  = new LiveMissionTokenServices();
    $this->LiveSurvivorServices      = new LiveSurvivorServices();
    $this->LiveSurvivorSkillServices = new LiveSurvivorSkillServices();
    $this->LiveZombieServices        = new LiveZombieServices();
    $this->MissionServices           = new MissionServices();
    $this->MissionTokenServices      = new MissionTokenServices();
    $this->SpawnLiveDeckServices     = new SpawnLiveDeckServices();
    $this->SurvivorServices          = new SurvivorServices();
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPageContent($WpPage)
  {
    $Bean = new WpPageOnlineBean($WpPage);
    return $Bean->getContentPage();
  }
  public function getHeaderChatSaisie($label='Général', $liveId=0)
  { return '<li class="nav-item"><a class="nav-link active" href="#" data-liveid="'.$liveId.'">'.$label.'</a></li>'; }
  /**
   * @return string
   */
  public function getContentNotLogged()
  {
    $ts = date(self::CST_FORMATDATE, time());
    $strMsg  = '<li class="msg-technique" data-timestamp="'.$ts.'"><div><span class="timestamp">'.$ts;
    $strMsg .= '</span></div>Bienvenue sur cette interface. Inscrivez-vous et identifiez-vous pour rejoindre la discussion.</li>';
    $args = array(
      'Buttons',
      'Options',
      $strMsg,
      '',
      'hidden',
      $this->getHeaderChatSaisie(),
    );
    return $this->getPublicPageOnline($args);
  }
  /**
   * @return string
   */
  public function getContentLoggedNotLive()
  {
    $ts = date(self::CST_FORMATDATE, time());
    $args = array();
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/fragment-online-identification.php');
    $strCanvas = vsprintf($str, $args);
    $strMsg  = '<li class="msg-technique" data-timestamp="'.$ts.'"><div><span class="timestamp">'.$ts;
    $strMsg .= '</span></div>Bienvenue sur cette interface.</li>';
    $args = array(
      'Buttons',
      'Options',
      $strMsg,
      $strCanvas,
      '',
      $this->getHeaderChatSaisie(),
    );
    return $this->getPublicPageOnline($args);
  }
  /**
   * @return string
   */
  public function getContentLoggedAndLive()
  {
    $deckKey = $_SESSION[self::CST_DECKKEY];
    $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, array(self::CST_DECKKEY=>$deckKey));
    if (empty($Lives)) {
      unset($_SESSION[self::CST_DECKKEY]);
      return $this->getContentLoggedNotLive();
    }
    //$ts = date(self::CST_FORMATDATE, time());
    $Live = array_shift($Lives);
    $this->Live = $Live;
    $args = array(self::CST_LIVEID=>$Live->getId());
    $LiveMissions = $this->LiveMissionServices->getLiveMissionsWithFilters(__FILE__, __LINE__, $args);
    if (empty($LiveMissions)) {
      if (isset($_POST['createMission'])) {
        $missionId = $_POST[self::CST_MISSIONID];
        $Mission = $this->MissionServices->select(__FILE__, __LINE__, $missionId);
        if ($Mission->isLiveAble()) {
          $this->Mission = $Mission;
          $this->buildLiveMission();
          return $this->getContentSurvivors();
        }
      }
      $returned = $this->getMenuMissionSelection();
    } else {
      $LiveMission = array_shift($LiveMissions);
      $this->Mission = $LiveMission->getMission();
      // On doit choisir les Survivants joués. Sauf si c'est déjà fait.
      $returned = $this->getContentSurvivors();
    }
    return $returned;
  }
  private function buildLiveMission()
  {
    $liveId = $this->Live->getId();
    $missionId = $this->Mission->getId();
    // On doit créer un LiveMission
    $args = array(
      self::CST_LIVEID=>$liveId,
      self::CST_MISSIONID=>$missionId,
    );
    $LiveMission = new LiveMission($args);
    $this->LiveMissionServices->insert(__FILE__, __LINE__, $LiveMission);
    // On doit créer des Live_MissionToken
    $MissionTokens = $this->MissionTokenServices->getMissionTokensWithFilters(__FILE__, __LINE__, array(self::CST_MISSIONID=>$missionId));
    while (!empty($MissionTokens)) {
      $MissionToken = array_shift($MissionTokens);
      $arrLMT = array(self::CST_LIVEID=>$liveId, 'missionTokenId'=>$MissionToken->getId(), self::CST_STATUS=>$MissionToken->getStatus());
      $LiveMissionToken = new LiveMissionToken($arrLMT);
      $this->LiveMissionTokenServices->insert(__FILE__, __LINE__, $LiveMissionToken);
    }
    // On doit créer des Live_MissionZombies, si nécessaire.
    $LiveZombies = $this->MissionServices->getStartingZombies($this->Live, $this->Mission);
    while (!empty($LiveZombies)) {
      $LiveZombie = array_shift($LiveZombies);
      $this->LiveZombieServices->insert(__FILE__, __LINE__, $LiveZombie);
    }
    // On doit créer des EquipmentLive
    $EquipmentLiveDeck = new EquipmentLiveDeck(array(self::CST_LIVEID=>$liveId, self::CST_STATUS=>'P'));
    $arrEE = $this->MissionServices->getStartingEquipmentDeck($this->Mission);
    $this->EquipmentLiveDeckServices->createDeck($EquipmentLiveDeck, $arrEE);
    // On doit créer des EquipmentSpawn
    $SpawnLiveDeck = new SpawnLiveDeck(array(self::CST_LIVEID=>$liveId, self::CST_STATUS=>'P'));
    $arrNumbers = $this->MissionServices->getSpawnDeck($this->Mission);
    $this->SpawnLiveDeckServices->createDeck($SpawnLiveDeck, $arrNumbers);
  }
  private function getContentSurvivors()
  {
    $Live = $this->Live;
    $args = array(self::CST_LIVEID=>$Live->getId());
    // Si on a au moins un LiveSurvivor, on affiche la Map.
    $LiveSurvivors = $this->LiveSurvivorServices->getLiveSurvivorsWithFilters(__FILE__, __LINE__, $args);
    if (!empty($LiveSurvivors)) {
      return $this->getContentOnline();
    }
    // A-t-on saisi une sélection de Survivants à ajouter à la partie Live ?
    if (isset($_POST['createSurvivor'])) {
      $Mission = $this->Mission;
      $hasSurvivorSelection = false;
      $LiveSurvivors = array();
      $args['missionZoneId'] = $Mission->getStartingMissionZoneId();
      $args['survivorTypeId'] = 1;
      $args['experiencePoints'] = 0;
      $args['hitPoints'] = 2;
      $survivorIds = $_POST['survivorId'];
      // On parcourt la sélection
      while (!empty($survivorIds)) {
        $survivorId = array_shift($survivorIds);
        $Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $survivorId);
        // On conserve ceux sélectionnables.
        if ($Survivor->isLiveAble()) {
          $hasSurvivorSelection = true;
          $args['survivorId'] = $Survivor->getId();
          $LiveSurvivor = new LiveSurvivor($args);
          $this->LiveSurvivorServices->insert(__FILE__, __LINE__, $LiveSurvivor);
          // Une fois le LiveSurvivor créé, on doit créer les LiveSurvivorSkills.
          $argsLSS = array('liveSurvivorId'=>$LiveSurvivor->getId());
          $SurvivorSkills = $Survivor->getSurvivorSkills(1);
          while (!empty($SurvivorSkills)) {
            $SurvivorSkill = array_shift($SurvivorSkills);
            $argsLSS['skillId'] = $SurvivorSkill->getSkillId();
            $argsLSS['tagLevelId'] = $SurvivorSkill->getTagLevelId();
            $argsLSS['locked'] = ($SurvivorSkill->getTagLevelId()>=20?1:0);
            $LiveSurvivorSkill = new LiveSurvivorSkill($argsLSS);
            $this->LiveSurvivorSkillServices->insert(__FILE__, __LINE__, $LiveSurvivorSkill);
            if ($LiveSurvivorSkill->isStartsWith()) {
              // Si cette compétence est une compétence qui permet au Survivant de commencer avec un équipement, on le gère.
              $Skill = $LiveSurvivorSkill->getSkill();
              $EquipmentExpansions = EquipmentExpansion::getFromStartingSkill($Skill);
              $LiveSurvivor->removeStartingEquipmentFromDeckAndEquip($Live, $EquipmentExpansions);
            }
          }
          array_push($LiveSurvivors, $LiveSurvivor);
        }
      }
      $Mission->addStandardStartingEquipment($Live, $LiveSurvivors);
      // Si on en a au moins un de sélectionnable, la phase de préparation de la partie est finie, on affiche la Map.
      if ($hasSurvivorSelection) {
        return $this->getContentOnline();
      }
    }
    
    // Bon, bein, on veut afficher la liste des Survivants pouvant être joués...
    $Survivors = $this->SurvivorServices->getSurvivorsWithFilters(__FILE__, __LINE__, array('liveAble'=>1));
    $strDivs = '';
    while (!empty($Survivors)) {
      $Survivor = array_shift($Survivors);
      $strDivs .= '<div type="button" class="btn btn-dark btn-survivor" data-survivor-id="'.$Survivor->getId().'">';
      $strDivs .= '<input type="checkbox" name="survivorId[]" class="hidden" value="'.$Survivor->getId().'"/>';
      $strDivs .= '<i class="far fa-square"></i></span> '.$Survivor->getName().'</div>';
    }
    $args = array(
      $strDivs,
      $this->debugMsg,
    );
    $strSelection = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/online-survivor-selection.php');
    $strMsg = vsprintf($strSelection, $args);

    $args = array(
      'Buttons',
      'Options',
      '',
      $strMsg,
      '',
      $this->getHeaderChatSaisie($Live->getDeckKey(), $Live->getId()),
    );
    return $this->getPublicPageOnline($args);
  }
  public function getActionButtons($Live='')
  {
    // On part du Live poru récupérer la LiveMission puis le LiveSurvivor actif.
    if ($Live=='') {
      $Live = $this->Live;
    }
    $LiveMissions = $this->LiveMissionServices->getLiveMissionsWithFilters(__FILE__, __LINE__, array(self::CST_LIVEID=>$Live->getId()));
    $LiveMission = array_shift($LiveMissions);
    $LiveSurvivor = $this->LiveSurvivorServices->select(__FILE__, __LINE__, $LiveMission->getActiveLiveSurvivorId());
    if ($LiveSurvivor->getId()=='') {
      // Si on n'a pas de LiveSurvivor actif, on les récupère tous et on affiche les boutons en grisant ceux ayant déjà joué.
      $LiveSurvivors = $this->LiveSurvivorServices->getLiveSurvivorsWithFilters(__FILE__, __LINE__, array(self::CST_LIVEID=>$Live->getId()));
      while (!empty($LiveSurvivors)) {
        $LiveSurvivor = array_shift($LiveSurvivors);
        $returned = $LiveSurvivor->getBean()->getPortraitButton();
      }
    } else {
      // On affiche les Boutons associés aux Actions disponibles pour le Survivant.
      // TODO : On doit aussi trouver une façon de stocker les Actions disponibles...
      $returned = $LiveSurvivor->getBean()->getActionsButton();
    }
    return $returned;
  }
  private function getContentOnline()
  {
    $Live = $this->Live;
    $LiveSurvivors = $this->LiveSurvivorServices->getLiveSurvivorsWithFilters(__FILE__, __LINE__, array(self::CST_LIVEID=>$Live->getId()));
    $sideBar = '';
    while (!empty($LiveSurvivors)) {
      $LiveSurvivor = array_shift($LiveSurvivors);
      $Bean = $LiveSurvivor->getBean();
      $sideBar .= $Bean->getSideBarContent();
    }
    $args = array(
      $this->getActionButtons(),
      $sideBar,
      '',
      'TOTO',
      '',
      $this->getHeaderChatSaisie($Live->getDeckKey(), $Live->getId()),
    );
    return $this->getPublicPageOnline($args);
  }
  private function getMenuMissionSelection()
  {
    $Live = $this->Live;
    $arrFilters = array(
      self::CST_LIVEABLE=>1,
    );
    $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, $arrFilters);
    $strDivs = '';
    while (!empty($Missions)) {
      $Mission = array_shift($Missions);
      $strDivs .= '<div type="button" class="btn btn-dark btn-mission" data-mission-id="'.$Mission->getId().'">';
      $strDivs .= '<input type="radio" name="missionId" class="hidden" value="'.$Mission->getId().'"/>';
      $strDivs .= '<i class="far fa-square"></i></span> '.$Mission->getTitle().'</div>';
    }
    $args = array(
      $strDivs,
    );
    $strSelection = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/online-mission-selection.php');
    $strMsg = vsprintf($strSelection, $args);
    $args = array(
      'Buttons',
      'Options',
      '',
      $strMsg,
      '',
      $this->getHeaderChatSaisie($Live->getDeckKey(), $Live->getId()),
    );
    return $this->getPublicPageOnline($args);
  }
  public function getPublicPageOnline($args)
  { return vsprintf(file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-online.php'), $args); }
  /**
   * {@inheritDoc}
   * @see PagePageBean::getContentPage()
   */
  public function getContentPage()
  {
    if (isset($_POST[self::CST_KEYACCESS])) {
      $args = array(self::CST_DECKKEY=>$_POST[self::CST_KEYACCESS]);
      $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
      $Live = array_shift($Lives);
      if (empty($Live)) {
        $args['dateUpdate'] = date(self::CST_FORMATDATE);
        $Live = new Live($args);
        $this->LiveServices->insert(__FILE__, __LINE__, $Live);
        $Live->setId(MySQL::getLastInsertId());
      }
      $_SESSION[self::CST_DECKKEY] = $_POST[self::CST_KEYACCESS];
    }
    if (isset($_SESSION[self::CST_DECKKEY])) {
      return $this->getContentLoggedAndLive();
    } elseif (is_user_logged_in()) {
      return $this->getContentLoggedNotLive();
    } else {
      return $this->getContentNotLogged();
    }
  }
  
  
}
