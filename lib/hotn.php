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
      $message = self::t('There are no children available.');

      return self::theme_overview(array(), '', $message);
    }
    $child_output = array();
    foreach ($childs as $child) {

      $child_output[] = self::theme_overview_child($child);
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
    $childs = hotnConnector::get_feed('child', $parameter);

    $child_output = array();
    foreach ($childs as $child) {
      $child_output[] = new hotnSponsorChild($child);
    }

    // If not empty sort sort the array.
    if (!empty($parameter['hotnsort']) && $sort = $parameter['hotnsort']) {
      usort($child_output, function ($a, $b) use ($sort) {
        if ($a == $b) {
          return FALSE;
        }

        // Create function name of method by sort key and call this function.
        $function_name = 'getChild' . $sort;
        $a_val = call_user_func(array($a, $function_name));
        $b_val = call_user_func(array($b, $function_name));

        // If sort is birthday set string to timestamp.
        if ($sort == 'Birthdate') {
          $a_val = strtotime($a_val);
          $b_val = strtotime($b_val);
        }

        return ($a_val < $b_val) ? -1 : 1;
      });
    }

    return $child_output;
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
      // Create function name of method by sort key and call this function.
      $function_name = 'getChild' . $child_key;
      $value = call_user_func(array($child, $function_name));

      //$value = $child[$child_key];

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
    $detail_url = $_SERVER['REQUEST_URI'] . '?hotnChildID=' . $child->getChildId();

    $output = '';

    $output .= '<div class="item child-overview">';

    $output .= '<div class="image">';
    $output .= '<img src="' . $child->getChildSmallImage() . '" title="' . $child->getChildName() . '">';
    $output .= '</div>';

    $output .= '<div class="info">';
    $output .= '<span class="name">' . $child->getChildName() . '</span>';
    $output .= '<br />';
    $output .= '<span class="country">' . $child->getChildCountry() . '</span>';
    $output .= '<br />';
    $output .= '<span class="birthdate">' . $child->getChildBirthdate() . '</span>';
    $output .= '<br />';
    $output .= '<span class="more-info"><a href="' . $detail_url . '">More info</a></span>';
    $output .= '<br />';

    $output .= '</div>';

    return $output;
  }

  /**
   * Theme function for child items on overview page.
   */
  private function theme_overview(array $childs, $count, $message = NULL) {
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
    $output .= '  <option value="Birthdate">' . self::t('Age') . '</option> ';
    $output .= '  <option value="Country">' . self::t('Country') . '</option> ';
    $output .= '  <option value="Gender">' . self::t('Gender') . '</option> ';
    $output .= '</select>';
    $output .= '<span class="link hotn-filter-form-reset">' . self::t('Reset') . '</span>';
    $output .= '</form>';
    $output .= '</div>';

    $output .= '<div id="hotn-child-list">';

    if (!empty($count)) {
      $output .= '<div class="child-count">';
      $output .= $count . ' ' . self::t('found children');
      $output .= '</div>';
    }

    if (!empty($message)) {
      $output .= '<div class="child-message">';
      $output .= $message;
      $output .= '</div>';
    }

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
