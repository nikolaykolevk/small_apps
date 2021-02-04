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
class JFormFieldiCmap_lng extends JFormField
{
	protected $type = 'icmap_lng';

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

		$ic_submit_lng = $session->get('ic_submit_lng', '');

		$lng_value = $ic_submit_lng ? $ic_submit_lng : $this->value;

		if ($coords != NULL
			&& $lng_value == '0.0000000000000000')
		{
			$ex        = explode(', ', $coords);
			$lng_value = $ex[1];
		}
		elseif ($lng_value != '0.0000000000000000')
		{
			$lng_value = $lng_value;
		}
		else
		{
			$lng_value = NULL;
		}

		$html = '<div class="clr"></div>';
		$html.= '<label class="icmap-label">' . JText::_('COM_ICAGENDA_GOOGLE_MAPS_LONGITUDE_LBL') . '</label> <input name="' . $this->name . '" id="lng" type="text"' . $class . ' value="' . $lng_value . '"/>';

		// clear the data so we don't process it again
		$session->clear('ic_submit_lng');

		return $html;
	}
}
