<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_bugs
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//ini_set('display_errors', 'On');
//error_reporting(E_ALL);

if (!JFactory::getUser()->authorise('core.manage', 'com_bugs'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$controller	= JControllerLegacy::getInstance('JBugs');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
