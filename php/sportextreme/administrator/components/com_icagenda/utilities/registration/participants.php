<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.0 2018-05-25
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
 * class icagendaRegistrationParticipants
 */
class icagendaRegistrationParticipants
{
	/**
	 * Function to check if display of participants list
	 *
	 * @since   3.6.0
	 */
	static public function listDisplay($item)
	{
		$iCparams = JComponentHelper::getParams('com_icagenda');

		// Get Option if usage of iCagenda registration form for this event
		$evtParams = icagendaEvent::evtParams($item);
		$regLink   = $evtParams->get('RegButtonLink', '');

		// Hide/Show Option
		$participantList = $iCparams->get('participantList', 0);

		// Access Levels Option
		$accessParticipantList = $iCparams->get('accessParticipantList', 1);

		if ($participantList == 1
			&& ! $regLink
			&& icagendaEvents::accessLevels($accessParticipantList)
			)
		{
			return $participantList;
		}

		return false;
	}

	/**
	 * Function to display title List of Participants (if no slide effect)
	 *
	 * @since   3.6.0
	 */
	static public function listTitle($item)
	{
		$params = JFactory::getApplication()->getParams();

		// Get Option if usage of iCagenda registration form for this event
		$evtParams = icagendaEvent::evtParams($item);
		$regLink   = $evtParams->get('RegButtonLink', '');

		$participantList  = $params->get('participantList', 0);
		$participantSlide = $params->get('participantSlide', '');

		$registration = $item->params->get('statutReg', '');

		if ($participantSlide == 0
			&& $registration == 1
			&& $participantList == 1
			&& ! $regLink
			)
		{
			return JText::_('COM_ICAGENDA_EVENT_LIST_OF_PARTICIPANTS');
		}
	}

	/**
	 * Function to display list of participants
	 * TO BE REFACTORED (in process)
	 *
	 * @since   3.6.0
	 * @version 3.6.6
	 */
	static public function registeredUsers($i)
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$eventTimeZone = null;

		// Get Component PARAMS
		$iCparams = JComponentHelper::getParams('com_icagenda');

		// Get Type Registration (for all dates or per date)
		$typeReg = $i->params->get('typeReg', 1);

		// Preparing connection to db
		$db = JFactory::getDbo();

