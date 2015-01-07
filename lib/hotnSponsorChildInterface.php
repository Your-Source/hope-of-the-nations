<?php
/**
 * Main class file for Hope of the Nations.
 */

interface hotnSponsorChildInterface {

  /**
   * Construct function for get all child properties.
   * @param (array) $child Array with all child properties.
   */
  function __construct($child);

  /**
   * Returns int with child ID.
   * @return (int) $child_id Returns int of childid.
   */
  public function getChildId();

  /**
   * Returns string with child name.
   * @return (string) $name Returns name of the child.
   */
  public function getChildName();

  /**
   * Returns string with child story.
   * @return (string) $story Returns story of the child.
   */
  public function getChildStory();

  /**
   * Returns string with child hobbies.
   * @return (string) $hobbies Returns hobbies of the child.
   */
  public function getChildHobbies();

  /**
   * Returns string with child country.
   * @return (string) $country Returns country of the child.
   */
  public function getChildCountry();

  /**
   * Returns string with child birthday.
   * @return (string) $birthday Returns birthdate of the child.
   */
  public function getChildBirthdate();

  /**
   * Returns int with child age.
   * @return (int) $age Returns age of the child.
   */
  public function getChildAge();

  /**
   * Returns string with child gender.
   * @return (string) $gender Returns gender of the child.
   */
  public function getChildGender();

  /**
   * Returns URL to an image from a child.
   * @return (string) Returns URL of an image from a child.
   */
  public function getChildImage();

  /**
   * Returns string with project information.
   * @return (string) $ProjectInformation Returns project information about a child.
   */
  public function ProjectInformation();

  /**
   * Returns boolean with status ID.
   * @return (boolean) $StatusId Returns status ID of a child.
   */
  public function getStatusId();
}
