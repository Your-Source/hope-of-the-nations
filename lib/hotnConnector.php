<?php
/**
 * Connector class file for Hope of the Nations.
 */

class hotnConnector {

  /**
   * Get the feed with the data.
   * @param  string $type type of URL.
   * @param  array  $data extra query data.
   * @return array array with the data of the request.
   */
  public static function get_feed($type = 'child', $data = array()) {
    $hotnsessionkey = 'hotn_' . $type;
    $sess_ttl = !empty(hotnConfig::$session_ttl) ? hotnConfig::$session_ttl : 0;

    // Set API key to query and build query.
    foreach ($data as $key => $value) {
      // Unset the key if the value is empty of not contains hotn-.
      // If the key is agegroup exclude this from unset.
      if (strlen($value) < 1 || strpos($key, 'hotn-') === FALSE) {
        unset($data[$key]);
      }
      else {
        // Replace hotn- to nothing, set new parameter to array and delete old key.
        $new_key = str_replace('hotn-', '', $key);
        $data[$new_key] = $data[$key];
        unset($data[$key]);
      }
    }

    // If session width data is empty or time to live of the session is expired.
    if (empty($_SESSION[$hotnsessionkey])
      || (time() - $_SESSION[$hotnsessionkey . '_created'] > $sess_ttl)) {

      // Get the data from the request.
      try {
        $data = self::type_data_request($type, $data);
      }
      catch (Exception $e) {
        throw new Exception('Could not fetch data from web service.');
      };

      // Set the data to the session and set also a created time to session.
      try {
        $_SESSION[$hotnsessionkey] = $data;
        $_SESSION[$hotnsessionkey . '_created'] = time();
      }
      catch (Exception $e) {
        throw new Exception('Error setting data to the session variable.');
      };

      // Return array with the JSON data from URL request..
      return json_decode($data, TRUE);
    }

    // Return array with the JSON data from session.
    return json_decode($_SESSION[$hotnsessionkey], TRUE);
  }

  /**
   * Set the sponsor to the API.
   * @param array $values All values posted from form.
   * @return int If message contain the string was posted successfully.
   */
  public static function setSponsor($values) {
    $json_value = json_encode($values, TRUE);

    $request = self::type_data_request('sponsor', $json_value);

    return strpos($request, 'was posted successfully');
  }

  /**
   * Request function for building the URL and sending requests.
   * @param  string $type type of URL.
   * @param  array  $data extra query data.
   * @return string string with data of page.
   */
  private static function type_data_request($type = 'child', $data = array()) {
    $url_data = hotnConfig::${$type . 'UrlData'};

    // Build the url for call the API.
    $url = hotnConfig::$url . '/' . $url_data['uri'];

    // Set the http options.
    $options = array(
      'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => $url_data['method'],
      ),
    );

    // If content is FALSE set the data to the parameter.
    // As Fallback set the data as HTTP content.
    if (!$url_data['content']) {
      $parameter = $data;
    }
    else {
      $options['http']['content'] = $data;
    }

    // Set API key to URL parameter.
    $parameter['apikey'] = hotnConfig::$apikey;
    $query = http_build_query($parameter);

    // Create URL with query.
    $url = $url . '?' . $query;

    $context = stream_context_create($options);
    // Get result with file get contents.
    try {
      $result = file_get_contents($url, FALSE, $context);
    }
    catch (Exception $e) {
      throw new Exception('Could not connect to web service.');
    };

    return $result;
  }

}
