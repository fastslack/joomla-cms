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
 * Bugs helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_bugs
 * @since       3.1
 */
class JBugsHelper
{
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  The category ID.
	 * @return  JObject
	 * @since   3.1
	 */
	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($categoryId))
		{
			$assetName = 'com_bugs';
			$level = 'component';
		}
		else
		{
			$assetName = 'com_bugs.category.'.(int) $categoryId;
			$level = 'category';
		}

		$actions = JAccess::getActions('com_bugs', $level);

		foreach ($actions as $action)
		{
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}

		return $result;
	}
}
