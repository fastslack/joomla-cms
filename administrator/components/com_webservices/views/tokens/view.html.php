<?php
/**
 * @package     Webservices.Admin
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tokens View
 *
 * @package     Webservices.Admin
 * @subpackage  Views
 * @since       1.0
 */
class WebservicesViewTokens extends JViewLegacy
{
	/**
	 * @var  array
	 */
	protected $items;

	/**
	 * @var  object
	 */
	protected $state;

	/**
	 * @var  JPagination
	 */
	protected $pagination;

	/**
	 * Display method
	 *
	 * @param   string  $tpl  template name
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		require_once JPATH_COMPONENT.'/helpers/webservices.php';

		// Get items
		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		$this->pagination = $this->get('Pagination');

		WebservicesHelper::addSubmenu('webservices');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Get the view title.
	 *
	 * @return  string  The view title.
	 */
	public function getTitle()
	{
		return JText::_('COM_WEBSERVICES_TOKENS_LIST_TITLE');
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/webservices.php';

		$state	= $this->get('State');
		$canDo	= WebservicesHelper::getActions($state->get('filter.category_id'));
		$user	= JFactory::getUser();
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_WEBSERVICES_MANAGER_TOKENS'), 'webservices.png');
		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('token.add');
		}

		if ($canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'token.delete');
		}

		JToolbarHelper::divider();

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_webservices');
		}

		JToolbarHelper::help('JHELP_COMPONENTS_WEBSERVICES_LINKS');

		JHtmlSidebar::setAction('index.php?option=com_webservices&view=tokens');

/*
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_CATEGORY'),
			'filter_category_id',
			JHtml::_('select.options', JHtml::_('category.options', 'com_weblinks'), 'value', 'text', $this->state->get('filter.category_id'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);

		JHtmlSidebar::addFilter(
		JText::_('JOPTION_SELECT_TAG'),
		'filter_tag',
		JHtml::_('select.options', JHtml::_('tag.options', true, true), 'value', 'text', $this->state->get('filter.tag'))
		);
*/
	}
}
