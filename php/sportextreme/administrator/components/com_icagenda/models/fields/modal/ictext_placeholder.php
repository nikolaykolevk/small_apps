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
 * @since       3.2.10
 *
 * @deprecated  4.0.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

class JFormFieldModal_ictext_Placeholder extends JFormField
{
	protected $type = 'modal_ictext_Placeholder';

	protected function getInput()
	{
		jimport('joomla.application.component.helper');

		$class = JFactory::getApplication()->input->get('class');

		$icagendaParams = JComponentHelper::getParams('com_icagenda');

		$replace = array("jform", "[", "]", "_Placeholder");
		$name    = str_replace($replace, "", $this->name);
		$Type    = $name . '_Placeholder';

		$tos_Type = $icagendaParams->get($Type);

		$placeholder = ( ! isset($tos_Type)) ? JText::_( 'COM_ICAGENDA_' . strtoupper($name) . '_PLACEHOLDER') : '';

		$html ='<input type="text" id="' . $this->id . '" class="' . $class . ' input-xxlarge" name="' . $this->name . '" value="' . $this->value . '" placeholder="' . $placeholder . '"/>';

		return $html;
	}
}
