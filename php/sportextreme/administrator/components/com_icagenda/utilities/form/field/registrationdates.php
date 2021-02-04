<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.13 2017-10-14
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

//jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');

if (version_compare(JVERSION, '3.0', 'lt'))
{
	jimport('joomla.application.component.modelform');
	jimport('joomla.registry.registry');
}

use Joomla\Registry\Registry;

/**
 * Application :        SITE
 * Registration form :  dates select list/all dates form field
 */
class icagendaFormFieldRegistrationDates extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since   3.6.0
	 */
	protected $item;

	protected $type = 'registrationdates';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 * @since   3.6.0
	 */
	protected function getInput()
	{
		$app        = JFactory::getApplication();
		$session    = JFactory::getSession();

		$model      = new iCagendaModelRegistration();
		$this->item = $model->getItem();

		$item       = $this->item;
		$typeReg    = $item->params->get('typeReg', '');

		// Get date from url if set, and from session if set
		$url_date       = $app->input->get('date', '');
		$session_date   = $session->get('session_date', '');

		if ($url_date)
		{
			$ex_date		= explode('-', $url_date);
			$defaultDate	= $ex_date[0] . '-' . $ex_date[1] . '-' . $ex_date[2] . ' ' . $ex_date[3] . ':' . $ex_date[4] . ':00';
		}
		else
		{
			$defaultDate	= $session_date;
		}

//		$session->set('session_date', '');

		$value = ! empty($this->value) ? $this->value : $defaultDate;

		$html = '';

		// Return Select list of dates
		if ($typeReg == 1)
		{
			$options = array();

			if ( ! $this->getOptions()) return false;

			if (count($this->getOptions()) > 1)
			{
				foreach ($this->getOptions() as $opt)
				{
					$date_get = explode('@@', $opt);
					$date_value = $date_get[0];
					$date_option = $date_get[1];
//					$options[] = JHTML::_('select.option', $opt->value, $opt->option);
					$options[] = JHtml::_('select.option', $date_value, $date_option);
				}

				$html.= JHtml::_('select.genericlist', $options, $this->name, 'class="select-large"', 'value', 'text', $value, $this->id);

				$html.= '<span class="iCFormTip iCicon iCicon-info-circle" title="';
				$html.= htmlspecialchars('<strong>' . JText::_($this->element['label']) . '</strong><br />'
						. JText::_($this->element['label'] . '_DESC'));
				$html.= '"></span>';

				return $html;
			}
			else
			{
				foreach ($this->getOptions() as $opt)
				{
					$date_get = explode('@@', $opt);
					$date_value = $date_get[0];
					$date_option = $date_get[1];
				}

				$html.= '<input id="' . $this->id . '" type="hidden" name="' . $this->name . '" value="' . $date_value . '" />';
				$html.= '<div style="padding-top: 5px;" class="ic-inlinetext-input">' . $date_option . '</div>';

				return $html;
			}
		}

		// Return for all dates
		else
		{
			$html.= '<div style="float:left">';

			$EVENT_SINGLE_DATES	= icagendaEvent::displayListSingleDates($item);
			$EVENT_PERIOD		= icagendaEvent::displayPeriodDates($item);

			if ($EVENT_PERIOD)
			{
				$html.= $EVENT_PERIOD;
			}

			if ($EVENT_SINGLE_DATES)
			{
				$html.= $EVENT_SINGLE_DATES;
			}

			$html.= '<input id="jform_period" type="hidden" name="jform[period]" value="1" />';
			$html.= '</div>';

			return $html;
		}
	}

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	3.6.0
	 */
	protected function getOptions()
	{
		$options = array();

		if ($this->item)
		{
			$options = icagendaRegistration::formDatesList($this->item);
		}

		return $options;
	}
}
