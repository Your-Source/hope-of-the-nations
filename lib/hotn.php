<?php
/**
 * Main class file for Hope of the Nations.
 */
include_once __DIR__ . '/hotnConnector.php';
include_once __DIR__ . '/hotnForm.php';
include_once __DIR__ . '/hotnSponsorChildInterface.php';
include_once __DIR__ . '/hotnSponsorChild.php';

define('HOTN_MAX_ITEMS_PAGER', 12);

class hotn {
  private static $children_count_filtered;
  private static $children_count_total;
  private static $pagers_items;

  /**
   * Function for showing a list with children and showing the child ID page.
   * @return string Returns string with all content.
   */
  public static function load() {
    // If child ID is set as parameter the current page is a child detail page.
    if (!empty($_GET['hotnChildID'])) {
      $child_detail = self::get_child($_GET['hotnChildID']);
      $form = hotnForm::form(self::get_child_list(array('hotn-Id' => $_GET['hotnChildID'])));

      $output = $child_detail . $form;
      return $output;
    }

    return self::get_overview();
  }

  /**
   * Function to create children overview page.
   * @return string complete page with children overview.
   */
  public static function get_overview() {
    $children = self::get_child_list($_GET, FALSE);
    $count = self::children_count_filtered();

    if (empty($children)) {
      $message = self::hotn_t('There are no children available.');

      return self::hotn_theme_overview(array(), '', $message);
    }
    $child_output = array();
    foreach ($children as $child) {

      $child_output[] = self::hotn_theme_overview_child($child);
    }

    return self::hotn_theme_overview($child_output, $count);
  }

  /**
   * Function to create child detail page.
   * @param  int $childid The ID of the child.
   * @return string Returns the markup of child detail page.
   */
  public static function get_child($childid) {
    $children = self::get_child_list(array('hotn-Id' => $childid));

    if (empty($children)) {
      return self::hotn_t('This child is not available.');
    }

    return self::hotn_theme_detail_child($children[0]);
  }

  /**
   * Translate function for text.
   * @param  string $string string to translate by external translate function.
   * @return string translated string.
   */
  public static function hotn_t($string, $parameters = array()) {
    // Get the translate function name from the configuration file.
    $translator_func = hotnConfig::$translator_func;

    // If no translation function is specified use this default function.
    if (!empty($translator_func)) {
      // If function exists return the translated string.
      if (function_exists($translator_func)) {
        $string = call_user_func($translator_func, $string);
      }
    }
    else {
      $custom_translate_lang = hotnConfig::$custom_translate_lang;
      // If not English set the translations.
      if ($custom_translate_lang != 'en') {
        include __DIR__ . '/translation/' . $custom_translate_lang . '.php';

        // If string is in variable return the translated string.
        if (array_key_exists($string, ${'hotn_translation_' . $custom_translate_lang})) {
          $string = ${'hotn_translation_' . $custom_translate_lang}[$string];
        }
      }
    }

    $string = strtr($string, $parameters);

    return $string;
  }

