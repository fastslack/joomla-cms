<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_bugs
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

/**
 * Report bugs view.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_bugs
 * @since       3.1
 */
class JBugsViewReport extends JViewLegacy
{
	/**
	 * Display the view.
	 *
	 * @param	string	$tpl	The subtemplate to display.
	 *
	 * @return	void
	 */
	function display($tpl = null)
	{
		$db = JFactory::getDBO();
		$version = new JVersion;

		// Getting the form
		$this->form		= $this->get('Form');

		// Getting the data from server
		$this->data = array();
		$this->data['version_joomla'] =	$version->getLongVersion();
		$this->data['version_php'] = phpversion();
		$this->data['version_server'] = $_SERVER['SERVER_SOFTWARE'];
		$this->data['version_mysql'] = $db->getVersion();
		$this->data['version_browser'] = $_SERVER['HTTP_USER_AGENT'];

		// Setting the toolbar
		JToolbarHelper::title(JText::_( 'Report bugs' ), 'bugs');
		JToolbarHelper::custom('report.send', 'health.png', 'health.png', 'COM_BUGS_SEND', false);
		JToolbarHelper::cancel('report.cancel');
		JToolbarHelper::spacer();

		parent::display($tpl);
	}
}
