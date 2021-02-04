<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.7 2019-01-06
 *
 * @package     iCagenda.Site
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

// Get Application
$app = JFactory::getApplication();

// Check Errors: iC Library & iCagenda Utilities
$UTILITIES_DIR = is_dir(JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities');

if ( ( ! $UTILITIES_DIR)
	|| ( ! class_exists('iCLibrary')) )
{
	$alert_message = JText::_('ICAGENDA_CAN_NOT_LOAD').'<br />';
	$alert_message.= '<ul>';
	if ( ! class_exists('iCLibrary')) $alert_message.= '<li>' . JText::_('IC_LIBRARY_NOT_LOADED') . '</li>';
	if ( ! $UTILITIES_DIR) $alert_message.= '<li>' . JText::_('ICAGENDA_A_FOLDER_IS_MISSING') . '</li>';
	$alert_message.= '</ul>';
	if ( ! $UTILITIES_DIR) $alert_message.= JText::_('ICAGENDA_IS_NOT_CORRECTLY_INSTALLED') . ' ';
	if ( ! $UTILITIES_DIR) $alert_message.= JText::_('ICAGENDA_INSTALL_AGAIN') . '<br />';
	if ( ! $UTILITIES_DIR) $alert_message.= JText::_('IC_ALTERNATIVELY') . ':<br /><ul>';
	if ($UTILITIES_DIR) $alert_message.= JText::_('IC_PLEASE') . ', ';
	if ( ! class_exists('iCLibrary'))
	{
		if ( ! $UTILITIES_DIR) $alert_message.= '<li>';
		$alert_message.= JText::_('IC_LIBRARY_CHECK_PLUGIN_AND_LIBRARY');
		if ( ! $UTILITIES_DIR) $alert_message.= '</li>';
	}
	if ( ! $UTILITIES_DIR)
	{
		$alert_message.= '<li>' . JText::Sprintf('ICAGENDA_UTILITIES_FIX_MANUAL'
						, '<strong>admin/utilities</strong>'
						, '<strong>administrator/components/com_icagenda/</strong>');
		$alert_message.= '</li></ul>';
	}

	// Get the message queue
	$messages = $app->getMessageQueue();

	$display_alert_message = false;

	// If we have messages
	if (is_array($messages) && count($messages))
	{
		// Check each message for the one we want
		foreach ($messages as $key => $value)
		{
			if ($value['message'] == $alert_message)
			{
				$display_alert_message = true;
			}
		}
	}

	if ( ! $display_alert_message)
	{
		$app->enqueueMessage($alert_message, 'error');
	}

	return false;
}

$time_loading = JComponentHelper::getParams('com_icagenda')->get('time_loading', '');

if ($time_loading)
{
	$starttime = iCLibrary::getMicrotime();
}

// Load Utilities
JLoader::registerPrefix('icagenda', JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities');

// Set JInput
$jinput = $app->input;

// Redirect old layouts of the view 'list' to new separated views for event details and registration (since 3.6.0)
$layout = $jinput->get('layout', '');
$id     = $jinput->get('id', '');
$Itemid = $jinput->get('Itemid', '');

if (in_array($layout, array('event', 'registration')))
{
	$new_event_url = 'index.php?option=com_icagenda&view=' . $layout . '&id=' . $id . '&Itemid=' . $Itemid;
	$app->redirect((string) $new_event_url, 301);
}

// Test if translation is missing, set to en-GB by default
$language = JFactory::getLanguage();
$language->load('com_icagenda', JPATH_SITE, 'en-GB', true);
$language->load('com_icagenda', JPATH_SITE, null, true);

// Load Vector iCicons Font
JHtml::stylesheet('media/com_icagenda/icicons/style.css');

// CSS files which could be overridden into your site template. (eg. /templates/my_template/css/com_icagenda/icagenda-front.css)
JHtml::stylesheet('com_icagenda/icagenda-front.css', false, true);
JHtml::stylesheet('com_icagenda/tipTip.css', false, true);

// Set iCtip
$iCtip   = array();
$iCtip[] = '	jQuery(document).ready(function(){';
$iCtip[] = '		jQuery(".iCtip").tipTip({maxWidth: "200", defaultPosition: "top", edgeOffset: 1});';
$iCtip[] = '	});';

// Add the script to the document head.
JFactory::getDocument()->addScriptDeclaration(implode("\n", $iCtip));

// Perform the Requested task
$controller = JControllerLegacy::getInstance('iCagenda');
$controller->execute(JFactory::getApplication()->input->get('task', 'display'));

// Redirect if set by the controller
$controller->redirect();

// Time to create content
if ($time_loading)
{
	$endtime = iCLibrary::getMicrotime();

	echo '<center style="font-size:8px;">Time to create content: ' . round($endtime-$starttime, 3) . ' seconds</center>';
}