		// Preparing the query
		$query = $db->getQuery(true);
		$query->select(' r.id AS regID, r.userid AS userid, r.name AS registeredUsers, r.date as regDate, r.people as regPeople, r.email as regEmail,
						u.name AS name, u.username AS username, GROUP_CONCAT(ua.user_action) AS user_action')
			->from('#__icagenda_registration AS r')
			->leftJoin('#__users as u ON u.id = r.userid')
			->leftJoin('#__icagenda_user_actions as ua ON ua.parent_id = r.id')
			->group('r.id, ua.parent_id')
			->where('r.eventid = ' . (int) $i->id)
			->where('r.state = 1');

		// Get var event date alias if set or var 'event_date' set to session in event details view
		$session    = JFactory::getSession();
		$event_date = $session->get('event_date', '');
		$get_date   = $input->get('date', ($event_date ? date('Y-m-d-H-i', strtotime($event_date)) : ''));

		// Convert to SQL datetime if set, or return empty.
		$dateday = icagendaEvent::convertDateAliasToSQLDatetime($get_date);

		// Registration type: by single date/period (1)
		if ($dateday && $typeReg == 1)
		{
//			$query->where('r.date = ' . $db->q($dateday)); // This is the good logic if correctly set
			$query->where('(r.date = ' . $db->q($dateday) . ' OR (r.date = "" AND r.period = 1))');
		}
		elseif ( ! $dateday && $typeReg == 1)
		{
//			$query->where('r.date = "" AND r.period = 0'); // This is the good logic if correctly set
			$query->where('r.date = ""');
		}

		$db->setQuery($query);

		$registeredUsers = $db->loadObjectList();
		$nbusers         = count($registeredUsers);
//		$nbusers         = $i->registered;
		$nbmax           = $nbusers-1;
		$registration    = $i->params->get('statutReg', '');
		$n               = '0';

		// Slide Params
		$participantList    = $iCparams->get('participantList', 0);
		$participantSlide   = $iCparams->get('participantSlide', 1);
		$participantDisplay = $iCparams->get('participantDisplay', 1);
		$fullListColumns    = $iCparams->get('fullListColumns', 'tiers');

		// logged-in Users: Name/User Name Option
		$nameJoomlaUser = $iCparams->get('nameJoomlaUser', 1);

		$this_date = JHtml::date($dateday, 'Y-m-d H:i', $eventTimeZone);

		// Start List of Participants
		jimport( 'joomla.html.html.sliders' );
		$slider_c = '';

		$list_participants = '';

		if ($participantList == 1 && $registration == 1)
		{
			$n_list='names_noslide';

			// Check Admin / Organiser permissions to access registration data
			$organiser_rights = JFactory::getUser()->authorise('icagenda.access.registration');


			if ($participantSlide == 1)
			{
				$n_list   = 'names_slide';
				$slider_c = 'class="pane-slider content"';

				$list_participants.= JHtml::_('sliders.start', 'icagenda', array('useCookie'=>0, 'startOffset'=>-1, 'startTransition'=>1));
				$list_participants.= JHtml::_('sliders.panel', JText::_('COM_ICAGENDA_EVENT_LIST_OF_PARTICIPANTS'), 'slide1');
			}

			foreach ($registeredUsers as $reguser)
			{
				$this_reg_date  = strtotime($reguser->regDate)
								? JHtml::date($reguser->regDate, 'Y-m-d H:i', $eventTimeZone)
								: $reguser->regDate;

				if ( ($this_reg_date == $this_date)
					|| ($typeReg == 2)
					)
				{
					$n = $n+1;
				}

				// Registration by dates, and registration date is not filled
				elseif ($typeReg == 1
					&& ! $this_reg_date)
				{
					$n = $n+1;
				}
			}

			if ($nbusers == NULL || ($n == 0 && ! empty($get_date)))
			{
				$list_participants.= '<div ' . $slider_c . '>';
				$list_participants.= '&nbsp;' . JText::_('COM_ICAGENDA_NO_REGISTRATION') . '&nbsp;';
				$list_participants.= '</div>';
			}

			// Full display
			elseif ($participantDisplay == 1)
			{
				$column = isset($fullListColumns) ? $fullListColumns : 'tiers';

				$list_participants.= '<div ' . $slider_c . '>';

				foreach ($registeredUsers as $reguser)
				{
					$this_reg_date  = strtotime($reguser->regDate)
									? JHtml::date($reguser->regDate, 'Y-m-d H:i', $eventTimeZone)
									: $reguser->regDate;

					if ( ($this_reg_date == $this_date || empty($get_date))
						|| $typeReg == 2
						|| ($typeReg == 1 && ! $this_reg_date)
						)
					{
						// Get participant privacy consents
						$user_actions = explode(',', $reguser->user_action);

						// Get Name consent
						$name_consents = array();

//						foreach ($user_actions AS $k => $action)
//						{
//							$ex_action = explode('__', $action);

//							if ($ex_action[0] == 'consent_name'
//								&& isset($ex_action[1]))
//							{
//								$name_consents = explode('_', $ex_action[1]);
//							}
//						}

						if (in_array('consent_name_public', $user_actions)) $name_consents = array('public');
						if (in_array('consent_name_users', $user_actions)) $name_consents = array('users');

						$force_name_visibility = $iCparams->get('participant_name_consent', '');

						if ($force_name_visibility == '0')
						{
							$participant_name_visibility = $iCparams->get('participant_name_visibility', '');

							if ($participant_name_visibility == 1)
							{
								$name_consents = array('public');
							}
							elseif ($participant_name_visibility == 2)
							{
								$name_consents = array('users');
							}
						}

						if (in_array('public', $name_consents)
							|| (in_array('users', $name_consents) && JFactory::getUser()->id > 0))
						{
							// Get Username and name (To check: Is it needed ?)
							if ( ! empty($reguser->userid))
							{
								$data_name     = $reguser->name;
								$data_username = $reguser->username;

								if ($nameJoomlaUser == 1)
								{
									$reguser->registeredUsers = $reguser->registeredUsers;
								}
								else
								{
									$reguser->registeredUsers = $data_username;
								}
							}

							$participant_name = $reguser->registeredUsers;
						}
						elseif($organiser_rights)
						{
							// Information to be extended for logged-in user with admin/organiser permissions...
							$participant_name = $reguser->registeredUsers;
						}
						else
						{
							$participant_name = JText::_('IC_ANONYMOUS');
						}

						// Get Gravatar consent
						if (in_array('consent_gravatar', $user_actions))
						{
							$avatar             = md5(strtolower(trim($reguser->regEmail)));
							$participant_avatar = '<img alt="' . $participant_name . '"  src="https://www.gravatar.com/avatar/' . $avatar . '?s=36&d=mm"/>';
						}
						else
						{
							$participant_avatar = '<span class="iCicon-avatar ic-avatar"></span>';
						}

						$regDate = '';


						// Test if registered date before 3.3.3 could be converted
						if (strtotime($reguser->regDate))
						{
							// Control if date valid format (Y-m-d H:i)
							$datetime_format   = 'Y-m-d H:i:s';
							$datetime_input    = $reguser->regDate;
							$datetime_input    = trim($datetime_input);
							$datetime_is_valid = date($datetime_format, strtotime($datetime_input)) == $datetime_input;

							if ($datetime_is_valid) // New Data value (since 3.3.3)
							{
								$ex_reg_datetime_db = explode (' ', $datetime_input);
								$registered_date    = icagendaRender::dateToFormat(date('Y-m-d', strtotime($ex_reg_datetime_db['0'])));
								$reg_time_get       = isset($ex_reg_datetime_db['1']) ? $ex_reg_datetime_db['1'] : '';
							}
							else // Test if old date format (before 3.3.3) could be converted. If not, displays old format.
							{
								$ex_reg_datetime    = explode (' - ', trim($reguser->regDate));

								// Control if date valid format (Y-m-d) - Means could be converted
								$date_format   = 'Y-m-d H:i:s';
								$date_input    = $ex_reg_datetime['0'];
								$date_input    = trim($date_input);
								$date_str      = strtotime($date_input);
								$date_is_valid = date($date_format, $date_str) == $date_input;

								if ($date_is_valid)
								{
									$registered_date = icagendaRender::dateToFormat(date('Y-m-d', $date_str));
								}
								else
								{
									$registered_date = $ex_reg_datetime['0'];
								}

								$reg_time_get = isset($ex_reg_datetime['1']) ? $ex_reg_datetime['1'] : '';
							}

							$regDate.= $registered_date;

							if ($reg_time_get)
							{
								$regDate.= ' - ' . date('H:i', strtotime($reg_time_get));
							}
						}
						else
						{
							$regDate.= $reguser->regDate;
						}

						// Display List of Participants
						if ($n <= $nbmax || $n == $nbusers)
						{
							$list_participants.= '<table class="list_table ' . $column . '" cellpadding="0">'
												. '<tbody>'
												. '<tr>';

							$list_participants.= '<td class="imgbox">';
							$list_participants.= $participant_avatar;
							$list_participants.= '</td>';

							$list_participants.= '<td valign="middle">'
												. '<span class="list_name">' . $participant_name . '</span>'
												. '<span class="list_places"> (' . $reguser->regPeople . ')</span><br />'
												. '<span class="list_date">' . $regDate . '</span>'
												. '</td>';

							$list_participants.= '</tr></tbody></table>';
						}
					}
				}

				$list_participants.= '</div>';
			}

			// Avatar display
			elseif ($participantDisplay == 2)
			{
				$list_participants.= '<div ' . $slider_c . '>';

				foreach ($registeredUsers as $reguser)
				{
					$this_reg_date = strtotime($reguser->regDate)
									? JHtml::date($reguser->regDate, 'Y-m-d H:i', $eventTimeZone)
									: $reguser->regDate;

					if ( ($this_reg_date == $this_date || empty($get_date))
						|| $typeReg == 2
						|| ($typeReg == 1 && ! $this_reg_date)
						)
					{
						// Get participant privacy consents
						$user_actions = explode(',', $reguser->user_action);

						// Get Name consent
						$name_consents = array();

//						foreach ($user_actions AS $k => $action)
//						{
//							$ex_action = explode('__', $action);

//							if ($ex_action[0] == 'consent_name'
//								&& isset($ex_action[1]))
//							{
//								$name_consents = explode('_', $ex_action[1]);
//							}
//						}

						if (in_array('consent_name_public', $user_actions)) $name_consents = array('public');
						if (in_array('consent_name_users', $user_actions)) $name_consents = array('users');

						$force_name_visibility = $iCparams->get('participant_name_consent', '');

						if ($force_name_visibility == '0')
						{
							$participant_name_visibility = $iCparams->get('participant_name_visibility', '');

							if ($participant_name_visibility == 1)
							{
								$name_consents = array('public');
							}
							elseif ($participant_name_visibility == 2)
							{
								$name_consents = array('users');
							}
						}

						if (in_array('public', $name_consents)
							|| (in_array('users', $name_consents) && JFactory::getUser()->id > 0))
						{
							// Get Username and name (To check: Is it needed ?)
							if ( ! empty($reguser->userid))
							{
								$data_name     = $reguser->name;
								$data_username = $reguser->username;

								if ($nameJoomlaUser == 1)
								{
									$reguser->registeredUsers = $reguser->registeredUsers;
								}
								else
								{
									$reguser->registeredUsers = $data_username;
								}
							}

							$participant_name = $reguser->registeredUsers;
						}
						elseif($organiser_rights)
						{
							// Information to be extended for logged-in user with admin/organiser permissions...
							$participant_name = $reguser->registeredUsers;
						}
						else
						{
							$participant_name = JText::_('IC_ANONYMOUS');
						}

						// Get Gravatar consent
						if (in_array('consent_gravatar', $user_actions))
						{
							$avatar             = md5(strtolower(trim($reguser->regEmail)));
							$participant_avatar = '<img alt="' . $participant_name . '"  src="https://www.gravatar.com/avatar/' . $avatar . '?s=36&d=mm"/>';
						}
						else
						{
							$participant_avatar = '<span class="iCicon-avatar ic-avatar"></span>';
						}

						if ($n <= $nbmax || $n == $nbusers)
						{
							$list_participants.= '<div style="float:left; margin:3px; padding: 5px 0; text-align:center;"><div class="imgbox">' . $participant_avatar . '</div><strong style="text-align:center; font-size:9px;">' . $participant_name . '</strong></div>';
						}
					}
				}
				$list_participants.= '</div>';
			}

			// Name/username display
			elseif ($participantDisplay == 3)
			{
				$list_participants.= '<div ' . $slider_c . '>';
				$list_participants.= '<div class="' . $n_list . '">';

				$list_username = '';

				foreach ($registeredUsers as $reguser)
				{
					$this_reg_date  = strtotime($reguser->regDate)
									? JHtml::date($reguser->regDate, 'Y-m-d H:i', $eventTimeZone)
									: $reguser->regDate;

					if ( ($this_reg_date == $this_date ||empty($get_date))
						|| $typeReg == 2
						|| ($typeReg == 1 && ! $this_reg_date)
						)
					{
						// Get participant privacy consents
						$user_actions = explode(',', $reguser->user_action);

						// Get Name consent
						$name_consents = array();

//						foreach ($user_actions AS $k => $action)
//						{
//							$ex_action = explode('__', $action);

//							if ($ex_action[0] == 'consent_name'
//								&& isset($ex_action[1]))
//							{
//								$name_consents = explode('_', $ex_action[1]);
//							}
//						}

						if (in_array('consent_name_public', $user_actions)) $name_consents = array('public');
						if (in_array('consent_name_users', $user_actions)) $name_consents = array('users');

						$force_name_visibility = $iCparams->get('participant_name_consent', '');

						if ($force_name_visibility == '0')
						{
							$participant_name_visibility = $iCparams->get('participant_name_visibility', '');

							if ($participant_name_visibility == 1)
							{
								$name_consents = array('public');
							}
							elseif ($participant_name_visibility == 2)
							{
								$name_consents = array('users');
							}
						}

						if (in_array('public', $name_consents)
							|| (in_array('users', $name_consents) && JFactory::getUser()->id > 0))
						{
							// Get Username and name (To check: Is it needed ?)
							if ( ! empty($reguser->userid))
							{
								$data_name     = $reguser->name;
								$data_username = $reguser->username;

								if ($nameJoomlaUser == 1)
								{
									$reguser->registeredUsers = $reguser->registeredUsers;
								}
								else
								{
									$reguser->registeredUsers = $data_username;
								}
							}

							$participant_name = $reguser->registeredUsers;
						}
						elseif($organiser_rights)
						{
							// Information to be extended for logged-in user with admin/organiser permissions...
							$participant_name = $reguser->registeredUsers;
						}
						else
						{
							$participant_name = JText::_('IC_ANONYMOUS');
						}


						$list_username.= $participant_name . ', ';
					}
				}

				$list_participants.= trim($list_username, ", ");

				$list_participants.= '</div>';
				$list_participants.= '</div>';
			}

			if ($participantSlide == 1)
			{
				$list_participants.= JHtml::_('sliders.end');
			}
		}
		else
		{
			$list_participants.= '';
		}

		return $list_participants;
	}
}
