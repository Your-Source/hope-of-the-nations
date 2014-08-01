<?php
/**
 * Main class file for Hope of the Nations.
 */
include_once __dir__ . '/hotnConnector.php';
include_once __dir__ . '/hotnForm.php';
include_once __dir__ . '/hotnSponsorChildInterface.php';
include_once __dir__ . '/hotnSponsorChild.php';

define('HOTN_MAX_ITEMS_PAGER', 2);

class hotn {
  private static $childs_count;
  private static $childs_count_total;
  private static $pagers_items;

  public function get_overview() {
    $childs = self::get_child_list($_GET, FALSE);
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

    // If function exists return the translated string.
    if (function_exists($translator_func)) {
      return call_user_func($translator_func, array($string));
    }

    return $string;
  }

  /**
   * Function to get the child list and sort if parameter is set.
   */
  private function get_child_list($parameter = array(), $all_items) {
    $childs = hotnConnector::get_feed('child');

    $child_output = array();
    foreach ($childs as $child) {
      $child_output[] = new hotnSponsorChild($child);
    }

    // Set total count of childeren.
    self::$childs_count_total = count($child_output);

    // Filter the value by parameters.
    foreach ($parameter as $key => $input_value) {
      // Unset the key if the value is empty of not contains hotn-.
      if (!empty($input_value) && strpos($key, 'hotn-') !== FALSE) {
        // Replace hotn- to nothing, set new parameter to array and delete old key.
        $new_key = str_replace('hotn-', '', $key);

        // Set filter on operator to false.
        $filter_on_operator = FALSE;

        // Create switch with the new key for get the function name.
        switch ($new_key) {
          case 'gender':
            $function_name = 'getChildGender';
            break;

          case 'country':
            $function_name = 'getChildCountry';
            break;

          case 'agegroup':
            $filter_on_operator = TRUE;
            break;
        }

        // Foreach on all childs to filter if a child does not comply.
        foreach ($child_output as $child_key => $child) {

          // If filter on operator is true filter on a operator in form the switch.
          if ($filter_on_operator) {
            $age = $child->getChildAge();

            switch ($input_value) {
              case '0':
                $operator = ($age < 3);
                break;
              case '1':
                $operator = in_array($age, range(3, 6));
                break;
              case '2':
                $operator = in_array($age, range(7, 9));
                break;
              case '3':
                $operator = ($age >= 10);
                break;
            }

            // Unset the child.
            if (!$operator) {
              unset($child_output[$child_key]);
            }
          }
          else {
            $value_to_filter = call_user_func(array($child, $function_name));
            // If the input value not is filter value unset the child.
            if ($input_value != $value_to_filter) {
              unset($child_output[$child_key]);
            }
          }
        }
      }
    }

    // Set count of childeren after filter.
    self::$childs_count = count($child_output);

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

    // Select items for the pager.
    $pager = !empty($parameter['hotnpager']) ? $parameter['hotnpager'] : 0;
    $start = HOTN_MAX_ITEMS_PAGER * $pager;
    $end = HOTN_MAX_ITEMS_PAGER * $pager + (HOTN_MAX_ITEMS_PAGER - 1);
    // Set this only if parameter is avaible.
    if (!$all_items) {
      self::$pagers_items = ceil(count($child_output) / HOTN_MAX_ITEMS_PAGER);
    }


    $child_output = array_values($child_output);
    foreach ($child_output as $key => $child) {
      if ($key < $start || $key > $end) {
        unset($child_output[$key]);
      }
    }

    return $child_output;
  }

  /**
   * Count all childeren after filter and return this.
   */
  private function get_child_count() {
    return self::$childs_count;
  }

  /**
   * Count all childeren bevore filter and return this.
   */
  private function get_child_count_total() {
    return self::$childs_count_total;
  }

