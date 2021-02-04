<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.7 2019-01-06
 *
 * @package     iCagenda.Admin
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * View class Admin - List of Registrations - iCagenda
 */
class iCagendaViewRegistrations extends JViewLegacy
{
	protected $params;
	protected $state;
	protected $items;
	protected $pagination;
	protected $events;
	protected $dates;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Joomla 2.5
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			jimport( 'joomla.environment.request' );

			JHtml::stylesheet('com_icagenda/template.j25.css', false, true);
			JHtml::stylesheet('com_icagenda/icagenda-back.j25.css', false, true);
		}

		$this->params     = JComponentHelper::getParams('com_icagenda');
		$this->state      = $this->get('State');
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->events     = $this->get('Events');
		$this->dates      = $this->get('Dates');

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
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/icagenda.php';

		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$state = $this->get('State');
		$canDo = iCagendaHelper::getActions();

		// Set Title
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JToolBarHelper::title('iCagenda - ' . JText::_('COM_ICAGENDA_TITLE_REGISTRATION'), 'registration.png');
		}
		else
		{
			JToolBarHelper::title('iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_TITLE_REGISTRATION') . '</span>', 'users');
		}

		$icTitle = JText::_('COM_ICAGENDA_TITLE_REGISTRATION');

		$sitename = $app->getCfg('sitename');
		$title    = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;

		$document->setTitle($title);

		//Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/registration';

		if (file_exists($formPath))
		{
			$bar = JToolBar::getInstance('toolbar');

			// Add Export Button to the ToolBar
			if (version_compare(JVERSION, '3.7.0', 'lt'))
			{
				// Joomla 2.5
				$bar->appendButton('Popup', 'export', 'JTOOLBAR_EXPORT', 'index.php?option=com_icagenda&amp;view=download&amp;tmpl=component', 600, 300);

				JToolBarHelper::divider();
			}
			else
			{
				// Instantiate a new JLayoutFile instance and render the export button
				$layout = new JLayoutFile('joomla.toolbar.modal');

				$dhtml  = $layout->render(
					array(
						'selector' => 'downloadModal',
						'icon'     => 'download',
						'text'     => JText::_('JTOOLBAR_EXPORT'),
					)
				);

				$bar->appendButton('Custom', $dhtml, 'download');
			}

			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('registration.add', 'JTOOLBAR_NEW');
			}

			if ($canDo->get('core.edit') || $canDo->get('core.edit.own'))
			{
				JToolBarHelper::editList('registration.edit', 'JTOOLBAR_EDIT');
			}

		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
//				JToolBarHelper::divider();
				JToolBarHelper::custom('registrations.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('registrations.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			else
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'registrations.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('registrations.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('registrations.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'registrations.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('registrations.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_icagenda');
		}

		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			JHtmlSidebar::setAction('index.php?option=com_icagenda&view=registrations');

			JHtmlSidebar::addFilter(
				JText::_('COM_ICAGENDA_REGISTRATIONS_SELECT_STATUS'),
				'filter_published',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state', '1'), true)
			);
			JHtmlSidebar::addFilter(
				JText::_('COM_ICAGENDA_REGISTRATIONS_SELECT_CATEGORY'),
				'filter_categories',
				JHtml::_('select.options', $this->get('Categories'), 'value', 'text', $this->state->get('filter.categories'), true)
			);
			JHtmlSidebar::addFilter(
				JText::_('COM_ICAGENDA_REGISTRATIONS_SELECT_EVENT'),
				'filter_events',
				JHtml::_('select.options', $this->get('Events'), 'value', 'text', $this->state->get('filter.events'), true)
			);
			JHtmlSidebar::addFilter(
				JText::_('COM_ICAGENDA_REGISTRATIONS_SELECT_DATE'),
				'filter_dates',
				JHtml::_('select.options', $this->get('Dates'), 'value', 'text', $this->state->get('filter.dates'), true)
			);
		}
	}
}
