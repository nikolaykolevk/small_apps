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
 * @version 	3.6.4 2016-10-13
 * @since       3.6.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

/**
 * ADDTHIS - Social Networks
 */
class icagendaAddthis
{
	/*
	 * Function to display sharing on social networks
	 *
	 * VIEW: Event Details
	 *
	 * @since 3.6.0
	 */
	static public function sharing($item)
	{
		$addthisEvent = $item->params->get('atevent', 1);

		if ($addthisEvent == 1)
		{
			return self::share();
		}

		return false;
	}

	// function AddThis social networks sharing
	static public function share()
	{
		$url			= parse_url(Juri::base());
		$addthis_scheme	= ($url['scheme'] == 'https') ? 'https://' : 'http://';

		$params			= JFactory::getApplication()->getParams();

		$addthis		= $params->get('addthis', '');
		$float			= $params->get('atfloat', '');
		$icon			= $params->get('aticon', '');

		if ($float == 1)
		{
			$floataddthis	= 'floating';
			$float_position	= 'position: fixed;';
			$float_side		= 'left';
		}
		elseif ($float == 2)
		{
			$floataddthis	= 'floating';
			$float_position	= 'position: fixed;';
			$float_side		= 'right';
		}
		else
		{
			$floataddthis	= 'default';
			$float_position	= '';
			$float_side		= 'right';
		}

		if ($icon == 2)
		{
			$iconaddthis	= '32x32';
		}
		else
		{
			$iconaddthis	= '16x16';
		}

		$at_div = '<div class="share ic-share" style="' . $float_position . '">';
		$at_div.= '<!-- AddThis Button BEGIN -->';
		$at_div.= '<div class="addthis_toolbox';
		$at_div.= ' addthis_' . $floataddthis . '_style';
		$at_div.= ' addthis_' . $iconaddthis . '_style"';
		$at_div.= ' style="' . $float_side . ': 2%; top: 40%;">';
		$at_div.= '<a class="addthis_button_preferred_1"></a>';
		$at_div.= '<a class="addthis_button_preferred_2"></a>';
		$at_div.= '<a class="addthis_button_preferred_3"></a>';
		$at_div.= '<a class="addthis_button_preferred_4"></a>';
		$at_div.= '<a class="addthis_button_compact"></a>';
		$at_div.= '<a class="addthis_counter addthis_bubble_style"></a>';
		$at_div.= '</div>';

		if ($addthis)
		{
			$at_div.= '<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>';
			$at_div.= '<script type="text/javascript" src="' . $addthis_scheme . 's7.addthis.com/js/300/addthis_widget.js#pubid=' . $addthis . '"  async="async"></script>';
		}
		else
		{
			$at_div.= '<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>';
			$at_div.= '<script type="text/javascript" src="' . $addthis_scheme . 's7.addthis.com/js/300/addthis_widget.js#pubid=ra-5024db5322322e8b" async="async"></script>';
		}

		$at_div.= '<!-- AddThis Button END -->';
		$at_div.= '</div>';

		return $at_div;
	}
}
