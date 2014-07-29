<?php
/**
 * Main class file for Hope of the Nations.
 */
include_once __dir__ . '/hotnConnector.php';
include_once __dir__ . '/hotnForm.php';
include_once __dir__ . '/hotnSponsorChildInterface.php';
include_once __dir__ . '/hotnSponsorChild.php';

class hotn {

  public function get_overview() {
    $childs = hotnConnector::get_feed('child', $_GET);

    if (empty($childs)) {
      return self::t('There are no children available.');
    }
    $child_output = array();
    foreach ($childs as $child) {
      $c = new hotnSponsorChild($child);

      $child_output[] = self::theme_overview_child($c);
    }

    return self::theme_overview($child_output);
  }

  public function t($string) {
    $translator_func = hotnConfig::$translator_func;

    if (function_exists($translator_func)) {
      return call_user_func($translator_func, array($string));
    }

    return $string;
  }

  /**
   * Theme function for child items on overview page.
   */
  private function theme_overview_child(hotnSponsorChild $child) {
    $output = '';

    $output .= '<div class="item child-overview">';

    $output .= '<div class="image">';
    $output .= ' ';
    $output .= '</div>';

    $output .= '<div class="info">';
    $output .= '<span class="name">' . $child->getChildName() . '</span>';
    $output .= '<br />';
    $output .= '<span class="country">' . $child->getChildCountry() . '</span>';
    $output .= '<br />';
    $output .= '<span class="birthdate">' . $child->getChildBirthdate() . '</span>';
    $output .= '<br />';
    $output .= '<span class="more-info"><a href="#">More info</a></span>';
    $output .= '<br />';

    $output .= '</div>';

    return $output;
  }

  /**
   * Theme function for child items on overview page.
   */
  private function theme_overview(array $childs) {
    $output = '<div>';

    $output .= '<form method="get"> ';
    $output .= '<select name="hotn-gender"> ';
    $output .= '  <option value="">Selecteer</option> ';
    $output .= '  <option value="Jongen">Jongen</option> ';
    $output .= '  <option value="Meisje">Meisje</option> ';
    $output .= '</select>';
    $output .= '<select name="hotn-agegroup"> ';
    $output .= '  <option value="">Selecteer</option> ';
    $output .= '  <option value="0">below 3</option> ';
    $output .= '  <option value="1">3 - 6</option> ';
    $output .= '  <option value="2">7 - 9</option> ';
    $output .= '  <option value="3">10 or above</option> ';
    $output .= '</select>';
    $output .= '<input type="submit" value="Submit">';
    $output .= '</form>';

    $output .= '<div class="child-list">';

    foreach ($childs as $child) {
      $output .= $child;
    }

    $output .= '</div>';

    $output .= '</div>';

    return $output;
  }
}
