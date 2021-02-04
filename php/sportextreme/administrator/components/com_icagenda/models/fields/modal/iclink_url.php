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

jimport('joomla.filesystem.path');
jimport('joomla.form.formfield');

/**
 * Supports a url type field.
 */
class JFormFieldModal_iclink_url extends JFormField
{
	/**
	 * The form field type.
	 */
	protected $type = 'modal_iclink_url';

	/**
	 * Method to get the field input markup.
	 */
	protected function getInput()
	{
		jimport('joomla.application.component.helper');

		$icagendaParams = JComponentHelper::getParams('com_icagenda');

		$Explode  = explode('_', $this->name);
		$TypeName = $Explode[0] . ']';

		$replace  = array("jform", "params", "[", "]");
		$name     = str_replace($replace, "", $TypeName);

		$Type = JFactory::getApplication()->input->get('type');

		$Type_default = $name . '_default';
		$Type_article = $name . '_article';
		$Type_url     = $name . '_url';

		$editor = JFactory::getEditor();

		$html = array();

		$html[] = '<div id="' . $Type_url . '"><fieldset class="span9 iCleft">';
		$html[] = '<input type="url" name="' . $this->name . '" value="' . $this->value . '" />';
		$html[] = '</fieldset></div>';

		// Article
		if ($Type == '1')
		{
			$html[] = '<script type="text/javascript">';
			$html[] = 'document.getElementById("' . $Type_article . '").style.display = "block";';
			$html[] = 'document.getElementById("' . $Type_url . '").style.display = "none";';
			$html[] = '</script>';
		}

		// URL
		elseif ($Type == '2')
		{
			$html[] = '<script type="text/javascript">';
			$html[] = 'document.getElementById("' . $Type_article . '").style.display = "none";';
			$html[] = 'document.getElementById("' . $Type_url . '").style.display = "block";';
			$html[] = '</script>';
		}

		// iCagenda default
		else
		{
			$html[] = '<script type="text/javascript">';
			$html[] = 'document.getElementById("' . $Type_article . '").style.display = "none";';
			$html[] = 'document.getElementById("' . $Type_url . '").style.display = "none";';
			$html[] = '</script>';
		}

		return implode("\n", $html);
	}
}
