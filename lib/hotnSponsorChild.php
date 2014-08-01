<?php
/**
 * SponsorChild class file for Hope of the Nations.
 */

class hotnSponsorChild implements hotnSponsorChildInterface {
  private $child = array();

  /**
   * Construct function for get all child properties.
   * @param array $child Array with all child properties.
   */
  function __construct($child) {
    $this->child = $child;
  }

  /**
   * Returns int with child id.
   * @return int Returns int of childid.
   */
  public function getChildId() {
    return $this->child['ChildID'];
  }

  /**
   * Returns string with child name.
   * @return string Returns name of the child.
   */
  public function getChildName() {
    return $this->child['Name'];
  }

  /**
   * Returns string with child story.
   * @return string Returns story of the child.
   */
  public function getChildStory() {
    return $this->child['Story'];
  }

  /**
   * Returns string with child hobbies.
   * @return string Returns hobbies of the child.
   */
  public function getChildHobbies() {
    return $this->child['Hobbies'];
  }

  /**
   * Returns string with child country.
   * @return string Returns country of the child.
   */
  public function getChildCountry() {
    return $this->child['Country'];
  }

  /**
   * Returns string with child birthday.
   * @return string Returns birthdate of the child.
   */
  public function getChildBirthdate() {
    return $this->child['Birthdate'];
  }

  /**
   * Returns int with child age.
   * @return int Returns age of the child.
   */
  public function getChildAge() {
    $birthdate = $this->child['Birthdate'];

    // Create date from birthdate.
    $date = new DateTime($birthdate);
    $now = new DateTime();
    // Get diff between now and birthdate.
    $interval = $now->diff($date);
    $age = $interval->y;

    return $age;
  }

  /**
   * Returns string with child gender.
   * @return string Returns gender of the child.
   */
  public function getChildGender() {
    return $this->child['Gender'];
  }

  /**
   * Returns string with url to small child image.
   * @return string Returns base64 object of small Image from the child.
   */
  public function getChildSmallImage() {
    if (!empty($this->child['SmallImage'])) {
      return 'data:image/png;base64,' . $this->child['SmallImage'];
    }

    return 'images/child-fallback.png';
  }

  /**
   * Returns string with url to large child image.
   * @return string Returns base64 object of small Image from the child.
   */
  public function getChildLargeImage() {
    if (!empty($this->child['LargeImage'])) {
      return 'data:image/png;base64,' . $this->child['LargeImage'];
    }

    return 'images/child-fallback.png';
  }

}
