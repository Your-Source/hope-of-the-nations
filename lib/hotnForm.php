<?php
/**
 * Form class file for Hope of the Nations.
 */

class hotnForm {

  /**
   * Shows hotn sponsor form.
   * @param  [type] $childs array with the current child.
   * @return (string) Form with all fields.
   */
  public function form($childs) {

    if (!empty($_POST['ChildID'])) {
      $messages = self::hotn_form_validate($_POST);

      return self::hotn_theme_form($childs[0], $_POST, $messages);
    }

    return self::hotn_theme_form($childs[0]);
  }

  private function hotn_form_validate($values) {
    $messages = array();

    // Set all values to plain text.
    foreach ($values as $key => $value) {
      $values[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // Check all fields on name has content.
    $required_fields = array(
      'Lastname' => hotn::hotn_t('Name'),
      'Agreed' => hotn::hotn_t('Agreement'),
    );
    foreach ($required_fields as $fieldkey => $fieldname) {
      if (empty($values[$fieldkey])) {
        $messages[] = hotn::hotn_t('The field @fieldname is required.', array('@fieldname' => $fieldname));
      }
    }

    return $messages;
  }

  /**
   * [hotn_theme_form description]
   * @param hotnSponsorChild $child Instance of child.
   * @param (array) $values values of field items.
   * @param (array) $messages array with all messages to show by form
   * @return (string) return form with all items.
   */
  private function hotn_theme_form(hotnSponsorChild $child, $values = array(), $messages = array()) {
    // Set all values to variables.
    foreach ($values as $valkey => $value) {
      ${$valkey} = $value;
    }

    $title = hotn::hotn_t('Registration form');

    // Discription text for sponsor form.
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

    // Values for radio set
    $salutation_values = array(
      'De heer' => hotn::hotn_t('Mr'),
      'Mevrouw' => hotn::hotn_t('Ms.'),
    );
    $country_values = array(
      'Nederland' => hotn::hotn_t('Netherlands'),
      'Belgie' => hotn::hotn_t('Belgium'),
    );
    $amount_values = array(
      '15' => hotn::hotn_t('&#8364; 15,-'),
      '30' => hotn::hotn_t('&#8364; 30,-'),
    );
    $period_values = array(
      '1' => hotn::hotn_t('1 year'),
      '2' => hotn::hotn_t('2 years'),
      '5' => hotn::hotn_t('5 years'),
      '5+' => hotn::hotn_t('or until further notice with a minimum of 5 years.'),
    );

    $output = '<div id="hotn-child-form">';
    $output .= '<h1 class="hotn-title">' . $title . '</h1>';

    $output .= '<div id="messages">';
    if (!empty($messages)) {
      foreach ($messages as $message) {
        $output .= $message . '<br />';
      }
    }
    $output .= '</div>';
var_dump($values);
    $output .= '<form method="POST" id="hotn-child-sponsor-form"> ';

    $output .= '<div class="field markup">';
    $output .= hotn::hotn_t($form_discription, $form_discription_placeholder);
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Salutation') . ':' . '</label> ';
    $output .= self::hotn_theme_radio($salutation_values, 'Salutation', !empty($Salutation) ? $Salutation : '');
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Name') . ':' . '</label> ';
    $output .= '<input type="text" name="Lastname" value="' . (!empty($Lastname) ? $Lastname : '') . '">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('First name') . ':' . '</label> ';
    $output .= '<input type="text" name="Firstname" value="' . (!empty($Firstname) ? $Firstname : '') . '">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Initials') . ':' . '</label> ';
    $output .= '<input type="text" name="Initials" value="' . (!empty($Initials) ? $Initials : '') . '">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Date of birth') . ':' . '</label> ';
    $output .= '<input type="date" name="DateOfBirth" value="' . (!empty($DateOfBirth) ? $DateOfBirth : '') . '">';
    $output .= '</div>';
    $output .= '<div class="field markup">';
    $output .= hotn::hotn_t($email_discription, $email_discription_placeholder);
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Email') . ':' . '</label> ';
    $output .= '<input type="email" name="EmailAddress" value="' . (!empty($EmailAddress) ? $EmailAddress : '') . '">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Country') . ':' . '</label> ';
    $output .= self::hotn_theme_radio($country_values, 'Country', !empty($Country) ? $Country : '');
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Zip code') . ':' . '</label> ';
    $output .= '<input type="text" name="Postcode" value="' . (!empty($Postcode) ? $Postcode : '') . '">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Premises') . ':' . '</label> ';
    $output .= '<input type="number" name="Premises" value="' . (!empty($Premises) ? $Premises : '') . '">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Phone number') . ':' . '</label> ';
    $output .= '<input type="tel" name="PhoneNumber" value="' . (!empty($PhoneNumber) ? $PhoneNumber : '') . '">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Mobilephone number') . ':' . '</label> ';
    $output .= '<input type="tel" name="MobilePhone" value="' . (!empty($MobilePhone) ? $MobilePhone : '') . '">';
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Bankaccount') . ':' . '</label> ';
    $output .= '<input type="text" name="BankAccount" value="' . (!empty($BankAccount) ? $BankAccount : '') . '">';
    $output .= '</div>';
    $output .= '<div class="field markup">';
    $output .= hotn::hotn_t($sponsor_discription, $email_discription_placeholder);
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Sponsor amount') . ':' . '</label> ';
    $output .= self::hotn_theme_radio($amount_values, 'Amount', !empty($Amount) ? $Amount : '');
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Duration') . ':' . '</label> ';
    $output .= self::hotn_theme_radio($period_values, 'Duration', !empty($Period) ? $Period : '');
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<input type="checkbox" name="Agreed" value="1"> ' . hotn::hotn_t($agreement_text);
    $output .= '</div>';
    $output .= '<input type="hidden" name="ChildID" value="' .  hotn::hotn_t($child->getChildID()) . '">';
    $output .= '<div class="field submit">';
    $output .= '<input type="submit" name="submit" value="' . hotn::hotn_t('Submit') . '">';
    $output .= '</div>';


    $output .= '</form>';

    $output .= '</div>';

    return $output;
  }

  /**
   * [hotn_theme_radio description]
   * @param array $values All values of set with radio form items.
   * @param string $name Name of the set radio buttons.
   * @param string $checked_val value of current checked value.
   * @return string $output Output of set with radio buttons.
   */
  private function hotn_theme_radio($values = array(), $name = '', $checked_val = '') {
    $output = '';

    foreach ($values as $key => $value) {
      $checked = (!empty($checked_val) && $key == $checked_val ? 'checked' : '');

      $output .= '<input type="radio" name="' . $name . '" value="' . $key . '" ' . $checked . '> ' . $value . ' ';
    }

    return $output;
  }
}
