<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.13 2017-11-10
 *
 * @package     iCagenda.Site
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

$app     = JFactory::getApplication();
$isSef   = $app->getCfg('sef');
$eventId = $app->input->getInt('id');
$menu    = $app->getMenu();

$lang    = JFactory::getLanguage();

// Look for the home menu
if (JLanguageMultilang::isEnabled())
{
	$home = $menu->getDefault($lang->getTag());
}
else
{
	$home  = $menu->getDefault();
}

//$regid = $app->input->get('reg');

$startDate      = icagendaRender::dateToFormat($this->item->startdate);
$startTime      = icagendaRender::dateToTime($this->item->startdate);
$endDate        = icagendaRender::dateToFormat($this->item->enddate);
$endTime        = icagendaRender::dateToTime($this->item->enddate);

if ( ! $this->registration)
{
	$app->redirect(JRoute::_('index.php?option=com_icagenda&view=event&id=' . $app->input->getInt('id'), false));
}

$regDateTime    = icagendaRender::dateToFormat($this->registration->date)
				. ' - ' . icagendaRender::dateToTime($this->registration->date);


$period_set     = substr($this->item->startdate, 0, 4);

$urlDateVar     = '';

// Registration Type is 'all dates of the event' (single dates + period)
if ($this->registration->period == 1)
{
	$registrationDates  = '';
}

// Registration Type is 'select list of dates'
// Is a Period (no date, and period = 0)
elseif ($this->registration->date == '' && ! $this->registration->period)
{
	$registrationDates  = (iCDate::isDate($this->item->startdate) && iCDate::isDate($this->item->enddate))
//						? JText::sprintf( 'COM_ICAGENDA_REGISTERED_EVENT_PERIOD', $startDate, $startTime, $endDate, $endTime )
						? '<div class="ic-label">' . JText::_('COM_ICAGENDA_REGISTRATION_DATES') . ': </div>'
							. '<div class="ic-value">' . JText::_('COM_ICAGENDA_PERIOD_FROM') . ' ' . $startDate . ' ' . $startTime
							. ' ' . JText::_('COM_ICAGENDA_PERIOD_TO') . ' ' . $endDate . ' ' . $endTime . '</div>'
						: '';
}

// Registration Type is 'select list of dates' (single dates + period)
// Is a Single date
else
{
//	$registrationDates  = JText::sprintf( 'COM_ICAGENDA_REGISTERED_EVENT_DATE', $regDateTime, '' );
	$registrationDates  = '<div class="ic-label">' . JText::_('COM_ICAGENDA_REGISTRATION_DATE') . '</div>'
						. '<div class="ic-value">' . $regDateTime . '</div>';

	$date_var           = ($isSef == '1') ? '?date=' : '&amp;date=';
	$urlDateVar         = $date_var . icagendaEvent::urlDateVar($this->registration->date);
}

$eventURL = JRoute::_('index.php?option=com_icagenda&view=event&id=' . $eventId) . $urlDateVar;

// Complete Page Content
$content =  '<p>' . JText::_('COM_ICAGENDA_REGISTRATION_TY') . ' ' . $this->registration->name . ',</p>'; // @TODO: name used in sprintf %s
$content.=  '<p>' . JText::sprintf('COM_ICAGENDA_REGISTRATION_COMPLETE_CONFIRMED', $this->item->title) . '</p>';

$content.=  '<div class="ic-divTable ic-align-left ic-clearfix">';

// Currently, only 'Simple Registration' system (in roadmap: 'Paiement' and 'Attend/Not Attend' systems)
$simpleRegSystem = JText::_('COM_ICAGENDA_REGISTRATION_SUMMARY_REGISTRATION');

$content.=  '<legend>' . JText::sprintf('COM_ICAGENDA_REGISTRATION_SUMMARY_LEGEND', $simpleRegSystem) . '</legend>';


// Event
$content.=  '<div class="ic-divRow">';
$content.=  '<div class="ic-label">' . JText::_('IC_EVENT') . '</div>'
			. '<div class="ic-value">' . $this->item->title . '</div>';
$content.=  '</div>';

// Date(s) booked
$content.=  '<div class="ic-divRow">';
$content.=  $registrationDates;
$content.=  '</div>';

// Number of tickets booked
$content.=  '<div class="ic-divRow">';
$content.=  '<div class="ic-label">' . JText::_('ICAGENDA_REGISTRATION_FORM_PEOPLE') . '</div>'
			. '<div class="ic-value">' . $this->registration->people . '</div>';
$content.=  '</div>';

$content.=  $this->iCagendaOnRegistrationCompleteDataDisplay;

$content.=  '</div>';

?>
<div id="icagenda" class="ic-registration-complete<?php echo $this->pageclass_sfx; ?>">
	<h1>
		<?php echo $this->escape(JText::_('COM_ICAGENDA_REGISTRATION_TITLE')); ?>
	</h1>
	<div class="ic-registration-complete-content">
		<?php echo $content; ?>
	</div>
	<br />
	<div class="ic-registration-complete-buttons">
		<a href="<?php echo JRoute::_('index.php?Itemid=' . $home->id); ?>" class="ic-btn ic-btn-info button">
		<?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
			<i class="icon-home icon-white"></i>&nbsp;<?php echo JTEXT::_('JERROR_LAYOUT_HOME_PAGE'); ?>
		<?php else : ?>
			<span style="color:#FFF"><?php echo JTEXT::_('JERROR_LAYOUT_HOME_PAGE'); ?></span>
		<?php endif; ?>
		</a>
		&nbsp;
		<a href="<?php echo $eventURL; ?>" class="ic-btn ic-btn-success button">
		<?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
			<i class="icon-eye icon-white"></i>&nbsp;<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_EVENT_LINK'); ?>
		<?php else : ?>
			<span style="color:#FFF"><?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_EVENT_LINK'); ?></span>
		<?php endif; ?>
		</a>
	</div>
	<br />
</div>
<?php
if (version_compare(JVERSION, '3.0', 'lt'))
{
	JHtml::_('stylesheet', 'icagenda-front.j25.css', 'components/com_icagenda/add/css/');
}
