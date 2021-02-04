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
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

class JFormFieldModal_coordinate extends JFormField
{
	protected $type = 'modal_coordinate';
	
	protected function getInput()
	{
		$def = JFactory::getApplication()->input->get('def');

		if ($def == '') $def = $this->value;

		$html = '
			<!--div class="clr"></div>
			<div id="map_canvas" style="width:100%; height:300px"></div><br/>
			<label>' . JText::_('COM_ICAGENDA_FORM_LBL_EVENT_GPS') . '</label>&nbsp;<input name="' . $this->name . '" id="jform_coordinate" type="text" size="41" value="' . $def . '"/-->
			<div class="clr"></div>
			<!--input name="latitude" id="lat" type="text"/>
			<input name="longitude" id="lng" type="text"/-->';

		$html.= '<input name="' . $this->name . '" id="lat" type="text" size="41" value="' . $this->value . '"/>
			<!--script>
				document.getElementById("coords").value=document.getElementById("lat").value+", "+document.getElementById("lng").value;
			</script-->';

		return $html;
	}
}