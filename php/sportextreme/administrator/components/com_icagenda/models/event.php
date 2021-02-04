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
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * Event Model.
 */
class iCagendaModelEvent extends JModelAdmin
{
	/**
	 * @var     string  The prefix to use with controller messages.
	 * @since   1.0
	 */
	protected $text_prefix = 'COM_ICAGENDA';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   3.5.6
	 */
	protected function canDelete($record)
	{
		if ( ! empty($record->id))
		{
			if ($record->state != -2)
			{
				return false;
			}

			$user = JFactory::getUser();

			if ($user->authorise('core.delete'))
			{
				icagendaCustomfields::deleteData($record->id, 2);
				icagendaCustomfields::cleanData(2);

				return true;
			}
		}

		return false;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function prepareTable( $table )
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);

		if (empty($table->id))
		{
			// Set the values
			$table->created = $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__icagenda_events'));
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
		else
		{
			// Set the values
			$table->modified = $date->toSql();
			$table->modified_by = $user->get('id');
		}
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object
	 *
	 * @since   1.0
	 */
	public function getTable($type = 'Event', $prefix = 'iCagendaTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer $pk The id of the primary key.
	 *
	 * @return  mixed   Object on success, false on failure.
	 *
	 * @since   1.0
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Do any procesing on fields here if needed
		}

		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_icagenda.event', 'event',
								array('control' => 'jform', 'load_data' => $loadData));
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
	 * @since   1.0
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data_array = $app->getUserState('com_icagenda.edit.event.data', array());

		if (empty($data_array))
		{
			$data = $this->getItem();
		}
		else
		{
			$data = new JObject;
			$data->setProperties($data_array);
		}

//		if (JFactory::getUser($data->created_by)->get('name') == false)
//		{
//			JLog::add(JText::_('The author of this event is no longer a user on this site'), JLog::WARNING, 'jerror');
//		}

		// Set correctly param 'first_published_and_approved' if not set (for frontend submitted and approved events)
		if ($data->state == 1
			&& $data->approval == 0)
		{
			$data->params['first_published_and_approved'] = 1;
		}
		
		// If not array, creates array with week days data
		if ( ! is_array($data->weekdays))
		{
			$data->weekdays = explode(',', $data->weekdays);
		}

		// Retrieves data, to display selected week days
		$arrayWeekDays = $data->weekdays;

		foreach ($arrayWeekDays as $allTest)
		{
			if ($allTest == '')
			{
				$data->weekdays = '0,1,2,3,4,5,6';
			}
		}

		// Set displaytime default value
		if ( ! isset($data->displaytime))
		{
			$data->displaytime = JComponentHelper::getParams('com_icagenda')->get('displaytime', '1');
		}

		// Set Features
		$data->features = $this->getFeatures($data->id);

		// Convert features into an array so that the form control can be set
		if ( ! isset($data->features))
		{
			$data->features = array();
		}

