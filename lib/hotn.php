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
    $childs = hotnConnector::get_feed();

    foreach ($childs as $child) {
      $c = new hotnSponsorChild($child);

      echo self::theme_overview_child($c);
    }


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
    $output .= '</div>';

    $output .= '</div>';

    return $output;
  }
}
