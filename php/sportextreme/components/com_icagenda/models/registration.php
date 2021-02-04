<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.9 2019-05-15
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

if (version_compare(JVERSION, '3.4', 'lt'))
{
	jimport('joomla.application.component.modelitem');
	jimport('joomla.registry.registry');
}

use Joomla\Registry\Registry;

/**
 * Registration model class for iCagenda
 */
class iCagendaModelRegistration extends JModelForm
{
	protected $_context = 'com_icagenda.registration';

	protected $data;

	/**
	 * Method to get the registration form data.
	 *
	 * The base form data is loaded and then an event is fired
	 * for users plugins to extend the data.
	 *
	 * @return  mixed  Data object on success, false on failure.
	 *
	 * @since   3.6.0
	 */
	public function getData()
	{
		if ($this->data === null)
		{
			$this->data = new stdClass;
			$app = JFactory::getApplication();
			$params = JComponentHelper::getParams('com_icagenda');

			// Override the base user data with any data in the session.
			$temp = (array) $app->getUserState('com_icagenda.registration.data', array());

			foreach ($temp as $k => $v)
			{
				$this->data->$k = $v;
			}
		}

		return $this->data;
	}

	/**
	 * Method to get the registration form.
	 *
	 * The base form is loaded from XML and then an event is fired
	 * for users plugins to extend the form with extra fields.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since   3.6.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_icagenda.registration', 'registration', array('control' => 'jform', 'load_data' => $loadData));

		// Get the application object.
		$app = JFactory::getApplication();
		$params = $app->getParams();

		$user = JFactory::getUser();
		$user_id = $user->get('id');

		// Autofill name and email if registered user log in
		if ($user_id && $params->get('autofilluser', 1) == 1)
		{
			$form->setValue('uid', null, $user_id);

			// logged-in Users: Name/User Name Option
			$user_name = ($params->get('nameJoomlaUser', 1) == 1) ? $user->get('name') : $user->get('username');
			$user_mail = $user->get('email');
		}
		else
		{
			$form->setFieldAttribute('uid', 'disabled', 'disabled');

			$user_name = '';
			$user_mail = '';
		}

		$nameAttr = ! empty($user_name) ? 'readonly' : 'required';
		$form->setFieldAttribute('name', $nameAttr, 'true');

		if ($user_name)
		{
			$form->setValue('name', null, $user_name);
		}

		// Set CORE 'Email' attributes
		if ($user_mail)
		{
			$form->setValue('email', null, $user_mail);
			$form->setFieldAttribute('email', 'readonly', 'true');
			$form->removeField('email2');
		}
		else
		{
			if ( ! $params->get('emailConfirm', 1))
			{
				$form->removeField('email2');
			}

			if ($params->get('emailRequired', 1))
			{
				$form->setFieldAttribute('email', 'required', 'true');

				if ($params->get('emailConfirm', 1))
				{
					$form->setFieldAttribute('email2', 'required', 'true');
				}
			}
		}

		// Set CORE 'Phone' attributes
		if ($params->get('phoneRequired', 0))
		{
			$form->setFieldAttribute('phone', 'required', 'true');
			$form->setFieldAttribute('phone', 'validate', 'ic.tel');
		}

		// Check if Custom Fields required @commented 3.6.5
//		$listCustomFields = icagendaCustomfields::getCustomFields(1, $params->get('custom_form', ''), 1);

//		if ($listCustomFields)
//		{
//			foreach ($listCustomFields AS $cf)
//			{
//				if ($cf->required == 1)
//				{
//					$form->setFieldAttribute(str_replace('core_', '', $cf->slug), 'required', 'true');
//				}
//			}
//		}

		// If Participants List is not enabled, we remove validation for related privacy boxes
		if ($params->get('participantList', 0) == 0)
		{
			// Do not remove name consent if required
			if ($params->get('participant_name_consent', '') != '1')
			{
				$form->removeField('consent_name_public', 'consent');
				$form->removeField('consent_name_users', 'consent');
			}

			// Do not remove Gravatar consent if required
			if ($params->get('participant_gravatar_consent', '') != '1')
			{
				$form->removeField('consent_gravatar', 'consent');
			}
		}
		else
		{
			$participant_name_visibility = $params->get('participant_name_visibility', '');

			if ($participant_name_visibility == 1)
			{
				$form->removeField('consent_name_users', 'consent');
			}
			elseif ($participant_name_visibility == 2)
			{
				$form->removeField('consent_name_public', 'consent');
			}
			else
			{
				$form->removeField('consent_name_public', 'consent');
				$form->removeField('consent_name_users', 'consent');
			}
		}

		// If Consent Organiser is not enabled, we remove validation for related privacy box
		if ($params->get('privacy_organiser', 0) == 0)
		{
			$form->removeField('consent_organiser', 'consent');
		}

		// If Terms and Conditions is not enabled, we remove validation for checkbox
		if ($params->get('terms', 1) == 0)
		{
			$form->removeField('consent_terms', 'consent');
		}

		// If Captcha not displayed, we remove validation for captcha form field
		if ($params->get('reg_captcha', 0) == 0)
		{
			$form->removeField('captcha');
		}

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   3.6.0
	 */
	protected function loadFormData()
	{
		$data = $this->getData();

//		$this->preprocessData('com_icagenda.registration', $data);

		return $data;
	}

