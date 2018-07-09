<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionBean extends MainPageBean
{
  /**
   * Template pour afficher une Mission
   * @var $tplMissionExtract
   */
  public static $tplMissionExtract  = 'web/pages/public/fragments/article-mission-extract.php';

  public function __construct($Mission='')
  {
    $services = array('Expansion', 'Mission', 'Objective', 'Rule', 'Tile');
    parent::__construct($services);
    if ($Mission=='') {
      $Mission = new Mission();
    }
    $this->Mission = $Mission;
    $this->tplRow = 'web/pages/admin/mission/row.php';
    $this->tplEdit = 'web/pages/admin/mission/edit.php';
  }
  /**
   * @return string
   */
  public function getRowForAdminPage()
  {
    $Mission = $this->Mission;
    $queryArgs = array('onglet'=>'mission', self::CST_POSTACTION=>'edit', 'id'=>$Mission->getId());
    $hrefEdit = $this->getQueryArg($queryArgs);
    $queryArgs[self::CST_POSTACTION] = 'trash';
    $hrefTrash = $this->getQueryArg($queryArgs);
    $queryArgs[self::CST_POSTACTION] = 'clone';
    $hrefClone = $this->getQueryArg($queryArgs);
    $urlWpPost = $Mission->getWpPostUrl();
    $args = array(
      // Identifiant de la Mission
      $Mission->getId(),
      // Code de la Mission
      $Mission->getCode(),
      // Url d'édition
      $hrefEdit,
      // Titre de la Mission
      $Mission->getTitle(),
      // Url de suppression
      $hrefTrash,
      // Url de Duplication
      $hrefClone,
      // Article publié ?
      $urlWpPost!='#' ? '' : ' hidden',
      // Url Article
      $urlWpPost,
        $Mission->getStrDifficulty(),
        $Mission->getStrDuree(),
        $Mission->getStrNbJoueurs(),
        $Mission->getStrOrigine(),
        $Mission->getStrTiles(),
        $Mission->getStrRules(),
        $Mission->getStrObjectives(),
        $Mission->getStrExpansions(),
  );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/fragments/fragment-row-mission.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function getRowForMissionsPage()
  {
    $Mission = $this->Mission;
    $urlWpPost = $Mission->getWpPostUrl();
    $args = array(
      $urlWpPost,
      $urlWpPost=='#' ? 'disabled' : '',
      $Mission->getCode(),
      $Mission->getTitle(),
      $Mission->getStrDifficulty(),
      $Mission->getStrDuree(),
      $Mission->getStrNbJoueurs(),
      $Mission->getStrExpansions(),
      $Mission->getStrOrigine(),
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/fragment-row-mission.php');
    return vsprintf($str, $args);
  }
  /**
   * @param array $post
   * @return string
   */
  public static function staticBuildBlockTiles($post)
  {
    $action = $post['dealAction'];
    $rkCol = ($action==self::CST_RMVCOL ? $post['rkCol'] : 0);
    $rkRow = ($action==self::CST_RMVROW ? $post['rkRow'] : 0);
    $missionId = $post['missionId'];
    $MissionServices = new MissionServices();
    $MissionTileServices = new MissionTileServices();
    $Mission = $MissionServices->select(__FILE__, __LINE__, $missionId);
    $Bean = new MissionBean($Mission);
    switch ($action) {
      case self::CST_RMVROW :
        $MissionTiles = $Mission->getMissionTiles();
        if (!empty($MissionTiles)) {
          foreach ($MissionTiles as $MissionTile) {
            if ($MissionTile->getCoordY()==$rkRow) {
              $MissionTileServices->delete(__FILE__, __LINE__, $MissionTile);
            } elseif ($MissionTile->getCoordY()>$rkRow) {
              $MissionTile->setCoordY($MissionTile->getCoordY()-1);
              $MissionTileServices->update(__FILE__, __LINE__, $MissionTile);
            }
          }
        }
        $Mission->setHeight($Mission->getHeight()-1);
      break;
      case self::CST_RMVCOL  :
        $MissionTiles = $Mission->getMissionTiles();
        if (!empty($MissionTiles)) {
          foreach ($MissionTiles as $MissionTile) {
            if ($MissionTile->getCoordX()==$rkCol) {
              $MissionTileServices->delete(__FILE__, __LINE__, $MissionTile);
            } elseif ($MissionTile->getCoordX()>$rkCol) {
              $MissionTile->setCoordX($MissionTile->getCoordX()-1);
              $MissionTileServices->update(__FILE__, __LINE__, $MissionTile);
            }
          }
        }
        $Mission->setWidth($Mission->getWidth()-1);
      break;
      case 'addRow' :
        $Mission->setHeight($Mission->getHeight()+1);
      break;
      case 'addCol'  :
        $Mission->setWidth($Mission->getWidth()+1);
      break;
      default : break;
    }
    $MissionServices->update(__FILE__, __LINE__, $Mission);
     return '{"mapEditor":'.json_encode($Bean->buildBlockTiles($Mission)).'}';
  }
  /**
   * @return string
   */
  public function buildBlockTiles()
  {
    $Mission = $this->Mission;
    $width = $Mission->getWidth();
    $height = $Mission->getHeight();
    $disabledButton = '<button type="button" class="btn btn-secondary" disabled></button>';
    $openDivTile = '<div class="col tile%2$s" data-rkcol="%1$s">';
    $closeDivTile = '</div>';
    $colBreaker = '<div class="w-100"></div>';
    $addButton = '<button type="button" class="btn btn-info" data-action="%1$s">+</button>';
    $rmvButton = '<button type="button" class="btn btn-info" data-action="%1$s" data-%2$s="%3$s">-</button>';
    $firstRow  = vsprintf($openDivTile, array(0, ' firstRow')).$disabledButton.$closeDivTile;
    $lastRow  =  $colBreaker.'<div class="col tile prependBefore firstRow" data-rkcol="0">'.sprintf($addButton, 'addRow').$closeDivTile;
    $innerRows = array();
    for ($i=0; $i<$height; $i++) {
      $innerRows[$i]  = $colBreaker.vsprintf($openDivTile, array(0, ''));
      $innerRows[$i] .= vsprintf($rmvButton, array(self::CST_RMVROW, 'row', $i+1)).$closeDivTile;
    }
    for ($i=1; $i<=$width; $i++) {
      $firstRow  .= vsprintf($openDivTile, array($i, ' firstRow')).vsprintf($rmvButton, array(self::CST_RMVCOL, 'col', $i)).$closeDivTile;
      $lastRow  .= vsprintf($openDivTile, array($i, ' firstRow')).$disabledButton.$closeDivTile;
    }
    $classe = 'custom-select custom-select-sm filters';
    $arrOrientations = array('N'=>'north', 'E'=>'east', 'S'=>'south', 'O'=>'west');
    for ($i=0; $i<$height; $i++) {
      for ($j=1; $j<=$width; $j++) {
        $name = 'tile_'.$j.'_'.($i+1).'-';
        $orientation = $Mission->getTileOrientation($j, $i+1);
        switch ($orientation) {
          case 'N' :
            $classImg = ' north';
          break;
          case 'E' :
            $classImg = ' east';
          break;
          case 'S' :
            $classImg = ' south';
          break;
          case 'O' :
            $classImg = ' west';
          break;
          default : break;
        }
        $tileCode = $Mission->getTileCode($j, $i+1);
        $innerRows[$i] .= vsprintf($openDivTile, array($j, ''));
        $innerRows[$i] .= '<img class="thumbTile'.$classImg.'" src="/wp-content/plugins/zombicide/web/rsc/images/tiles/';
        $innerRows[$i] .= $Mission->getTileCode($j, $i+1).'-500px.png" alt="'.$tileCode.'">';
        $innerRows[$i] .= $this->TileServices->getTilesSelect(__FILE__, __LINE__, $tileCode, $name, $classe, false, '--');
        foreach ($arrOrientations as $key => $value) {
          $innerRows[$i] .= '<button type="button" class="rdv '.$value.($orientation==$key ? ' active' : '');
          $innerRows[$i] .= '" data-action="'.$key.'" data-col="'.$j.'" data-row="'.($i+1).'"></button>';
        }
        $innerRows[$i] .= $closeDivTile;
      }
      $innerRows[$i] .= vsprintf($openDivTile, array($width+1, '')).$disabledButton.$closeDivTile;
    }
    $firstRow .= vsprintf($openDivTile, array($width+1, ' firstRow')).sprintf($addButton, 'addCol').$closeDivTile;
    $lastRow .= vsprintf($openDivTile, array($width+1, ' firstRow')).$disabledButton.$closeDivTile;
    $returned = '<div class="row tileRow" data-width="'.$Mission->getWidth().'" data-height="';
    return $returned.$Mission->getHeight().'">'.$firstRow.implode('', $innerRows).$lastRow.'</div>';
  }
  private function getMissionObjAndRuleGenericBlock($Objs, $none, $type, $select)
  {
    $Mission = $this->Mission;
    $str = '';
    if (empty($Objs)) {
      $str .= '<li>'.$none.'</li>';
    } else {
      foreach ($Objs as $id => $Obj) {
        $str .= '<li class="showTooltip"><span class="tooltip"><header>'.$Obj->getTitle();
        $str .= ' <button class="btn btn-xs btn-danger float-right" data-type="'.$type;
        $str .= '" data-id="'.$id.'"><i class="fas fa-times-circle"></i></button></header><content>';
        $str .= $Obj->getDescription().'</content></span></li>';
      }
    }
    $str .= '<li class="showTooltip"><span class="tooltip"><header><div class="input-group"><input type="text" id="'.$type;
    $str .= '-title" name="'.$type.'-title" class="form-control"><div class="input-group-append">';
    $str .= '<button class="btn btn-success float-right" data-type="'.$type;
    $str .= '" data-missionid="'.$Mission->getId().'"><i class="fas fa-plus-circle"></i></button></div></div></header><content>';
    $str .= $select;
    return $str.'<textarea id="'.$type.'-description" name="'.$type.'-description" class="form-control"></textarea></content></span></li>';
  }
  /**
   * @return string
   */
  public function getMissionRulesBlock()
  {
    $this->MissionRules = $this->Mission->getMissionRules();
    $displayMissionRules = array();
    if (!empty($this->MissionRules)) {
      foreach ($this->MissionRules as $MissionRule) {
        $Rule = $MissionRule->getRule();
        if ($Rule->getSetting()==1) {
          continue;
        }
        $displayMissionRules[$MissionRule->getId()] = $MissionRule;
      }
    }
    $none = '<li>Aucune règle spéciale</li>';
    $type = 'rule';
    $select = $this->RuleServices->getRuleNoSettingSelect(__FILE__, __LINE__, '', 'id', 'custom-select custom-select-sm filters');
    return $this->getMissionObjAndRuleGenericBlock($displayMissionRules, $none, $type, $select);
  }
  /**
   * @return string
   */
  public function getMissionSettingsBlock()
  {
    if (!empty($this->MissionRules)) {
      foreach ($this->MissionRules as $MissionRule) {
        $Rule = $MissionRule->getRule();
        if ($Rule->getSetting()==0) {
          continue;
        }
        $displayMissionRules[$MissionRule->getId()] = $MissionRule;
      }
    }
    $none = '<li>Aucune mise en place particulière</li>';
    $type = 'setting';
    $select = $this->RuleServices->getRuleSettingSelect(__FILE__, __LINE__, '', 'id', 'custom-select custom-select-sm filters');
    return $this->getMissionObjAndRuleGenericBlock($displayMissionRules, $none, $type, $select);
  }
  /**
   * @return string
   */
  public function getMissionObjectivesBlock()
  {
    $this->MissionObjectives = $this->Mission->getMissionObjectives();
    if (!empty($this->MissionObjectives)) {
      foreach ($this->MissionObjectives as $MissionObjective) {
        $displayMissionObjectives[$MissionObjective->getId()] = $MissionObjective;
      }
    }
    $none = '<li>Aucun objectif</li>';
    $type = 'objective';
    $select = $this->ObjectiveServices->getObjectiveSelect(__FILE__, __LINE__, '', 'id', 'custom-select custom-select-sm filters');
    return $this->getMissionObjAndRuleGenericBlock($displayMissionObjectives, $none, $type, $select);
  }
  /**
   * @return string
   */
  public function displayCanvas()
  {
    $Mission = $this->Mission;
    $strCanvas = '<canvas id="canvas-background" width="'.($Mission->getWidth()*500).'" height="'.($Mission->getHeight()*500).'"></canvas>';
    $strCanvas .= '<script src="/wp-content/plugins/zombicide/web/rsc/jcanvas.min.js"></script>';
    $strCanvas .= '<script>';
    $strCanvas .= "var srcImg ='/wp-content/plugins/zombicide/web/rsc/images/missions/".$Mission->getCode().".jpg';\r\n";
    $strCanvas .= "var xStart ='".($Mission->getWidth()*250)."';\r\n";
    $strCanvas .= "var yStart ='".($Mission->getHeight()*250)."';\r\n";
    return $strCanvas.'</script>';
  }
  
  
}