		if ( ! is_array($data->features))
		{
			$data->features = explode(',', $data->features);
		}

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.4.0
	 */
	public function save($data)
	{
		$jinput = JFactory::getApplication()->input;
		$date   = JFactory::getDate();
		$user   = JFactory::getUser();

		// Fix version before 3.4.0 to set a created date (will use last modified date if exists, or current date)
		if (empty($data['created']))
		{
			$data['created'] = ( ! empty($data['modified'])) ? $data['modified'] : $date->toSql();
		}

		// Check first published and approved
		if ($data['state'] == 1
			&& $data['approval'] == 0
			&& $data['params']['first_published_and_approved'] == 0)
		{
			$data['params']['first_published_and_approved'] = 1;
			$first_published_and_approved = true;
		}
		else
		{
			$first_published_and_approved = false;
		}

		// Alter the title for save as copy
		if ($jinput->get('task') == 'save2copy')
		{
			$origTable = clone $this->getTable();
			$origTable->load($jinput->getInt('id'));

			if ($data['title'] == $origTable->title)
			{
				list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
				$data['title'] = $title;
				$data['alias'] = $alias;
			}
			else
			{
				if ($data['alias'] == $origTable->alias)
				{
					$data['alias'] = '';
				}
			}
			$data['state'] = 0;
		}

		// Automatic handling of alias if empty
		if (in_array($jinput->get('task'), array('apply', 'save', 'save2new')) && $data['alias'] == null)
		{
			if (JFactory::getConfig()->get('unicodeslugs') == 1)
			{
				$data['alias'] = JFilterOutput::stringURLUnicodeSlug($data['title']);
			}
			else
			{
				$data['alias'] = JFilterOutput::stringURLSafe($data['title']);
			}
		}

		// Use created date in case alias is still empty
		if ($data['alias'] == null || empty($data['alias']))
		{
			$data['alias'] = JFilterOutput::stringURLSafe($data['created']);
		}

		// Force to not add unicode characters if unicodeslugs is not enabled
		if (JFactory::getConfig()->get('unicodeslugs') != 1)
		{
			$data['alias'] = JFilterOutput::stringURLSafe($data['alias']);
		}

		// Check start and end date format (am/pm to sql date)
		$data['startdate'] = iCDate::isDate($data['startdate']) ? date('Y-m-d H:i:s', strtotime($data['startdate'])) : $data['startdate'];
		$data['enddate']   = iCDate::isDate($data['enddate'])   ? date('Y-m-d H:i:s', strtotime($data['enddate']))   : $data['enddate'];
		
		// Set File Uploaded
		if ( ! isset($data['file']))
		{
			$files        = $jinput->files->get('jform', null);
			$fileUrl      = ! empty($files['file']['name']) ? $this->upload($files['file']) : '';
			$data['file'] = $fileUrl;
		}

		// Set Creator infos
		$userId	= $user->get('id');
		$userName = $user->get('name');

		// Event created in admin, set current logged-in user as creator
		if (empty($data['created_by']) && empty($data['username']))
		{
			$data['created_by'] = (int) $userId;
			$data['username'] = $userName;
		}

		// Event edited in admin, created_by not empty, but creator is not the current logged-in user
		elseif ( ! empty($data['created_by']) && $data['created_by'] != $userId)
		{
			$data['username'] = JFactory::getUser($data['created_by'])->get('name', '');
		}

		// Set Params
		if (isset($data['params']) && is_array($data['params']))
		{
			// Keep params for later control
			$params = $data['params'];

			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($data['params']);

			$data['params'] = (string)$parameter;
		}

		// Get Event ID from the result back to the Table after saving.
		$table = $this->getTable();

		if ($table->save($data) === true)
		{
			$data['id'] = $table->id;
		}
		else
		{
			$data['id'] = null;
		}

		if (parent::save($data))
		{
			// Save Features to database
			$this->maintainFeatures($data);

			// Save Custom Fields to database
			if (isset($data['custom_fields']) && is_array($data['custom_fields']))
			{
				icagendaCustomfields::saveToData($data['custom_fields'], $data['id'], 2);
			}

			// ====================================
			// START : HACK FOR A FEW PRO USERS !!!
			// ====================================

			$mail_new_event = JComponentHelper::getParams('com_icagenda')->get('mail_new_event', '0');

			if ($mail_new_event == 1)
			{
				$new_event = $jinput->get('new_event');

				// Send notification email if new event
				if ($new_event == '1' && $data['id'] && $data['state'] == '1' && $data['approval'] == '0')
				{
					self::notificationNewEvent($data);
				}
			}

			// ====================================
			// END : HACK FOR A FEW PRO USERS !!!
			// ====================================

			// Plugin Event handler 'iCagendaOnNewEvent'
			if ($first_published_and_approved)
			{
				// JOOMLA 3.x/2.5 SWITCH
				if (version_compare(JVERSION, '3.0', 'ge'))
				{
					$dispatcher = JEventDispatcher::getInstance();
				}
				else
				{
					$dispatcher = JDispatcher::getInstance();
				}

				JPluginHelper::importPlugin('icagenda');

				$dispatcher->trigger('iCagendaOnNewEvent', array((object) $data));
			}

			return true;
		}

		return false;
	}

