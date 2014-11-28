<?php
/**
 * SponsorChild class file for Hope of the Nations.
 */

class hotnSponsorChild implements hotnSponsorChildInterface {
  private $child = array();

  /**
   * Construct function for get all child properties.
   * @param (array) $child Array with all child properties.
   */
  function __construct($child) {
    $this->child = $child;
  }

  /**
   * Returns int with child ID.
   * @return (int) $child_id Returns int of childid.
   */
  public function getChildId() {
    return $this->child['ChildID'];
  }

  /**
   * Returns string with child name.
   * @return (string) $name Returns name of the child.
   */
  public function getChildName() {
    return $this->child['Name'];
  }

  /**
   * Returns string with child story.
   * @return (string) $story Returns story of the child.
   */
  public function getChildStory() {
    return $this->child['Story'];
  }

  /**
   * Returns string with child hobbies.
   * @return (string) $hobbies Returns hobbies of the child.
   */
  public function getChildHobbies() {
    return $this->child['Hobbies'];
  }

  /**
   * Returns string with child country.
   * @return (string) $country Returns country of the child.
   */
  public function getChildCountry() {
    return $this->child['Country'];
  }

  /**
   * Returns string with child birthday.
   * @return (string) $birthday Returns birthdate of the child.
   */
  public function getChildBirthdate() {
    $time = strtotime($this->child['Birthdate']);

    // If birthdate is not a integer return null.
    if ($time == 0) {
      return NULL;
    }

    return date('d-m-Y', $time);
  }

  /**
   * Returns int with child age.
   * @return (int) $age Returns age of the child.
   */
  public function getChildAge() {
    $birthdate = $this->child['Birthdate'];

    // If birthdate is not a integer return null.
    if (strtotime($birthdate) == 0) {
      return NULL;
    }

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
   * @return (string) $gender Returns gender of the child.
   */
  public function getChildGender() {
    return $this->child['Gender'];
  }

  /**
   * Returns URL to an image from a child.
   * @return (string) Returns URL of an image from a child.
   */
  public function getChildImage() {
    // Build the URL for call the API.
    return hotnConfig::$url . '/' . hotnConfig::$childPictureUri . '/' . $this->child['ChildID'];
  }

  /**
   * Returns string with project information.
   * @return (string) $ProjectInformation Returns project information about a child.
   */
  public function ProjectInformation() {
    return $this->child['ProjectInformation'];
  }

  /**
   * Returns boolean with status ID.
   * @return (boolean) $StatusId Returns status ID of a child.
   */
  public function getStatusId() {
    return $this->child['StatusId'];
  }
}
