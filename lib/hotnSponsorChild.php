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
   */
  public function getChildId() {
    return $this->child['ChildID'];
  }

  /**
   * Returns string with child name.
   */
  public function getChildName() {
    return $this->child['Name'];
  }

  /**
   * Returns string with child story.
   */
  public function getChildStory() {
    return $this->child['Story'];
  }

  /**
   * Returns string with child hobbies.
   */
  public function getChildHobbies() {
    return $this->child['Hobbies'];
  }

  /**
   * Returns string with child country.
   */
  public function getChildCountry() {
    return $this->child['Country'];
  }

  /**
   * Returns string with child birthday.
   */
  public function getChildBirthdate() {
    return $this->child['Birthdate'];
  }

  /**
   * Returns string with child age.
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
   */
  public function getChildGender() {
    return $this->child['Gender'];
  }

  /**
   * Returns string with url to small child image.
   */
  public function getChildSmallImage() {
    if (!empty($this->child['SmallImage'])) {
      return 'data:image/png;base64,' . $this->child['SmallImage'];
    }

    return 'images/child-fallback.png';
  }

  /**
   * Returns string with url to large child image.
   */
  public function getChildLargeImage() {
    return $this->child['LargeImage'];
  }

}
