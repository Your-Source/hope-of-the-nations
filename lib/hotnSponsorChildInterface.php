<?php
/**
 * Main class file for Hope of the Nations.
 */

interface hotnSponsorChildInterface {

  /**
   * Construct function for get all child properties.
   * @param array $child Array with all child properties.
   */
  function __construct($child);

 /**
   * Returns int with child id.
   * @return int Returns int of childid.
   */
  public function getChildId();

  /**
   * Returns string with child name.
   * @return string Returns name of the child.
   */
  public function getChildName();

  /**
   * Returns string with child story.
   * @return string Returns story of the child.
   */
  public function getChildStory();

  /**
   * Returns string with child hobbies.
   * @return string Returns hobbies of the child.
   */
  public function getChildHobbies();

  /**
   * Returns string with child country.
   * @return string Returns country of the child.
   */
  public function getChildCountry();

  /**
   * Returns string with child birthday.
   * @return string Returns birthdate of the child.
   */
  public function getChildBirthdate();

  /**
   * Returns int with child age.
   * @return int Returns age of the child.
   */
  public function getChildAge();

  /**
   * Returns string with child gender.
   * @return string Returns gender of the child.
   */
  public function getChildGender();

  /**
   * Returns string with url to small child image.
   * @return string Returns base64 object of small Image from the child.
   */
  public function getChildSmallImage();

  /**
   * Returns string with url to large child image.
   * @return string Returns base64 object of small Image from the child.
   */
  public function getChildLargeImage();
}
