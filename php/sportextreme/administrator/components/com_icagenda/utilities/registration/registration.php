<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.5 2018-10-02
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
 * class icagendaRegistration
 */
class icagendaRegistration
{
	/**
	 * Function to get total of registered people for one event/date
	 *
	 * @since   3.6.5
	 */
	public static function getRegisteredTotal($eventID, $regDate = null, $typeReg = '1')
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('sum(' . $db->qn('r.people') . ') AS total')
			->from($db->qn('#__icagenda_registration', 'r'))
			->where($db->qn('r.eventid') . ' = ' . (int) $eventID)
			->where($db->qn('r.state') . ' = 1');

		// Registration type: by single date/period (1)
		if ($regDate && $typeReg == 1)
		{
//			$query->where('r.date = ' . $db->q($regDate)); // This is the good logic if correctly set
			$query->where('(r.date = ' . $db->q($regDate) . ' OR (r.date = "" AND r.period = 1))');
		}
		elseif ( ! $regDate && $typeReg == 1)
		{
//			$query->where('r.date = "" AND r.period = 0'); // This is the good logic if correctly set
			$query->where('r.date = ""');
		}

		// Registration type: all dates (2)
//		else
//		{
//			$query->where('r.date = "" AND r.period = 1');
//		}

		$db->setQuery($query);

		$total = $db->loadResult();

