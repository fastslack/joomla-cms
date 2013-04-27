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
 * The JBugs ajax controller 
 *
 * @package     jUpgradePro
 * @subpackage  com_jupgradepro
 * @since       3.1
 */
class JBugsControllerAjax extends JControllerLegacy
{
	/**
	 * @var		string	The context for persistent state.
	 * @since   3.1
	 */
	protected $context = 'com_bugs.ajax';

	/**
	 * Proxy for getModel.
	 *
	 * @param   string	$name	The name of the model.
	 * @param   string	$prefix	The prefix for the model class name.
	 *
	 * @return  jUpgradeProModel
	 * @since   3.1
	 */
	public function getModel($name = '', $prefix = 'jUpgradeProModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}