	/**
	 * Override preprocessForm to load the '#' plugin group instead of content.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @since   3.6.0
	 * @throws  Exception if there is an error in the form event.
	 */
//	protected function preprocessForm(JForm $form, $data, $group = 'content')
//	{
//		parent::preprocessForm($form, $data, $group);
//	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   3.6.0
	 */
	protected function populateState()
	{
		// Get the application object.
		$app = JFactory::getApplication();

		// Load the parameters.
		$params = $app->getParams('com_icagenda');
		$this->setState('params', $params);

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('event.id', $pk);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $temp  The form data.
	 *
	 * @return  mixed  @todo : update return (The user id on success, false on failure).
	 *
	 * @since   3.6.0
	 */
	public function register($temp)
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$data = (array) $this->getData();

		foreach ($temp as $k => $v)
		{
			$data[$k] = $v;
		}

		$db     = JFactory::getDbo();
		$date   = JFactory::getDate();
		$params = $app->getParams();

		$eventTimeZone = null;

			$reg_data = new stdClass();

			// Set the values
			$reg_data->id               = null;
			$reg_data->userid           = isset($data['uid']) ? $data['uid'] : '';
			$reg_data->name             = isset($data['name']) ? $data['name'] : '';
			$reg_data->email            = isset($data['email']) ? $data['email'] : '';
			$reg_data->phone            = isset($data['phone']) ? trim($data['phone']) : '';
			$reg_data->date             = isset($data['date']) ? $data['date'] : '';
			$reg_data->period           = isset($data['period']) ? $data['period'] : '0';
			$reg_data->people           = isset($data['people']) ? $data['people'] : '1';
//			$reg_data->notes            = isset($data['notes']) ? htmlentities(strip_tags($data['notes'])) : '';
			$reg_data->notes            = isset($data['notes']) ? $data['notes'] : '';
			$reg_data->eventid          = isset($data['eventid']) ? $data['eventid'] : '0';
			$reg_data->itemid           = isset($data['menuid']) ? $data['menuid'] : '';
			$reg_data->created          = $date->toSql();
			$reg_data->created_by       = $reg_data->userid;
			$reg_data->checked_out_time = date('Y-m-d H:i:s');

			$type_registration = isset($data['type_registration']) ? $data['type_registration'] : '1';
			$current_url       = isset($data['current_url']) ? $data['current_url'] : 'index.php';
			$max_nb_of_tickets = isset($data['max_nb_of_tickets']) ? $data['max_nb_of_tickets'] : '1000000';
			$custom_fields     = isset($data['custom_fields']) ? $data['custom_fields'] : false;
			$email2            = isset($data['email2']) ? $data['email2'] : false;
			$consent_data      = isset($data['consent']) ? $data['consent'] : array();


			// Control if still ticket left
			$registered = icagendaRegistration::getRegisteredTotal($reg_data->eventid, $reg_data->date, $type_registration);

			// Check number of tickets left
			$tickets_left = $max_nb_of_tickets - $registered;

			// IF NO TICKETS LEFT
			if ($tickets_left <= 0 || $registered >= $max_nb_of_tickets)
			{
				$app->enqueueMessage(JText::_('COM_ICAGENDA_ALERT_NO_TICKETS_AVAILABLE'), 'warning');

//				$app->redirect(htmlspecialchars_decode($urlEvent));
				return false;
			}

			// IF NOT ENOUGH TICKETS LEFT
			elseif ($tickets_left < $reg_data->people)
			{
				$msg = JText::_('COM_ICAGENDA_ALERT_NOT_ENOUGH_TICKETS_AVAILABLE') . '<br />';
				$msg.= JText::sprintf('COM_ICAGENDA_ALERT_NOT_ENOUGH_TICKETS_AVAILABLE_NOW', $tickets_left) . '<br />';
				$msg.= JText::_('COM_ICAGENDA_ALERT_NOT_ENOUGH_TICKETS_AVAILABLE_CHANGE_NUMBER');

				$app->enqueueMessage($msg, 'error');

//				$app->redirect(htmlspecialchars_decode($current_url));
				return false;
			}


			// CONTROL NAME VALUE
			$name_isValid = '1';

			$pattern = "#[/\\\\/\<>/\"%;=\[\]\+()&]|^[0-9]#i";
			$pattern = "#[/\\\\/\<>/\";=\[\]\+()%&]#i";

			// Filter Name
			$data['name'] = str_replace("'", '’', $data['name']);
			
			// Remove non-printable characters.
			$data['name'] = (string) preg_replace('/[\x00-\x1F\x7F]/', '', $data['name']);

			if ($data['name'])
			{
				$nbMatches = preg_match($pattern, $data['name']);

				// Name contains invalid characters
				if ($nbMatches && $nbMatches == 1)
				{
					$name_isValid = '0';
					$app->enqueueMessage(JText::sprintf('COM_ICAGENDA_REGISTRATION_NAME_NOT_VALID', '<strong>' . htmlentities($data['name'], ENT_COMPAT, 'UTF-8') . '</strong>'), 'error');

					return false;
				}

				// Name is less than 2 characters
				// @TODO change minimum to an option or 1 letter ?...
				if (strlen(utf8_decode(trim($data['name']))) < 2)
				{
					$name_isValid = '0';
					$app->enqueueMessage(JText::_('COM_ICAGENDA_REGISTRATION_NAME_MINIMUM_CHARACTERS'), 'error');

					return false;
				}
			}

			$reg_data->name = filter_var($reg_data->name, FILTER_SANITIZE_STRING);

			// Advanced Checkdnsrr email
			$emailCheckdnsrr = JComponentHelper::getParams('com_icagenda')->get('emailCheckdnsrr', '0');

			if ( ! empty($reg_data->email))
			{
				$validEmail = true;
				$checkdnsrr = true;

				if (($emailCheckdnsrr == 1) && function_exists('checkdnsrr'))
				{
					$provider = explode('@', $reg_data->email);

					if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
					{
						if (version_compare(phpversion(), '5.3.0', '<'))
						{
							$checkdnsrr = true;
						}
					}
					else
					{
						$checkdnsrr = checkdnsrr($provider[1]);
					}
				}
				else
				{
					$checkdnsrr = true;
				}
			}
			else
			{
				$checkdnsrr = true;
			}

			// Check if valid email address
			$validEmail = $validEmail ? icagendaRegistration::validEmail($reg_data->email) : false;

			if ( ! $checkdnsrr
				|| ! $validEmail
				&& $reg_data->email
				)
			{
				// message if email is invalid
				$app->enqueueMessage(JText::_('COM_ICAGENDA_REGISTRATION_EMAIL_NOT_VALID'), 'error');

				return false;
			}

			$eventid = (int) $reg_data->eventid;
			$period  = (int) $reg_data->period;
			$people  = (int) $reg_data->people;
			$userid  = (int) $reg_data->userid;
			$name    = $reg_data->name;
			$email   = $reg_data->email;
			$phone   = $reg_data->phone;
			$notes   = html_entity_decode($reg_data->notes);
			$dateReg = $reg_data->date;

			$limitRegEmail = $params->get('limitRegEmail', 1);
			$limitRegDate  = $params->get('limitRegDate', 1);

			if ($limitRegEmail == 1 || $limitRegDate == 1)
			{
				$query = $db->getQuery(true);

				if ($limitRegDate == 0)
				{
					$query = "
						SELECT COUNT(*)
						FROM `#__icagenda_registration`
						WHERE `email` = '$email' AND `eventid`='$eventid' AND `state`='1'
					";
				}
				elseif ($limitRegDate == 1)
				{
					$query = "
						SELECT COUNT(*)
						FROM `#__icagenda_registration`
						WHERE `email` = '$email' AND `eventid`='$eventid' AND `date`='$dateReg' AND `state`='1'
					";
				}

				$db->setQuery($query);

				if ($email != NULL)
				{
					if ($db->loadResult() > 0)
					{
						$app->enqueueMessage(JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ALERT' ) . ' ' . $reg_data->email, 'warning');

						return false;
					}
				}
			}


			// Control Custom Fields required if not empty
			if ($custom_fields && is_array($custom_fields))
			{
				$requiredEmptyFields = icagendaCustomfields::requiredIsEmpty($custom_fields, 1);

				if ( ! empty($requiredEmptyFields))
				{
					foreach ($requiredEmptyFields as $fieldEmpty)
					{
						$app->enqueueMessage(JText::sprintf('JLIB_FORM_VALIDATE_FIELD_INVALID', $fieldEmpty), 'warning');
					}

					return false;
				}
			}


		// Get Event params
		$query = $db->getQuery(true);
		$query->select('e.params')
			->from('#__icagenda_events AS e')
			->where('e.id = ' . (int) $eventid);
		$db->setQuery($query);
		$event = $db->loadObject();

		// Check for Registration Actions
		$evtParams       = icagendaEvent::evtParams($event->params);
		$reg_actions     = $evtParams->get('registration_actions', $params->get('registration_actions', 0));
//		$reg_actions     = ($reg_actions !== 0) ? $reg_actions : '';

		if ($reg_actions)
		{
			$reg_data->state = 0;
			$reg_data->status = 2;
		}
//		else // @todo: enable in 3.8 (3.7.3 ?)
//		{
//			$reg_data->state = 1;
//			$reg_data->status = 3;
//		}


		// Save Registration data.
		$db->insertObject('#__icagenda_registration', $reg_data, 'id');


		// Save Custom Fields to database.
		if ($custom_fields && is_array($custom_fields))
		{
			icagendaCustomfields::saveToData($custom_fields, $reg_data->id, 1);
		}

		// Save Consents to database.
		if ($consent_data && is_array($consent_data))
		{
 			foreach ($consent_data AS $key => $value)
			{
				if ($value)
				{
					$key_suffix = '';
					$key_string = $key;

//					if (is_array($value) && $key == 'consent_name')
//					{
//						$key_suffix.= '_';

//						foreach ($value as $k => $v)
//						{
//							$key_suffix.= '_' . $v;
//						}
//					}

					// Create and populate an object.
					$action = new stdClass();

					$action->user_id        = $reg_data->userid;
					$action->user_action    = $key . $key_suffix;
					$action->parent_form    = 1; // Registration
					$action->parent_id      = $reg_data->id; // Registration ID

					switch($key_string)
					{
						// Name Visibility: Public
						case 'consent_name_public':
							$action->action_subject = JText::_('COM_ICAGENDA_REGISTRATION_CONSENT_NAME_LABEL');
							$action->action_body    = strip_tags(JText::_('COM_ICAGENDA_REGISTRATION_CONSENT_NAME'));
							break;

						// Name Visibility: Users
						case 'consent_name_users':
							$action->action_subject = JText::_('COM_ICAGENDA_REGISTRATION_CONSENT_NAME_LABEL');
							$action->action_body    = strip_tags(JText::_('COM_ICAGENDA_REGISTRATION_' . strtoupper($key_string)));
							break;

						// Terms
						case 'consent_terms':
							$action->action_subject = JText::_('COM_ICAGENDA_REGISTRATION_' . strtoupper($key_string) . '_LABEL');
							$action->action_body    = JText::sprintf('COM_ICAGENDA_REGISTRATION_' . strtoupper($key_string), JText::_('COM_ICAGENDA_REGISTRATION_CONSENT_TERMS_LABEL'));
							break;

						// Default
						default:
							$action->action_subject = JText::_('COM_ICAGENDA_REGISTRATION_' . strtoupper($key_string) . '_LABEL');
							$action->action_body    = strip_tags(JText::_('COM_ICAGENDA_REGISTRATION_' . strtoupper($key_string)));
							break;
					}

					$action->user_ip        = $app->input->server->get('REMOTE_ADDR', '', 'string');
					$action->user_agent     = $app->input->server->get('HTTP_USER_AGENT', '', 'string');
					$action->state          = 1;
					$action->created_time   = JFactory::getDate()->toSql();

					// Insert the object into the user profile table.
					$result = JFactory::getDbo()->insertObject('#__icagenda_user_actions', $action);
				}
			}
		}


		// Set Registration ID in the session.
		$app->setUserState('com_icagenda.registration.regid', $reg_data->id);


		if ($reg_actions)
		{
			return $reg_actions;
		}
		else
		{
			$this->sendNotificationEmails($reg_data);

			return 'complete';
		}
	}


	/**
	 * Method to complete action on the form data.
	 *
	 * @since   3.6.13
	 */
	public function actions($action, $data, $regData)
	{
		if ($data)
		{
			JPluginHelper::importPlugin('icagenda');

			$dispatcher = JEventDispatcher::getInstance();
			$complete   = $dispatcher->trigger('iCagendaOnRegistrationActionsComplete', array('com_icagenda.registration', $data, $regData['id']));

			if ($complete)
			{
				$this->sendNotificationEmails((object)$regData, $data);
			}
		}

		return $complete;
	}


	/**
	 * Method to cancel date(s) registration for one event.
	 *
	 * @since   3.6.13
	 */
	public function cancel($dates_cancelled = array(), $user_id = null)
	{
		if ( ! empty($dates_cancelled) && (int) $user_id)
		{
			$app  = JFactory::getApplication();
			$date = JFactory::getDate();
			$db   = JFactory::getDbo();

			JArrayHelper::toInteger($dates_cancelled);
			$dates_cancelled = implode(',', $dates_cancelled);

			// Conditions for which records should be cancelled.
			$conditions = array(
				$db->quoteName('state') . ' <> 0', 
				$db->quoteName('id') . ' IN (' . $dates_cancelled . ')', 
				$db->quoteName('userid') . ' = ' . $db->quote($user_id),
			);

			// Check if any to be cancelled.
			$query = $db->getQuery(true);
			$query->select('r.*');
			$query->from('#__icagenda_registration AS r');
			$query->where($conditions);
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if ( ! $result) return false;

			// Update registration(s) cancelled
			$query = $db->getQuery(true);

			$user_ip     = $app->input->server->get('REMOTE_ADDR', '', 'string');
			$user_agent  = $app->input->server->get('HTTP_USER_AGENT', '', 'string');
			$date_cancel = $date->toSql();

			// Save user action for each registration cancelled.
			foreach ($result as $k => $v)
			{
				// Create and populate an object.
				$action = new stdClass();

				$action->user_id        = $user_id;
				$action->user_action    = 'cancel_registration';
				$action->parent_form    = 1; // Registration
				$action->parent_id      = $v->id; // Registration ID
				$action->action_subject = JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_USERACTION_SUBJECT');
				$action->action_body    = strip_tags(JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_USERACTION_BODY'));
				$action->user_ip        = $user_ip;
				$action->user_agent     = $user_agent;
				$action->state          = 1;
				$action->created_time   = $date_cancel;

				// Insert the object into the user profile table.
				$result = $db->insertObject('#__icagenda_user_actions', $action);

				// Update params for each registration cancelled, adding cancelled date and by user id.
//				$params = json_decode($v->params, true);

//				$params['cancelled']    = (string) $date->toSql();
//				$params['cancelled_by'] = (string) $user_id;

				// Store the combined new and existing values back as a JSON string
//				$paramsString = json_encode($params);

//				$query->update($db->quoteName('#__icagenda_registration'))->set($db->quoteName('params') . ' = ' . $db->quote($paramsString));
//				$db->setQuery($query);
			}

			// Common fields to update.
			$fields = array(
				$db->quoteName('state') . ' = 0',
				$db->quoteName('status') . ' = 0',
			);

			$query->update($db->quoteName('#__icagenda_registration'))->set($fields)->where($conditions);
			$db->setQuery($query);

			$result = $db->execute();

			return $result;
		}
	}


	/**
	 * Method to get event data.
	 *
	 * @param   integer  $pk  The id of the event.
	 *
	 * @return  mixed  Event item data object on success, false on failure.
	 *
	 * @since   3.6.0
	 */
	public function getItem($pk = null)
	{
		$app     = JFactory::getApplication();
		$session = JFactory::getSession();
		$user    = JFactory::getUser();

		$input = $app->input;

		$pk = ( ! empty($pk)) ? $pk : (int) $this->getState('event.id');

		if ( ! isset($this->_item[$pk]))
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'e.*'
						)
					);
				$query->from('#__icagenda_events AS e');

				// Join on category table.
				$query->select('c.title AS cat_title, c.color AS cat_color')
					->join('LEFT', '#__icagenda_category AS c on c.id = e.catid');

				// Filter by language
				if ($this->getState('filter.language'))
				{
					$query->where('e.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
				}

				$query->where('e.id = ' . (int) $pk);

				// Features - extract the number of displayable icons per event
				$query->select('feat.count AS features');
				$sub_query = $db->getQuery(true);
				$sub_query->select('fx.event_id, COUNT(*) AS count');
				$sub_query->from('`#__icagenda_feature_xref` AS fx');
				$sub_query->innerJoin("`#__icagenda_feature` AS f ON fx.feature_id=f.id AND f.state=1 AND f.icon<>'-1'");
				$sub_query->group('fx.event_id');
				$query->leftJoin('(' . (string) $sub_query . ') AS feat ON e.id=feat.event_id');

				// Registrations - Get total of registered people
				$evtParams  = icagendaEvent::getParams((int) $pk);

				$typeReg    = $evtParams->get('typeReg', 1);

				$query->select($db->qn('r.count', 'registered'));
				$sub_query  = $db->getQuery(true)
							->select(array(
									$db->qn('r.eventid'),
									'sum(' . $db->qn('r.people') . ') AS count',
								))
							->from($db->qn('#__icagenda_registration', 'r'))
							->where($db->qn('r.state') . ' = 1');

				// Get var event date alias if set or var 'event_date' set to session in event details view
				$event_date = $session->get('event_date', '');
				$get_date   = $input->get('date', ($event_date ? date('Y-m-d-H-i', strtotime($event_date)) : ''));

				// Convert to SQL datetime if set, or return empty.
				$dateday    = icagendaEvent::convertDateAliasToSQLDatetime($get_date);

				// Redirect and remove date var, if not correctly set
				if ($get_date
					&& ! $dateday)
				{
					$event_url = JUri::getInstance()->toString();
					$cleanurl  = preg_replace('/&date=[^&]*/', '', $event_url);
					$cleanurl  = preg_replace('/\?date=[^\?]*/', '', $cleanurl);

					$app->redirect($cleanurl);

					return false;
				}

				// Registration type: by single date/period (1)
				if ($dateday && $typeReg == 1)
				{
//					$sub_query->where('r.date = ' . $db->q($dateday)); // This is the good logic if correctly set
					$sub_query->where('(r.date = ' . $db->q($dateday) . ' OR (r.date = "" AND r.period = 1))');
				}
				elseif ( ! $dateday && $typeReg == 1)
				{
//					$sub_query->where('r.date = "" AND r.period = 0'); // This is the good logic if correctly set
					$sub_query->where('r.date = ""');
				}

				$sub_query->group('r.eventid');
				$query->leftJoin('(' . (string) $sub_query . ') AS r ON e.id = r.eventid');

				$db->setQuery($query);

				$item = $db->loadObject();

				if (empty($item))
				{
					throw new \Exception(\JText::_('COM_ICAGENDA_ERROR_EVENT_NOT_FOUND'), 404);
				}
				else
				{
					// Convert parameter fields to objects.
					$registry = version_compare(JVERSION, '3.4', 'lt') ? new JRegistry : new Registry;
					$registry->loadString($item->params);

					// Merge Event params to app params
					$item->params = clone $this->getState('params');
					$item->params->merge($registry);

					// iCagenda event view variables
//					$item->typeReg             = $item->params->get('typeReg', ''); // DEPRECATED 3.6.0
//					$item->maxNbTicketsPerReg  = icagendaRegistration::maxNbTicketsPerReg($item->params); // DEPRECATED (to be refactoried)
//					$item->maxNbTickets = icagendaRegistration::maxNbTickets($item); // DEPRECATED 3.6.0

					$item->typeReg      = $item->params->get('typeReg', '1');
					$item->maxReg       = $item->params->get('maxReg', '1000000');
					$maxNbTicketsPerReg = icagendaRegistration::maxNbTicketsPerReg($item->params);

					// Set default nb of tickets bookable
					$session_date       = $session->get('session_date', '');

					$item->ticketsBookable = icagendaRegistration::getTicketsBookable($item->id, $session_date, $item->typeReg, $item->maxReg, $maxNbTicketsPerReg);

					if ($session_date)
					{
						$item->default_tickets      = $item->ticketsBookable;
					}

					$item->tickets      = ($item->maxReg >= $maxNbTicketsPerReg) ? $maxNbTicketsPerReg : $item->maxReg;

					$item->eventHasPeriod      = icagendaEvent::eventHasPeriod($item->period, $item->startdate, $item->enddate);
					$item->periodIsNotFinished = icagendaEvent::periodIsNotFinished($item->enddate);

					// Extract the feature details, if needed
					if (is_null($item->features))
					{
						$item->features = array();
					}
					else
					{
						$db = $this->getDbo();
						$query = $db->getQuery(true);
						$query->select('DISTINCT f.icon, f.icon_alt');
						$query->from('`#__icagenda_feature_xref` AS fx');
						$query->innerJoin("`#__icagenda_feature` AS f ON fx.feature_id=f.id AND f.state=1 AND f.icon<>'-1'");
						$query->where('fx.event_id=' . $item->id);
						$query->order('f.ordering DESC'); // Order descending because the icons are floated right
						$db->setQuery($query);
						$item->features = $db->loadObjectList();
					}
				}

				$this->_item[$pk] = $item;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					throw new \Exception($e->getMessage(), 404);
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	}

	/**
	 * Method to get registration data.
	 *
	 * @param   integer  $regid  The id of the registration.
	 *
	 * @return  mixed  Registration processed data object on success, false on failure.
	 *
	 * @since   3.6.0
	 */
	public function getRegistration($regid = null)
	{
		$app = JFactory::getApplication();

		$regid = ( ! empty($regid)) ? $regid : $app->getUserState('com_icagenda.registration.regid', '');

		if ($regid)
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'r.*'
						)
					);
				$query->from('#__icagenda_registration AS r');
				$query->where('r.id = ' . (int) $regid);
				$db->setQuery($query);

