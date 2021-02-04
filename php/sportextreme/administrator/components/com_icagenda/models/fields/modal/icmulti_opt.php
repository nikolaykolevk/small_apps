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
 * @version     3.6.2 2016-09-19
 * @since       3.2.6
 * @TODO        refactory OR to be removed for 3.7.0 (J3 support only)
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

class JFormFieldModal_icmulti_opt extends JFormField
{
	protected $type='modal_icmulti_opt';

	protected function getInput()
	{
		$replace = array("jform", "params", "[", "]");
		$name_input = str_replace($replace, "", $this->name);
		$get_location = explode('_', $name_input);
		$location = $get_location['1'];
		$name = $get_location['0'];

		$Type = $this->value;

		$Type_none = $name . '_none';
		$Type_checkbox = $name . '_checkbox';

		$class_global = 'btn-primary';
		$class_none = 'btn-danger';
		$class_checkbox = 'btn-success';
		$checked_none = ' checked="checked"';
		$checked_checkbox = '';

		if ($Type == '0')
		{
			$class_global = '';
			$class_none = 'btn-danger';
			$class_checkbox = '';
			$checked_global = '';
			$checked_none = ' checked="checked"';
			$checked_checkbox = '';
		}
		elseif ($Type == '1')
		{
			$class_global = '';
			$class_none = '';
			$class_checkbox = 'btn-success';
			$checked_global = '';
			$checked_none = '';
			$checked_checkbox = ' checked="checked"';
		}
		else
		{
			$class_global = 'btn-primary';
			$class_none = '';
			$class_checkbox = '';
			$checked_global = ' checked="checked"';
			$checked_none = '';
			$checked_checkbox = '';
		}

		$html = array();


		$html[] = '<fieldset class="radio btn-group">';

		if ($location == 'menu')
		{
			$html[] = '<label class="' . $class_global . '">' . JText::_('JGLOBAL_USE_GLOBAL') . '<input type="radio" id="' . $name . '_global" name="' . $this->name . '" value="global"  onClick="icglobal_' . $name . '();"' . $checked_global . ' /></label>';
		}

		$html[] = '<label class="' . $class_none . '">' . JText::_('JNO') . '<input type="radio" id="' . $name . '_0" name="' . $this->name . '" value="0"  onClick="icnone_' . $name . '();"' . $checked_none . ' /></label>';
		$html[] = '<label class="' . $class_checkbox . '">' . JText::_('JYES') . '<input type="radio" id="' . $name . '_1" name="' . $this->name . '" value="1"  onClick="iccheckbox_' . $name . '();"' . $checked_checkbox . ' /></label>';
		$html[] = '</fieldset>';


		$html[] = '<script type="text/javascript">';
		$html[] = 'var typeset = "' . $Type . '";';

		if ($location == 'menu')
		{
			$html[] = 'function icglobal_' . $name . '() {';
			$html[] = '    document.getElementById("' . $Type_checkbox . '").style.display = "none";';
			$html[] = '    document.getElementById("' . $name . '_global").setAttribute("checked", "checked");';
			$html[] = '}';
		}

		$html[] = 'function icnone_' . $name . '() {';
		$html[] = '    document.getElementById("' . $Type_checkbox . '").style.display = "none";';
		$html[] = '    document.getElementById("' . $name . '_0").setAttribute("checked", "checked");';
		$html[] = '}';
		$html[] = 'function iccheckbox_' . $name . '() {';
		$html[] = '    document.getElementById("' . $Type_checkbox . '").style.display = "block";';
		$html[] = '    document.getElementById("' . $name . '_1").setAttribute("checked", "checked");';
		$html[] = '}';
		$html[] = '</script>';

		return implode("\n", $html);
	}
}
