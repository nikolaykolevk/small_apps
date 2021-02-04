<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.3 2018-06-30
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.googlemaps
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * class icagendaGooglemaps
 */
class icagendaGooglemaps
{
	/**
	 * Function to display Google Map
	 *
	 * @since   3.6.0
	 */
	static public function display($item)
	{
		$iCparams = JComponentHelper::getParams('com_icagenda');

		// Hide/Show Option
		$GoogleMaps = $iCparams->get('GoogleMaps', 1);

		// Access Levels Option
		$accessGoogleMaps = $iCparams->get('accessGoogleMaps', 1);

		$markerLat = self::lat($item);
		$markerLng = self::lng($item);

		if ($GoogleMaps == 1
			&& icagendaEvents::accessLevels($accessGoogleMaps)
			&& (($markerLat != NULL && $markerLng != NULL) || ($item->address && $iCparams->get('maps_service', '') == '2'))
			)
		{
			return true;
		}

		return false;
	}

	/**
	 * Function to return Google Map (frontend only)
	 *
	 * @since   3.6.0
	 */
	static public function map($item)
	{
		$params       = JFactory::getApplication()->getParams();
		$map_width    = $params->get('m_width', '100%');
		$map_height   = $params->get('m_height', '300px');
		$mapID        = $item->id;

		$iCparams     = JComponentHelper::getParams('com_icagenda');
		$maps_service = $iCparams->get('maps_service', '');

		$iCgmap = '<!-- Event Map -->';

		if ($maps_service == '2')
		{
			$embedKey = $iCparams->get('googlemaps_embed_key', '');

			$iCgmap.= '<div class="icagenda_map" id="embed_map-' . (int) $mapID . '"';
			$iCgmap.= ' style="width:' . $map_width . '; height:' . $map_height . '">';
			$iCgmap.= '<iframe
				width="' . $map_width . '"
				height="' . $map_height . '"
				frameborder="0" style="border:0;" class="icagenda_map"
				src="https://www.google.com/maps/embed/v1/place?key=' . trim($embedKey)
					. '&q=' . urlencode(strip_tags($item->address)) . '" allowfullscreen>
				</iframe>';
			$iCgmap.= '</div>';
		}
		elseif ($maps_service == '3')
		{
			$markerLat = self::lat($item);
			$markerLng = self::lng($item);

			$iCgmap.= '<div class="icagenda_map" id="map_canvas' . (int) $mapID . '"';
			$iCgmap.= ' style="width:' . $map_width . '; height:' . $map_height . '">';
			$iCgmap.= '</div>';
			$iCgmap.= '<script type="text/javascript">';
			$iCgmap.= 'icMapInitialize(' . $markerLat . ', ' . $markerLng . ', ' . (int) $mapID . ');';
			$iCgmap.= '</script>';
		}

		return $maps_service ? $iCgmap : JText::_('COM_ICAGENDA_MAPS_SERVICE_NOT_AVAILABLE');
	}

	/**
	 * Function to return Latitude
	 *
	 * @since   3.6.0
	 */
	static public function lat($item)
	{
		// Convert old coordinate value to latitude
		if ($item->coordinate != NULL
			&& $item->lat == '0.0000000000000000')
		{
			$ex = explode(', ', $item->coordinate);

			$latitude = $ex[0];
		}
		else
		{
			$latitude = ($item->lat != '0.0000000000000000') ? $item->lat : NULL;
		}

		return $latitude;
	}

	/**
	 * Function to return Longitude
	 *
	 * @since   3.6.0
	 */
	static public function lng($item)
	{
		if ($item->coordinate != NULL
			&& $item->lng == '0.0000000000000000')
		{
			$ex = explode(', ', $item->coordinate);

			$longitude = $ex[1];
		}
		else
		{
			$longitude = ($item->lng != '0.0000000000000000') ? $item->lng : NULL;
		}

		return $longitude;
	}

	/**
	 * Load Google Maps Scripts.
	 *
	 * @param   type   show (display map) or edit (create and set map values)
	 *
	 * @since   3.6.0
	 */
	public static function loadGMapScripts($type = 'show')
	{
		$iCparams     = JComponentHelper::getParams('com_icagenda');
		$maps_service = $iCparams->get('maps_service', '');

		if ($maps_service == '3')
		{
			// Google Maps api V3
			$document      = JFactory::getDocument();
			$scripts       = array_keys($document->_scripts);
			$gmapApiLoaded = false;

			for ($i = 0; $i < count($scripts); $i++)
			{
    			if ( stripos($scripts[$i], 'maps.googleapis.com') !== false
    				&& stripos($scripts[$i], 'maps.gstatic.com') !== false )
				{
					$gmapApiLoaded = true;
				}
			}

			if ( ! $gmapApiLoaded)
			{
				$curlang   = $document->language;
				$lang      = substr($curlang, 0, 2);
				$key       = $iCparams->get('googlemaps_browser_key', '');
				$client_id = $iCparams->get('googlemaps_client_id', '');
				$client    = (substr($client_id, 0, 4) === 'gme-') ? $client_id : 'gme-' . $client_id;

				// Google Maps API variables
				$apiLang   = '?language=' . trim($lang);
				$apiLib    = '&librairies=places';
				$apiKey    = ($key && ! $client_id) ? '&key=' . trim($key) : '';
				$apiClient = $client_id ? '&client=' . trim($client) : '';

				$document->addScript('https://maps.googleapis.com/maps/api/js' . $apiLang . $apiLib . $apiKey . $apiClient);
			}

			if ($type == 'show')
			{
				JHtml::script('com_icagenda/icmap-front.js', false, true);
			}
			else
			{
				JHtml::script('com_icagenda/icmap.js', false, true);
			}
		}
	}
}
