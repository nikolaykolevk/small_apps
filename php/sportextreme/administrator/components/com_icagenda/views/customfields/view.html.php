<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-26
 *
 * @package     iCagenda.Admin
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * View class Admin - List of Custom Fields - iCagenda.
 */
class iCagendaViewCustomfields extends JViewLegacy
{
	protected $params;
	protected $state;
	protected $items;
	protected $pagination;
	protected $cfGroups;

	/**
	 * Display the view
	 *
	 * @since   3.4.0
	 */
	public function display($tpl = null)
	{
		// Joomla 2.5
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			jimport('joomla.environment.request');

			JHtml::stylesheet( 'com_icagenda/icagenda-back.j25.css', false, true );
		}

		$this->params     = JComponentHelper::getParams('com_icagenda');

		$this->state      = $this->get('State');
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->cfGroups   = $this->get('CustomFieldGroups');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);

			return false;
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();

			if (version_compare(JVERSION, '3.0', 'ge'))
			{
				$this->sidebar = JHtmlSidebar::render();
			}
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   3.4.0
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/icagenda.php';

		$state  = $this->get('State');
		$user   = JFactory::getUser();
		$userId = $user->get('id');
		$canDo  = iCagendaHelper::getActions();

		// Set Title
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JToolBarHelper::title('iCagenda - ' . JText::_('COM_ICAGENDA_CUSTOMFIELDS'), 'customfields.png');
		}
		else
		{
			JToolBarHelper::title('iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_CUSTOMFIELDS') . '</span>', 'list-2');
		}

		$icTitle = JText::_('COM_ICAGENDA_CUSTOMFIELDS');

		$document = JFactory::getDocument();
		$app      = JFactory::getApplication();
		$sitename = $app->getCfg('sitename');
		$title    = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;

		$document->setTitle($title);

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/customfield';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('customfield.add','JTOOLBAR_NEW');
			}

			if ($canDo->get('core.edit'))
			{
				JToolBarHelper::editList('customfield.edit','JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('customfields.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('customfields.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			else
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'customfields.delete','JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('customfields.archive','JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('customfields.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'customfields.delete','JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('customfields.trash','JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_icagenda');
		}

		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			JHtmlSidebar::setAction('index.php?option=com_icagenda&view=customfields');

			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
			);

			JHtmlSidebar::addFilter(
				JText::_('COM_ICAGENDA_CUSTOMFIELDS_FILTER_OPTION_SELECT_PARENT_FORM'),
				'filter_parent_form',
				JHtml::_('select.options', $this->get('ParentForm'), 'value', 'text', $this->state->get('filter.parent_form'), true)
			);

			JHtmlSidebar::addFilter(
				JText::_('COM_ICAGENDA_CUSTOMFIELDS_FILTER_OPTION_SELECT_GROUP'),
				'filter_group',
				JHtml::_('select.options', $this->get('Groups'), 'value', 'text', $this->state->get('filter.group'), true)
			);

			JHtmlSidebar::addFilter(
				JText::_('COM_ICAGENDA_CUSTOMFIELDS_FILTER_OPTION_SELECT_TYPE'),
				'filter_type',
				JHtml::_('select.options', $this->get('FieldTypes'), 'value', 'text', $this->state->get('filter.type'), true)
			);
		}
	}
}
