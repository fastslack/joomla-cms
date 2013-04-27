<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_bugs
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Bugs Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_bugs
 * @since       3.1
 */
class JBugsController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean			$cachable	If true, the view output will be cached
	 * @param   array  $urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 * @since   3.1
	 */
	public function display($cachable = false, $urlparams = false)
	{
		//require_once JPATH_COMPONENT.'/helpers/bugs.php';

		$view   = $this->input->get('view', 'report');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'bug' && $layout == 'edit' && !$this->checkEditId('com_bugs.edit.bug', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_bugs&view=bug', false));

			return false;
		}

		// set default view if not set
		JRequest::setVar('view', $view);

		// set default view if not set
		JRequest::setVar('layout', 'default');

		// call parent behavior
		parent::display($cachable);

		return $this;
	}
}
