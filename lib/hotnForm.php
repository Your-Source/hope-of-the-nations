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
    $email_discription = 'We will use your email address to keep you informed of news and the situation of "@name". For us this is the cheapest way of communication.';
    $email_discription_placeholder = array(
      '@name' => $child->getChildName(),
    );

    $output = '<div id="hotn-child-form">';
    $output .= '<h1 class="hotn-title">' . $title . '</h1>';

    $output .= '<form method="get" id="hotn-child-sponsor-form"> ';

    $output .= '<div class="field markup">';
    $output .= hotn::hotn_t($form_discription, $form_discription_placeholder);
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Salutation') . ':' . '</label> ';
    $output .= '<input type="radio" name="title" value="De heer">' . hotn::hotn_t('Mr') . ' ';
    $output .= '<input type="radio" name="title" value="Mevrouw">' . hotn::hotn_t('Ms.');
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Name') . ':' . '</label> ';
    $output .= '<input type="text" name="Lastname">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('First name') . ':' . '</label> ';
    $output .= '<input type="text" name="Firstname">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Initials') . ':' . '</label> ';
    $output .= '<input type="text" name="Initials">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Date of birth') . ':' . '</label> ';
    $output .= '<input type="date" name="DateOfBirth">';
    $output .= '</div>';
    $output .= '<div class="field markup">';
    $output .= hotn::hotn_t($email_discription, $email_discription_placeholder);
    $output .= '</div>';


    $output .= '</form>';

    $output .= '</div>';

    return $output;
  }
}
