<?php
/**
 * Form class file for Hope of the Nations.
 */

class hotnForm {

  /**
   * Shows hotn sponsor form.
   * @param (array) $children array with the current child.
   * @return (string) Form with all fields.
   */
  public static function form($children) {

    if (!empty($_POST['ChildID'])) {
      $value = $_POST;
      $messages = self::hotn_form_validate($value);

      if (!empty($messages)) {
        return self::hotn_theme_form($children[0], $value, $messages);
      }

      if (hotnConnector::setSponsor($value)) {
        $message = hotn::hotn_t('You are sponsoring a child now.');
        return self::hotn_theme_display_message($message);
      }

      $message = hotn::hotn_t('Oops, something went wrong. Try again or contact the site administrator.');
      return self::hotn_theme_display_message($message);
    }

    return self::hotn_theme_form($children[0]);
  }

  /**
   * Validate the form value and set message if something is wrong.
   * @param  array $values All values from the form.
   * @return array $messages All messages if field is empty.
   */
  private static function hotn_form_validate(&$values) {
    // All required fields of sponsor form.
    $required_fields = array(
      'Salutation' => hotn::hotn_t('Salutation'),
      'Lastname' => hotn::hotn_t('Name'),
      'Firstname' => hotn::hotn_t('First name'),
      'Initials' => hotn::hotn_t('Initials'),
      'DateOfBirth' => hotn::hotn_t('Date of birth'),
      'EmailAddress' => hotn::hotn_t('Email'),
      'Country' => hotn::hotn_t('Country'),
      'Postcode' => hotn::hotn_t('Zip code'),
      'Premises' => hotn::hotn_t('Premises'),
      'PhoneNumber' => hotn::hotn_t('Phone number'),
      'MobilePhone' => hotn::hotn_t('Mobilephone number'),
      'BankAccount' => hotn::hotn_t('Bankaccount'),
      'Amount' => hotn::hotn_t('Sponsor amount'),
      'Duration' => hotn::hotn_t('Duration'),
      'Agreed' => hotn::hotn_t('Agreement'),
      'ChildID' => 'ChildID',
    );

    $messages = array();

    foreach ($values as $key => $value) {
      // Set the value to plain text.
      $values[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

      if (!array_key_exists($key, $required_fields)) {
        unset($values[$key]);
      }
    }

    // Check if all fields in the required fields array have content.
    foreach ($required_fields as $fieldkey => $fieldname) {
      if (empty($values[$fieldkey])) {
        $messages[] = hotn::hotn_t('The field @fieldname is required.', array('@fieldname' => $fieldname));
      }
    }

    // Field names where the value parse to integer.
    $fields_int = array(
      'Amount',
      'Agreed',
      'ChildID',
    );
    foreach ($fields_int as $field) {
      // If field is not empty parse to integer.
      if (!empty($values[$field])) {
        $values[$field] = intval($values[$field]);
      }
    }

    // Reformat the value of the date field.
    if (!empty($values['DateOfBirth'])) {
      $timestamp = strtotime($values['DateOfBirth']);

      $date = date('Y-m-d', $timestamp);

      // Replace the date with the new format.
      $values['DateOfBirth'] = $date;
    }

    return $messages;
  }

  /**
   * Theme function for creating the sponsor form.
   * @param hotnSponsorChild $child Instance of child.
   * @param (array) $values values of field items.
   * @param (array) $messages array with all messages to show by form.
   * @return (string) return form with all items.
   */
  private static function hotn_theme_form(hotnSponsorChild $child, $values = array(), $messages = array()) {
    // Set all values to variables.
    foreach ($values as $valkey => $value) {
      ${$valkey} = $value;
    }

    $title = hotn::hotn_t('Registration form');

    // Description text for sponsor form.
    $form_description = 'Thank you for considering to sponsor @name. You monthly gift of  &#8364; 30 of &#8364; 15 gives @gender a change of a life without poverty. Via Hope of the Nations she/he receives education, supplementary nutrition, medical care and mental training.
Fill in the form below to support @name';
    $form_description_placeholder = array(
      '@name' => $child->getChildName(),
      '@gender' => hotn::hotn_t(($child->getChildGender() == 'Jongen') ? 'him' : 'her'),
    );
    $email_description = 'We will use your email address to keep you informed of news and the situation of @name. For us this is the least pricely way of communication.';
    $email_description_placeholder = array(
      '@name' => $child->getChildName(),
    );
    $sponsor_description = 'Yes! I decide to sponsor @name!';
    $agreement_text = 'I allow Hope of the Nations to send a collection order to my bank. I allow my bank to debit this amount from my account monthly according to the order by Hope of the Nations. I reserve the right to let my bank arrange refunding within 56 calendar days (8 weeks) after debiting my account without any statement of reasons. I agree the amount is debited at the first following debiting day (the 5th or the 25th of the current month)';

    // Values and keys for the diffrent radio sets.
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

    // Begin the output for the form.
    $output = '<div id="hotn-child-form">';
    $output .= '<h1 class="hotn-title">' . $title . '</h1>';

    $output .= '<div id="messages">';
    if (!empty($messages)) {
      foreach ($messages as $message) {
        $output .= $message . '<br />';
      }
    }
    $output .= '</div>';

    $output .= '<form method="POST" id="hotn-child-sponsor-form"> ';

    $output .= '<div class="field markup">';
    $output .= hotn::hotn_t($form_description, $form_description_placeholder);
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
    $output .= hotn::hotn_t($email_description, $form_description_placeholder);
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
    $output .= '<input type="text" name="Premises" value="' . (!empty($Premises) ? $Premises : '') . '">';
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
    $output .= '<label>' . hotn::hotn_t('IBAN') . ':' . '</label> ';
    $output .= '<input type="text" name="BankAccount" value="' . (!empty($BankAccount) ? $BankAccount : '') . '">';
    $output .= '</div>';
    $output .= '<div class="field markup">';
    $output .= hotn::hotn_t($sponsor_description, $form_description_placeholder);
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Sponsor amount') . ':' . '</label> ';
    $output .= self::hotn_theme_radio($amount_values, 'Amount', !empty($Amount) ? $Amount : '');
    $output .= '</div>';
    $output .= '<div class="field">';
    $output .= '<label>' . hotn::hotn_t('Duration') . ':' . '</label> ';
    $output .= self::hotn_theme_radio($period_values, 'Duration', !empty($Duration) ? $Duration : '');
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
   * Render markup for message on screen.
   * @param  string $message Message for display message.
   * @return string Markup for display message.
   */
  private static function hotn_theme_display_message($message) {
    $output = '<div id="hotn-child-form">';
    $output .= '<div class="display-message">';
    $output .= $message;
    $output .= '</div>';
    $output .= '</div>';

    return $output;
  }

  /**
   * Render markup for radio form item.
   * @param array $values All values of set with radio form items.
   * @param string $name Name of the set radio buttons.
   * @param string $checked_val value of current checked value.
   * @return string $output Output of set with radio buttons.
   */
  private static function hotn_theme_radio($values = array(), $name = '', $checked_val = '') {
    $output = '<div class="radio-items">';

    foreach ($values as $key => $value) {
      $checked = (!empty($checked_val) && $key == $checked_val ? 'checked' : '');

      $output .= '<div class="radio-item">';
      $output .= '<input type="radio" name="' . $name . '" value="' . $key . '" ' . $checked . '> ' . $value . ' ';
      $output .= '</div>';
    }

    $output .= '</div>';

    return $output;
  }
}