  /**
   * Function to create array with filter criteria from children list.
   */
  private function get_child_filter($child_key) {
    // Get all childs.
    $childs = self::get_child_list(array(), TRUE);

    $output = array();
    foreach ($childs as $child) {
      // Create function name of method by sort key and call this function.
      $function_name = 'getChild' . $child_key;
      $value = call_user_func(array($child, $function_name));

      // If value is not in array output add it to the array.
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
    $output .= '</div>';

    $output .= '</div>';

    return $output;
  }

  /**
   * Theme function for child items on overview page.
   */
  private function theme_overview(array $childs, $count, $message = NULL) {
    // Set items for select input.
    $agegroup = array(
      0 => self::t('below 3'),
      1 => self::t('3 - 6'),
      2 => self::t('7 - 9'),
      3 => self::t('10 or above'),
    );
    $sort = array(
      'Name' => self::t('Name'),
      'Birthdate' => self::t('Age'),
      'Country' => self::t('Country'),
      'Gender' => self::t('Gender'),
    );

    $output = '<div id="hotn-overview">';
    $output .= '<h1 class="hotn-title">' . self::t('Child sponsorship') . '</h1>';

    $output .= '<div> ';
    $output .= '<form method="get" id="hotn-filter-form"> ';
    $output .= '<div class="field">';
    $output .= '<label>' . self::t('Age:') . '</label>';
    $output .= self::theme_select('hotn-agegroup', $agegroup);
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . self::t('Country:') . '</label>';
    $output .= self::theme_select('hotn-country', self::get_child_filter('Country'));
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . self::t('Gender:') . '</label>';
    $output .= self::theme_select('hotn-gender', self::get_child_filter('Gender'));
    $output .= '</div>';
    $output .= '<div class="field field-sort">';
    $output .= '<label>' . self::t('Sort:') . '</label>';
    $output .= self::theme_select('hotnsort', $sort, self::t('Sort'));
    $output .= '</div>';
    $output .= '<div class="links">';
    $output .= '<a href="#" class="link hotn-filter-form-reset">' . self::t('Reset') . '</a>';
    $output .= '</div>';
    $output .= '</form>';
    $output .= '</div>';

    $output .= '<div id="hotn-child-list">';

    if (!empty($count)) {
      $string = self::format_plural($count, self::t('child found'), self::t('children found'));

      $output .= '<div class="child-count">';
      $output .= $count . ' ' . $string;
      $output .= '</div>';
    }

    if (!empty($message)) {
      $output .= '<div class="child-message">';
      $output .= $message;
      $output .= '</div>';
    }

    $output .= '<div class="items">';
    foreach ($childs as $child) {
      $output .= $child;
    }
    $output .= '</div>';

    $output .= '<div id="hotn-pager">';
    $output .= self::theme_pager();
    $output .= '</div>';

    $output .= '</div>';

    $output .= '</div>';

    return $output;
  }

  /**
   * Theme function for select box.
   */
  private function theme_select($name, $items, $title = NULL) {
    $title = !empty($title) ? $title : self::t('Select');

    $output = '';

    $output .= '<select name="' . $name . '"> ';
    $output .= '<option value="">' . $title . '</option> ';

    foreach ($items as $key => $value) {
      $output .= '<option value="' . $key . '">' . $value . '</option> ';
    }

    $output .= '</select>';

    return $output;
  }

  /**
   * Theme function for creating the pager.
   */
  private function theme_pager() {
    // Get the current pager id. If empty fallback 0.
    $current_pager = !empty($_GET['hotnpager']) ? $_GET['hotnpager'] : 0;
    $pagers_items = self::$pagers_items;
    $pager = '';

    // If 1 page return nothing.
    if ($pagers_items <= 1) {
      return '';
    }

    for ($i=0; $i < $pagers_items; $i++) {
      $page = $i + 1;

      if ($current_pager == $i) {
        $pager .= '<span data-pager="' . $i . '" class="current-pager">' . $page . '</span> ';
      }
      else {
        $pager .= '<a href="#" data-pager="' . $i . '" class="pager">' . $page . '</a> ';
      }
    }

    return $pager;
  }

  /**
   * Return string by 1 item or lower else higher.
   */
  private function format_plural($count, $string1, $string2) {
    if ($count <= 1) {
      return $string1;
    }
    else {
      return $string2;
    }
  }
}
