<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.13 2017-10-20
 *
 * @package     iCagenda.Site
 * @subpackage  Add.elements
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
 *------------------------------------------------------------------------------
 *	iCagenda Set Var for Theme Packs - List
 *------------------------------------------------------------------------------
*/

$EVENT_TITLE					= $item->titleFormat;
$EVENT_TITLEBAR					= $item->titlebar;
$EVENT_META_AS_SHORTDESC		= $item->metaAsShortDesc;
$EVENT_SHORT_DESCRIPTION		= $item->shortDescription;
$EVENT_DESCRIPTION				= $item->description;
$AUTO_SHORT_DESCRIPTION			= $item->descShort;
$CATEGORY_FONTCOLOR				= $item->fontColor;

$app			= JFactory::getApplication();
$isSef			= $app->getCfg('sef');

$datesDisplay	= $this->params->get('datesDisplay', 1);

$eventTimeZone	= null;
$weekdays       = ($item->weekdays || $item->weekdays == '0') ? true : false;

$this_date		= JHtml::date($evt, 'Y-m-d H:i', $eventTimeZone);
$date_today		= JHtml::date('now', 'Y-m-d');
$period			= unserialize($item->period);
$period			= is_array($period) ? $period : array();
$is_in_period	= (in_array($this_date, $period)) ? true : false;

if ($is_in_period
	&& $item->weekdays == ''
	&& strtotime($item->startdate) <= strtotime($date_today)
	&& strtotime($item->enddate) >= strtotime($date_today)
	)
{
	$ongoing = true;
}
else
{
	$ongoing = false;
}

// Day in Date Box (list of events)
$EVENT_DAY			= $this->params->get('day_display_global', 1)
					? icagendaEvents::day($evt, $item)
					: false;

// Month in Date Box (list of events)
$EVENT_MONTH		= $this->params->get('month_display_global', 1)
					? icagendaEvents::dateBox($this_date, $this->params->get('event_month_format', 'monthshort'), $ongoing)
					: false;

if ($this->template == 'default'
	&& $this->params->get('event_month_format') == 'month')
{
	$style = '.ic-month {'
			. '  font-size: 12px;'
			. '}'; 
	JFactory::getDocument()->addStyleDeclaration($style);
}

// @deprecated since 3.6.0 (EVENT_MONTHSHORT kept for B/C)
$EVENT_MONTHSHORT	= $this->params->get('month_display_global', 1) ? icagendaEvents::dateBox($this_date, 'monthshort', $ongoing) : false;

// Year in Date Box (list of events)
$EVENT_YEAR			= $this->params->get('year_display_global', 1)
					? icagendaEvents::dateBox($evt, 'year', $ongoing)
					: false;

// Time in Date Box (list of events)
$EVENT_TIME			= ($this->params->get('time_display_global', 0) && $item->displaytime == 1)
					? icagendaEvents::dateToTimeFormat($evt)
					: false;

// Load Event Data
$EVENT_DATE			= icagendaEvent::nextDate($evt, $item);
$EVENT_SET_DATE		= icagendaEvent::urlDateVar($evt);
$READ_MORE			= ($this->params->get('shortdesc_display_global', '') == '' && ! $item->shortdesc)
					? icagendaEvent::readMore($item->url, $item->desc, '[&#46;&#46;&#46;]')
					: false;

// URL to event details view (list of events)
//if ($datesDisplay == 1)
//{
	$date_var		= ($isSef == '1') ? '?date=' : '&amp;date=';
	$set_url_date	= $date_var . $EVENT_SET_DATE;
	$date_url		= ( ! $weekdays && in_array($this_date, $period))
					? ''
					: $date_var . $EVENT_SET_DATE;

	$EVENT_URL = $item->url . $date_url;
//}
//else
//{
//	$EVENT_URL = $item->url;
//}

/**
 *	Event Dates
 */
$EVENT_NEXT				= $item->next;

/**
 *	Feature Icons
 */
$FEATURES_ICONSIZE_LIST		= $this->params->get('features_icon_size_list');
$FEATURES_ICONSIZE_EVENT	= $this->params->get('features_icon_size_event');
$SHOW_ICON_TITLE			= $this->params->get('show_icon_title');
// Get media path
$params_media = JComponentHelper::getParams('com_media');
$image_path = $params_media->get('image_path', 'images');
$FEATURES_ICONROOT_LIST		= JUri::root() . $image_path . '/icagenda/feature_icons/' . $FEATURES_ICONSIZE_LIST . '/';
$FEATURES_ICONROOT_EVENT	= JUri::root() . $image_path . '/icagenda/feature_icons/' . $FEATURES_ICONSIZE_EVENT . '/';
$FEATURES_ICONS				= array();

