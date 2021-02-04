<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     iCagenda
 * @subpackage  utilities
 * @copyright   Copyright (c)2012-2019 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      Cyril Rezé (Lyr!C)
 * @link        http://www.joomlic.com
 *
 * @version     3.6.0 2016-03-03
 * @since       3.6.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

/**
 * class icagendaThemeJoomla25
 */
class icagendaThemeJoomla25
{
	/**
	 * Load jQuery on Joomla 2.5 sites
	 *
	 * @since		3.6.0
	 * @deprecated	4.0.0
	 */
	static public function loadjQuery()
	{
		$app		= JFactory::getApplication();
		$document	= JFactory::getDocument();

		// Joomla 2.5
		JHtml::stylesheet( 'com_icagenda/icagenda-front.j25.css', false, true );

		JHtml::_('behavior.mootools');

		// Load jQuery, if not loaded before
		$scripts		= array_keys($document->_scripts);
		$scriptFound	= false;
		$count_scripts	= count($scripts);

		for ($i = 0; $i < $count_scripts; $i++)
		{
			if (stripos($scripts[$i], 'jquery.min.js') !== false
				|| stripos($scripts[$i], 'jquery.js') !== false)
			{
				$scriptFound = true;
			}
		}

		// jQuery Library Loader
		if ( ! $scriptFound)
		{
			// Load jQuery, if not loaded before
			if ( ! $app->get('jquery'))
			{
				$app->set('jquery', true);

				// Add jQuery Library
				$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');
				JHtml::script('com_icagenda/jquery.noconflict.js', false, true);
			}
		}
	}
}