				$registration = $db->loadObject();

				if (empty($registration))
				{
					return false;
				}
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					throw new \Exception($e->getMessage(), 404);
				}
				else
				{
					$this->setError($e);
					$registration = false;
				}
			}
		}
		else
		{
			return false;
		}

		return $registration;
	}

	/**
	 * Method to get registrations of the current user for one event.
	 *
	 * @param   integer  $eventID  The id of the event.
	 *
	 * @since   3.6.13
	 */
	public function getParticipantEventRegistrations($eventID = null) // getRegistrationsPublished
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

//		$eventID = ( ! empty($eventID)) ? $eventID : $app->input->getInt('id');
		$eventID = ( ! empty($eventID)) ? $eventID : (int) $this->getState('event.id');

		if ($eventID && $user->get('id'))
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true);
				$query->select('r.id, r.state, r.date, r.period');
				$query->from('#__icagenda_registration AS r');
				$query->where('r.state = 1');
				$query->where('r.eventid = ' . (int) $eventID);
				$query->where('r.userid = ' . (int) $user->get('id'));
				$db->setQuery($query);

				$registrationIDs = $db->loadObjectList();

				if (empty($registrationIDs))
				{
					return false;
				}
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					throw new \Exception($e->getMessage(), 404);
				}
				else
				{
					$this->setError($e);
					$registrationIDs = false;
				}
			}
		}
		else
		{
			return false;
		}

		return $registrationIDs;
	}


	/**
	 * Method to send Notification emails.
	 *
	 * @since   3.6.13
	 */
	private function sendNotificationEmails($reg_data, $data = null)
	{
		$app    = JFactory::getApplication();
		$db     = JFactory::getDbo();
		$user   = JFactory::getUser();

		$params = $app->getParams();
		$input  = $app->input;


		$eventid = $reg_data->eventid;

		// Get Event details
		$query  = $db->getQuery(true);
		$query->select('e.*,
				e.created_by AS authorID, e.email AS contactemail')
			->from('#__icagenda_events AS e')
			->where('e.id = ' . (int) $eventid);
		$db->setQuery($query);
		$event  = $db->loadObject();


		// Set Date in url
		$dateInUrl  = ($reg_data->date && $params->get('datesDisplay', 1) == 1)
					? '&amp;date=' . iCDate::dateToAlias(date('Y-m-d H:i', strtotime($reg_data->date)))
					: '';

		// Get the "event" URL
		$baseURL    = JUri::base();
		$subpathURL = JUri::base(true);

		$baseURL    = str_replace('/administrator', '', $baseURL);
		$subpathURL = str_replace('/administrator', '', $subpathURL);

		// Sub Path filtering
		$subpathURL = ltrim($subpathURL, '/');

		// URL Event Details filtering
		$urlEvent  = str_replace('&amp;', '&', JRoute::_('index.php?option=com_icagenda&view=event&id=' . (int) $reg_data->eventid . '&Itemid=' . $input->getInt('Itemid') . $dateInUrl));
		$urlEvent  = ltrim($urlEvent, '/');

		if (substr($urlEvent, 0, strlen($subpathURL) + 1) == "$subpathURL/")
		{
			$urlEvent = substr($urlEvent, strlen($subpathURL) + 1);
		}

		$urlEvent  = rtrim($baseURL, '/') . '/' . ltrim($urlEvent, '/');

		// URL Registration Cancellation filtering
		$urlRegistrationCancel  = str_replace('&amp;', '&', JRoute::_('index.php?option=com_icagenda&view=registration&layout=cancel&id=' . (int) $reg_data->eventid . '&Itemid=' . $input->getInt('Itemid')));
		$urlRegistrationCancel  = ltrim($urlRegistrationCancel, '/');

		if (substr($urlRegistrationCancel, 0, strlen($subpathURL) + 1) == "$subpathURL/")
		{
			$urlRegistrationCancel = substr($urlRegistrationCancel, strlen($subpathURL) + 1);
		}

		$urlRegistrationCancel  = rtrim($baseURL, '/') . '/' . ltrim($urlRegistrationCancel, '/');


			$startdate    = $event->startdate;
			$enddate      = $event->enddate;
//			$title        = $event->title;
			$authorID     = $event->authorID;
//			$contactemail = $event->contactemail;
			$displayTime  = $event->displaytime;

			$startD = strip_tags(icagendaRender::dateToFormat($startdate));
			$endD   = strip_tags(icagendaRender::dateToFormat($enddate));
			$startT = ! empty($displayTime) ? icagendaRender::dateToTime($startdate) : '';
			$endT   = ! empty($displayTime) ? icagendaRender::dateToTime($enddate) : '';

			$regDate = strip_tags(icagendaRender::dateToFormat($reg_data->date));
			$regTime = icagendaRender::dateToTime($reg_data->date);

			$regDateTime      = ! empty($displayTime) ? $regDate.' - '.$regTime : $regDate;
			$regStartDateTime = ! empty($displayTime) ? $startD.' - '.$startT : $startD;
			$regEndDateTime   = ! empty($displayTime) ? $endD.' - '.$endT : $endD;

			$periodreg = $reg_data->period;

			$defaultemail           = $params->get('regEmailUser', '1');
			$emailUserSubjectPeriod = $params->get('emailUserSubjectPeriod', '');
			$emailUserBodyPeriod    = $params->get('emailUserBodyPeriod', '');
			$emailUserSubjectDate   = $params->get('emailUserSubjectDate', '');
			$emailUserBodyDate      = $params->get('emailUserBodyDate', '');

			$emailAdminSend         = $params->get('emailAdminSend', '1');
			$emailAdminSend_select  = $params->get('emailAdminSend_select', array('0'));
			$emailAdminSend_custom  = $params->get('emailAdminSend_Placeholder', '');

			$emailUserSend          = $params->get('emailUserSend', '1');

			$eUSP = isset($emailUserSubjectPeriod)
					? $emailUserSubjectPeriod
					: JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_PERIOD_DEFAULT_SUBJECT' );

			$eUBP = isset($emailUserBodyPeriod)
					? $emailUserBodyPeriod
					: JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_PERIOD_DEFAULT_BODY' );

			$eUSD = isset($emailUserSubjectDate)
					? $emailUserSubjectDate
					: JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_SUBJECT' );

			$eUBD = isset($emailUserBodyDate)
					? $emailUserBodyDate
					: JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_BODY' );

			$period_set = substr($startdate, 0, 4);

			// Registration Type is set 'for all dates of the event'
			if ($periodreg == 1)
			{
				$adminsubject = JText::_('COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_DEFAULT_SUBJECT');
				$adminbody    = JText::_('COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_DATE_DEFAULT_BODY');

				if ($defaultemail == 0)
				{
					$subject = $eUSP;
					$body    = $eUBP;
				}
				else
				{
					$subject = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_SUBJECT' );
					$body    = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_BODY' );
				}
			}

			// Registration Type is 'select list of dates' (single dates + period)
			// Period (no date, and period = 0)
			elseif ($reg_data->date == '' && ! $periodreg)
			{
				$adminsubject   = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_DEFAULT_SUBJECT' );
				$adminbody      = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_PERIOD_DEFAULT_BODY' );

				if ($defaultemail == 0)
				{
					$subject = $eUSP;
					$body    = $eUBP;
				}
				else
				{
					$subject = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_PERIOD_DEFAULT_SUBJECT' );
					$body    = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_PERIOD_DEFAULT_BODY' );
				}
			}

			// Registration Type is 'select list of dates' (single dates + period)
			// Single date
			else
			{
				$adminsubject = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_DEFAULT_SUBJECT' );
				$adminbody    = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_DATE_DEFAULT_BODY' );

				if ($defaultemail == 0)
				{
					$subject = $eUSD;
					$body    = $eUBD;
				}
				else
				{
					$subject = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_SUBJECT' );
					$body    = JText::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_BODY' );
				}
			}

			// Get the site name
			$sitename = $app->getCfg('sitename');

			$siteURL = JURI::base();
			$siteURL = rtrim($siteURL,'/');

			// Get Author Email
			$authormail = '';

			if ($authorID != NULL)
			{
				// Preparing the query
				$query = $db->getQuery(true);
				$query->select('email AS authormail, name AS authorname')->from('#__users AS u')->where("(u.id=$authorID)");
				$db->setQuery($query);
				$result = $db->loadObject();

				$authormail = $result->authormail;
				$authorname = $result->authorname;

				if ($authormail == NULL)
				{
					$authormail = $app->getCfg('mailfrom');
				}
			}

			// Generates filled custom fields into email body
			$customfields = icagendaCustomfields::getListNotEmpty($reg_data->id, 1);

 			$custom_fields = '';

			$newline = ($defaultemail == '0') ? "<br />" : "\n";

			if ($customfields)
			{
				foreach ($customfields AS $customfield)
				{
					$cf_value      = isset($customfield->cf_value) ? $customfield->cf_value : JText::_('IC_NOT_SPECIFIED');
					$custom_fields.= $customfield->cf_title . ": " . $cf_value . $newline;
				}
			}

			if ($data)
			{
				JPluginHelper::importPlugin('icagenda');

				$dispatcher   = JEventDispatcher::getInstance();
				$replacements = $dispatcher->trigger('iCagendaOnRegistrationActionsCompleteEmail', array('com_icagenda.registration', $data, $reg_data));

				list($replacements) = $replacements;

				foreach ($replacements as $key => $value)
				{
					$subject      = str_replace($key, $value, $subject);
					$body         = str_replace($key, $value, $body);
					$adminsubject = str_replace($key, $value, $adminsubject);
					$adminbody    = str_replace($key, $value, $adminbody);
				}
			}

			// MAIL REPLACEMENTS
			$replacements = array(
				'\\n'             => '\n',
				'[SITENAME]'      => $sitename,
				'[SITEURL]'       => $siteURL,
				'[AUTHOR]'        => $authorname,
				'[AUTHOREMAIL]'   => $authormail,
				'[CONTACTEMAIL]'  => $event->contactemail,
				'[TITLE]'         => $event->title,
				'[SHORTDESC]'     => $event->shortdesc,
				'[DESC]'          => $event->desc,
				'[METADESC]'      => $event->metadesc,
				'[EVENTID]'       => (int) $reg_data->eventid,
				'[EVENTURL]'      => $urlEvent,
				'[URL_CANCEL]'    => $reg_data->userid
										? $urlRegistrationCancel
										: '',
				'[NAME]'          => $reg_data->name,
				'[USERID]'        => $reg_data->userid,
				'[EMAIL]'         => $reg_data->email
										? $reg_data->email
										: JText::_('COM_ICAGENDA_NOT_SPECIFIED'),
				'[PHONE]'         => $reg_data->phone
										? $reg_data->phone
										: JText::_('COM_ICAGENDA_NOT_SPECIFIED'),
				'[PLACES]'        => $reg_data->people,
				'[CUSTOMFIELDS]'  => $custom_fields,
				'[NOTES]'         => html_entity_decode($reg_data->notes),
				'[STARTDATE]'     => $startD,
				'[ENDDATE]'       => $endD,
				'[DATE]'          => $regDate          ? $regDate          : '',
				'[TIME]'          => $regDate          ? $regTime          : '',
				'[DATETIME]'      => ($periodreg != 1) ? $regDateTime      : JText::_('COM_ICAGENDA_REG_FOR_ALL_DATES'),
				'[STARTDATETIME]' => $startD           ? $regStartDateTime : '',
				'[ENDDATETIME]'   => $endD             ? $regEndDateTime   : '',
				'&nbsp;'          => ' ',
			);

			foreach ($replacements as $key => $value)
			{
				$subject      = str_replace($key, $value, $subject);
				$body         = str_replace($key, $value, $body);
				$adminsubject = str_replace($key, $value, $adminsubject);
				$adminbody    = str_replace($key, $value, $adminbody);
			}

			// Set Sender of USER and ADMIN emails
			$mailer = JFactory::getMailer();
			$adminmailer = JFactory::getMailer();

			$mailfrom = $app->getCfg('mailfrom');
			$fromname = $app->getCfg('fromname');

			$mailer->setSender(array( $mailfrom, $fromname ));
			$adminmailer->setSender(array( $mailfrom, $fromname ));

			// Set Recipient of USER email
			$user = JFactory::getUser();

			if (!isset($reg_data->email))
			{
				$recipient = $user->email;
			}
			else
			{
				$recipient = $reg_data->email;
			}

			if ($reg_data->email)
			{
				$mailer->addRecipient($recipient);
			}

			// Set Recipient of ADMIN email
			$admin_array = array();

			if (in_array('0', $emailAdminSend_select))
			{
				array_push($admin_array, $mailfrom);
			}

			if (in_array('1', $emailAdminSend_select))
			{
				if ($adminmailer->validateAddress($authormail))
				{
					array_push($admin_array, $authormail);
				}
			}

			if (in_array('2', $emailAdminSend_select))
			{
				$customs_emails = explode(',', $emailAdminSend_custom);
				$customs_emails = str_replace(' ','', $customs_emails);

				foreach ($customs_emails AS $cust_mail)
				{
					if ($adminmailer->validateAddress($cust_mail))
					{
						array_push($admin_array, $cust_mail);
					}
				}
			}

			if (in_array('3', $emailAdminSend_select))
			{
				if ($adminmailer->validateAddress($event->contactemail))
				{
					array_push($admin_array, $event->contactemail);
				}
			}

			$adminrecipient = count($admin_array) ? $admin_array : $mailfrom;
			$adminmailer->addRecipient($adminrecipient);

			// FIX Joomla 3.5.1 issue on some servers, by addition of "Optional" ReplyTo, not previously set.
			$mailer->addReplyTo($mailfrom, $fromname);
			$adminmailer->addReplyTo($mailfrom, $fromname);

			// Set Subject of USER and ADMIN email
			$mailer->setSubject($subject);
			$adminmailer->setSubject($adminsubject);

			// Set Body of USER and ADMIN email
			if ($defaultemail == 0)
			{
				// HTML custom notification email send to user
				$mailer->isHTML(true);

				// JDocs: When sending HTML emails you should normally set the Encoding to base64
				//        in order to avoid unwanted characters in the output.
				//        See https://docs.joomla.org/Sending_email_from_extensions
				$mailer->Encoding = 'base64'; // JDocs Sending HTML Email
			}

			$adminbody = str_replace("<br />", "\n", $adminbody);

			$mailer->setBody($body);
			$adminmailer->setBody($adminbody);


		// Send USER email confirmation, if enabled
		if ($emailUserSend == 1
			&& isset($reg_data->email)
			&& $reg_data->email
			)
		{
			$send = $mailer->Send();
		}


		// Send ADMIN email notification, if enabled
		if ($emailAdminSend == 1
			&& isset($reg_data->eventid)
			&& $reg_data->eventid != '0'
			&& $reg_data->name != NULL
			)
		{
			$sendadmin = $adminmailer->Send();
		}
	}
}
