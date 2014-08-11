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
    $sponsor_discription = 'Yes! I decide to sponsor "@name"!';
    $agreement_text = 'Ik geef Hope of the Nations toestemming om een incasso-opdracht te sturen naar mijn bank. Ik geef mijn bank toestemming om dit bedrag maandelijks van mijn rekening af te schrijven overeenkomstig de opdracht van Hope of the nations. Ik behoud het recht om binnen 56 kalenderdagen (8 weken) na de afschrijving, zonder opgaaf van redenen, mijn bank terugbetaling te laten verzorgen. Ik ga ermee akkoord dat de incassering plaatsvindt op het eerstvolgende incassomoment (5e of 25e van de maand).';

    $output = '<div id="hotn-child-form">';
    $output .= '<h1 class="hotn-title">' . $title . '</h1>';

    $output .= '<form method="POST" id="hotn-child-sponsor-form"> ';

    $output .= '<div class="field markup">';
    $output .= hotn::hotn_t($form_discription, $form_discription_placeholder);
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Salutation') . ':' . '</label> ';
    $output .= '<input type="radio" name="Salutation" value="De heer"> ' . hotn::hotn_t('Mr') . ' ';
    $output .= '<input type="radio" name="Salutation" value="Mevrouw"> ' . hotn::hotn_t('Ms.');
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
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Email') . ':' . '</label> ';
    $output .= '<input type="email" name="EmailAddress">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Country') . ':' . '</label> ';
    $output .= '<input type="radio" name="Country" value="Nederland">' . hotn::hotn_t('Netherlands') . ' ';
    $output .= '<input type="radio" name="Country" value="Belgie">' . hotn::hotn_t('Belgium');
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Zip code') . ':' . '</label> ';
    $output .= '<input type="text" name="Postcode">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Premises') . ':' . '</label> ';
    $output .= '<input type="number" name="Premises">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Phone number') . ':' . '</label> ';
    $output .= '<input type="tel" name="PhoneNumber">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Mobilephone number') . ':' . '</label> ';
    $output .= '<input type="tel" name="MobilePhone">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Bankaccount') . ':' . '</label> ';
    $output .= '<input type="text" name="BankAccount">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('In the name of') . ':' . '</label> ';
    $output .= '<input type="text" name="BankAccountName">';
    $output .= '</div>';
    $output .= '<div class="field markup">';
    $output .= hotn::hotn_t($sponsor_discription, $email_discription_placeholder);
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Sponsor amount') . ':' . '</label> ';
    $output .= '<input type="radio" name="Amount" value="15"> ' . hotn::hotn_t('&#8364; 15,-') . ' ';
    $output .= '<input type="radio" name="Amount" value="30"> ' . hotn::hotn_t('&#8364; 30,-');
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Period') . ':' . '</label> ';
    $output .= '<input type="radio" name="Period" value="1"> ' . hotn::hotn_t('1 year') . ' ';
    $output .= '<input type="radio" name="Period" value="2"> ' . hotn::hotn_t('2 years') . ' ';
    $output .= '<input type="radio" name="Period" value="5"> ' . hotn::hotn_t('5 years') . ' ';
    $output .= '<input type="radio" name="Period" value="5+"> ' . hotn::hotn_t('or until further notice with a minimum of 5 years.');
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<input type="checkbox" name="agreement" value="1"> ' . hotn::hotn_t($agreement_text);
    $output .= '</div>';
    $output .= '<div class="field submit">';
    $output .= '<input type="submit" name="submit" value="' . hotn::hotn_t('Submit') . '">';
    $output .= '</div>';


    $output .= '</form>';

    $output .= '</div>';

    return $output;
  }
}