	/**
	 * Upload File
	 *
	 * @since   3.5.3
	 */
	protected function upload($file)
	{
		// Get Joomla Images PATH setting
		$image_path = JComponentHelper::getParams('com_media')->get('image_path', 'images');

		// Get filename (name + ext)
		$filename = $file['name'];

		// Get file extension
		$fileExtension = JFile::getExt($filename);

		// Clean up file name to url safe string
		$fileTitle = iCFilterOutput::stringToSlug(JFile::stripExt($filename), '-');

		// If slug generated is empty, new slug based on current date/time
		if ( ! $fileTitle)
		{
			$fileTitle = JFactory::getDate()->format("YmdHis");
		}

		// Return new filename
		$filename = $fileTitle . '.' . $fileExtension;

		// Increment file name if filename already exists
		while (JFile::exists(JPATH_SITE . '/' . $image_path . '/icagenda/files/' . $filename))
		{
			// Get file extension
			$fileExtension = JFile::getExt($filename);

			// Get file title
			$fileTitle = JFile::stripExt($filename);

			// Increment file title (eg. filename-3.jpg)
			$fileTitle = iCString::increment($fileTitle, 'dash');

			$filename = $fileTitle . '.' . $fileExtension;
		}

		// Save file
		if ($filename != '')
		{
			// Set up the temporary source and destination of the file
			$src  = $file['tmp_name'];
			$dest = JPATH_SITE . '/' . $image_path . '/icagenda/files/' . $filename;

			// Create folder 'files' in ROOT/IMAGES_PATH/icagenda/ if does not exist
			$folder[0][0] = 'icagenda/files/' ;
			$folder[0][1] = JPATH_SITE . '/' . $image_path . '/' . $folder[0][0];

			$error = array();

			foreach ($folder as $key => $value)
			{
				if ( ! JFolder::exists( $value[1]))
				{
					if (JFolder::create( $value[1], 0755 ))
					{
						$this->data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
						JFile::write($value[1] . "/index.html", $this->data);
						$error[] = 0;
					}
					else
					{
						$error[] = 1;
					}
				}
				else //Folder exist
				{
					$error[] = 0;
				}
			}

			// Return file path on success
			if ( JFile::upload($src, $dest, false) )
			{
				return $image_path . '/icagenda/files/' . $filename;
			}
		}
	}

	/**
	 * Maintain features to data
	 *
	 * @since   3.4.0
	 */
	protected function maintainFeatures($data)
	{
		// Get the list of feature ids to be linked to the event
		$features = isset($data['features']) && is_array($data['features']) ? implode(',', $data['features']) : '';

		$db = JFactory::getDbo();

		// Write any new feature records to the icagenda_feature_xref table
		if ( ! empty($features))
		{
			// Get a list of the valid features already present for this event
			$query = $db->getQuery(true);

			$query->select('feature_id')
				->from($db->qn('#__icagenda_feature_xref'));

			$query->where('event_id = ' . (int) $data['id']);
			$query->where('feature_id IN (' . $features . ')');

			$db->setQuery($query);

			$existing_features = $db->loadColumn(0);

			// Identify the insert list
			if (empty($existing_features))
			{
				$new_features = $data['features'];
			}
			else
			{
				$new_features = array();

				foreach ($data['features'] as $feature)
				{
					if ( ! in_array($feature, $existing_features))
					{
						$new_features[] = $feature;
					}
				}
			}
			// Write the needed xref records
			if ( ! empty($new_features))
			{
				$xref = new JObject;
				$xref->set('event_id', $data['id']);

				foreach ($new_features as $feature)
				{
					$xref->set('feature_id', $feature);
					$db->insertObject('#__icagenda_feature_xref', $xref);
					$db->setQuery($query);

					if ( ! $db->execute())
					{
						return false;
					}
				}
			}
		}

		// Delete any unwanted feature records from the icagenda_feature_xref table
		$query = $db->getQuery(true);
		$query->delete($db->qn('#__icagenda_feature_xref'));
		$query->where('event_id = ' . (int) $data['id']);

		if ( ! empty($features))
		{
			// Delete only unwanted features
			$query->where('feature_id NOT IN (' . $features . ')');
		}

		$db->setQuery($query);
		$db->execute($query);

		if ( ! $db->execute())
		{
			return false;
		}

		return true;
	}

