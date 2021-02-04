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
 * @since       3.3.3
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

/**
 * Link type selector (Default, Article, URL).
 */
class JFormFieldModal_iclink_type extends JFormField
{
	/**
	 * The form field type.
	 */
	protected $type = 'modal_iclink_type';

	protected function getInput()
	{
		jimport('joomla.application.component.helper');

		$jinput = JFactory::getApplication()->input;

		$location = $jinput->get('option', 'com_config');

		if ($location != 'com_config')
		{
			$default_text = JText::_('JGLOBAL_USE_GLOBAL');
		}
		else
		{
			$default_text = JText::_('IC_DEFAULT');
		}

		// Get Type value
		$Type = isset($this->value) ? $this->value : '';

		// Clean jform name
		$replace = array("jform", "params", "[", "]");
		$name    = str_replace($replace, "", $this->name);

		$Type_default = $name . '_default';
		$Type_article = $name . '_article';
		$Type_url     = $name . '_url';

		// Set Var type, to get selected option
		$jinput->set('type', $Type);

		// Article
		if ($Type == '1')
		{
			$class_default   = '';
			$class_article   = 'btn-success';
			$class_url       = '';
			$checked_default = '';
			$checked_article = ' checked="checked"';
			$checked_url     = '';
		}

		// URL
		elseif ($Type == '2')
		{
			$class_default   = '';
			$class_article   = '';
			$class_url       = 'btn-success';
			$checked_default = '';
			$checked_article = '';
			$checked_url     = ' checked="checked"';
		}

		// iCagenda default
		else
		{
			$class_default   = 'btn-primary';
			$class_article   = '';
			$class_url       = '';
			$checked_default = ' checked="checked"';
			$checked_article = '';
			$checked_url     = '';
		}

		$html   = array();
		$html[] = '<fieldset class="radio btn-group">';
		$html[] = '<label class="' . $class_default . '">' . $default_text . '<input type="radio"  id="' . $name . '_0" name="' . $this->name . '" value=""  onClick="icdefault_' . $name . '();"' . $checked_default . ' /></label>';
		$html[] = '<label class="' . $class_article . '">' . JText::_( 'COM_ICAGENDA_REGISTRATION_LINK_ARTICLE' ) . '<input type="radio"  id="' . $name . '_1" name="' . $this->name . '" value="1"  onClick="icarticle_' . $name . '();"' . $checked_article . ' /></label>';
		$html[] = '<label class="' . $class_url . '">' . JText::_( 'COM_ICAGENDA_REGISTRATION_LINK_URL' ) . '<input type="radio"  id="' . $name . '_2" name="' . $this->name . '" value="2"  onClick="icurl_' . $name . '();"' . $checked_url . ' /></label>';
		$html[] = '</fieldset>';

		// Script
		$html[] = '<script type="text/javascript">';
		$html[] = 'function icdefault_' . $name . '()';
		$html[] = '{';
		$html[] = 'document.getElementById("' . $Type_article . '").style.display = "none";';
		$html[] = 'document.getElementById("' . $Type_url . '").style.display = "none";';
//		$html[] = '$("#'.$name.'_0").attr("checked", "checked");';
		$html[] = '}';
		$html[] = 'function icarticle_' . $name . '()';
		$html[] = '{';
		$html[] = 'document.getElementById("' . $Type_article . '").style.display = "block";';
		$html[] = 'document.getElementById("' . $Type_url . '").style.display = "none";';
//		$html[] = '$("#'.$name.'_1").attr("checked", "checked");';
		$html[] = '}';
		$html[] = 'function icurl_' . $name . '()';
		$html[] = '{';
		$html[] = 'document.getElementById("' . $Type_article . '").style.display = "none";';
		$html[] = 'document.getElementById("' . $Type_url . '").style.display = "block";';
//		$html[] = '$("#'.$name.'_2").attr("checked", "checked");';
		$html[] = '}';
		$html[] = '</script>';

		return implode("\n", $html);
	}
}
