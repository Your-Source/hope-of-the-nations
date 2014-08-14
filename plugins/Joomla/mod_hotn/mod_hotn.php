<?php
/**
 * Module file for Hope of the Nations.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

date_default_timezone_set('Europe/Amsterdam');

$library_path = JPATH_BASE . DS . 'libraries' . DS . 'hotn';

// If library does not exist set error message and return.
if (!file_exists($library_path . DS . 'hotnConfig.php')) {
  JError::raiseWarning(100, JText::_('The Hope of the Nations library is not present.'));

  return;
}

// Include library files.
require_once $library_path . DS . 'hotnConfig.php';
require_once $library_path . DS . 'lib' . DS . 'hotn.php';

// Include javascript an css file to document header.
$document = JFactory::getDocument();
$document->addScript('libraries' . DS . 'hotn' . '/js/hotn.js');
$document->addStyleSheet('libraries' . DS . 'hotn' . '/css/hotn-style.css');

print hotn::load();
