<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.3 2018-07-18
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities
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
 * class icagendaRender
 */
class icagendaRender
{
	/**
	 * Function to return formatted title
	 *
	 * @since	3.6.0
	 */
	static public function titleToFormat($title)
	{
		$text_transform	= JComponentHelper::getParams('com_icagenda')->get('titleTransform', '');
		$mbString		= extension_loaded('mbstring');

		if ($text_transform == 1)
		{
			$titleFormat = $mbString ? iCString::mb_ucfirst(mb_strtolower($title)) : ucfirst(strtolower($title));

			return $titleFormat;
		}
		elseif ($text_transform == 2)
		{
			$titleFormat = $mbString ? mb_convert_case($title, MB_CASE_TITLE, "UTF-8") : ucwords(strtolower($title));

			return $titleFormat;
		}
		elseif ($text_transform == 3)
		{
			$titleFormat = $mbString ? mb_strtoupper($title, "UTF-8") : strtoupper($title);

			return $titleFormat;
		}
		elseif ($text_transform == 4)
		{
			$titleFormat = $mbString ? mb_strtolower($title, "UTF-8") : strtolower($title);

			return $titleFormat;
		}

		return $title;
	}

	/**
	 * Function to return formatted date (using globalization from iC Library)
	 *
	 * @since	3.6.0
	 */
	static public function dateToFormat($date)
	{
		if (iCDate::isDate($date))
		{
			// Date Format Option (Global Component Option)
			$date_format_global = JComponentHelper::getParams('com_icagenda')->get('date_format_global', 'Y - m - d');

			// Date Format Option (Menu Option)
			$date_format_menu   = JFactory::getApplication()->getParams()->get('format', '');

			// Set Date Format option to be used
			$format             = $date_format_menu ? $date_format_menu : $date_format_global;

			// Separator Option
			$separator          = JFactory::getApplication()->getParams()->get('date_separator', ' ');

			if ( ! is_numeric($format))
			{
				// Update old Date Format options of versions before 2.1.7
				$format = str_replace(array('nosep', 'sepb', 'sepa'), '', $format);
				$format = str_replace('.', ' .', $format);
				$format = str_replace(',', ' ,', $format);
			}

			$dateFormatted      = iCGlobalize::dateFormat($date, $format, $separator);

			return $dateFormatted;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Function to return formatted time
	 *
	 * @since	3.6.0
	 */
	static public function dateToTime($date)
	{
		if (iCDate::isDate($date))
		{
			$eventTimeZone = null;
			$datetime      = JHtml::date($date, 'Y-m-d H:i', $eventTimeZone);
			$timeformat    = JComponentHelper::getParams('com_icagenda')->get('timeformat', 1);

			$lang_time     = ($timeformat == 1) ? 'H:i' : 'h:i A';

			$time          = date($lang_time, strtotime($datetime));

			return $time;
		}

		return false;
	}

	/**
	 * Function to return Email Cloaked TAG
	 *
	 * @since	3.6.8
	 */
	static public function emailTag($email)
	{
		$plugin = JPluginHelper::getPlugin('content', 'emailcloak');

		if ($plugin)
		{
			$params = new JRegistry($plugin->params);
			$mode   = $params->get('mode', 1);

			return JHtml::_('email.cloak', $email, $mode);
		}

		return $email;
	}

	/**
	 * Function to return Website TAG
	 *
	 * @since	3.6.0
	 */
	static public function websiteTag($url)
	{
		$targetOption = JComponentHelper::getParams('com_icagenda')->get('targetLink', '');
		$target       = ! empty($targetOption) ? '_blank' : '_parent';

		$link         = iCUrl::urlParsed($url, 'scheme');

		return '<a href="' . $link . '" rel="nofollow" target="' . $target . '">' . $url . '</a>';
	}

	/**
	 * Function to return File attachment TAG
	 *
	 * @since	3.6.0
	 */
	static public function fileTag($file)
	{
		return '<a class="icDownload" href="' . $file . '" rel="nofollow" target="_blank">' . JText::_('COM_ICAGENDA_EVENT_DOWNLOAD') . '</a>';
	}
}
