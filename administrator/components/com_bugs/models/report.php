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

/**
 * Bugs model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_bugs
 * @since       3.1
 */
class JBugsModelReport extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since   3.1
	 */
	protected $text_prefix = 'COM_BUGS';

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 * @since   3.1
	 */
	public function getTable($type = 'Bug', $prefix = 'BugsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array  $data		An optional array of data for the form to interogate.
	 * @param   boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return  JForm	A JForm object on success, false on failure
	 * @since   3.1
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();

		// Handle the optional arguments.
		$options['control'] = JArrayHelper::getValue($options, 'control', false);

		// Get the form.
		JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');

		$form = JForm::getInstance('com_bugs.report', 'report');

		if (empty($form))
		{
			return false;
		}

		return $form;
	}
}