  /**
   * Function to get the child list and sort if parameter is set.
   * @param  array   $parameters All parameters to filter and sort the children
   * @param  boolean $all_items  Get all items and exclude the sort and filtering.
   * @return array With all children in their own object.
   */
  private static function get_child_list($parameters = array(), $all_items = FALSE) {
    $children = hotnConnector::get_feed('child');
    $operator = NULL;
    $child_output = array();
    $function_name = '';

    foreach ($children as $child) {
      $child_output[] = new hotnSponsorChild($child);
    }

    // Set total count of children.
    self::$children_count_total = count($child_output);

    // The parameters will be prefixed by 'hotn-' to
    // prevent name space issues with existing CMS.
    foreach ($parameters as $key => $input_value) {
      // Unset the key if the value is empty of
      // the key doesn't contain hotn- at the beginning.
      if (!empty($input_value) && strpos($key, 'hotn-') !== FALSE) {
        // Replace hotn- to nothing because the new key is a standard of the REST API.
        $new_key = str_replace('hotn-', '', $key);

        // Set filter on age as default to FALSE.
        $filter_on_age = FALSE;

        // Switch for the function name by a filter key.
        switch ($new_key) {
          case 'gender':
            $function_name = 'getChildGender';
            break;

          case 'country':
            $function_name = 'getChildCountry';
            break;

          case 'agegroup':
            $filter_on_age = TRUE;
            break;
          default:
            // If new is not empty filter on all keys.
            if (!empty($new_key)) {
              $function_name = 'getChild' . $new_key;
            }
        }

        // Iterate over children and evaluate operator.
        foreach ($child_output as $child_key => $child) {

          // If filter on age is TRUE filter on the value added through the form.
          if ($filter_on_age) {
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

            // Unset the child that does not match any value.
            if (!$operator) {
              unset($child_output[$child_key]);
            }
          }
          else {
            $value_to_filter = call_user_func(array($child, $function_name));

            if ($input_value != $value_to_filter) {
              unset($child_output[$child_key]);
            }
          }
        }
      }
    }

    // Set count of children after filtering.
    self::$children_count_filtered = count($child_output);

    // If not empty sort the array by the chosen filter, default by API sorting order.
    if (!empty($parameters['hotnsort'])) {
      $sort = $parameters['hotnsort'];
      usort($child_output, function ($a, $b) use ($sort) {
        if ($a == $b) {
          return FALSE;
        }

        // Create function name of method by sorting key and calling this function.
        $function_name = 'getChild' . $sort;
        $a_val = call_user_func(array($a, $function_name));
        $b_val = call_user_func(array($b, $function_name));

        // If sort is birthday set string to timestamp for correct sort.
        if ($sort == 'Birthdate') {
          $a_val = strtotime($a_val);
          $b_val = strtotime($b_val);
        }

        return ($a_val < $b_val) ? -1 : 1;
      });
    }

    // Select items for the pager.
    $pager = !empty($parameters['hotnpager']) ? $parameters['hotnpager'] : 0;
    $start = HOTN_MAX_ITEMS_PAGER * $pager;
    $end = HOTN_MAX_ITEMS_PAGER * $pager + (HOTN_MAX_ITEMS_PAGER - 1);
    // Set this only if parameter is available.
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
   * Count all children after filter and return this.
   * @return int With count of all children.
   */
  private static function children_count_filtered() {
    return self::$children_count_filtered;
  }

  /**
   * Count all children before filter and return this.
   * @return int With count of all children.
   */
  private static function get_children_count_total() {
    return self::$children_count_total;
  }

  /**
   * Function to create array with filter criteria from children list.
   * @param (string) $child_key
   *  E.g. the country or gender method name.
   * @return array
   */
  private static function get_child_filter($child_key) {
    // Get all children.
    $children = self::get_child_list(array(), TRUE);

    $output = array();
    foreach ($children as $child) {
      // Create function name of method by sort key and call this function.
      $function_name = 'getChild' . $child_key;
      $value = call_user_func(array($child, $function_name));

      // If value is not in array output, add it to the array.
      if(!in_array($value, $output)){
        $output[$value] = $value;
      }
    }
    asort($output);

    return $output;
  }

  /**
   * Render markup for child on overview page.
   * @param  hotnSponsorChild $child Child object
   * @return string Display child item on overview.
   */
  private static function hotn_theme_overview_child(hotnSponsorChild $child) {
    $uri = strtok($_SERVER["REQUEST_URI"], '?');

    $parameters = array();
    // Add all URL parameters from a CMS also to the detail URL for a correct
    // URL routing. If the key not hotn add to to the parameters array.
    foreach ($_GET as $key => $value) {
      if (strpos($key, 'hotn') === FALSE && !empty($value)) {
        $parameters[$key] = $value;
      }
    }

    // Implode the parameters array to string with as glue & for a valid URL parameter.
    $url_param = '';
    if (!empty($parameters)) {

      foreach($parameters as $key => $parameter) {
        $url_param .= '&' . $key . '=' . $parameter;
      }
    }

    $detail_url = $uri . '?hotnChildID=' . $child->getChildId() . $url_param;

    $output = '<div class="item child-overview">';

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
    $output .= '<span class="more-info"><a href="' . $detail_url . '">' . self::hotn_t('More info') . '</a></span>';
    $output .= '</div>';

    $output .= '</div>';

    return $output;
  }

  /**
   * Render markup for child detail page.
   * @param  hotnSponsorChild $child Instance of child.
   * @return string Returns markup for child detail page.
   */
  private static function hotn_theme_detail_child(hotnSponsorChild $child) {
    $info_placeholders = array(
      '@name' => $child->getChildName(),
      '@birthdate' => $child->getChildBirthdate(),
      '@country' => $child->getChildCountry(),
    );
    $info_string = self::hotn_t('@name is born on @birthdate and lives in @country', $info_placeholders);

    $story_title = self::hotn_t('My name is @name', $info_placeholders);

    $base_url = !empty(hotnConfig::$base_url) ? hotnConfig::$base_url : $_SERVER['SERVER_NAME'];

    $request_uri = $_SERVER['REQUEST_URI'];
    $request_uri = substr($request_uri, 1);
    $url = $base_url . '/' . $request_uri;
    $url_html = urlencode($url);

    $title = self::hotn_t('Sponsor a child');
    $title_html = urlencode($title);

    $output = '<div id="hotn-child-detail">';
    $output .= '<h1 class="hotn-title">' . $title . '</h1>';

    $output .= '<div class="image">';
    $output .= '<img src="' . $child->getChildLargeImage() . '" title="' . $child->getChildName() . '">';
    $output .= '</div>';

    $output .= '<div class="information">';
    $output .= $info_string;
    $output .= '</div>';

    $output .= '<div class="share">';

    $output .= '<span class="facebook"><a href="http://www.facebook.com/sharer.php?u=' . $url_html . '" target="_blank" class="facebook external" title="Facebook">Facebook</a></span> ';
    $output .= '<span class="twitter"><a href="https://twitter.com/intent/tweet?text=' . $url_html . '" target="_blank" class="twitter external" title="Twitter">Twitter</a></span> ';
    $output .= '<span class="linkedin"><a href="http://www.linkedin.com/shareArticle?mini=1&amp;url=' . $url_html . '" target="_blank" class="linkedin external" title="LinkedIn">LinkedIn</a></span> ';
    $output .= '<span class="blogger"><a href="https://www.blogger.com/blog-this.g?u=' . $url_html . '&n=' . $title_html . '" target="_blank" class="blogger external" title="Blogger">Blogger</a></span> ';
    $output .= '<span class="googleplus"><a href="https://plus.google.com/share?url=' . $url_html . '" target="_blank" class="google external" title="Google+">Google+</a></span> ';

    $output .= '</div>';

    $output .= '<div class="child-detail-story">';
    $output .= '<h3>' . $story_title . '</h3>';
    $output .= $child->getChildStory();
    $output .= '</div>';

    $output .= '<div class="child-detail-story">';
    $output .= '<h3>' . self::hotn_t('Project information') . '</h3>';
    $output .= $child->ProjectInformation();
    $output .= '</div>';

    $output .= '</div>';

    return $output;
  }

  /**
   * Render markup for children overview page.
   * @param  array  $children All child displays.
   * @param  int $count Count of all children.
   * @param  string $message Message for children.
   * @return string Display all children, form and pager.
   */
  private static function hotn_theme_overview(array $children, $count, $message = NULL) {
    // Set items for selecting input.
    $agegroup = array(
      0 => self::hotn_t('below 3'),
      1 => self::hotn_t('3 - 6'),
      2 => self::hotn_t('7 - 9'),
      3 => self::hotn_t('10 or above'),
    );
    $sort = array(
      'Name' => self::hotn_t('Name'),
      'Birthdate' => self::hotn_t('Age'),
      'Country' => self::hotn_t('Country'),
      'Gender' => self::hotn_t('Gender'),
    );

    $output = '<div id="hotn-overview">';
    $output .= '<h1 class="hotn-title">' . self::hotn_t('Child sponsorship') . '</h1>';

    $output .= '<div> ';
    $output .= '<form method="get" id="hotn-filter-form"> ';
    $output .= '<div class="field">';
    $output .= '<label>' . self::hotn_t('Age') . ':' . '</label>';
    $output .= self::hotn_theme_select('hotn-agegroup', $agegroup);
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . self::hotn_t('Country') . ':' . '</label>';
    $output .= self::hotn_theme_select('hotn-country', self::get_child_filter('Country'));
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . self::hotn_t('Gender') . ':' . '</label>';
    $output .= self::hotn_theme_select('hotn-gender', self::get_child_filter('Gender'));
    $output .= '</div>';
    $output .= '<div class="field field-sort">';
    $output .= '<label>' . self::hotn_t('Sort') . ':' . '</label>';
    $output .= self::hotn_theme_select('hotnsort', $sort, self::hotn_t('Sort'));
    $output .= '</div>';
    $output .= '<div class="links">';
    $output .= '<a href="#" class="link hotn-filter-form-reset">' . self::hotn_t('Reset') . '</a>';
    $output .= '</div>';
    $output .= '</form>';
    $output .= '</div>';

    $output .= '<div id="hotn-child-list">';

    if (!empty($count)) {
      $string = self::hotn_format_plural($count, self::hotn_t('child found'), self::hotn_t('children found'));

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
    foreach ($children as $child) {
      $output .= $child;
    }
    $output .= '</div>';

    $output .= '<div id="hotn-pager">';
    $output .= self::hotn_theme_pager();
    $output .= '</div>';

    $output .= '</div>';

    $output .= '</div>';

    return $output;
  }

  /**
   * Render markup for select form element.
   * @param  string $name Name of the select tag.
   * @param  array $items All items for in the select.
   * @param  string $title Title for first option. Default is select.
   * @return string Returns select field.
   */
  private static function hotn_theme_select($name, $items, $title = NULL) {
    $title = !empty($title) ? $title : self::hotn_t('Select');

    $output = '<select name="' . $name . '"> ';
    $output .= '<option value="">' . $title . '</option> ';

    foreach ($items as $key => $value) {
      $output .= '<option value="' . $key . '">' . $value . '</option> ';
    }

    $output .= '</select>';

    return $output;
  }

  /**
   * Render markup for pager.
   * @return string Returns a pager.
   */
  private static function hotn_theme_pager() {
    // Get the current pager ID. If empty fallback 0.
    $current_pager = !empty($_GET['hotnpager']) ? $_GET['hotnpager'] : 0;
    $pager_count = self::$pagers_items;
    $prev_pager = $current_pager - 1;
    $next_pager = $current_pager + 1;
    $last_pager = $pager_count - 1;
    $pager = '';

    // If 1 page return nothing.
    if ($pager_count <= 1) {
      return '';
    }

    // Set the prev and first pager if prev_pager higer or equal than 0.
    if ($prev_pager >= 0) {
      $pager .= '<a href="#" data-pager="0" class="pager">' . self::hotn_t('First') . '</a> ';
      $pager .= '<a href="#" data-pager="' . $prev_pager . '" class="pager">' . self::hotn_t('Previous') . '</a> ';
    }

    for ($i=0; $i < $pager_count; $i++) {
      $page = $i + 1;

      if ($current_pager == $i) {
        $pager .= '<span data-pager="' . $i . '" class="current-pager">' . $page . '</span> ';
      }
      else {
        $pager .= '<a href="#" data-pager="' . $i . '" class="pager">' . $page . '</a> ';
      }
    }

    // Set next and last pager if next_pager lower is than pager_count.
    if ($next_pager < $pager_count) {
      $pager .= '<a href="#" data-pager="' . $next_pager . '" class="pager">' . self::hotn_t('Next') . '</a> ';
      $pager .= '<a href="#" data-pager="' . $last_pager . '" class="pager">' . self::hotn_t('Last') . '</a> ';
    }

    return $pager;
  }

  /**
   * Return string for 1 item or multiple.
   * @param  int $count A count of all items.
   * @param  string $string1 String for 1 or lower items.
   * @param  string $string2 String for 2 or more items.
   * @return String with the correct string by count of items.
   */
  private static function hotn_format_plural($count, $string1, $string2) {
    if ($count <= 1) {
      return $string1;
    }
    else {
      return $string2;
    }
  }
}