		return $total;
	}

	/**
	 * Function to get list of registered people per date/period for one event
	 * Not Use: BETA
	 *
	 * @since   3.6.6
	 */
	public static function getListRegisteredTotal($eventID)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('sum(' . $db->qn('r.people') . ') AS people, r.date AS date, r.period AS period')
			->from($db->qn('#__icagenda_registration', 'r'))
			->where($db->qn('r.eventid') . ' = ' . (int) $eventID)
			->where($db->qn('r.state') . ' = 1')
			->group('r.date');

		$db->setQuery($query);

		$list = $db->loadObjectList();

		return $list;
	}

	/**
	 * Function to get available tickets for one event/date
	 *
	 * @since   3.6.5
	 */
	public static function getTicketsAvailable($eventID, $regDate, $typeReg = '1', $maxReg = '1000000')
	{
		$regDate          = ($typeReg == 1) ? $regDate : '';
		$registered       = self::getRegisteredTotal($eventID, $regDate, $typeReg);
		$ticketsAvailable = ($maxReg - $registered);

		if ($ticketsAvailable < 0) $ticketsAvailable = '0';

		return $ticketsAvailable;
	}

	/**
	 * Function to get list of available tickets per date/period for one event
	 * Not Use: BETA
	 *
	 * @since   3.6.6
	 */
	public static function getListTicketsAvailable($eventID, $typeReg = '1', $maxReg = '1000000')
	{
		$listDatesTicketsAvailable = array();

		$listRegisteredTotal = self::getListRegisteredTotal($eventID);

		foreach ($listRegisteredTotal as $k => $v)
		{
			$listDatesTicketsAvailable[$v->date] = ($maxReg - $v->people);
		}

		return $listDatesTicketsAvailable;
	}

	/**
	 * Function to get max number of tickets bookable (people) for one event/date
	 *
	 * @since   3.6.5
	 */
	public static function getTicketsBookable($eventID, $regDate, $typeReg = '1', $maxReg = '1000000', $tickets = '5')
	{
		$ticketsAvailable = self::getTicketsAvailable($eventID, $regDate, $typeReg, $maxReg);

		if ($ticketsAvailable > $tickets)
		{
			return $tickets;
		}
		else
		{
			return $ticketsAvailable;
		}
	}

	/**
	 * Function to return dates drop list for registrable dates
	 *
	 * @since   3.6.0 (Migrated from 3.5 - TO BE REVIEWED)
	 *          version 3.6.5
	 *
	 * @TODO    set option/value to array
	 * @TODO    migrate HTML rendering fot options text to form field type getOptions ($type = registrationdates)
	 */
	public static function formDatesList($item)
	{
		$app      = JFactory::getApplication();
		$params   = $app->getParams();
		$iCparams = JComponentHelper::getParams('com_icagenda');

		$eventTimeZone   = null;
		$date_today      = JHtml::date('now', 'Y-m-d');
		$date_time_today = JHtml::date('now', 'Y-m-d H:i');
		$allDates        = self::eventAllDates($item);
		$timeformat      = $params->get('timeformat', 1);
		$perioddates     = iCDatePeriod::listDates($item->startdate, $item->enddate, $eventTimeZone);

//		$evtParams   = icagendaEvent::evtParams($item->params);
		$typeReg     = $item->params->get('typeReg', '1');
		$max_tickets = $item->params->get('maxReg', '1000000');
		$regDeadline = $item->params->get('reg_deadline', 1);

		// Check the period if individual dates
		$only_startdate = ($item->weekdays || $item->weekdays == '0') ? false : true;

		$lang_time = ($timeformat == 1) ? 'H:i' : 'h:i A';

		sort($allDates);

		$p = 0;

//		print_r(self::getListTicketsAvailable($item->id, $typeReg, $max_tickets));
// TEST	$listTicketsAvailable = $item->listTicketsAvailable;

		$upDays = array();

		foreach ($allDates as $k => $d)
		{
			$date_control = JHtml::date($d, 'Y-m-d H:i', $eventTimeZone);

			if ($only_startdate && in_array($date_control, $perioddates))
			{
				$is_full_period = true;
				$datetime_date  = ($regDeadline == 2)
								? date('Y-m-d H:i:s', strtotime($item->enddate))
								: date('Y-m-d H:i:s', strtotime($item->startdate));
				$datetime_check = $datetime_date;
			}
			else
			{
				$is_full_period = false;
				$datetime_date  = date('Y-m-d H:i:s', strtotime($d));
				$datetime_check = ($regDeadline == 2)
								? date('Y-m-d', strtotime($d)) . ' 23:59:59'
								: date('Y-m-d H:i:s', strtotime($d));
			}

			$regDate         = $is_full_period ? '' : $datetime_date;
			$nb_tickets_left = self::getTicketsBookable($item->id, $regDate, $typeReg, $max_tickets, $item->params->get('maxNbTicketsPerReg', 5));

// TEST		$nb_tickets_left = isset($listTicketsAvailable[$regDate]) ? $listTicketsAvailable[$regDate] : $max_tickets;

			$date_today_compare = ($item->displaytime == 1) ? $date_time_today : $date_today;

			if (strtotime($datetime_check) > strtotime($date_today_compare)
				&& $nb_tickets_left > 0)
			{
				// Removed for now, as gives confusion (nb of maximum tickets for one registration to selected date)
//				$tickets_left = ($max_tickets != '1000000') ? ' (&#10003;' . $nb_tickets_left . ')' : '';
				$tickets_left = '';

				if ($is_full_period)
				{
					if ($p == 0)
					{
						if (icagendaRender::dateToFormat($item->startdate) == icagendaRender::dateToFormat($item->enddate))
						{
							$upDays[$k] = '@@' . strip_tags(icagendaRender::dateToFormat($item->startdate)) . $tickets_left;
						}
						else
						{
							$upDays[$k] = '@@' . strip_tags(icagendaRender::dateToFormat($item->startdate)) . ' &#x279c; ' . strip_tags(icagendaRender::dateToFormat($item->enddate)) . $tickets_left;
						}

						$p = $p+1;
					}
				}
				else
				{
					$date = strip_tags(icagendaRender::dateToFormat($d));

					$event_time = ($item->displaytime == 1) ? ' - ' . date($lang_time, strtotime($datetime_date)) : '';

					$upDays[$k] = $datetime_date . '@@' . $date . $event_time . $tickets_left;
				}
			}
		}

		return $upDays;
	}

	/**
	 * Function to get option setting for max nb of tickets per registration
	 *
	 * @since   3.6.0   (Migrated from 3.5)
	 *          version 3.6.5
	 */
	public static function maxNbTicketsPerReg($params)
	{
		$iCparams = JComponentHelper::getParams('com_icagenda');

		$useGlobal    = $params->get('maxRlistGlobal', '');
		$evt_maxRlist = $params->get('maxRlist', '5');

		// Control and edit param values to iCagenda v3
		if ($useGlobal == '1') $useGlobal = '';
		elseif ($useGlobal == '0') $useGlobal = '2';

		if ($useGlobal == '2')
		{
			$maxNbTicketsPerReg = $evt_maxRlist;
		}
		else
		{
			$maxNbTicketsPerReg = $iCparams->get('maxRlist', '5');
		}

		return $maxNbTicketsPerReg;
	}




	/**
	 * Function pre-formated to display Register button and registered bubble
	 *
	 * @since   3.6.0 (Migrated from 3.5 - TO BE REVIEWED)
	 *          version 3.6.5
	 */
	static public function reg($i)
	{
		$statutReg     = $i->params->get('statutReg', '');
		$regDeadline   = $i->params->get('reg_deadline', 1);
		$RegButtonText = $i->params->get('RegButtonText', '');
		$typeReg       = $i->params->get('typeReg', '1');
		$regLink       = $i->params->get('RegButtonLink', '');

		$accessReg            = self::accessReg($i);
		$availableDates       = self::upcomingDatesBooking($i);
		$ticketsCouldBeBooked = self::ticketsCouldBeBooked($i);

		$registered = isset($i->registered) ? $i->registered : '0';

		// Initialize controls
		$date_today      = JHtml::date('now', 'Y-m-d');
		$date_time_today = JHtml::date('now', 'Y-m-d H:i');

		$access    = '0';
		$control   = '';
		$TextRegBt = '';

		$period             = unserialize($i->period);
		$period             = is_array($period) ? $period : array();
		$only_startdate     = ($i->weekdays || $i->weekdays == '0') ? false : true;
		$datetime_startdate = JHtml::date($i->startdate, 'Y-m-d H:i', null);

		// Get var event date alias if set or var 'event_date' set to session in event details view
		$session    = JFactory::getSession();
		$event_date = $session->get('event_date', '');
		$get_date   = JFactory::getApplication()->input->get('date', ($event_date ? date('Y-m-d-H-i', strtotime($event_date)) : ''));

		if ($get_date)
		{
			// Convert to SQL datetime if set, or return empty.
			$dateday = icagendaEvent::convertDateAliasToSQLDatetime($get_date);

			$date_is_upcoming = (strtotime($dateday) > strtotime($date_time_today)) ? true : false;

			$is_full_period = false;

			// If registration until end date
			if ($regDeadline == 2)
			{
				if (in_array($dateday, $period))
				{
					// Case Single Date from period (weekdays):
					$endDatetime = date('Y-m-d', strtotime($dateday)) . ' ' . date('H:i:s', strtotime($i->enddate));
				}
				else
				{
					// Case Single Date:
					$endDatetime = date('Y-m-d', strtotime($dateday)) . ' 23:59:59';
				}
			}
			else
			{
				$startDatetime = date('Y-m-d H:i', strtotime($dateday));
			}
		}
		else
		{
			if ($only_startdate && in_array($datetime_startdate, $period))
			{
				$is_full_period = true;
			}
			else
			{
				$is_full_period = false;
			}

			if (count($period) > 0
				&& $only_startdate
				&& (strtotime($datetime_startdate) < strtotime($date_time_today))
				)
			{
				$date_is_upcoming	= false;
			}
			else
			{
				$date_is_upcoming	= true;
			}

			// If registration until end date
			if ($regDeadline == 2)
			{
				if ($is_full_period)
				{
					$endDatetime = date('Y-m-d H:i:s', strtotime($i->enddate));
				}
				else
				{
					$endDatetime = date('Y-m-d', strtotime($date_today)) . ' ' . date('H:i:s', strtotime($i->enddate));
				}
			}
			else
			{
				$startDatetime = date('Y-m-d H:i', strtotime($i->startdate));
			}
		}

		// Access Control
		$user       = JFactory::getUser();
		$userLevels = $user->getAuthorisedViewLevels();

		if ($i->params->get('RegButtonText'))
		{
			$TextRegBt = $i->params->get('RegButtonText');
		}
		elseif ($RegButtonText)
		{
			$TextRegBt = $RegButtonText;
		}
		else
		{
			$TextRegBt = JText::_( 'COM_ICAGENDA_REGISTRATION_REGISTER');
		}

		$regButton_type = ''; // DEV. NOT IN USE

		if ($regButton_type == 'button') // DEV. NOT IN USE
		{
			$doc = JFactory::getDocument();
			$style = '.regis_button {'
					. 'text-transform: none !important;'
					. 'padding: 10px 14px 10px;'
					. '-webkit-border-radius: 10px;'
					. '-moz-border-radius: 10px;'
					. 'border-radius: 10px;'
					. 'color: #FFFFFF;'
					. 'background-color: #D90000;'
					. '*background-color: #751616;'
					. 'background-image: -ms-linear-gradient(top,#D90000,#751616);'
					. 'background-image: -webkit-gradient(linear,0 0,0 100%,from(#D90000),to(#751616));'
					. 'background-image: -webkit-linear-gradient(top,#D90000,#751616);'
					. 'background-image: -o-linear-gradient(top,#D90000,#751616);'
					. 'background-image: linear-gradient(top,#D90000,#751616);'
					. 'background-image: -moz-linear-gradient(top,#D90000,#751616);'
					. 'background-repeat: repeat-x;'
					. 'filter: progid:dximagetransform.microsoft.gradient(startColorstr="#D90000",endColorstr="#751616",GradientType=0);'
					. 'filter: progid:dximagetransform.microsoft.gradient(enabled=false);'
					. '*zoom: 1;'
					. '-webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);'
					. '-moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);'
					. 'box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);'
					. '}'
					. '.regis_button:hover {'
					. 'color: #F9F9F9;'
					. 'background-color: #b60000;'
					. '*background-color: #531111;'
					. 'background-image: -ms-linear-gradient(top,#b60000,#531111);'
					. 'background-image: -webkit-gradient(linear,0 0,0 100%,from(#b60000),to(#531111));'
					. 'background-image: -webkit-linear-gradient(top,#b60000,#531111);'
					. 'background-image: -o-linear-gradient(top,#b60000,#531111);'
					. 'background-image: linear-gradient(top,#b60000,#531111);'
					. 'background-image: -moz-linear-gradient(top,#b60000,#531111);'
					. 'background-repeat: repeat-x;'
					. 'filter: progid:dximagetransform.microsoft.gradient(startColorstr="#b60000",endColorstr="#531111",GradientType=0);'
					. 'filter: progid:dximagetransform.microsoft.gradient(enabled=false);'
					. '*zoom: 1;'
					. '}';
			$doc->addStyleDeclaration( $style );
		}


		if ($statutReg == 1)
		{
			$formDatesList  = self::formDatesList($i);
			$dates_bookable = $formDatesList ? $formDatesList : array();
			$this_event_url = JURI::getInstance()->toString();

			$cleanurl = preg_replace('/&date=[^&]*/', '', $this_event_url);
			$cleanurl = preg_replace('/\?date=[^\?]*/', '', $cleanurl);

			$isSef    = JFactory::getApplication()->getCfg('sef');
			$date_var = ($isSef == 1) ? '?date=' :'&amp;date=';

			$select_date = '<div style="display: block; max-height: 130px; width: 180px; overflow-y: auto;">';

			foreach ($dates_bookable AS $d)
			{
				$ex_d     = explode('@@', $d);
				$date_url = date('Y-m-d-H-i', strtotime($ex_d[0]));

				$select_date.= '<div class="ic-tip-link">';
				$select_date.= '<a href="' . $cleanurl . $date_var . $date_url . '" class="ic-title-cal-tip" rel="nofollow" target="_parent">';
				$select_date.= '&#160;' . $ex_d[1] . '&#160;';
				$select_date.= '</a>';
				$select_date.= '</div>';

				// If the date bookable is the current next date (DEPRECATED ?)
				$is_next = ($date_url == date('Y-m-d-H-i', strtotime($i->next))) ? true : false;
			}

			$select_date.= '</div>';

			/*
			 * Buttons HTML
			 */
			// Button Register OK
			$btn_register_ok = '<a href="' . self::regUrl($i) . '" rel="nofollow">';
			$btn_register_ok.= '<div class="ic-btn ic-btn-success ic-btn-small ic-event-register regis_button">';
			$btn_register_ok.= '<i class="iCicon iCicon-register"></i>&nbsp;' . $TextRegBt;
			$btn_register_ok.= '</div>';
			$btn_register_ok.= '</a>';

			// Button Register Private
			$btn_register_vip = '<a href="' . self::regUrl($i) . '" rel="nofollow">';
			$btn_register_vip.= '<div class="ic-btn ic-btn-danger ic-btn-small ic-event-register regis_button">';
			$btn_register_vip.= '<i class="iCicon iCicon-private"></i>&nbsp;' . $TextRegBt;
			$btn_register_vip.= '</div>';
			$btn_register_vip.= '</a>';

			// Button Select Date
			$btn_select_date = '<a class="ic-addtocal" title="' . htmlspecialchars($select_date) . '" rel="nofollow">';
			$btn_select_date.= '<div class="ic-btn ic-btn-info ic-btn-small ic-event-full">';
			$btn_select_date.= '<i class="iCicon iCicon-people"></i>&nbsp;' . JText::_('COM_ICAGENDA_REGISTRATION_DATE_NO_TICKETS_LEFT');
			$btn_select_date.= '</div>';
			$btn_select_date.= '<br />';
			$btn_select_date.= '<span class="ic-select-another-date">' . JText::_('COM_ICAGENDA_REGISTRATION_REGISTER_ANOTHER_DATE') . '</span>';
			$btn_select_date.= '</a>';

			// Button Registration Close
			$btn_reg_close = '<div class="ic-btn ic-btn-default ic-btn-small ic-event-finished">';
			$btn_reg_close.= '<i class="iCicon iCicon-blocked"></i>&nbsp;' . JText::_('COM_ICAGENDA_REGISTRATION_CLOSED');
			$btn_reg_close.= '</div>';

			// Button Registration Complete
			$btn_reg_complete = '<div class="ic-btn ic-btn-info ic-btn-small ic-event-full">';
			$btn_reg_complete.= '<i class="iCicon iCicon-people"></i>&nbsp;' . JText::_('COM_ICAGENDA_REGISTRATION_EVENT_FULL');
			$btn_reg_complete.= '</div>';


			$reg_button = '<div class="ic-registration-box">';

			// Available date(s) (boolean) and ticket(s) available for this event (boolean)
			if ($availableDates
				&& $ticketsCouldBeBooked
				)
			{
				if (in_array($accessReg, $userLevels))
				{
					if ($i->registered == self::maxReg($i))
					{
						if ($typeReg != '2')
						{
							$reg_button.= $btn_select_date;
						}
						else
						{
							$reg_button.= $btn_reg_close;
						}
					}

//					elseif ($date_is_upcoming
//						|| $is_next)
					elseif ($date_is_upcoming)
					{
						$reg_button.= $btn_register_ok;
					}

					// Registration Until end date (if registration for all dates, allow to register to a past date)
					elseif ($regDeadline == 2
						&& (strtotime($endDatetime) > strtotime($date_time_today) || $typeReg == 2)
						)
					{
						$reg_button.= $btn_register_ok;
					}


					elseif ($regDeadline == 1
						&& strtotime($startDatetime) < strtotime($date_time_today)
						)
					{
						if ($typeReg != '2' && $availableDates)
						{
							$reg_button.= $btn_select_date;
						}
						else
						{
							$reg_button.= $btn_reg_close;
						}
					}

					else
					{
						$reg_button.= $btn_select_date;
					}
				}
				else
				{
					$reg_button.= $btn_register_vip;
				}
			}

			// Available date(s) (boolean) but no ticket left (boolean)
			elseif ($availableDates
				&& ! $ticketsCouldBeBooked
				)
			{
				if ( ! $date_is_upcoming && $typeReg == 2)
				{
					$reg_button.= $btn_reg_close;
				}
				elseif ( ! $date_is_upcoming && $typeReg == 1)
				{
					$reg_button.= $btn_reg_complete;
				}
				else
				{
					$reg_button.= $btn_select_date;
				}
			}
			elseif ( ! $availableDates)
			{
				$reg_button.= $btn_reg_close;
			}
			else
			{
				return false;
			}

			if ( ! $regLink)
			{
				$reg_button.= '&nbsp;<i class="iCicon iCicon-people ic-people"></i>';
				$reg_button.= '<div class="ic-registered" >' . $registered . '</div>';
			}

			$reg_button.= '</div>';
		}
		else
		{
			return false;
		}

		return $reg_button;
	}

	/**
	 * Function to return Registration Status for this event
	 *
	 * @todo               check and remove from library, as not needed in modules or list
	 * DEPRECATED 3.6.0 :  use now $item->params (global + event params merged)
	 */
	static public function statutReg($item)
	{
		$params       = JFactory::getApplication()->getParams();
		$gstatutReg   = $params->get('statutReg', '');

		$evtParams    = icagendaEvent::evtParams($item->params);
		$evtstatutReg = $evtParams->get('statutReg', '');

		// Control and edit param values to iCagenda v3
		if ($evtstatutReg == '2')
		{
			$evtstatutReg = '0';
		}

		$statutReg = ($evtstatutReg != '') ? $evtstatutReg : $gstatutReg;

		return $statutReg;
	}

	/**
	 * Function to return Registration Access Level for this event
	 *
	 * @since   3.6.0 (Migrated from 3.5 - TO BE REVIEWED)
	 */
	static public function accessReg($item)
	{
		$reg_form_access = JComponentHelper::getParams('com_icagenda')->get('reg_form_access', 1);
//		$evtParams       = icagendaEvent::evtParams($item);
		$accessReg       = $item->params->get('accessReg', $reg_form_access);

		return $accessReg;
	}

	/**
	 * Function return true if upcoming dates for Booking
	 *
	 * @since   3.6.0 (Migrated from 3.5 - TO BE REVIEWED)
	 */
	public static function upcomingDatesBooking($item)
	{
		if (count(self::formDatesList($item)) > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Ticket(s) could be booked
	 *
	 * @since   3.6.0 (Migrated from 3.5 - TO BE REVIEWED)
	 */
	static public function ticketsCouldBeBooked($i)
	{
		$eventTimeZone  = null;
		$date_today     = JHtml::date('now', 'Y-m-d');
		$datetime_today = JHtml::date('now', 'Y-m-d H:i');
		$allDates       = self::eventAllDates($i);
		$maxReg         = $i->params->get('maxReg', '1000000');
		$typeReg        = $i->params->get('typeReg', '1');
		$perioddates    = iCDatePeriod::listDates($i->startdate, $i->enddate, $eventTimeZone);
		$regDeadline    = $i->params->get('reg_deadline', 1);

		// Check the period if individual dates
		$only_startdate = ($i->weekdays || $i->weekdays == '0') ? false : true;

		sort($allDates);

		// If registration for all dates, and not registration until end of event, no registration if past date(s)
		if ($typeReg ==  '2'
			&& $regDeadline != 2)
		{
			foreach ($allDates as $k => $d)
			{
				if (strtotime($d) < strtotime($datetime_today))
				{
					return false;
				}
			}
		}

		$total_tickets_bookable = 0;

		foreach ($allDates as $k => $d)
		{
			$date_control = JHtml::date($d, 'Y-m-d H:i', $eventTimeZone);
			$is_in_period = in_array($date_control, $perioddates) ? true : false;

			if ($only_startdate && $is_in_period)
			{
				$is_full_period = true;
			}
			else
			{
				$is_full_period = false;
			}

			$datetime_date      = date('Y-m-d H:i:s', strtotime($d));
			$datetime_startdate = date('Y-m-d H:i:s', strtotime($i->startdate));
			$datetime_enddate   = date('Y-m-d H:i:s', strtotime($i->enddate));

			// Nb of tickets left
			$nb_tickets_left = $maxReg - $i->registered;

			// is Full period (& reg.type for all dates) & reg.limit until start date
			if ($is_full_period
				&& $typeReg == 2
				&& $regDeadline == 1
				&& (strtotime($datetime_startdate) < strtotime($date_today))
				)
			{
				$total_tickets_bookable = 0;
			}

			// is Full period & reg.limit until end dates
			elseif ($is_full_period
				&& $regDeadline == 2
				&& (strtotime($datetime_enddate) <= strtotime($datetime_today))
				)
			{
				$total_tickets_bookable = 0;
			}

			// timestamp datetime > timestamp today date & reg.limit until start date
			elseif ($regDeadline == 1
				&& strtotime($d) > strtotime($date_today))
			{
				if ($nb_tickets_left > 0)
				{
					$total_tickets_bookable = $total_tickets_bookable + $nb_tickets_left;
				}
			}

			// is in period & timestamp datetime > timestamp today date & reg.limit until end date
			elseif ($regDeadline == 2
				&& $is_in_period
				&& strtotime(date('Y-m-d', strtotime($d)) . ' ' . date('H:i', strtotime($datetime_enddate))) > strtotime($datetime_today)
				)
			{
				if ($nb_tickets_left > 0)
				{
					$total_tickets_bookable = $total_tickets_bookable + $nb_tickets_left;
				}
			}

			// is single date & timestamp date > timestamp today date & reg.limit until end date
			elseif ($regDeadline == 2
				&& ! $is_in_period
				&& strtotime(date('Y-m-d', strtotime($d))) > strtotime($date_today)
				)
			{
				if ($nb_tickets_left > 0)
				{
					$total_tickets_bookable = $total_tickets_bookable + $nb_tickets_left;
				}
			}
		}

		if ($total_tickets_bookable > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Function to list all dates of an event
	 *
	 * @since   3.6.0 (Migrated from 3.5 - TO BE REVIEWED)
	 *          version 3.6.6
	 */
	static public function eventAllDates($i)
	{
		// Declare eventAllDates array
		$eventAllDates = array();

		// Get WeekDays Array
		$WeeksDays = iCDatePeriod::weekdaysToArray($i->weekdays);

		// If Single Dates, added each one to All Dates for this event
		$singledates = iCString::isSerialized($i->dates) ? unserialize($i->dates) : array();

		foreach ($singledates as $sd)
		{
			$isValid = iCDate::isDate($sd);

			if ( $isValid )
			{
				array_push($eventAllDates, date('Y-m-d H:i:s', strtotime($sd)));
			}
		}

		$perioddates = iCDatePeriod::listDates($i->startdate, $i->enddate);

		if ( (isset ($perioddates))
			&& ($perioddates != NULL) )
		{
			foreach ($perioddates as $Dat)
			{
				if (in_array(date('w', strtotime($Dat)), $WeeksDays))
				{
					$isValid = iCDate::isDate($Dat);

					if ($isValid)
					{
						$SingleDate = date('Y-m-d H:i:s', strtotime($Dat));

						array_push($eventAllDates, $SingleDate);
					}
				}
			}
		}

		return $eventAllDates;
	}

	/**
	 * Function to get number of tickets booked (for one date of the event)
	 *
	 * @since   3.6.0 (Migrated from 3.5 - TO BE REVIEWED)
	 */
	public static function nbTicketsBooked($event_id, $date = null, $typeReg = 1, $is_full_period = false)
	{
		// Registrations total
		$db    = Jfactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('sum(r.people) AS count');
		$query->from('#__icagenda_registration AS r');
		$query->where('r.state = 1');

		$query->where('r.eventid = ' . $db->q($event_id));

		// Get nb of tickets booked for the date only if registration per date
		if ($typeReg != 2)
		{
			// Single date
			if ($date && ! $is_full_period)
			{
				$query->where('r.date = ' . $db->q($date));
			}

			// Full Period (DEPRECATED 3.7)
			else
			{
				$query->where('r.date = "" AND r.period = 0');
			}
		}

		$db->setQuery($query);

		$result = $db->loadResult();

		return $result;
	}

	/**
	 * Function to get link to registration page
	 *
	 * @since   3.6.0 (Migrated from 3.5 - TO BE REVIEWED)
	 */
	public static function regUrl($i)
	{
		$app         = JFactory::getApplication();
		$isSef       = $app->getCfg('sef');
		$params      = $app->getParams();
		$Itemid      = $app->input->get('Itemid');
		$date        = $app->input->get('date', '');
		$this_itemid = $params->get('itemid', $Itemid);

		$event_slug  = empty($i->alias) ? $i->id : $i->id . ':' . $i->alias;
		$date_var    = ($isSef == '1') ? '?date=' : '&amp;date=';
		$date_slug   = $date ? $date_var . $date : '';

		$icagenda_form = JRoute::_('index.php?option=com_icagenda&view=registration&id=' . $event_slug
						. '&Itemid=' . (int) $this_itemid)
//						. $date_slug
						;

//		$evtParams       = icagendaEvent::evtParams($i->params);
		$regLink         = $i->params->get('RegButtonLink', '');
		$regLinkArticle  = $i->params->get('RegButtonLink_Article', $icagenda_form);
		$regLinkUrl      = $i->params->get('RegButtonLink_Url', $icagenda_form);
		$RegButtonTarget = $i->params->get('RegButtonTarget', '0');

		if ($RegButtonTarget == 1)
		{
			$browserTarget = '_blank';
		}
		else
		{
			$browserTarget = '_parent';
		}

		if ($regLink == 1 && is_numeric($regLinkArticle))
		{
			$regUrl = JRoute::_('index.php?option=com_content&view=article&id=' . $regLinkArticle) . '" rel="nofollow" target="' . $browserTarget;
		}
		elseif ($regLink == 2)
		{
			$regUrl = iCUrl::urlParsed($regLinkUrl) . '" rel="nofollow" target="' . $browserTarget;
		}
		else
		{
			$regUrl = $icagenda_form . '" rel="nofollow" target="' . $browserTarget;
		}

		return $regUrl;
	}

	/**
	 * Function Max Nb Tickets (Control if set)
	 *
	 * @todo               check and remove if not needed in modules or list
	 * DEPRECATED 3.6.0 :  do not use $item->params (global + event params merged)
	 */
	static public function maxNbTickets($item)
	{
		$evtParams    = icagendaEvent::evtParams($item->params);
		$maxNbTickets = $evtParams->get('maxReg', '1000000');

		if ($maxNbTickets != '1000000'
			&& self::statutReg($item) == '1')
		{
			return $maxNbTickets;
		}
	}

	/**
	 * Function to check a valid email address
	 *
	 * @since   3.6.0 (Migrated from 3.5 - TO BE REVIEWED)
	 */
	static public function validEmail($email)
	{
		$isValid = true;
		$atIndex = strrpos($email, "@");

		if (is_bool($atIndex) && !$atIndex)
		{
			$isValid = false;
		}
		else
		{
			$domain    = substr($email, $atIndex+1);
			$local     = substr($email, 0, $atIndex);
			$localLen  = strlen($local);
			$domainLen = strlen($domain);

			if ($localLen < 1 || $localLen > 64)
			{
				// local part length exceeded
				$isValid = false;
			}
			elseif ($domainLen < 1 || $domainLen > 255)
			{
				// domain part length exceeded
				$isValid = false;
			}
			elseif ($local[0] == '.' || $local[$localLen-1] == '.')
			{
				// local part starts or ends with '.'
				$isValid = false;
			}
			elseif (preg_match('/\\.\\./', $local))
			{
				// local part has two consecutive dots
				$isValid = false;
			}
			elseif (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
			{
				// character not valid in domain part
				$isValid = false;
			}
			elseif (preg_match('/\\.\\./', $domain))
			{
				// domain part has two consecutive dots
				$isValid = false;
			}
			elseif (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
			{
				// character not valid in local part unless
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local)))
				{
					$isValid = false;
				}
			}

			// Check the domain name
			if ($isValid
				&& ! self::is_valid_domain_name($domain))
			{
				return false;
			}

			// Uncommented below to have PHP run a proper DNS check (risky on shared hosts!)
			/**
			if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
				// domain not found in DNS
				$isValid = false;
			}
			/**/
		}

		return $isValid;
	}

	/**
	 * Function to check if a domain is valid
	 *
	 * @since   3.6.0 (Migrated from 3.5 - TO BE REVIEWED)
	 */
	static public function is_valid_domain_name($domain_name)
	{
		$pieces = explode(".", $domain_name);

		foreach ($pieces as $piece)
		{
			if (!preg_match('/^[a-z\d][a-z\d-]{0,62}$/i', $piece)
				|| preg_match('/-$/', $piece))
			{
				return false;
			}
		}

		return true;
	}




	// TO BE CHECKED

	/**
	 * Function Max Registrations per event (OLD before 3.2.8, for use with old theme packs or custom one)
	 *
	 * Keep for B/C : DEPRECATED! (edit: still used... to be checked...)
	 */
	static public function maxReg($item)
	{
		$maxReg = $item->params->get('maxReg', '1000000');

		return $maxReg;
	}



	/**
	 * DEPRECATED: to be removed 3.7.0
	 */

	/**
	 * Ticket(s) booked
	 *
	 * @since   3.6.0 (Migrated from 3.5 - TO BE REVIEWED)
	 *
	 * @deprecated 3.6.5 - @removed 3.7.0
	 */
	static public function totalRegistered($i)
	{
		$app     = JFactory::getApplication();
		$session = JFactory::getSession();

		$eventTimeZone  = null;
		$date_today     = JHtml::date('now', 'Y-m-d');
		$allDates       = self::eventAllDates($i);
		$evtParams      = icagendaEvent::evtParams($i->params);
		$typeReg        = $evtParams->get('typeReg', '');
		$perioddates    = iCDatePeriod::listDates($i->startdate, $i->enddate, $eventTimeZone);

		// Check the period if individual dates
		$only_startdate = ($i->weekdays || $i->weekdays == '0') ? false : true;

		sort($allDates);

		$total_tickets_booked = 0;

		// Get date from url if set, and from session if set
		$url_date     = $app->input->get('date', '');
		$session_date = $session->get('session_date', '');

		if ($url_date)
		{
			$ex_date        = explode('-', $url_date);
			$defaultDate    = (count($ex_date) == 5)
							? $ex_date[0] . '-' . $ex_date[1] . '-' . $ex_date[2] . ' ' . $ex_date[3] . ':' . $ex_date[4] . ':00'
							: '';
		}
		else
		{
			$defaultDate	= $session_date;
		}

		$this_date = ! empty($defaultDate) ? JHtml::date($defaultDate, 'Y-m-d H:i:s', $eventTimeZone) : null;

		// By Single Dates (registration type is not for all dates of the events)
		if ($typeReg != 2)
		{
			foreach ($allDates as $k => $d)
			{
				$date_control = JHtml::date($d, 'Y-m-d H:i', $eventTimeZone);

				if ($only_startdate && in_array($date_control, $perioddates))
				{
					$is_full_period = true;
				}
				else
				{
					$is_full_period = false;
				}

				$datetime_date = date('Y-m-d H:i:s', strtotime($d));
				$nb_tickets    = self::getNbTicketsBooked($datetime_date, $i->registered, $i->id, $is_full_period);

				// NO Date in URL - FULL PERIOD (no weekdays) - Date IS in the PERIOD
				if ( ! $defaultDate && $only_startdate && in_array($date_control, $perioddates))
				{
					$total_tickets_booked = $nb_tickets;
				}

				// Date in URL - FULL PERIOD (no weekdays) - Date IS NOT in the PERIOD
				elseif ($defaultDate && $only_startdate && ! in_array($date_control, $perioddates))
				{
					if ($nb_tickets > 0
						&& strtotime($this_date) == strtotime($datetime_date))
					{
						$total_tickets_booked = $total_tickets_booked + $nb_tickets;
					}

					// Only one date for registration, and the setting option of event is set to list of dates (equals "for all dates of event)
					elseif (count($allDates) == 1)
					{
						$nb_tickets           = self::getNbTicketsBooked(null, $i->registered, $i->id, $is_full_period);
						$total_tickets_booked = $total_tickets_booked + $nb_tickets;
					}
				}

				// Date in URL - PERIOD is Individual DATES (weekdays selected)
				elseif ($defaultDate && ! $only_startdate)
				{
					if ($nb_tickets > 0
						&& strtotime($this_date) == strtotime($datetime_date))
					{
						$total_tickets_booked = $total_tickets_booked + $nb_tickets;
					}
				}

				// NO Date in URL (all tickets for the events, not taking into account the dates)
				elseif ( ! $defaultDate)
				{
					if ($nb_tickets > 0)
					{
						$total_tickets_booked = self::getNbTicketsBooked(null, $i->registered, $i->id, $is_full_period);
					}
				}
			}
		}
		else
		{
			$nb_tickets = self::getNbTicketsBooked('period', $i->registered, $i->id);

			$total_tickets_booked = $nb_tickets;
		}

		return $total_tickets_booked;
	}

	/**
	 * Function to get number of tickets booked (for one date)
	 *
	 * @since   3.6.0 (Migrated from 3.5)
	 *
	 * @deprecated 3.6.5 - @removed 3.7.0
	 */
	public static function getNbTicketsBooked($date, $event_registered, $event_id = null, $date_control = null)
	{
		$eventTimeZone    = null;
		$event_registered = is_array($event_registered) ? $event_registered : array();
		$nb_registrations = 0;

		// Get Date if set in url as var
		$get_date = JFactory::getApplication()->input->get('date', null);

		if ( ! $get_date && $date_control)
		{
			$get_date = null;
		}

		foreach ($event_registered AS $reg)
		{
			$ex_reg = explode('@@', $reg); // eventid@@date@@people

			if ( ! $date || $date == 'period')
			{
				$nb_registrations = $nb_registrations + $ex_reg[2];
			}
			elseif ($get_date
				&& $event_id == $ex_reg[0]
				&& date('Y-m-d H:i', strtotime($date)) == date('Y-m-d H:i', strtotime($ex_reg[1]))
				)
			{
				$nb_registrations = $nb_registrations + $ex_reg[2];
			}
			elseif ( ! $get_date
				&& $event_id == $ex_reg[0]
				&& $ex_reg[1] == 'period'
				)
			{
				$nb_registrations = $nb_registrations + $ex_reg[2];
			}
			elseif ( ! $get_date
				&& $event_id == $ex_reg[0]
				&& date('Y-m-d H:i', strtotime($date)) == date('Y-m-d H:i', strtotime($ex_reg[1]))
				)
			{
				$nb_registrations = $nb_registrations + $ex_reg[2];
			}
		}

		return $nb_registrations;
	}
}
