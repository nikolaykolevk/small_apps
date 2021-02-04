<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.3 2018-07-17
 *
 * @package     iCagenda.Site
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die();

jimport('joomla.application.component.helper');

/**
 * View class Site - Add an Event - iCagenda
 */
class iCagendaViewSubmit extends JViewLegacy
{
	// TODO: check and remove
	protected $return_page;

	protected $state;
	protected $item;
	protected $form;

	protected $params;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;

		// Initialiase variables.
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');

		if (version_compare(JVERSION, '3.2', 'lt'))
		{
			if (JRequest::get('POST')) $this->get('data');
		}
		else
		{
			if ($jinput->post->getArray()) $this->get('data');
		}

		// loading params
		$params = $app->getParams();

		// Shortcuts
		$item = $this->item;

		$this->template = $params->get('template');
		$this->title    = $params->get('title');
		$this->format   = $params->get('format');
		$this->copy     = $params->get('copy');

		$this->submit_imageDisplay        = $params->get('submit_imageDisplay', 1);
		$this->submit_periodDisplay       = $params->get('submit_periodDisplay', 1);
		$this->submit_weekdaysDisplay     = $params->get('submit_weekdaysDisplay', 1);
		$this->submit_datesDisplay        = $params->get('submit_datesDisplay', 1);
		$this->submit_displaytimeDisplay  = $params->get('submit_displaytimeDisplay', 0);
		$this->submit_shortdescDisplay    = $params->get('submit_shortdescDisplay', 1);
		$this->submit_descDisplay         = $params->get('submit_descDisplay', 1);
		$this->submit_metadescDisplay     = $params->get('submit_metadescDisplay', 0);
		$this->submit_venueDisplay        = $params->get('submit_venueDisplay', 1);
		$this->submit_emailDisplay        = $params->get('submit_emailDisplay', 1);
		$this->submit_phoneDisplay        = $params->get('submit_phoneDisplay', 1);
		$this->submit_websiteDisplay      = $params->get('submit_websiteDisplay', 1);
		$this->submit_customfieldsDisplay = $params->get('submit_customfieldsDisplay', 1);
		$this->submit_fileDisplay         = $params->get('submit_fileDisplay', 1);
		$this->submit_gmapDisplay         = $params->get('submit_gmapDisplay', 1);
		$this->submit_regoptionsDisplay   = $params->get('submit_regoptionsDisplay', 1);
		$this->statutReg                  = $params->get('statutReg', 0);
		$this->ShortDescLimit             = $params->get('ShortDescLimit', '160');
		$this->submit_imageMaxSize        = $params->get('submit_imageMaxSize', '800');
		$this->submit_captcha             = $params->get('submit_captcha', 0);
		$this->submit_form_validation     = $params->get('submit_form_validation', '');

		$this->params   = $this->state->get('params');
		$this->iCparams = $this->params;

		// Process the content plugins.
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			$this->dispatcher = JDispatcher::getInstance();
		}
		else
		{
			$this->dispatcher = JEventDispatcher::getInstance();
		}

		JPluginHelper::importPlugin('content');
		$this->dispatcher->trigger('iCagendaOnSubmitPrepare', array ('com_icagenda.submit', &$item, &$this->params));

		$this->pluginEvent = new stdClass;

		$results = $this->dispatcher->trigger('iCagendaOnSubmitBeforeDisplay', array('com_icagenda.submit', &$item, &$this->params));
		$this->pluginEvent->iCagendaOnSubmitBeforeDisplay = trim(implode("\n", $results));

		$results = $this->dispatcher->trigger('iCagendaOnSubmitAfterDisplay', array('com_icagenda.submit', &$item, &$this->params));
		$this->pluginEvent->iCagendaOnSubmitAfterDisplay = trim(implode("\n", $results));


		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);

			return false;
		}

		$this->_prepareDocument();

		icagendaInfo::commentVersion();

		parent::display($tpl);

		// Add CSS
		icagendaTheme::loadComponentCSS($this->template);
		icagendaThemeStyle::addMediaCss($this->template, 'component');

		icagendaForm::loadDateTimePickerJSLanguage();

		$jlayout       = $jinput->get('layout', '');
		$layouts_array = array('event', 'registration');
		$layout        = in_array($jlayout, $layouts_array) ? $jlayout : '';

		if ( ! $layout || $layout == 'submit')
		{
			JHtml::stylesheet('com_icagenda/icagenda.css', false, true);
			JHtml::stylesheet('com_icagenda/jquery-ui-1.8.17.custom.css', false, true);
		}
	}


	protected function _prepareDocument()
	{
		$app     = JFactory::getApplication();
		$menus   = $app->getMenu();
		$pathway = $app->getPathway();

		$title = null;
		$menu  = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description', ''))
		{
			$this->document->setDescription($this->params->get('menu-meta_description', ''));
		}

		if ($this->params->get('menu-meta_keywords', ''))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords', ''));
		}

		if ($app->getCfg('MetaTitle') == '1'
			&& $this->params->get('menupage_title', ''))
		{
			$this->document->setMetaData('title', $this->params->get('page_title', ''));
		}
	}
}
