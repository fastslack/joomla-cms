<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_config
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Remove Root Controller for global configuration
 *
 * @package     Joomla.Administrator
 * @subpackage  com_config
 * @since       3.2
*/
class ConfigControllerApplicationRemoveroot extends JControllerBase
{

	/**
	 * Method to remove root in global configuration.
	 *
	 * @return  bool	True on success.
	 *
	 * @since   3.2
	 */
	public function execute()
	{
		// Check for request forgeries.
		if(!JSession::checkToken('get'))
		{
			$this->app->redirect('index.php', JText::_('JINVALID_TOKEN'));
		}

		// Check if the user is authorized to do this.
		if (!JFactory::getUser()->authorise('core.admin'))
		{
			$this->app->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// Initialise model.
		$model = new ConfigModelsApplication;

		// Attempt to save the configuration and remove root.
		$return = $model->removeroot();

		// Check the return value.
		if ($return === false)
		{
			// Save failed, go back to the screen and display a notice.
			$this->app->redirect(JRoute::_('index.php', false), JText::sprintf('JERROR_SAVE_FAILED', $model->getError()), 'error');
		}

		// Set the success message.
		$message = JText::_('COM_CONFIG_SAVE_SUCCESS');

		// Set the redirect based on the task.
		$this->app->redirect(JRoute::_('index.php', false), $message);

	}
}
