<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.13 2018-04-04
 *
 * @package     iCagenda.Site
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

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Year frontend search filter.
 */
class JFormFieldYear extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	3.6.0
	 */
	protected $type = 'year';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	3.6.0
	 */
	public function getOptions()
	{
		$params = JFactory::getApplication()->getParams();

		$session = JFactory::getSession();

		$app            = JFactory::getApplication();
		$params         = $app->getParams();

		$filters_mode   = ($app->getMenu()->getActive()->params->get('search_filters') == 1)
						? $params->get('filters_mode', 1)
						: JComponentHelper::getParams('com_icagenda')->get('filters_mode', 1);

		$session_options = $session->get('filter_year_options');

		$filters_mode_session = $session->get('filters_mode_session');

		if ($session_options && $filters_mode == $filters_mode_session)
		{
			rsort($session_options);

			return $session_options;
		}

		// Initialize variables.
		$options = array();

		$filterTime = ($filters_mode == 1)
					? $params->get('time', 0)
					: '0';

		$dates = icagendaEventsData::getAllDates($filterTime);

		if (count($dates) < 1)
		{
			$year = $app->input->get('filter_year', '');

			if ($year)
			{
				$year_option = new stdClass;
				$year_option->value = $year;
				$year_option->label = $year;

				if ( ! in_array($year_option, $options))
				{
					$options[] = $year_option;
				}
			}
		}
		else
		{
			foreach ($dates AS $date)
			{
				$year       = substr($date, 0, 4);
				$year		= ((int) $year > 0)
							? $year
							: '';

				if ($year)
				{
					$year_option = new stdClass;
					$year_option->value = $year;
					$year_option->label = $year;

					if ( ! in_array($year_option, $options))
					{
						$options[] = $year_option;
					}
				}
			}
		}

		rsort($options);

		$session->set('filter_year_options', $options);
		$session->set('filters_mode_session', $filters_mode);

		return $options;
	}
}
