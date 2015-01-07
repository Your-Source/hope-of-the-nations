<?php
/**
 * Module file for Hope of the Nations.
 */
define('DS', DIRECTORY_SEPARATOR);

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$library_path = JPATH_BASE . DS . 'libraries' . DS . 'hotn';

// If library does not exist set error message and return.
if (!file_exists($library_path . DS . 'hotnConfig.php')) {
  JError::raiseWarning(100, JText::_('The Hope of the Nations library is not present.'));

  return;
}

// Include library files.
require_once $library_path . DS . 'hotnConfig.php';
require_once $library_path . DS . 'lib' . DS . 'hotn.php';

// Set administrator email to library.
$config = JFactory::getConfig();
hotnConfig::$admin_email = $config->get('mailfrom');

// Include javascript an css file to document header.
$document = JFactory::getDocument();
$document->addScript('libraries' . DS . 'hotn' . DS . 'js' . DS . 'jquery-1.11.1.min.js');
$document->addScript('libraries' . DS . 'hotn' . DS . 'js' . DS . 'hotn.js');
$document->addStyleSheet('libraries' . DS . 'hotn' . DS . 'css' . DS . 'hotn-style.css');

print hotn::load();
