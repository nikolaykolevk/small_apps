<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-28
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities
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

/**
 * class icagendaEvent
 */
class icagendaEvent
{
	/**
	 * Function to get Event Params
	 *
	 * @return  JRegistry Event Params
	 *
	 * @since   3.6.0
	 */
	public static function evtParams($params)
	{
		$evtParams = new JRegistry($params);

		return $evtParams;
	}

	/**
	 * Function to return the back arrow button (No item needed)
	 *
	 * @return  HTML
	 *
	 * @since   3.6.0
	 */
	public static function backArrow($item = null)
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;
		$jview  = $jinput->get('view');

		// Get Current Itemid
		$this_itemid = $jinput->getInt('Itemid', 0);

		// TODO: Remove jlayout control (3.6)
		$jlayout       = $jinput->get('layout', '');
		$layouts_array = array('event', 'registration');
		$layout        = in_array($jlayout, $layouts_array) ? $jlayout : '';

		$manageraction = $jinput->get('manageraction', '');
		$referer       = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

		// RTL css if site language is RTL
		$lang      = JFactory::getLanguage();
		$back_icon = ($lang->isRTL()) ? 'iCicon iCicon-nextic' : 'iCicon iCicon-backic';

		if ( ($layout != '' || $jview == 'event')
			&& strpos($referer,'registration') === false
			&& ! $manageraction)
		{
			if ($referer != "")
			{
				$BackArrow = '<a class="iCtip" href="' . str_replace(array('"', '<', '>', "'"), '', $referer) .'" title="' . JText::_('COM_ICAGENDA_BACK') . '"><span class="' . $back_icon . '"></span> <span class="small">' . JText::_('COM_ICAGENDA_BACK') .'</span></a>';
			}
			else
			{
				$BackArrow = '';

				return false;
			}
		}
		elseif ($manageraction || strpos($referer,'registration') !== false)
		{
			$BackArrow = '<a class="iCtip" href="' . JRoute::_('index.php?option=com_icagenda&Itemid=' . (int)$this_itemid) .'" title="' . JText::_('COM_ICAGENDA_BACK') . '"><span class="' . $back_icon . '"></span> <span class="small">' . JText::_('COM_ICAGENDA_BACK') . '</span></a>';
		}
		else
		{
			return false;
		}

