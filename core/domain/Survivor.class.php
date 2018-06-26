<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Survivor
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Survivor extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Nom de la donnée
   * @var string $name
   */
  protected $name;
  /**
   * A un profil Zombivor
   * @var int $zombivor
   */
  protected $zombivor;
  /**
   * A un profil Ultimate
   * @var int $ultimate
   */
  protected $ultimate;
  /**
   * Id de l'extension
   * @var int $expansionId
   */
  protected $expansionId;
  /**
   * Background du Survivant
   * @var string $background
   */
  protected $background;
  /**
   * Eventuelle image alternative
   * @var string $altImgName
   */
  protected $altImgName;
  
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    $services = array('Expansion', 'Skill', 'SurvivorSkill');
    parent::__construct($attributes, $services);
  }

  /**
   * @return int
   */
  public function getId()
  {return $this->id; }
  /**
   * @return string
   */
  public function getName()
  { return $this->name; }
  /**
   * @return boolean
   */
  public function isZombivor()
  { return ($this->zombivor==1); }
  /**
   * @return boolean
   */
  public function isUltimate()
  { return ($this->ultimate==1); }
  /**
   * @return int
   */
  public function getExpansionId()
  { return $this->expansionId; }
  /**
   * @return string
   */
  public function getBackground()
  { return $this->background; }
  /**
   * @return string
   */
  public function getAltImgName()
  { return $this->altImgName; }
  /**
   * @param int $id
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param string $name
   */
  public function setName($name)
  { $this->name=$name; }
  /**
   * @param int $zombivor
   */
  public function setZombivor($zombivor)
  { $this->zombivor=$zombivor; }
  /**
   * @param int $ultimate
   */
  public function setUltimate($ultimate)
  { $this->ultimate=$ultimate; }
  /**
   * @param int $expansionId
   */
  public function setExpansionId($expansionId)
  { $this->expansionId=$expansionId; }
  /**
   * @param string $background
   */
  public function setBackground($background)
  { $this->background=$background; }
  /**
   * @param string $altImgName
   */
  public function setAltImgName($altImgName)
  { $this->altImgName=$altImgName; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('Survivor'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Survivor
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Survivor(), self::getClassVars(), $row); }
  /**
   * @return string
   */
  public function getExpansionName()
  { return $this->getExpansion()->getName(); }
  /**
   * @param string $str
   * @return string
   */
  public function getNiceName($str)
  { return str_replace(array(' ', '#'), '', strtolower($str)); }
  /**
   * @param string $type
   * @return string
   */
  public function getPortraitUrl($type='')
  {
    $nicename = $this->getNiceName($this->name);
    $baseUrl = '/wp-content/plugins/zombicide/web/rsc/images/portraits/p';
    return $baseUrl.$nicename.($type!=''?'-'.$type:'').'.jpg';
  }
  /**
   * @param bool $isHome
   * @return string
   */
  public function getStrClassFilters($isHome)
  { return ' col-12 col-sm-6 col-md-4'; }
  /**
   * @param string $type
   * @return string
   */
  public function getUlSkills($type='')
  {
    $SurvivorSkills = $this->getSurvivorSkills();
    $str = '';
    $strTmp = '';
    if (!empty($SurvivorSkills)) {
      foreach ($SurvivorSkills as $SurvivorSkill) {
        if ($type=='' && $SurvivorSkill->getSurvivorTypeId()!=1) {
          continue;
        }
        if ($type=='z' && $SurvivorSkill->getSurvivorTypeId()!=2) {
          continue;
        }
        if ($type=='u' && $SurvivorSkill->getSurvivorTypeId()!=3) {
          continue;
        }
        if ($type=='uz' && $SurvivorSkill->getSurvivorTypeId()!=4) {
          continue;
        }
        switch ($SurvivorSkill->getTagLevelId()) {
          case 10 :
          case 11 :
            $strTmp .= '<li><span class="badge badge-blue-skill">'.$SurvivorSkill->getSkillName().'</span></li>';
          break;
          case 20 :
            $str .= '<ul class="">'.$strTmp.'</ul>';
            $strTmp = '';
            $strTmp .= '<li><span class="badge badge-yellow-skill">'.$SurvivorSkill->getSkillName().'</span></li>';
          break;
          case 30 :
            $str .= '<ul class="">'.$strTmp.'</ul>';
            $strTmp = '';
          case 31 :
            $strTmp .= '<li><span class="badge badge-orange-skill">'.$SurvivorSkill->getSkillName().'</span></li>';
          break;
          case 40 :
            $str .= '<ul class="">'.$strTmp.'</ul>';
            $strTmp = '';
          case 41 :
          case 42 :
            $strTmp .= '<li><span class="badge badge-red-skill">'.$SurvivorSkill->getSkillName().'</span></li>';
          break;
          default : break;
        }
      }
      $str .= '<ul class="">'.$strTmp.'</ul>';
    }
    return $str;
  }
}
