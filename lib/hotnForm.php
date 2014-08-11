<?php
/**
 * Form class file for Hope of the Nations.
 */

class hotnForm {

  public function form($childs) {


    return self::hotn_theme_form($childs[0]);
  }

  private function hotn_theme_form($child) {
    $title = hotn::hotn_t('Registration form');

    $form_discription = 'Thank you for considering to sponsor "@name". You monthly gift of  &#8364; 30 of &#8364; 15 gives @gender a change of a life without poverty. Via Hope of the Nations she/he receives education, supplementary nutrition, medical care and mental training.
Fill in the form below to support "@name"';
    $form_discription_placeholder = array(
      '@name' => $child->getChildName(),
      '@gender' => hotn::hotn_t(($child->getChildGender() == 'Jongen') ? 'him' : 'her'),
    );
;
    $output = '<div id="hotn-child-form">';
    $output .= '<h1 class="hotn-title">' . $title . '</h1>';

    $output .= '<form method="get" id="hotn-child-sponsor-form"> ';
    $output .= '<div class="field">';
    $output .= hotn::hotn_t($form_discription, $form_discription_placeholder);
    $output .= '</div>';
    $output .= '</form>';

    $output .= '</div>';

    return $output;
  }
}
