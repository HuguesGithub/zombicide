<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LocalServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LocalServices extends GlobalServices implements ConstantsInterface
{
  /**
   * Local variable to store labelDefault
   * @var string $labelDefault
   */
  protected $labelDefault = '';
  /**
   * Local variable to store style class
   * @var string $classe
   */
  protected $classe = 'form-control';
  /**
   * Local variable to store if select are multiple or not
   * @var boolean $multiple
   */
  protected $multiple = false;

  /**
   */
  public function __construct()
  {
  }
  
  /**
   * @param string $file
   * @param string $line
   * @param array $arrSetLabels
   * @param string $name
   * @param string $value
   */
  protected function getSetSelect($file, $line, $arrSetLabels, $name, $value)
  {
    $strSelect = '';
    $selName = $name;
    if ($this->labelDefault!='') {
      $strSelect .= '<label class="screen-reader-text" for="'.$name.'">'.$this->labelDefault.'</label>';
    }
    $strSelect .= '<select id="'.$name.'" name="'.$selName.'" class="'.$this->classe.'"'.($this->multiple?' multiple':'').'>';
    if (!$this->multiple && $this->labelDefault!='') {
      $strSelect .= '<option value="">'.$this->labelDefault.'</option>';
    }
    if (!empty($arrSetLabels)) {
      foreach ($arrSetLabels as $key => $labelValue) {
        if ($key=='') {
          continue;
        }
        $strSelect .= '<option value="'.$key.'"';
        $strSelect .= ($this->isKeySelected($key, $value) ? ' selected="selected"' : '');
        $strSelect .= '>'.$labelValue.'</option>';
      }
    }
    return $strSelect.'</select>';
  }
  private function isKeySelected($key, $values)
  {
    if (!is_array($values)) {
      return $key==$values;
    }
    $isSelected = false;
    while (!empty($values)) {
      $value = array_shift($values);
      if ($key==$value) {
        $isSelected = true;
      }
    }
    return $isSelected;
  }
  /**
   * Vérifie qu'un élément du tableau n'est ni vide ni un tableau.
   * @param array $arrFilters
   * @param string $tag
   * @return boolean
   */
  protected function isNonEmptyAndNoArray($arrFilters, $tag)
  { return !empty($arrFilters[$tag]) && !is_array($arrFilters[$tag]); }
  
}
