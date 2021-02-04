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
 * @since       2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * View class Admin - Mail Newsletter - iCagenda
 */
class iCagendaViewMail extends JViewLegacy
{
	protected $data;

	protected $state;

	protected $item;

	protected $form;

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

			JHtml::_('behavior.mootools');

			$app      = JFactory::getApplication();
			$document = JFactory::getDocument();

			// load jQuery, if not loaded before
			$scripts = array_keys($document->_scripts);
			$scriptFound = false;

			for ($i = 0; $i < count($scripts); $i++)
			{
				if (stripos($scripts[$i], 'jquery.min.js') !== false
					|| stripos($scripts[$i], 'jquery.js') !== false)
				{
					$scriptFound = true;
				}
			}

			// jQuery Library Loader
			if (!$scriptFound)
			{
				// load jQuery, if not loaded before
				if (!$app->get('jquery'))
				{
					$app->set('jquery', true);

					// Add jQuery Library
					$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');
					JHtml::script('com_icagenda/jquery.noconflict.js', false, true);
				}
			}
		}

		$this->form = $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);

			return false;
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$jinput   = $app->input;

		$jinput->set('hidemainmenu', true);

		$user = JFactory::getUser();

		$canDo = iCagendaHelper::getActions();

		// Set Title
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JToolBarHelper::title(JText::_('COM_ICAGENDA_TITLE_MAIL'), 'mail.png');
		}
		else
		{
			JToolBarHelper::title('iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_TITLE_MAIL') . '</span>', 'mail');
		}

		$icTitle = JText::_('COM_ICAGENDA_TITLE_MAIL');

		$sitename = $app->getCfg('sitename');
		$title    = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;

		$document->setTitle($title);

		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JToolBarHelper::custom('mail.send', 'forward.png', 'forward.png', 'ICAGENDA_JTOOLBAR_SEND', false);
		}
		else
		{
			JToolbarHelper::custom('mail.send', 'envelope.png', 'send_f2.png', 'ICAGENDA_JTOOLBAR_SEND', false);
		}

		JToolBarHelper::cancel('mail.cancel', 'JTOOLBAR_CLOSE');
	}
}
