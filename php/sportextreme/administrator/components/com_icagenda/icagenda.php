<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-26
 *
 * @package     iCagenda.Admin
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
	$alert_message = JText::_('ICAGENDA_CAN_NOT_LOAD') . '<br />';
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

		return;
	}
}
else
{
	// Loads Utilities
	JLoader::registerPrefix('icagenda', JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities');

	// Common fields
	JFormHelper::addFieldPath(JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities/form/field');

	if ( ! defined('IC_LIBRARY'))
	{
		define('IC_LIBRARY', '1.3.0');
	}
}

// Set Input J3
$jinput = JFactory::getApplication()->input;

// Load Live Update & Joomla import
require_once JPATH_ADMINISTRATOR . '/components/com_icagenda/liveupdate/liveupdate.php';

if ($jinput->get('view') == 'liveupdate')
{
	LiveUpdate::handleRequest();

	return;
}

if (version_compare(JVERSION, '3.0', 'lt'))
{
	jimport('joomla.application.component.controller');

	$level = E_ALL & ~E_NOTICE & ~E_DEPRECATED;

	// Remove not-error message (only needed for Joomla 2.5) : Strict Standards
	if (version_compare(PHP_VERSION, '5.4.0-dev', '>='))
	{
		if ( ! defined('E_STRICT'))
		{
			define('E_STRICT', 2048);
		}

		$level &= ~E_STRICT;
	}

	error_reporting($level);
}

// Set some css property
$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-48-icagenda {background-image: none;}');

// Load Vector iCicons Font
JHtml::stylesheet( 'media/com_icagenda/icicons/style.css' );

// CSS files which could be overridden into your site template. (eg. /templates/my_template/css/com_icagenda/icagenda-back.css)
JHtml::stylesheet( 'com_icagenda/icagenda-back.css', false, true );

// Load translations
$language = JFactory::getLanguage();
$language->load('com_icagenda', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('com_icagenda', JPATH_ADMINISTRATOR, null, true);

// Access check.
if ( ! JFactory::getUser()->authorise('core.manage', 'com_icagenda'))
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');

	return false;
}

// Require helper file
JLoader::register('iCagendaHelper', dirname(__FILE__) . '/helpers/icagenda.php');

// Check config params
icagendaParams::encryptPassword();

// Get an instance of the controller prefixed by iCagenda
// Joomla 3.x / 2.5 SWITCH
if (version_compare(JVERSION, '3.0', 'ge'))
{
	$controller = JControllerLegacy::getInstance('iCagenda');
}
else
{
	$controller = JController::getInstance('iCagenda');
}

// Perform the Request task
$controller->execute($jinput->get('task'));

// Redirect if set by the controller
$controller->redirect();
