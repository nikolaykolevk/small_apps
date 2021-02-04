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

/**
 * Returns Latitude from Google Maps address auto-complete field.
 */
class JFormFieldiCmap_lat extends JFormField
{
	protected $type = 'icmap_lat';

	protected function getInput()
	{
		$session = JFactory::getSession();

		// Check if coords set (deprecated)
		$id = JFactory::getApplication()->input->getInt('id');

		$class = isset($this->class) ? ' class="' . $this->class . '"' : '';

		if (isset($id))
		{
			$db = JFactory::getDbo();
			$db->setQuery(
				'SELECT a.coordinate' .
				' FROM #__icagenda_events AS a' .
				' WHERE a.id = ' . (int) $id
			);

			$coords = $db->loadResult();
		}
		else
		{
			$coords = NULL;
		}

		$ic_submit_lat = $session->get('ic_submit_lat', '');

		$lat_value = $ic_submit_lat ? $ic_submit_lat : $this->value;

		if ($coords != NULL
			&& $lat_value == '0.0000000000000000')
		{
			$ex        = explode(', ', $coords);
			$lat_value = $ex[0];
		}
		elseif ($lat_value != '0.0000000000000000')
		{
			$lat_value = $lat_value;
		}
		else
		{
			$lat_value = NULL;
		}

		$html = '<div class="clr"></div>';
		$html.= '<label class="icmap-label">' . JText::_('COM_ICAGENDA_GOOGLE_MAPS_LATITUDE_LBL') . '</label> <input name="' . $this->name . '" id="lat" type="text"' . $class . ' value="' . $lat_value . '"/>';

		// clear the data so we don't process it again
		$session->clear('ic_submit_lat');

		return $html;
	}
}
