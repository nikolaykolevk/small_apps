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
 * @since       3.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

class JFormFieldModal_ph_regbt extends JFormField
{
	protected $type = 'modal_ph_regbt';

	protected function getInput()
	{
		jimport('joomla.application.component.helper');

		$class = JFactory::getApplication()->input->get('class');

		$icagendaParams   = JComponentHelper::getParams('com_icagenda');
		$extRegButtonText = $icagendaParams->get('RegButtonText');

		if ( ! isset($extRegButtonText)) $extRegButtonText = JText::_('COM_ICAGENDA_REGISTRATION_REGISTER');

		$html ='<input type="text" id="' . $this->id . '" class="' . $class . '" name="' . $this->name . '" value="' . $this->value . '" placeholder="' . $extRegButtonText . '"/>';

		return $html;
	}
}
