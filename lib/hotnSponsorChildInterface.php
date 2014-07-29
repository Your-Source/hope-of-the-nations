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
   */
  public function getChildId();

  /**
   * Returns string with child name.
   */
  public function getChildName();

  /**
   * Returns string with child story.
   */
  public function getChildStory();

  /**
   * Returns string with child hobbies.
   */
  public function getChildHobbies();

  /**
   * Returns string with child country.
   */
  public function getChildCountry();

  /**
   * Returns string with child birthday.
   */
  public function getChildBirthdate();

  /**
   * Returns string with child age.
   */
  public function getChildAge();

  /**
   * Returns string with child gender.
   */
  public function getChildGender();

  /**
   * Returns string with url to small child image.
   */
  public function getChildSmallImage();

  /**
   * Returns string with url to large child image.
   */
  public function getChildLargeImage();
}