	/**
	 * Extracts the list of Feature IDs linked to the event and returns an array
	 *
	 * @param   integer  $event_id
	 *
	 * @return  array/integer  Set of Feature IDs
	 *
	 * @since   3.5.3
	 */
	protected function getFeatures($event_id)
	{
		// Write any new feature records to the icagenda_feature_xref table
		if (empty($event_id))
		{
			return '';
		}
		else
		{
			$db = JFactory::getDbo();

			// Get a comma separated list of the ids of features present for this event
			// Note: Direct extraction of a comma separated list is avoided because each db type uses proprietary syntax
			$query = $db->getQuery(true);
			$query->select('fx.feature_id')
				->from($db->qn('#__icagenda_events', 'e'))
				->innerJoin('#__icagenda_feature_xref AS fx ON e.id=fx.event_id')
				->innerJoin('#__icagenda_feature AS f ON fx.feature_id=f.id AND f.state=1');
			$query->where('e.id = ' . (int) $event_id);
			$db->setQuery($query);
			$features = $db->loadColumn(0);

			// Return a comma separated list
			return implode(',', $features);
		}
	}

	/**
	 * Approve Function.
	 *
	 * @since   3.2.0
	 */
	function approve($cid, $approval = 0)
	{
		if (count($cid))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'UPDATE #__icagenda_events'
					. ' SET approval = ' . (int) $approval
					. ' WHERE id IN (' . $cids . ')';
					$this->_db->setQuery( $query );

			if ( ! $this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   3.6.0
	 */
//	protected function canDelete($record)
//	{
//		if ( ! empty($record->id))
//		{
//			if ($record->state != -2)
//			{
//				return false;
//			}

//			$user = JFactory::getUser();

//			return $user->authorise('core.delete', 'com_icagenda.event.' . (int) $record->id);
//		}

//		return false;
//	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   3.6.0
	 */
//	protected function canEditState($record)
//	{
//		$user = JFactory::getUser();

		// Check for existing event.
//		if (!empty($record->id))
//		{
//			return $user->authorise('core.edit.state', 'com_icagenda.event.' . (int) $record->id);
//		}
		// New event, so check against the category.
//		elseif (!empty($record->catid))
//		{
//			return $user->authorise('core.edit.state', 'com_icagenda.event.' . (int) $record->catid);
//		}
		// Default to component settings if neither event nor category known.
//		else
//		{
//			return parent::canEditState('com_icagenda');
//		}
//	}



	/**
	 * HACK FOR A FEW PRO USERS !!!
	 *
	 * Will be removed when creation of a notification plugin
	 *
	 */
	function notificationNewEvent($data)
	{
		$eventID		= $data['id'];
		$title			= $data['title'];
		$description	= $data['desc'];

		$venue = '';
		$venue.= $data['place'] ? $data['place'] . ' - ' : '';
		$venue.= $data['city'] ?: '';
		$venue.= ($data['city'] && $data['country']) ? ', ' : '';
		$venue.= $data['country'] ?: '';

		// Set Date
		$date = strtotime($data['startdate'])
			? 'Du ' . $data['startdate'] . ' au ' . $data['startdate']
			: $data['next'];

		// Set Image tag if exists
		$baseURL = JURI::base();
		$baseURL = str_replace('/administrator', '', $baseURL);
		$baseURL = ltrim($baseURL, '/');

		$image = $data['image'] ? '<img src="' . $baseURL . '/' . $data['image'] . '" alt="' . $data['image'] . '" />' : '';

		// Load iCagenda Global Options
		$iCparams = JComponentHelper::getParams('com_icagenda');

		// Load Joomla Config
		$config = JFactory::getConfig();

		// Switch Joomla 3.x / 2.5
		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			// Get the site name
			$sitename = $config->get('sitename');

			// Get Global Joomla Contact Infos
			$mailfrom = $config->get('mailfrom');
			$fromname = $config->get('fromname');

			// Get default language
			$langdefault = $config->get('language');
		}
		else
		{
			// Get the site name
			$sitename = $config->getValue('config.sitename');

			// Get Global Joomla Contact Infos
			$mailfrom = $config->getValue('config.mailfrom');
			$fromname = $config->getValue('config.fromname');

			// Get default language
			$langdefault = $config->getValue('config.language');
		}

		$siteURL = JURI::base();
		$siteURL = rtrim($siteURL,'/');

		$iCmenuitem = false;

		// Itemid Request (automatic detection of the first iCagenda menu-link, by menuID, and depending of current language)

		$langFrontend = $langdefault;
		$db = JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('id AS idm')
				->from('#__menu')
				->where( "(link = 'index.php?option=com_icagenda&view=list') AND (published > 0) AND (language = '$langFrontend')" );
		$db->setQuery($query);
		$idm = $db->loadResult();
		$mItemid = $idm;

		if ($mItemid == NULL)
		{
				$db = JFactory::getDbo();
				$query	= $db->getQuery(true);
				$query->select('id AS noidm')
						->from('#__menu')
						->where( "(link = 'index.php?option=com_icagenda&view=list') AND (published > 0) AND (language = '*')" );
				$db->setQuery($query);
				$noidm = $db->loadResult();
		}

		$nolink = '';

		if ($noidm == NULL && $mItemid == NULL)
		{
				$nolink = 1;
		}

		if (is_numeric($iCmenuitem))
		{
				$lien = $iCmenuitem;
		}
		else
		{
			if ($mItemid == NULL)
			{
					$lien = $noidm;
			}
			else
			{
					$lien = $mItemid;
			}
		}

		// Set Notification Email to each User groups allowed to receive a notification email when a new event created
		$groupid = $iCparams->get('newevent_Groups', array("8"));

		jimport( 'joomla.access.access' );
		$newevent_Groups_Array = array();

		foreach ($groupid AS $gp)
		{
			$GroupUsers = JAccess::getUsersByGroup($gp, False);
			$newevent_Groups_Array = array_merge($newevent_Groups_Array, $GroupUsers);
		}

		$db = JFactory::getDbo();
		$query	= $db->getQuery(true);

		$matches = implode(',', $newevent_Groups_Array);
		$query->select('ui.username AS username, ui.email AS email, ui.password AS passw, ui.block AS block, ui.activation AS activation')
			->from('#__users AS ui')
			->where( "ui.id IN ($matches) ");
		$db->setQuery($query);
		$users = $db->loadObjectList();

		foreach ($users AS $user)
		{
			// Create Notification Mailer
			$new_mailer = JFactory::getMailer();

			// Set Sender of Notification Email
			$new_mailer->setSender(array( $mailfrom, $fromname ));

			$username = $user->username;
			$passw = $user->passw;
			$email = $user->email;

			// Set Recipient of Notification Email
			$new_recipient = $email;
			$new_mailer->addRecipient($email);

			// Set Subject of New Event Notification Email
			$new_subject = 'Nouvel évènement, '.$sitename;
			$new_mailer->setSubject($new_subject);

			// Set Url to preview new event
			$baseURL = JURI::base();
			$baseURL = str_replace('/administrator', '', $baseURL);

			$urlpreview = str_replace('&amp;','&', JRoute::_($baseURL . 'index.php?option=com_icagenda&view=event&id=' . (int) $eventID . '&Itemid=' . (int) $lien));

			// Set Body of User Notification Email
			$new_body_hello = 'Bonjour,';
			$new_bodycontent = $new_body_hello.'<br /><br />';
			$new_body_text = $sitename.' vous propose un nouvel évènement :';
			$new_bodycontent.= $new_body_text.'<br /><br />';

			// Event Details
			$new_bodycontent.= $title ? 'Titre: '.$title.'<br />' : '';
			$new_bodycontent.= $description ? 'Description: '.$description.'<br />' : '';
			$new_bodycontent.= $venue ? 'Lieu: '.$venue.'<br />' : '';
			$new_bodycontent.= $date ? 'Date: '.$date.'<br /><br />' : '';
			$new_bodycontent.= $image.'<br /><br />';

			// Link to event details view
			$new_bodycontent.= '<a href="'.$urlpreview.'">'.$urlpreview.'</a><br /><br />';

			// Footer
			$new_body_footer = 'Do not answer to this e-mail notification as it is a generated e-mail. You are receiving this email message because you are registered at '.$sitename.'.';
			$new_bodycontent.= '<hr><small>'.$new_body_footer.'<small>';

			// Removes spaces (leading, ending) from Body
			$new_body = rtrim($new_bodycontent);

			// Authorizes HTML
			$new_mailer->isHTML(true);

			// JDocs: When sending HTML emails you should normally set the Encoding to base64
			//        in order to avoid unwanted characters in the output.
			//        See https://docs.joomla.org/Sending_email_from_extensions
			$new_mailer->Encoding = 'base64'; // JDocs Sending HTML Email

			// Set Body
			$new_mailer->setBody($new_body);

			// Send User Notification Email
			if (isset($email))
			{
				if ($user->block == '0' && empty($user->activation))
				{
					$send = $new_mailer->Send();
				}
			}
		}
	}
}
