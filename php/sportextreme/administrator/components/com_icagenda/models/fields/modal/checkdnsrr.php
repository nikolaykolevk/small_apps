<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright   Copyright (c)2012-2019 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @version     3.6.0 2015-10-14
 * @since       3.1.7
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

class JFormFieldModal_checkdnsrr extends JFormField
{
	protected $type='modal_checkdnsrr';

	protected function getInput()
	{
		if ( ! function_exists('checkdnsrr'))
		{
			if (version_compare(JVERSION, '3.0', 'lt'))
			{
				$html = '<label style="color:red"><b> ' . JText::_('COM_ICAGENDA_REGISTRATION_EMAIL_CHECKDNSRR_NOT_PRESENT_1') . '</b><br/>' . JText::_('COM_ICAGENDA_REGISTRATION_EMAIL_CHECKDNSRR_NOT_PRESENT_2') . '</label><br/>';
			}
			else
			{
				$html = '<div class="alert alert-error"><span class="icon-warning"></span><b> ' . JText::_('COM_ICAGENDA_REGISTRATION_EMAIL_CHECKDNSRR_NOT_PRESENT_1') . '</b><br/>' . JText::_('COM_ICAGENDA_REGISTRATION_EMAIL_CHECKDNSRR_NOT_PRESENT_2') . '</div>';
			}
		}
		else
		{
			$html = '<script>document.getElementById("jform_Checkdnsrr-lbl").style.display = "none"; document.getElementById("jform_Checkdnsrr-lbl").style.display = "none";</script>';
		}

		return $html;
	}
}
