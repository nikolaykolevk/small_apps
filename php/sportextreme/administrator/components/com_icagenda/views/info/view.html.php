<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-30
 *
 * @package     iCagenda.Admin
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.2.6
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport('joomla.filesystem.path');

// Access check.
if (JFactory::getUser()->authorise('core.admin', 'com_icagenda'))
{
	JToolBarHelper::preferences('com_icagenda');
}

/**
 * View class for a list of iCagenda.
 */
class iCagendaViewinfo extends JViewLegacy
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Joomla 2.5
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JHtml::stylesheet('com_icagenda/template.j25.css', false, true);

			JHtml::_('behavior.tooltip');
		}

		JHtml::_('behavior.modal');

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
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/icagenda.php';

		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$state = $this->get('State');

		// Set Title
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JToolBarHelper::title(JText::_('COM_ICAGENDA_TITLE_ICAGENDA_IMAGE'));
		}
		else
		{
			JToolBarHelper::title('iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_INFO') . '</span>', 'info-2');
		}

		$icTitle = JText::_('COM_ICAGENDA_INFO');

		$sitename = $app->getCfg('sitename');
		$title    = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;

		$document->setTitle($title);
	}
}