		return $BackArrow;
	}

	/**
	 * Set event Url
	 *
	 * @since   3.6.0
	 */
	public static function url($id, $alias = null)
	{
		$app    = JFactory::getApplication();
		$isSef  = $app->getCfg('sef');
		$jinput = $app->input;

		$menuID = $jinput->get('Itemid', 0);
		$date   = $jinput->get('date', '');
		$view   = $jinput->get('view', '');

		// Get var 'event_date' set to session in event details view
		$session    = JFactory::getSession();
		$event_date = $session->get('event_date', '');

		$event_slug = empty($alias) ? (int) $id : (int) $id . ':' . $alias;
		$this_date  = $event_date ? date('Y-m-d-H-i', strtotime($event_date)) : $date;

		$url = JRoute::_('index.php?option=com_icagenda&view=event&id=' . $event_slug . '&Itemid=' . (int) $menuID);

		if (is_numeric($menuID) && is_numeric($id)
			&& ! is_array($menuID) && ! is_array($id))
		{
			$date_var = ($isSef == '1') ? '?date=' : '&amp;date=';
			$url      = ($view != 'list' && $this_date) ? $url . $date_var . $this_date : $url;
		}
		else
		{
			$url = JRoute::_('index.php');
		}

		return $url;
	}

	/**
	 * Title + Manager Icons
	 *
	 * @since   3.6.0
	 */
	public static function titleBar($i)
	{
		$jinput = JFactory::getApplication()->input;

		$this_itemid       = $jinput->getInt('Itemid');
		$list_title_length = (int) JComponentHelper::getParams('com_icagenda')->get('list_title_length', '');

		$i_title = icagendaRender::titleToFormat($i->title);

		$jlayout       = $jinput->get('layout', '');
		$layouts_array = array('event', 'registration');
		$layout        = in_array($jlayout, $layouts_array) ? $jlayout : '';

		$mbString = extension_loaded('mbstring');

		$title_length = $mbString ? mb_strlen($i_title, 'UTF-8') : strlen($i_title);

		if (empty($layout)
			&& ! empty($list_title_length))
		{
			$title  = $mbString
					? trim(mb_substr($i_title, 0, $list_title_length, 'UTF-8'))
					: trim(substr($i_title, 0, $list_title_length));

			$new_title_length = $mbString ? mb_strlen($title, 'UTF-8') : strlen($title);

			if ($new_title_length < $title_length)
			{
				$title.= '...';
			}
		}
		else
		{
			$title = $i_title;
		}

		$approval = $i->approval;

		$event_slug = empty($i->alias) ? $i->id : $i->id . ':' . $i->alias;

		// Set Manager Actions Url
		$managerActionsURL = 'index.php?option=com_icagenda&view=event&id=' . $event_slug . '&Itemid=' . $this_itemid;

		$unapproved = '<a class="iCtip" href="' . JRoute::_($managerActionsURL) . '" title="' . JText::_('COM_ICAGENDA_APPROVE_AN_EVENT_LBL') . '"><small><span class="iCicon-open-details"></span></small></a>';

		if ($title != NULL && $approval == 1)
		{
			return $title . ' ' . $unapproved;
		}
		elseif ($title != NULL && $approval != 1)
		{
			return $title;
		}

		return NULL;
	}

	/**
	 * Next Date Text
	 *
	 * @since   3.6.0
	 */
	public static function dateText($i)
	{
		$eventTimeZone = null;

		$dates         = iCString::isSerialized($i->dates) ? unserialize($i->dates) : array(); // returns array
		$period        = iCString::isSerialized($i->period) ? unserialize($i->period) : array(); // returns array
		$weekdays      = $i->weekdays;
		$startdatetime = iCDate::isDate($i->startdate) ? date('Y-m-d H:i', strtotime($i->startdate)) : '';

		$site_date     = JHtml::date('now', 'Y-m-d');
		$site_datetime = JHtml::date('now', 'Y-m-d H:i');

		$alldates_array = array_merge($dates, $period);
 		$alldates       = array_filter($alldates_array, function($var) {return $var == iCDate::isDate($var);});

		$next_date     = date('Y-m-d', strtotime($i->next));
		$next_datetime = date('Y-m-d H:i', strtotime($i->next));

		$next_is_in_period = in_array($next_datetime, $period) ? true : false;

		$totDates = count($alldates);

		if ($totDates > 1
			&& $next_date > $site_date)
		{
			rsort($alldates);

			$last_date = JHtml::date($alldates[0], 'Y-m-d', $eventTimeZone);

			if ( ! $next_is_in_period
				&& $last_date == $next_date)
			{
				$dateText = JText::_('COM_ICAGENDA_EVENT_DATE_LAST');
			}
			elseif ( ! $next_is_in_period)
			{
				$dateText = JText::_('COM_ICAGENDA_EVENT_DATE_FUTUR');
			}
			elseif ($next_is_in_period
				&& $weekdays == NULL)
			{
				$dateText = JText::_('COM_ICAGENDA_LEGEND_DATES');
			}
			else
			{
				$dateText = JText::_('COM_ICAGENDA_EVENT_DATE');
			}
		}
		elseif ($totDates > 1
			&& $next_date < $site_date)
		{
			if ($totDates == 2)
			{
				$dateText   = $next_is_in_period
							? JText::_('COM_ICAGENDA_EVENT_DATE')
							: JText::_('COM_ICAGENDA_EVENT_DATE_PAST');
			}
			else
			{
				$dateText   = ($next_is_in_period && $weekdays == NULL)
							? JText::_('COM_ICAGENDA_LEGEND_DATES')
							: JText::_('COM_ICAGENDA_EVENT_DATE_PAST');
			}
		}
		elseif ($next_date == $site_date)
		{
			$dateText   = ($next_is_in_period && ($next_datetime < $site_datetime || $next_datetime != $startdatetime))
						? JText::_('COM_ICAGENDA_EVENT_DATE_PERIOD_NOW')
						: JText::_('COM_ICAGENDA_EVENT_DATE_TODAY');
		}
		else
		{
			$dateText = JText::_( 'COM_ICAGENDA_EVENT_DATE' );
		}

		return $dateText;
	}

	/**
	 * Get Next Date (or Last Date)
	 *
	 * @since   3.4.0
	 */
	public static function nextDate($evt, $i)
	{
		$eventTimeZone = null;

//		$singledates   = iCString::isSerialized($i->dates) ? unserialize($i->dates) : array(); // returns array
		$period        = iCString::isSerialized($i->period) ? unserialize($i->period) : array(); // returns array
		$startdatetime = $i->startdate;
		$enddatetime   = $i->enddate;
		$weekdays      = $i->weekdays;

		$site_date      = JHtml::date('now', 'Y-m-d');
		$UTC_today_date = JHtml::date('now', 'Y-m-d', $eventTimeZone);

		$next_date     = JHtml::date($evt, 'Y-m-d', $eventTimeZone);
		$next_datetime = JHtml::date($evt, 'Y-m-d H:i', $eventTimeZone);

		$start_date = JHtml::date($i->startdate, 'Y-m-d', $eventTimeZone);
		$end_date   = JHtml::date($i->enddate, 'Y-m-d', $eventTimeZone);

		// Check if date from a period with weekdays has end time of the period set in next.
//		$time_next_datetime = JHtml::date($next_datetime, 'H:i', $eventTimeZone);
		$time_next_datetime = date('H:i', strtotime($next_datetime));
		$time_startdate     = JHtml::date($i->startdate, 'H:i', $eventTimeZone);
		$time_enddate       = JHtml::date($i->enddate, 'H:i', $eventTimeZone);

		$data_next_datetime = date('Y-m-d H:i', strtotime($evt));

		if ($next_date == $site_date
			&& $time_next_datetime == $time_enddate)
		{
			$next_datetime = $next_date . ' ' . $time_startdate;
		}

		if ($period != NULL
			&& in_array($data_next_datetime, $period))
		{
			$next_is_in_period = true;
		}
		else
		{
			$next_is_in_period = false;
		}

		// Highlight event in progress
		if ($next_date == $site_date)
		{
			$start_span = '<span class="ic-next-today">';
			$end_span   = '</span>';
		}
		else
		{
			$start_span = $end_span = '';
		}

		$separator = '<span class="ic-datetime-separator"> - </span>';

		// Format Next Date
		if ($next_is_in_period
			&& ($start_date == $end_date || $weekdays != null))
		{
			// Next in the period & (same start/end date OR one or more weekday selected)
			$nextDate = $start_span;
			$nextDate.= '<span class="ic-single-startdate">';
			$nextDate.= icagendaRender::dateToFormat($evt);
			$nextDate.= '</span>';

			if ($i->displaytime == 1)
			{
				$nextDate.= ' <span class="ic-single-starttime">' . icagendaRender::dateToTime($i->startdate) . '</span>';

				if (icagendaRender::dateToTime($i->startdate) != icagendaRender::dateToTime($i->enddate))
				{
					$nextDate.= $separator . '<span class="ic-single-endtime">' . icagendaRender::dateToTime($i->enddate) . '</span>';
				}
			}

			$nextDate.= $end_span;
		}
		elseif ($next_is_in_period
			&& ($weekdays == null))
		{
			// Next in the period & different start/end date & no weekday selected
			$start = '<span class="ic-period-startdate">';
			$start.= icagendaRender::dateToFormat($i->startdate);
			$start.= '</span>';

			$end = '<span class="ic-period-enddate">';
			$end.= icagendaRender::dateToFormat($i->enddate);
			$end.= '</span>';

			if ($i->displaytime == 1)
			{
				$start.= ' <span class="ic-period-starttime">' . icagendaRender::dateToTime($i->startdate) . '</span>';

				$end.= ' <span class="ic-period-endtime">' . icagendaRender::dateToTime($i->enddate) . '</span>';
			}

			$nextDate = $start_span . $start . $separator . $end . $end_span;
		}
		else
		{
			// Next is a single date
			$nextDate = $start_span;
			$nextDate.= '<span class="ic-single-next">';
			$nextDate.= icagendaRender::dateToFormat($evt);
			$nextDate.= '</span>';

			if ($i->displaytime == 1)
			{
				$nextDate.= ' <span class="ic-single-starttime">' . icagendaRender::dateToTime($evt) . '</span>';
			}

			$nextDate.= $end_span;
		}

		return $nextDate;
	}

	/*
	 * Function to detect if info details exist in an event,
	 * and to hide or show it depending of Options (display and access levels)
	 *
	 * @since   3.6.0
	 */
	public static function infoDetails($item, $CUSTOM_FIELDS)
	{
		// Hide/Show Option
		$infoDetails = JComponentHelper::getParams('com_icagenda')->get('infoDetails', 1);

		// Access Levels Option
		$accessInfoDetails = JComponentHelper::getParams('com_icagenda')->get('accessInfoDetails', 1);

		if ( ($infoDetails == 1 && icagendaEvents::accessLevels($accessInfoDetails))
			&& ( ($item->params->get('statutReg', '') == '1' && $item->params->get('maxReg'))
				|| $item->phone
				|| $item->email
				|| $item->website
				|| $item->address
				|| $item->file
				|| $CUSTOM_FIELDS )
			)
		{
			return true;
		}

		return false;
	}

	/*
	 * Function to return a list of all single dates, HTML formatted.
	 * TO BE REFACTORED
	 *
	 * @since   3.6.0
	 */
	public static function displayListSingleDates($item)
	{
		$iCparams   = JComponentHelper::getParams('com_icagenda');
		$timeformat = JFactory::getApplication()->getParams()->get('timeformat', 1);

		// Hide/Show Option
		$SingleDates = $iCparams->get('SingleDates', 1);

		// Access Levels Option (to be checked!)
//		$accessSingleDates = $iCparams->get('accessSingleDates', 1);

		// Order by Dates
		$SingleDatesOrder = $iCparams->get('SingleDatesOrder', 1);

		// List Model
		$SingleDatesListModel = $iCparams->get('SingleDatesListModel', 1);

		if ($SingleDates == 1)
		{
//			if ($this->accessLevels($accessSingleDates))
//			{
				$days = iCString::isSerialized($item->dates) ? unserialize($item->dates) : array(); // returns array

				if ($SingleDatesOrder == 1)
				{
					rsort($days);
				}
				elseif ($SingleDatesOrder == 2)
				{
					sort($days);
				}

				$totDates = count($days);

				if ($timeformat == 1)
				{
					$lang_time = 'H:i';
				}
				else
				{
					$lang_time = 'h:i A';
				}

				// Detect if Singles Dates, and no single date with null value
				$displayDates = false;
				$nbDays       = count($days);

				foreach ($days as $k => $d)
				{
					if (iCDate::isDate($d) && $nbDays != 0)
					{
						$displayDates = true;
					}
				}

				$daysUl = '';

				if ($displayDates)
				{
					if ($SingleDatesListModel == '2')
					{
						$n = 0;
						$daysUl.= '<div class="alldates"><i>' . JText::_('COM_ICAGENDA_LEGEND_DATES') . ': </i>';

						foreach ($days as $k => $d)
						{
							$n  = $n+1;
							$fd = icagendaRender::dateToFormat($d);

							$timeDate   = ($item->displaytime == 1)
										? ' <span class="evttime">' . date($lang_time, strtotime($d)) . '</span>'
										: '';

							if ($n <= ($totDates-1))
							{
								$daysUl.= '<span class="alldates">' . $fd . $timeDate . '</span> - ';
							}
							elseif ($n == $totDates)
							{
	   							$daysUl.= '<span class="alldates">' . $fd . $timeDate . '</span>';
							}
						}

						$daysUl.= '</div>';
					}
					else
					{
						$daysUl.= '<ul class="alldates">';

						foreach ($days as $k => $d)
						{
							$fd = icagendaRender::dateToFormat($d);

							$timeDate   = ($item->displaytime == 1)
										? ' <span class="evttime">' . date($lang_time, strtotime($d)) . '</span>'
										: '';

							$daysUl.= '<li class="alldates">' . $fd . $timeDate . '</li>';
						}

						$daysUl.= '</ul>';
					}
				}

				if ($totDates > '0')
				{
					return $daysUl;
				}
				else
				{
					return false;
				}
//			}
//			else
//			{
//				return false;
//			}
		}
		else
		{
			return false;
		}
	}

	/*
	 * Function to display the period text width formatted dates (eg. from 00-00-0000 to 00-00-0000).
	 * TO BE REFACTORED
	 * @TODO remove inline style html tags (check css and add class declarations)
	 * @TODO (3.7) remove old deprecated class names
	 *
	 * @since   3.6.0
	 */
	public static function displayPeriodDates($item)
	{
		$iCparams = JComponentHelper::getParams('com_icagenda');

		// Hide/Show Option
		$PeriodDates = $iCparams->get('PeriodDates', 1);

		// List Model
		$SingleDatesListModel = $iCparams->get('SingleDatesListModel', 1);

		// First day of the week
		$firstday_week_global = $iCparams->get('firstday_week_global', 1);

		// Predefined variables
		$wdays = $showDays = $timeOneDay = $end = '';

		// WeekDays
		$weekdays    = $item->weekdays;
		$weekdaysall = empty($weekdays) ? true : false;

		if ($firstday_week_global == '1')
		{
			$weekdays_array = explode (',', $weekdays);

			if (in_array('0', $weekdays_array))
			{
				$weekdays = str_replace('0', '', $weekdays);
				$weekdays = $weekdays . ',7';
			}
		}

		if ( ! $weekdaysall)
		{
			$weekdays_array = explode (',', $weekdays);
			$wdaysArray     = array();

			foreach ($weekdays_array AS $wd)
			{
				if ($firstday_week_global != '1')
				{
					if ($wd == 0) $wdaysArray[] = JText::_('SUNDAY');
				}
				if ($wd == 1) $wdaysArray[] = JText::_('MONDAY');
				if ($wd == 2) $wdaysArray[] = JText::_('TUESDAY');
				if ($wd == 3) $wdaysArray[] = JText::_('WEDNESDAY');
				if ($wd == 4) $wdaysArray[] = JText::_('THURSDAY');
				if ($wd == 5) $wdaysArray[] = JText::_('FRIDAY');
				if ($wd == 6) $wdaysArray[] = JText::_('SATURDAY');
				if ($firstday_week_global == '1')
				{
					if ($wd == 7) $wdaysArray[] = JText::_('SUNDAY');
				}
			}

			$last  = array_slice($wdaysArray, -1);
			$first = join(', ', array_slice($wdaysArray, 0, -1));
			$both  = array_filter(array_merge(array($first), $last));

			// RTL css if site language is RTL
			$lang       = JFactory::getLanguage();
			$arrow_list = $lang->isRTL() ? '&#8629;' : '&#8627;';

			$wdays = $arrow_list . ' <small><i><span class="ic-period-weekdays">' . join(' & ', $both) . '</span></i></small>';
		}

		if ($PeriodDates == 1
			&& self::eventHasPeriod($item->period, $item->startdate, $item->enddate)
			)
		{
			$startDate = icagendaRender::dateToFormat($item->startdate);
			$endDate   = icagendaRender::dateToFormat($item->enddate);
			$startTime = icagendaRender::dateToTime($item->startdate);
			$endTime   = icagendaRender::dateToTime($item->enddate);

			if ($startDate == $endDate)
			{
				$start = '<span class="ic-period-startdate">';
				$start.= $startDate;
				$start.= '</span>';

				if ($item->displaytime == 1)
				{
					$timeOneDay = '<span class="evttime ic-period-time">' . $startTime;
					$timeOneDay.= ($startTime !== $endTime) ? ' - ' . $endTime : '';
					$timeOneDay.= '</span>';
				}
			}
			else
			{
				$start = '<span class="ic-period-text-from">'
						. ucfirst(JText::_('COM_ICAGENDA_PERIOD_FROM'))
						. '</span> ';
				$start.= '<span class="ic-period-startdate">'
						. $startDate
						. '</span>';

				if ($item->displaytime == 1)
				{
					$start.= ' <span class="evttime ic-period-starttime">'
							. $startTime
							. '</span>';
				}

				$end = '<span class="ic-period-text-to">'
						. JText::_('COM_ICAGENDA_PERIOD_TO')
						. '</span> ';
				$end.= '<span class="ic-period-enddate">'
						. $endDate
						. '</span>';

				if ($item->displaytime == 1)
				{
					$end.= ' <span class="evttime ic-period-endtime">'
							. $endTime
							. '</span>';
				}

				$showDays = $wdays;
			}

			// Horizontal List
			if ($SingleDatesListModel == 2)
			{
				$period = '<div class="ic-date-horizontal">' . JText::_('COM_ICAGENDA_EVENT_PERIOD') . ': ';
				$period.= $start . ' ' . $end . ' ' . $timeOneDay;

				if ( ! empty($showDays))
				{
					$period.= '<br /><span style="margin-left:30px">' . $showDays . '</span>';
				}

				$period.= '</div>';
			}

			// Vertical List
			else
			{
				$period = '<ul class="ic-date-vertical"><li>';
				$period.= $start . ' ' . $end . ' ' . $timeOneDay;

				if ( ! empty($showDays))
				{
					$period.= '<br/>' . $showDays;
				}

				$period.= '</li></ul>';
			}

			return $period;
		}
		else
		{
			return false;
		}
	}

	/*
	 * Function to check if period dates exist for this event
	 *
	 * @since   3.6.0
	 */
	public static function eventHasPeriod($period, $startdate, $enddate)
	{
		$period_dates = iCString::isSerialized($period) ? unserialize($period) : array(); // returns array

		if (count($period_dates) > 0
			&& iCDate::isDate($startdate)
			&& iCDate::isDate($enddate))
		{
			return true;
		}

		return false;
	}

	/*
	 * Function to check if period is not finished
	 *
	 * @since   3.6.0
	 */
	public static function periodIsNotFinished($enddate)
	{
		$eventTimeZone    = null;
		$datetime_today   = JHtml::date('now', 'Y-m-d H:i');
		$datetime_enddate = JHtml::date($enddate, 'Y-m-d H:i', $eventTimeZone);

		if (strtotime($datetime_enddate) > strtotime($datetime_today))
		{
			return true;
		}

		return false;
	}

	/*
	 * Function to set Meta-title for an event
	 *
	 * @since   3.6.0
	 */
	public static function setMetaTitle($item)
	{
		$limit     = '60';
		$metaTitle = iCFilterOutput::fullCleanHTML($item->title);

		if (strlen($metaTitle) > $limit)
		{
			$string_cut = substr($metaTitle, 0, $limit);
			$last_space = strrpos($string_cut, ' ');
			$string_ok  = substr($string_cut, 0, $last_space);
			$metaTitle  = $string_ok;
		}

		return $metaTitle;
	}

	/*
	 * Function to set Meta-description for an event
	 *
	 * @since   3.6.0
	 */
	public static function setMetaDesc($item)
	{
		$iCparams = JComponentHelper::getParams('com_icagenda');
		$limit    = $iCparams->get('char_limit_meta_description', '320');

		$metaDesc = iCFilterOutput::fullCleanHTML($item->metadesc);
		$metaDesc = (empty($metaDesc)) ? iCFilterOutput::fullCleanHTML($item->desc) : $metaDesc;

		if (strlen($metaDesc) > $limit)
		{
			$string_cut = substr($metaDesc, 0, $limit);
			$last_space = strrpos($string_cut, ' ');
			$string_ok  = substr($string_cut, 0, $last_space);
			$metaDesc   = $string_ok;
		}

		return $metaDesc;
	}

	/*
	 * Function to return event Url
	 *
	 * @since   3.6.0
	 */
	public static function eventURL($i)
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;

		$itemID = $jinput->get('Itemid', '0');

		$eventnumber = $i->id;
		$event_slug  = empty($i->alias) ? $i->id : $i->id . ':' . $i->alias;
		$date        = $i->next;

		// Get the "event" URL
		$baseURL    = JURI::base();
		$subpathURL = JURI::base(true);

		$baseURL    = str_replace('/administrator', '', $baseURL);
		$subpathURL = str_replace('/administrator', '', $subpathURL);

		$urlevent = str_replace('&amp;','&', JRoute::_('index.php?option=com_icagenda&view=event&Itemid=' . (int) $itemID . '&id=' . $event_slug));

		// Sub Path filtering
		$subpathURL = ltrim($subpathURL, '/');

		// URL Event Details filtering
		$urlevent = ltrim($urlevent, '/');

		if (substr($urlevent, 0, strlen($subpathURL)+1) == "$subpathURL/")
		{
			$urlevent = substr($urlevent, strlen($subpathURL)+1);
		}

		$urlevent = rtrim($baseURL,'/') . '/' . ltrim($urlevent,'/');

		$url = $urlevent;

		if (is_numeric($itemID) && is_numeric($eventnumber)
			&& ! is_array($itemID) && ! is_array($eventnumber)
			)
		{
			return $url;
		}
		else
		{
			$url = JRoute::_('index.php');

			return JURI::base() . $url;
		}
	}

	/*
	 * Function to convert a datetime to URL alias
	 * (see iCDate::dateToAlias from iC Library for general function)
	 *
	 * @since   3.6.0
	 */
	public static function urlDateVar($datetime)
	{
		if ( ! iCDate::isDate($datetime)) return false;

		$datetime     = date('Y-m-d H:i', strtotime($datetime));
		$date_explode = explode(' ', $datetime);

		$dateAlias = $date_explode['0'] . '-' . str_replace(':', '-', $date_explode['1']);

		return $dateAlias;
	}

	/*
	 * Function to convert a URL date alias in an SQL datetime string.
	 *
	 * @return  string  The date string in SQL datetime format.
	 *
	 * @since   3.6.0
	 */
	public static function convertDateAliasToSQLDatetime($dateAlias)
	{
		if (strlen(iCDate::dateToNumeric($dateAlias)) != '12') return '';

		$ex         = explode('-', $dateAlias);
		$datetime   = (count($ex) == 5)
					? $ex['0'] . '-' . $ex['1'] . '-' . $ex['2'] . ' ' . $ex['3'] . ':' . $ex['4'] . ':00'
					: '';

		return $datetime;
	}

	/*
	 * Function to generate the read more for introduction description
	 *
	 * @since   3.6.0
	 */
	public static function readMore ($url, $desc, $content = '')
	{
		$iCparams    = JComponentHelper::getParams('com_icagenda');
		$limitGlobal = $iCparams->get('limitGlobal', 0);

		if ($limitGlobal == 1)
		{
			$limit = $iCparams->get('ShortDescLimit', '100');
		}
		elseif ($limitGlobal == 0)
		{
			$customlimit = $iCparams->get('limit', '100');

			$limit = is_numeric($customlimit) ? $customlimit : $iCparams->get('ShortDescLimit', '100');
		}

		$limit = is_numeric($limit) ? $limit : '1';

		$readmore = '';

		$readmore = ($limit <= 1) ? '' : $content;
		$text     = preg_replace('/<img[^>]*>/Ui', '', $desc);

		if (strlen($text) > $limit)
		{
			$string_cut = substr($text, 0, $limit);
			$last_space = strrpos($string_cut, ' ');
			$string_ok  = substr($string_cut, 0, $last_space);
			$text       = $string_ok . ' ';
			$url        = $url;
			$text       = '<a href="' . $url . '" class="more">' . $readmore . '</a>';
		}
		else
		{
			$text = '';
		}

		return $text;
	}

	/**
	 * Loads the list of filled custom fields for this event
	 *
	 * @return  array
	 *
	 * @since   3.6.0
	 */
	public static function getCustomFields($id = null)
	{
		$customFields = icagendaEventData::loadEventCustomFields($id);

		foreach ($customFields as $cf)
		{
			if ($cf->title && $cf->value)
			{
				switch ($cf->type)
				{
					case 'url':
						$cf->value = iCRender::urlTag($cf->value);
						break;

					case 'email':
						$cf->value = JHtml::_('email.cloak', $cf->value);
						break;

					default:
						$cf->value = $cf->value;
						break;
				}
			}
		}

		return $customFields;
	}

	/**
	 * Function to get custom field groups of an event
	 *
	 * @param   integer  $id  Event id
	 *
	 * @return  array
	 *
	 * @since   3.6.0
	 */
	public static function getCustomfieldGroups($id = null)
	{
		// Create a new query object.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('e.params')
			->from('#__icagenda_events AS e')
			->where($db->qn('id') . ' = ' . $db->q($id));
		$db->setQuery($query);

		$result = json_decode($db->loadResult());

		if (isset($result->custom_form))
		{
			return $result->custom_form;
		}

		return false;
	}

	/**
	 * Function to get event params
	 *
	 * @param   integer  $id  Event id
	 *
	 * @return  object
	 *
	 * @since   3.6.5
	 */
	public static function getParams($id = null)
	{
		$db     = JFactory::getDbo();

		$query  = $db->getQuery(true)
				->select('e.params')
				->from($db->qn('#__icagenda_events', 'e'))
				->where($db->qn('e.id') . ' = ' . (int) $id);

		$db->setQuery($query);

		$params = json_decode($db->loadResult(), true);

		$evtParams = new JRegistry($params);

		return $evtParams;
	}
}
