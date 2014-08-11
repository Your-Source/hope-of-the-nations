<?php
/**
 * Form class file for Hope of the Nations.
 */

class hotnForm {

  public function form() {

    return self::hotn_theme_form();
  }

  private function hotn_theme_form() {
    $title = hotn::hotn_t('Registration form');

    $output = '<div id="hotn-child-form">';
    $output .= '<h1 class="hotn-title">' . $title . '</h1>';

    return $output;
  }
}
