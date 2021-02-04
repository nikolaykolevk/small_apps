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
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * View class Admin - Edit a Feature - iCagenda
 */
class iCagendaViewFeature extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');


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

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		if (isset($this->item->checked_out))
		{
			$checkedOut	= ! ($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		}
		else
		{
			$checkedOut = false;
		}

		$canDo = iCagendaHelper::getActions();

		// Set Title
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JToolBarHelper::title($isNew ? 'iCagenda - ' . JText::_('COM_ICAGENDA_LEGEND_NEW_FEATURE') : 'iCagenda - ' . JText::_('COM_ICAGENDA_LEGEND_EDIT_FEATURE'), 'feature.png');
		}
		else
		{
			JToolBarHelper::title($isNew ? 'iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_LEGEND_NEW_FEATURE') . '</span>'  : 'iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_LEGEND_EDIT_FEATURE') . '</span>' , $isNew ? 'new' : 'pencil-2');
		}

		$icTitle = $isNew ? JText::_('COM_ICAGENDA_LEGEND_NEW_FEATURE') : JText::_('COM_ICAGENDA_LEGEND_EDIT_FEATURE');

		$sitename = $app->getCfg('sitename');
		$title    = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;

		$document->setTitle($title);

		// If not checked out, can save the item.
		if ( ! $checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('feature.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('feature.save', 'JTOOLBAR_SAVE');
		}

		if ( ! $checkedOut && ($canDo->get('core.create')))
		{
			JToolBarHelper::custom('feature.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}

		// If an existing item, can save to a copy.
		if ( ! $isNew && $canDo->get('core.create'))
		{
			JToolBarHelper::custom('feature.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		if (empty($this->item->id))
		{
			JToolBarHelper::cancel('feature.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('feature.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
