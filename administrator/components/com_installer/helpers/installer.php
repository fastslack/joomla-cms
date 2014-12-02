<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Installer helper.
 *
 * @since  1.6
 */
class InstallerHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 */
	public static function addSubmenu($vName = 'install')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_INSTALLER_SUBMENU_INSTALL'),
			'index.php?option=com_installer',
			$vName == 'install'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_INSTALLER_SUBMENU_UPDATE'),
			'index.php?option=com_installer&view=update',
			$vName == 'update'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_INSTALLER_SUBMENU_MANAGE'),
			'index.php?option=com_installer&view=manage',
			$vName == 'manage'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_INSTALLER_SUBMENU_DISCOVER'),
			'index.php?option=com_installer&view=discover',
			$vName == 'discover'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_INSTALLER_SUBMENU_DATABASE'),
			'index.php?option=com_installer&view=database',
			$vName == 'database'
		);
		JHtmlSidebar::addEntry(
		JText::_('COM_INSTALLER_SUBMENU_WARNINGS'),
					'index.php?option=com_installer&view=warnings',
		$vName == 'warnings'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_INSTALLER_SUBMENU_LANGUAGES'),
			'index.php?option=com_installer&view=languages',
			$vName == 'languages'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_INSTALLER_SUBMENU_UPDATESITES'),
			'index.php?option=com_installer&view=updatesites',
			$vName == 'updatesites'
		);
	}

	/**
	 * Get a list of filter options for the extension types.
	 *
	 * @return  array  An array of stdClass objects.
	 *
	 * @since   3.0
	 */
	public static function getExtensionTypes()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT type')
			->from('#__extensions');
		$db->setQuery($query);
		$types = $db->loadColumn();

		$options = array();

		foreach ($types as $type)
		{
			$options[] = JHtml::_('select.option', $type, 'COM_INSTALLER_TYPE_' . strtoupper($type));
		}

		return $options;
	}

	/**
	 * Get a list of filter options for the extension types.
	 *
	 * @return  array  An array of stdClass objects.
	 *
	 * @since   3.0
	 */
	public static function getExtensionGroupes()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT folder')
			->from('#__extensions')
			->where('folder != ' . $db->quote(''))
			->order('folder');
		$db->setQuery($query);
		$folders = $db->loadColumn();

		$options = array();

		foreach ($folders as $folder)
		{
			$options[] = JHtml::_('select.option', $folder, $folder);
		}

		return $options;
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return  JObject
	 *
	 * @since   1.6
	 * @deprecated  3.2  Use JHelperContent::getActions() instead
	 */
	public static function getActions()
	{
		// Log usage of deprecated function
		JLog::add(__METHOD__ . '() is deprecated, use JHelperContent::getActions() with new arguments order instead.', JLog::WARNING, 'deprecated');

		// Get list of actions
		$result = JHelperContent::getActions('com_installer');

		return $result;
	}
}