if (isset($item->features) && is_array($item->features)
	&& (!empty($FEATURES_ICONSIZE_LIST) || !empty($FEATURES_ICONSIZE_EVENT)))
{
	foreach ($item->features as $feature)
	{
		$FEATURES_ICONS[] = array('icon' => $feature->icon, 'icon_alt' => $feature->icon_alt);
	}
}


/**
 *	Event Image and Thumbnails
 */
$EVENT_IMAGE	= $item->image;

$IMAGE_MEDIUM	= ($EVENT_IMAGE && icagendaClass::isLoaded('icagendaThumb'))
				? icagendaThumb::sizeMedium($item->image)
				: '';


/**
 *	Events List - Intro Text (TO DO: MIGRATE TO UTILITIES)
 */
$EVENT_DESC = ($item->desc || $item->shortdesc) ? true : false;

$shortdesc_display_global = $this->params->get('shortdesc_display_global', '');
$Filtering_ShortDesc_Global = JComponentHelper::getParams('com_icagenda')->get('Filtering_ShortDesc_Global', '');

if ($shortdesc_display_global == '1') // short desc
{
	$EVENT_DESCSHORT	= $item->shortdesc ? $item->shortdesc : false;

	if ($EVENT_DESCSHORT)
	{
		$EVENT_DESCSHORT	= empty($Filtering_ShortDesc_Global) ? '<i>' . $EVENT_DESCSHORT . '</i>' : $EVENT_DESCSHORT;
	}
}
elseif ($shortdesc_display_global == '2') // Auto-Introtext
{
	$EVENT_DESCSHORT	= $AUTO_SHORT_DESCRIPTION ? $AUTO_SHORT_DESCRIPTION : false;
}
elseif ($shortdesc_display_global == '0') // Hide
{
	$EVENT_DESCSHORT	= false;
}
else // Auto (First Short Description, if does not exist, Auto-generated short description from the full description. And if does not exist, will use meta description if not empty)
{
	$shortDescription = $item->shortdesc ? $item->shortdesc : $AUTO_SHORT_DESCRIPTION;

	$metaAsShortDesc = $EVENT_META_AS_SHORTDESC;

	if ($metaAsShortDesc)
	{
		$metaAsShortDesc	= empty($Filtering_ShortDesc_Global) ? '<i>' . $metaAsShortDesc . '</i>' : $metaAsShortDesc;
	}

	$EVENT_DESCSHORT	= $shortDescription ? $shortDescription : $metaAsShortDesc;
}

$EVENT_INTRO_TEXT = $EVENT_DESCSHORT; // New var name since 3.4.0



$EVENT_VENUE			= $this->params->get('venue_display_global', 1) ? $item->place : false;
$EVENT_POSTAL_CODE		= $this->params->get('city_display_global', 1) ? $item->city : false;
$EVENT_CITY				= $this->params->get('city_display_global', 1) ? $item->city : false;
$EVENT_COUNTRY			= $this->params->get('country_display_global', 1) ? $item->country : false;

$CATEGORY_TITLE			= $item->cat_title;
$CATEGORY_COLOR			= $item->cat_color;


/**
 *	Add Event Info from plugins (if exists)
 */
$onListAddEventInfo = $this->dispatcher->trigger('onListAddEventInfo', array('com_icagenda.list', &$item, &$this->params));

$IC_LIST_ADD_EVENT_INFO = '';

foreach ($onListAddEventInfo as $added_info)
{
	$IC_LIST_ADD_EVENT_INFO.= '<div class="ic-list-add-event-info">' . $added_info . '</div>';
}



// B/C Theme Packs (to be checked, and added if needed)
if ( ! in_array($this->template, array('default', 'ic_rounded')))
{
	$item->place_name				= $item->place;
	$item->startdatetime			= $item->startdate;
	$item->enddatetime				= $item->enddate;

	$item->startDate				= icagendaRender::dateToFormat($item->startdate);
	$item->endDate					= icagendaRender::dateToFormat($item->enddate);

	$item->startTime				= icagendaRender::dateToTime($item->startdate);
	$item->endTime					= icagendaRender::dateToTime($item->enddate);

	$item->day						= $EVENT_DAY;
	$item->monthShort				= $EVENT_MONTHSHORT;
	$item->year						= $EVENT_YEAR;
	$item->evenTime					= $EVENT_TIME;

	$this->atlist					= '';

	// To be checked
	$CUSTOM_FIELDS				= icagendaEventData::loadEventCustomFields($item->id);
}
