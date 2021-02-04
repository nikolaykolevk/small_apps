<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-30
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
 * class icagendaManager
 */
class icagendaManager
{
	/**
	 * Function to return manager icons toolbar
	 *
	 * @since   3.6.0
	 */
	public static function toolBar($item)
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;

		// Get Current Itemid
		$this_itemid = $jinput->getInt('Itemid');

		// Set Manager Actions Url
		$event_slug        = empty($item->alias) ? $item->id : $item->id . ':' . $item->alias;
		$managerActionsURL = 'index.php?option=com_icagenda&view=event&id=' . $event_slug . '&Itemid=' . $this_itemid;

		// Set Email Notification Url to event
		$linkEmailUrl = JURI::base() . 'index.php?option=com_icagenda&view=event&id=' . $event_slug . '&Itemid=' . $this_itemid;

		// Get Approval Status
		$approved = $item->approval;

		// Get User groups allowed to approve event submitted
		$groupid = JComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));

		$groupid = is_array($groupid) ? $groupid : array($groupid);

		// Get User Infos
		$user = JFactory::getUser();

		$icid = $user->get('id');
		$icu  = $user->get('username');
		$icp  = $user->get('password');

		// Get User groups of the user logged-in
		$userGroups = $user->getAuthorisedGroups();

		$baseURL = JURI::base();
		$subpathURL = JURI::base(true);

		$baseURL = str_replace('/administrator', '', $baseURL);
		$subpathURL = str_replace('/administrator', '', $subpathURL);

		$urlcheck = str_replace('&amp;','&', JRoute::_('administrator/index.php?option=com_icagenda&view=events') . '&icu=' . $icu . '&icp=' . $icp . '&filter_search=' . $item->id);

		// Sub Path filtering
		$subpathURL = ltrim($subpathURL, '/');

		// URL Event Check filtering
		$urlcheck = ltrim($urlcheck, '/');

		if (substr($urlcheck, 0, strlen($subpathURL)+1) == "$subpathURL/")
		{
			$urlcheck = substr($urlcheck, strlen($subpathURL)+1);
		}

		$urlcheck = rtrim($baseURL, '/') . '/' . ltrim($urlcheck, '/');

		$icu_approve = $jinput->get('manageraction', '');

		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			$approveIcon = '<span class="iCicon-16 approval"></span>';
		}
		else
		{
			$approveIcon = '<button class="btn btn-micro btn-warning btn-xs "><i class="icon-checkmark"></i></button>';
		}

		$approval_msg   = JText::sprintf('COM_ICAGENDA_APPROVE_AN_EVENT_NOTICE', $approveIcon);
		$approval_title = JText::_('COM_ICAGENDA_APPROVE_AN_EVENT_LBL');
		$approval_type  = 'notice';

		$jlayout        = $jinput->get('layout', '');
		$layouts_array  = array('event', 'registration');
		$icu_layout     = in_array($jlayout, $layouts_array) ? $jlayout : '';

		if ( array_intersect($userGroups, $groupid)
			|| in_array('8', $userGroups) )
		{
			if ($approved == 1)
			{
				if (version_compare(JVERSION, '3.0', 'lt'))
				{
					$approvalButton = '<a'
									. ' class="iCtip"'
									. ' href="' . JRoute::_($managerActionsURL . '&manageraction=approve') . '"'
									. ' title="' . JText::_('COM_ICAGENDA_APPROVE_AN_EVENT_LBL') . '">'
									. '<div class="iCicon-16 approval"></div>'
									. '</a>';
 				}
 				else
 				{
					$approvalButton = '<a'
									. ' class="iCtip"'
									. ' href="' . JRoute::_($managerActionsURL . '&manageraction=approve') . '"'
									. ' title="' . JText::_('COM_ICAGENDA_APPROVE_AN_EVENT_LBL') . '">'
									. '<button type="button" class="btn btn-micro btn-warning btn-xs">'
									. '<i class="icon-checkmark"></i>'
									. '</button>'
									. '</a>';
				}

				if ($icu_approve != 'approve')
				{
					$app->enqueueMessage($approval_msg, $approval_title, $approval_type);
				}

				if ($icu_approve == 'approve'
					&& $jinput->get('view', '') == 'event')
				{
        			$db    = Jfactory::getDbo();
					$query = $db->getQuery(true);
        			$query->clear();
					$query->update('#__icagenda_events');
					$query->set('approval = 0');
					$query->where(' id = ' . (int) $item->id);
					$db->setQuery((string) $query);
					$db->query($query);

					$approveSuccess = '"' . $item->title . '"';
					$alertmsg       = JText::sprintf('COM_ICAGENDA_APPROVED_SUCCESS', $approveSuccess);
					$alerttitle     = JText::_('COM_ICAGENDA_APPROVED');
					$alerttype      = 'success';
					$approvedLink   = JRoute::_($managerActionsURL);

					self::approvalNotification($item->created_by_email, $item->username, $item->title, $linkEmailUrl);

					// Plugin Event handler 'iCagendaOnNewEvent'
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

					$dispatcher->trigger('iCagendaOnNewEvent', array($item));

					// System Message Approval
					$app->enqueueMessage($alertmsg, $alerttitle, $alerttype);
				}
				else
				{
					return $approvalButton;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Function to send approval notification emails
	 *
	 * @since   3.6.0
	 */
	public static function approvalNotification($creatorEmail, $eventUsername, $eventTitle, $eventLink)
	{
		$app = JFactory::getApplication();

		// Load Joomla Config Mail Options
		$sitename = $app->getCfg('sitename');
		$mailfrom = $app->getCfg('mailfrom');
		$fromname = $app->getCfg('fromname');

		// Create User Mailer
		$approvedmailer = JFactory::getMailer();

		// Set Sender of Notification Email
		$approvedmailer->setSender(array( $mailfrom, $fromname ));

		// Set Recipient of Notification Email
		$approvedmailer->addRecipient($creatorEmail);

		// Set Subject of Notification Email
		$approvedsubject = JText::sprintf('COM_ICAGENDA_APPROVED_USEREMAIL_SUBJECT', $eventTitle);
		$approvedmailer->setSubject($approvedsubject);

		// Set Body of Notification Email
		$approvedbodycontent = JText::sprintf('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_HELLO', $eventUsername) . ',<br /><br />';
		$approvedbodycontent.= JText::sprintf('COM_ICAGENDA_APPROVED_USEREMAIL_BODY_INTRO', $sitename) . '<br /><br />';
//		$approvedbodycontent.= JText::_('COM_ICAGENDA_APPROVED_USEREMAIL_EVENT_LINK').'<br />';

		$eventLink_html = '<br /><a href="' . $eventLink . '">' . $eventLink . '</a>';
		$approvedbodycontent.= JText::sprintf('COM_ICAGENDA_APPROVED_USEREMAIL_EVENT_LINK', $eventLink_html).'<br /><br />';

//		$approvedbodycontent.= '<a href="' . $eventLink . '">' . $eventLink . '</a><br /><br />';
		$approvedbodycontent.= '<hr><small>' . JText::_('COM_ICAGENDA_APPROVED_USEREMAIL_EVENT_LINK_INFO') . '</small><br /><br />';

		$approvedbody = rtrim($approvedbodycontent);

		$approvedmailer->isHTML(true);

		// JDocs: When sending HTML emails you should normally set the Encoding to base64
		//        in order to avoid unwanted characters in the output.
		//        See https://docs.joomla.org/Sending_email_from_extensions
		$approvedmailer->Encoding = 'base64'; // JDocs Sending HTML Email

		$approvedmailer->setBody($approvedbody);

		// Send User Notification Email
		if (isset($creatorEmail))
		{
			$send = $approvedmailer->Send();
		}
	}
}
