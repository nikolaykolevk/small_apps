<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.0 2018-05-17
 *
 * @package     iCagenda.Site
 * @subpackage  mod_iccalendar
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * iCagenda - iC calendar
 */

// Get iCagenda component parameters
$com_params = JComponentHelper::getParams('com_icagenda');

// For Dev.
$time_loading = $com_params->get('time_loading', '');

if ($time_loading && class_exists('iCLibrary'))
{
	$starttime_cal = iCLibrary::getMicrotime();
}

jimport('joomla.application.component.helper');

// Get Application
$app    = JFactory::getApplication();
$jinput = $app->input;

// Check Errors: iC Library & iCagenda Utilities
$UTILITIES_DIR = is_dir(JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities');

if ( (!$UTILITIES_DIR)
	|| (!class_exists('iCLibrary')) )
{
	$alert_message = JText::_('ICAGENDA_CAN_NOT_LOAD').'<br />';
	$alert_message.= '<ul>';
	if (!class_exists('iCLibrary')) $alert_message.= '<li>' . JText::_('IC_LIBRARY_NOT_LOADED') . '</li>';
	if (!$UTILITIES_DIR) $alert_message.= '<li>' . JText::_('ICAGENDA_A_FOLDER_IS_MISSING') . '</li>';
	$alert_message.= '</ul>';
	if (!$UTILITIES_DIR) $alert_message.= JText::_('ICAGENDA_IS_NOT_CORRECTLY_INSTALLED') . ' ';
	if (!$UTILITIES_DIR) $alert_message.= JText::_('ICAGENDA_INSTALL_AGAIN') . '<br />';
	if (!$UTILITIES_DIR) $alert_message.= JText::_('IC_ALTERNATIVELY') . ':<br /><ul>';
	if ($UTILITIES_DIR) $alert_message.= JText::_('IC_PLEASE') . ', ';
	if (!class_exists('iCLibrary'))
	{
		if (!$UTILITIES_DIR) $alert_message.= '<li>';
		$alert_message.= JText::_('IC_LIBRARY_CHECK_PLUGIN_AND_LIBRARY');
		if (!$UTILITIES_DIR) $alert_message.= '</li>';
	}
	if (!$UTILITIES_DIR)
	{
		$alert_message.= '<li>' . JText::Sprintf('ICAGENDA_UTILITIES_FIX_MANUAL'
						, '<strong>admin/utilities</strong>'
						, '<strong>administrator/components/com_icagenda/</strong>');
		$alert_message.= '</li></ul>';
	}

	// Get the message queue
	$messages = $app->getMessageQueue();

	$display_alert_message = false;

	// If we have messages
	if (is_array($messages) && count($messages))
	{
		// Check each message for the one we want
		foreach ($messages as $key => $value)
		{
			if ($value['message'] == $alert_message)
			{
				$display_alert_message = true;
			}
		}
	}

	if (!$display_alert_message)
	{
		$app->enqueueMessage($alert_message, 'error');
	}

	echo JText::_('IC_MODULE_CAN_NOT_BE_LOADED') . '<br />';
	echo JText::_('IC_MODULE_CHECK_ALERT_MESSAGE');

	return false;
}

// Load iCagenda Utilities
JLoader::registerPrefix('icagenda', JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities');

jimport( 'joomla.environment.request' );

// Get Document
$document	= JFactory::getDocument();

// Test if translation is missing, set to en-GB by default
$language = JFactory::getLanguage();
$language->load( 'mod_iccalendar', JPATH_SITE, 'en-GB', true );
$language->load( 'mod_iccalendar', JPATH_SITE, null, true );

// Include the class of the syndicate functions only once
if ( ! class_exists('modiCcalendarHelper')) require_once(dirname(__FILE__) . '/helper.php');

// Check valid NEXT DATE (removed 3.6.3)
icagendaEventsData::getNext();

// Module ID
$modid		= $module->id;

// Params of the Module iC Calendar
$moduleclass_sfx	= htmlspecialchars($params->get('moduleclass_sfx'));
$mouseover			= $params->get('mouseover', 'click');
$mouseout			= $params->get('mouseout', 1);
$columns_bg_color	= array(
						$params->get('sun', ' '),
						$params->get('mon', ' '),
						$params->get('tue', ' '),
						$params->get('wed', ' '),
						$params->get('thu', ' '),
						$params->get('fri', ' '),
						$params->get('sat', ' '),
					);
$firstday			= $params->get('firstday', '1');
$calfontcolor		= $params->get('calfontcolor', ' ');
$OneEventbgcolor	= $params->get('OneEventbgcolor', ' ');
$Eventsbgcolor		= $params->get('Eventsbgcolor', ' ');
$bgcolor			= $params->get('bgcolor', ' ');
$bgimage			= $params->get('bgimage');
$bgimagerepeat		= $params->get('bgimagerepeat');
$closebutton		= $params->get('calendarclosebtn', 1);
$closebutton_custom	= $params->get('calendarclosebtn_Content', 'X');
$theme_calendar		= $params->get('template', 'default');
$firstMonth			= iCDate::isDate($params->get('firstMonth'))
					? $params->get('firstMonth')
					: '';

$setTodayTimezone	= $params->get('setTodayTimezone', '');

// Ordering set by default (time/category) - Option in developpement (Not used)
$events_ordering_first	= $params->get('events_ordering_first', '1_ASC');
$events_ordering_second	= $params->get('events_ordering_second', '2_ASC');
$ictip_ordering			= $events_ordering_first.'-'.$events_ordering_second;

$header_text			= $params->get('header_text', '');
$padding				= $params->get('padding', '0');

// Module
$cal		= new modiCcalendarHelper;
$data		= $cal->getStamp($params);
$url_date	= $jinput->get('date');
$iccaldate	= $jinput->get('iccaldate');

// First day of the current month
$this_month	= $firstMonth
//			? date("Y-m-d", strtotime("+1 month", strtotime($firstMonth)))
			? date("Y-m-01", strtotime($firstMonth))
			: JHtml::date('now', 'Y-m-01', null);

if ( isset($iccaldate)
	&& !empty($iccaldate) )
{
	// This should be the first day of a month
	$date_start = date('Y-m-01', strtotime($iccaldate));
}
else
{
	$date_start	= $this_month;
}

$nav = $cal->getNav($date_start, $modid);


// Search template of iC Calendar from the selected Theme Pack
$themes_path = '/components/com_icagenda/themes/packs/';

if ( ! file_exists(JPATH_BASE . $themes_path . $theme_calendar . '/' . $theme_calendar . '_calendar.php'))
{
	$theme_calendar = 'default';
}

$theme_tmpl	= JPATH_BASE . $themes_path . $theme_calendar . '/' . $theme_calendar;
$theme_css	= $themes_path . $theme_calendar . '/css/' . $theme_calendar;

$t_calendar		= $theme_tmpl . '_calendar.php';
$css_module		= $theme_css . '_module.css';
$css_mod_rtl	= $theme_css . '_module-rtl.css';

// ToolTip 2 in developpement (Not used)
$tip_type = '1';

if ($tip_type == 1)
{
	$t_day = $theme_tmpl . '_day.php';
}
elseif ($tip_type == 2)
{
	$t_day = $theme_tmpl . '_calendar_tip.php';
}

// Add the media specific CSS to the document
icagendaThemeStyle::addMediaCss($theme_calendar, 'module');

// Load Vector iCicons Font (navigation arrows)
JHtml::stylesheet( 'media/com_icagenda/icicons/style.css' );

// Theme pack component css
$document->addStyleSheet( JURI::base( true ) . $css_module );

// RTL css if site language is RTL
$lang = JFactory::getLanguage();

if ( $lang->isRTL()
	&& file_exists( JPATH_SITE . $css_mod_rtl) )
{
	$document->addStyleSheet( JURI::base( true ) . $css_mod_rtl );
}

if (version_compare(JVERSION, '3.0', 'ge'))
{
	// Request Joomla to load jQuery in no conflict mode
	JHtml::_('bootstrap.framework');
	JHtml::_('jquery.framework');
}
else
{
	//Load JS
	JHtml::_('behavior.mootools');

	$header = $document->getHeadData();
	$loadJquery = true;

	switch($params->get('loadJquery',"auto"))
	{
		case "0":
			$loadJquery = false;
			break;
		case "1":
			$loadJquery = true;
			break;
		case "auto":
			foreach ($header['scripts'] as $scriptName => $scriptData)
			{
				if (substr_count($scriptName,'jquery'))
				{
					$loadJquery = false;
					break;
				}
			}
			break;
	}

	//Add js
	$app = JFactory::getApplication();

	if ($loadJquery && !$app->get('jquery'))
	{
		$document->addScript( 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js' );
		$app->set('jquery', true);
	}

	$document->addScript( 'modules/mod_iccalendar/js/jquery.noconflict.js' );
}

if (ini_get('allow_url_fopen'))
{
	$file = file_get_contents($t_day);

	if ( ! strpos($file, "data-cal-date"))
	{
		$server_date = false;
		echo "<div class='alert alert-error'>'data-cal-date' not found in your Custom Theme Pack!</div>";
	}
	else
	{
		$server_date = true;
	}
}
else
{
	$server_date = true;
}

if ( ! $setTodayTimezone && $server_date)
{
	$document->addScript( 'modules/mod_iccalendar/js/jQuery.highlightToday.min.js' );
}


$icclasstip		= '.icevent a';
$icclass		= '.iccalendar';
$icagendabtn	= '.icagendabtn_' . $modid;
$mod_iccalendar	= '#mod_iccalendar_' . $modid;

$close_btn		= ($closebutton == 1) ? $closebutton_custom : JText::_('MOD_ICCALENDAR_CLOSE');

// Minimum popup width for mobile phone mode
$mobile_min_width = 320;

$stamp = new cal($data, $t_calendar, $t_day, $nav, $firstday, $columns_bg_color, $calfontcolor,
		$OneEventbgcolor, $Eventsbgcolor, $bgcolor, $bgimage, $bgimagerepeat,
		$moduleclass_sfx, $modid, $theme_calendar, $ictip_ordering, $header_text);

// Load Calendar Template
echo '<!-- iCagenda - Calendar -->';
echo '<div tabindex="0" id="ic-calendar-' . $modid . '" class="">';
require $t_calendar;
echo '</div>';
?>

<script type="text/javascript">
(function($){
	var icmouse = '<?php echo $mouseover; ?>';
	var mouseout = '<?php echo $mouseout; ?>';
	var icclasstip = '<?php echo $icclasstip; ?>';
	var icclass = '<?php echo $icclass; ?>';
	var position = '<?php echo $params->get('position', 'center'); ?>';
	var posmiddle = '<?php echo $params->get('posmiddle', 'top'); ?>';
	var modid = '<?php echo $modid; ?>';
	var modidid = '<?php echo '#'.$modid; ?>';
	var icagendabtn = '<?php echo $icagendabtn; ?>';
	var mod_iccalendar = '<?php echo $mod_iccalendar; ?>';
	var template = '<?php echo '.'.$theme_calendar; ?>';
	var loading = '<?php echo JText::_('MOD_ICCALENDAR_LOADING'); ?>';
	var closetxt = '<?php echo $close_btn; ?>';
	var tip_type = '<?php echo $tip_type; ?>';
	var tipwidth = <?php echo (int)$params->get('tipwidth', 390) ?>;
	var smallwidththreshold = <?php echo (int) $com_params->get('smallwidththreshold', 0) ?>;
	var verticaloffset = <?php echo (int)$params->get('verticaloffset', 0) ?>;
	var css_position = '';
	var mobile_min_width = <?php echo $mobile_min_width; ?>;
	var extra_css = '';

	$(document).on('click touchend', icagendabtn, function(e){<?php // Refresh the current month ?>
		e.preventDefault();

		url=$(this).attr('href');

		$(modidid).html('<\div class="icloading_box"><\div style="text-align:center;">' + loading + '<\/div><\div class="icloading_img"><\/div><\/div>').load(url + ' ' + mod_iccalendar, function(){$('<?php echo $mod_iccalendar ?>').highlightToday();});

	});

	// Calendar Keyboard Accessibility (experimental, since 3.5.14)
	if (typeof first_mod === 'undefined') {
		$i = '1';
		first_mod = modid;
		first_nb = $i;
		nb_mod = $i;
	} else {
		$i = (typeof $i === 'undefined') ? '2' : ++$i;
		nb_mod = $i;
	}

	$('#ic-calendar-'+modid).addClass('ic-'+nb_mod);

	$(document).keydown(function(e){

		// ctrl+alt+C : focus on first Calendar module
		// REMOVE: Polish language conflict, alt+C Ć
//		if (e.ctrlKey && e.altKey && e.keyCode == 67) {
//			$('#ic-calendar-'+first_mod).focus();
//		}

		// ctrl+alt+N : focus on Next calendar module
		if (e.ctrlKey && e.altKey && e.keyCode == 78) {
			if ($('#ic-calendar-'+modid).is(':focus')) {
				activ = $('#ic-calendar-'+modid).attr('class');
				act = activ.split('-');
				act = act[1];
				next = ++act;
			}
			mod_class = $('#ic-calendar-'+modid).attr('class');
			if ($('.ic-'+next).length == 0) next = 1;
			if (mod_class == 'ic-'+next) $('.ic-'+next).focus();
		}

		// On focused calendar module
		if ($('#ic-calendar-'+modid).is(':focus')){
			switch (e.keyCode) {
				case 37:
					// Left arrow pressed
					url = $('#ic-calendar-'+modid+' #ic-prev-month').attr('href');
					break;
				case 38:
					// Top arrow pressed
					url = $('#ic-calendar-'+modid+' #ic-next-year').attr('href');
					break;
				case 39:
					// Right arrow pressed
					url = $('#ic-calendar-'+modid+' #ic-next-month').attr('href');
					break;
				case 40:
					// Top arrow pressed
					url = $('#ic-calendar-'+modid+' #ic-prev-year').attr('href');
					break;
			}

			if ((!e.shiftKey && (e.keyCode == 37 || e.keyCode == 39)) ||
				(e.shiftKey && (e.keyCode == 38 || e.keyCode == 40))) {
				$(modidid).html('<\div class="icloading_box"><\div style="text-align:center;">' + loading + '<\/div><\div class="icloading_img"><\/div><\/div>').load(url + ' ' + mod_iccalendar, function(){$('<?php echo $mod_iccalendar ?>').highlightToday();});
			}

//			if ($(modidid+' '+icclasstip).is(':focus') && e.keyCode == 13){
//				var icmouse = "click";
//			}
		}
	});

	if (tip_type=='2') {<?php // Not used ?>
	$(document).on(icmouse, this, function(e){
		e.preventDefault();

		$(".iCaTip").tipTip({maxWidth: "400", defaultPosition: "top", edgeOffset: 1, activation:"hover", keepAlive: true});
	});
	}

	if (tip_type=='1') {<?php // Display the events popup ?>
		$view_width=$(window).width();<?php // Get the viewport width ?>
		if($view_width<smallwidththreshold){<?php // Mobile phones do not support 'hover' or 'click' in the conventional way ?>
			icmouse='click touchend';
		}

		$(document).on(icmouse, modidid+' '+icclasstip, function(e){
			$view_height=$(window).height();<?php // Get the viewport height ?>
			$view_width=$(window).width();<?php // Get the viewport width ?>
			e.preventDefault();
			$('#ictip').remove();
			$parent=$(this).parent();
			$tip=$($parent).children(modidid+' .spanEv').html();

			if ($view_width < smallwidththreshold)
			{
				<?php // Mobile phone style - fill the viewport ?>
				css_position = 'fixed';
				$width_px = Math.max(mobile_min_width,$view_width); <?php // Popup width is screen width (minimum 320px) ?>
				$width = '100%';
				$pos = '0px';
				$top = '0px';
				extra_css='border:0;border-radius:0;height:100%;box-shadow:none;margin:0px;padding:10px;min-width:'+mobile_min_width+'px;overflow-y:scroll;padding:<?php echo $padding ?>;';<?php // iPhone friendly size and allow scrolling if the page overflows ?>
			}
			else
			{
				css_position = 'absolute';
				$width_px = Math.min($view_width, tipwidth);
				$width = $width_px+'px';

				<?php // Horizontal positioning ?>
				switch(position) {
					case 'left':
						$pos=Math.max(0,$(modidid).offset().left-$width_px-10)+'px';
						break;
					case 'right':
						$pos=Math.max(0,Math.min($view_width-$width_px,$(modidid).offset().left+$(modidid).width()+10))+'px';
						break;
					default:<?php //Centre ?>
						$pos=Math.ceil(($view_width-$width_px)/2)+'px';
						break;
				}

				<?php // Vertical positioning ?>
				if (posmiddle === 'top')
				{
					$top = Math.max(0,$(modidid).offset().top-verticaloffset)+'px';<?php // Top ?>
				}
				else
				{
					$top = Math.max(0,$(modidid).offset().top+$(modidid).height()-verticaloffset)+'px';<?php // Bottom ?>
				}
			}


			$('body').append('<\div style="display:block; position:'+css_position+'; width:'+$width+'; left:'+$pos+'; top:'+$top+';'+extra_css+'" id="ictip"> '+$(this).parent().children('.date').html()+'<a class="close" style="cursor: pointer;"><\div style="display:block; width:auto; height:50px; text-align:right;">' + closetxt + '<\/div></a><span class="clr"></span>'+$tip+'<\/div>');

			// Tooltip Keyboard Accessibility (experimental, since 3.5.14)
			$(document).keydown(function(e){
				//	Shift : focus on tooltip events
				if ($('.icevent a').is(':focus') && e.keyCode == 16){
					$('.ictip-event a').focus();
				}
				//	esc : close tooltip
				if (($('.ictip-event a').is(':focus') || $('.icevent a').is(':focus')) && e.keyCode == 27){
					e.preventDefault();
					$('#ictip').remove();
				}
			});

			// Close Tooltip
			$(document).on('click touchend', '.close', function(e){
				e.preventDefault();
				$('#ictip').remove();
			});

			if (mouseout == '1')
			{
				$('#ictip')
					.mouseout(function() {
//						$( "div:first", this ).text( "mouse out" );
						$('#ictip').stop(true).fadeOut(300);
					})
					.mouseover(function() {
//						$( "div:first", this ).text( "mouse over" );
						$('#ictip').stop(true).fadeIn(300);
					});
			}
		});
	}

}) (jQuery);
</script>
<?php
if ( ! $setTodayTimezone && $server_date
	&& ! $firstMonth
	&& $lang->getTag() != 'fa-IR')
{
	$document->addScriptDeclaration('
		jQuery(document).ready(function(){
			jQuery("' . $mod_iccalendar . '").highlightToday("show_today");
		});
	');
}

// For Dev.
if ($time_loading)
{
	$endtime_cal = iCLibrary::getMicrotime();

	echo '<center style="font-size:8px;">Time to create calendar: ' . round($endtime_cal - $starttime_cal, 3) . ' seconds</center>';
}
