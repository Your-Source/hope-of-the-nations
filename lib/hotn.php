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
    $childs = self::get_child_list($_GET);
    $count = self::get_child_count($_GET);

    if (empty($childs)) {
      return self::t('There are no children available.');
    }
    $child_output = array();
    foreach ($childs as $child) {
      $c = new hotnSponsorChild($child);

      $child_output[] = self::theme_overview_child($c);
    }

    return self::theme_overview($child_output, $count);
  }

  /**
   * Translate function for text.
   */
  public function t($string) {
    // Get the translate function name of config.
    $translator_func = hotnConfig::$translator_func;

    // If function exists return with translated string.
    if (function_exists($translator_func)) {
      return call_user_func($translator_func, array($string));
    }

    return $string;
  }

  /**
   * Function to get the child list and sort if parameter is set.
   */
  private function get_child_list($parameter = array()) {
    $list = hotnConnector::get_feed('child', $parameter);

    // If not empty sort sort the array.
    if (!empty($parameter['hotnsort']) && $sort = $parameter['hotnsort']) {
      usort($list, function ($a, $b) use ($sort) {
        if ($a == $b) {
          return 0;
        }
        return ($a[$sort] < $b[$sort]) ? -1 : 1;
      });
    }

    return $list;
  }

  /**
   * Count all childeren by parameter and return this.
   */
  private function get_child_count($parameter = array()) {
    $list = hotnConnector::get_feed('child', $parameter);

    $count = count($list);

    return $count;
  }


  /**
   * Function to create array with filter criteria from children list.
   */
  private function get_child_filter($child_key) {
    // Get all childs.
    $childs = self::get_child_list();

    $output = array();
    foreach ($childs as $child) {
      $value = $child[$child_key];

      if(!in_array($value, $output)){
        $output[$value] = $value;
      }
    }
    asort($output);

    return $output;
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
    $output .= '<span class="birthdate">' . $child->getChildGender() . '</span>';

    $output .= '</div>';

    return $output;
  }

  /**
   * Theme function for child items on overview page.
   */
  private function theme_overview(array $childs, $count) {
    $output = '<div>';

    $output .= '<div> ';
    $output .= '<form method="get" id="hotn-filter-form"> ';
    $output .= '<label>' . self::t('Age:') . '</label>';
    $output .= '<select name="hotn-agegroup"> ';
    $output .= '  <option value="">' . self::t('Select') . '</option>';
    $output .= '  <option value="0">' . self::t('below 3') . '</option> ';
    $output .= '  <option value="1">' . self::t('3 - 6') . '</option> ';
    $output .= '  <option value="2">' . self::t('7 - 9') . '</option> ';
    $output .= '  <option value="3">' . self::t('10 or above') . '</option> ';
    $output .= '</select>';
    $output .= '<label>' . self::t('Country:') . '</label>';
    $output .= self::theme_select('hotn-country', self::get_child_filter('Country'));
    $output .= '<label>' . self::t('Gender:') . '</label>';
    $output .= self::theme_select('hotn-gender', self::get_child_filter('Gender'));
    $output .= '<label>' . self::t('Sort:') . '</label>';
    $output .= '<select name="hotnsort"> ';
    $output .= '  <option value="">' . self::t('Sort') . '</option>';
    $output .= '  <option value="Name">' . self::t('Name') . '</option> ';
    $output .= '  <option value="age">' . self::t('Age') . '</option> ';
    $output .= '  <option value="Country">' . self::t('Country') . '</option> ';
    $output .= '  <option value="Gender">' . self::t('Gender') . '</option> ';
    $output .= '</select>';
    $output .= '<span class="link hotn-filter-form-reset">' . self::t('Reset') . '</span>';
    $output .= '</form>';
    $output .= '</div>';

    $output .= '<div id="hotn-child-list">';

    $output .= '<div class="child-count">';
    $output .= $count . ' ' . self::t('found children');
    $output .= '</div>';

    foreach ($childs as $child) {
      $output .= $child;
    }

    $output .= '</div>';

    $output .= '</div>';

    return $output;
  }

  /**
   * Theme function for select box.
   */
  private function theme_select($name, $items) {
    $output = '';

    $output .= '<select name="' . $name . '"> ';
    $output .= '<option value="">' . self::t('Select') . '</option> ';

    foreach ($items as $key => $value) {
      $output .= '<option value="' . $key . '">' . $value . '</option> ';
    }

    $output .= '</select>';

    return $output;
  }
}
