<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.13 2018-03-30
 *
 * @package     iCagenda.Site
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.13
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');

if (version_compare(JVERSION, '3.4.0', 'lt'))
{
	JHtml::_('behavior.formvalidation');
}
else
{
	JHtml::_('behavior.formvalidator');
}

JHtml::_('formbehavior.chosen', 'select');

$app    = JFactory::getApplication();
$input  = $app->input;

$date_time_separator = ' - '; // @todo: create custom/globalized option
$start_end_separator = ' &#x279c; '; // @todo: create custom/globalized option

$iCclass_btn_info = 'ic-btn ic-btn-info button';

$eventURL = icagendaEvent::eventURL($this->item);
$listURL  = JRoute::_('index.php?option=com_icagenda&view=list&id=' . $input->getInt('id') . '&Itemid=' . $input->getInt('Itemid'));
?>
<div id="icagenda" class="ic-registration-cancel<?php echo $this->pageclass_sfx; ?>">

	<!--h1>
		<?php echo $this->escape(JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_LABEL')); ?>
	</h1-->

	<?php if ($input->get('dates_cancelled') && ! $this->participantEventRegistrations) : ?>

		<div class="ic-registration-cancel-content">
			<?php echo JText::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRMED', $this->item->title); ?>
		</div>
		<br />
		<div class="ic-registration-cancel-buttons">
			<a href="<?php echo JRoute::_(''); ?>" class="btn button">
				<span class="icon-home icon-white"></span>&nbsp;<?php echo JTEXT::_('JERROR_LAYOUT_HOME_PAGE'); ?>
			</a>
			&nbsp;
			<a href="<?php echo $listURL; ?>" class="btn btn-primary button">
				<span class="icon-eye icon-white"></span>&nbsp;<?php echo JTEXT::_('COM_ICAGENDA_BUTTON_VIEW_LIST'); ?>
			</a>
			&nbsp;
			<a href="<?php echo $eventURL; ?>" class="btn btn-info button">
				<span class="icon-eye icon-white"></span>&nbsp;<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_EVENT_LINK'); ?>
			</a>
		</div>
		<br />

	<?php elseif ($input->get('dates_cancelled') && $this->participantEventRegistrations) : ?>
		<div class="ic-registration-cancel-content">
			<p>
				<?php echo JText::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRMED', $this->item->title); ?>
			</p>
		</div>
		<br />
		<div class="ic-registration-cancel-buttons">
<!--
			<p>
				<?php echo JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_OTHER_DATES_FOR_THIS_EVENT'); ?>
			</p>
-->
			<a href="<?php echo $listURL; ?>" class="btn btn-primary">
				<?php echo JTEXT::_('COM_ICAGENDA_BUTTON_VIEW_LIST'); ?>
			</a>
			<a href="<?php echo $eventURL; ?>" class="btn btn-info">
				<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_EVENT_LINK'); ?>
			</a>
			<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_icagenda&view=registration&layout=cancel&id=' . $input->getInt('id') . '&Itemid=' . $input->getInt('Itemid')); ?>">
				<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_CANCEL_OTHER_DATES_BUTTON'); ?>
			</a>
<!--
			<a class="btn btn-info button" href="<?php echo JRoute::_('index.php?option=com_icagenda&view=registration&layout=cancel&id=' . $input->getInt('id') . '&Itemid=' . $input->getInt('Itemid')); ?>">
				<?php echo JTEXT::_('JYES'); ?>
			</a>
			&nbsp;
			<a class="btn btn-default button" href="<?php echo $eventURL; ?>">
				<?php echo JTEXT::_('JNO'); ?>
			</a>
-->
		</div>
		<br />


	<?php elseif ($this->participantEventRegistrations) : ?>

		<?php // START FORM ?>
		<form class="form-validate form-horizontal well"
			id="icagenda-registration-cancel"
			action="<?php echo JRoute::_('index.php?option=com_icagenda&task=registration.cancel'); ?>"
			method="post"
			enctype="multipart/form-data"
			>
			<fieldset>
				<legend><?php echo JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_LEGEND'); ?></legend>
				<div class="control-group">
					<label id="dates_cancelled-lbl" for="dates_cancelled">
						<?php echo JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_SELECT_DATES'); ?>
					</label>
					<select id="dates_cancelled" name="dates_cancelled[]" multiple required aria-required="true" message="missing dates">
						<?php foreach ($this->participantEventRegistrations as $key => $value) : ?>
							<?php if (iCDate::isDate($value->date)) : ?>
								<option value="<?php echo $value->id; ?>">
									<?php echo icagendaRender::dateToFormat($value->date); ?>
									<?php echo $date_time_separator; ?>
									<?php echo icagendaRender::dateToTime($value->date); ?>
								</option>
							<?php elseif ( ! $value->date && $value->period == 0) : ?>
								<option value="<?php echo $value->id; ?>">
									<?php echo strip_tags(icagendaRender::dateToFormat($this->item->startdate)); ?>
									<?php echo $start_end_separator; ?>
									<?php echo strip_tags(icagendaRender::dateToFormat($this->item->enddate)); ?>
								</option>
							<?php elseif ( ! $value->date && $value->period == 1) : ?>
								<option value="<?php echo $value->id; ?>">
									<?php echo Jtext::_('COM_ICAGENDA_REGISTRATION_CANCEL_ALL_DATES'); ?>
								</option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="control-group">
					<p>
						<?php echo JText::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRM_WARNING', $this->item->title); ?>
					</p>
					<button type="submit" class="btn btn-danger validate">
						<?php echo JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRM_BUTTON'); ?>
					</button>
					<a class="btn" href="<?php echo $eventURL; ?>">
						<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_CANCEL_DENY_BUTTON'); ?>
					</a>
					<input type="hidden" name="eventID" value="<?php echo $input->getInt('id'); ?>" />
					<input type="hidden" name="reg_id" value="<?php echo $input->getInt('reg_id'); ?>" />
					<input type="hidden" name="option" value="com_icagenda" />
					<input type="hidden" name="task" value="registration.cancel" />
				</div>
			</fieldset>
			<?php echo JHtml::_('form.token'); ?>
		</form>

	<?php elseif (isset($this->cancelled)) : ?>

		<div class="ic-registration-cancel-content">
			<?php echo JText::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRMED', $this->item->title); ?>
		</div>
		<br />
		<div class="ic-registration-cancel-buttons">
			<a href="<?php echo JRoute::_(''); ?>" class="<?php echo $iCclass_btn_info; ?>">
			<?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
				<i class="icon-home icon-white"></i>&nbsp;<?php echo JTEXT::_('JERROR_LAYOUT_HOME_PAGE'); ?>
			<?php else : ?>
				<span style="color:#FFF"><?php echo JTEXT::_('JERROR_LAYOUT_HOME_PAGE'); ?></span>
			<?php endif; ?>
			</a>
		</div>
		<br />

	<?php else : ?>

		<div class="ic-registration-cancel-content">
			<p class="alert alert-info"><?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_CANCEL_NONE'); ?></p>
		</div>
		<div class="ic-registration-cancel-buttons">
			<a href="<?php echo JRoute::_(''); ?>" class="<?php echo $iCclass_btn_info; ?>">
				<span class="icon-home icon-white"></span>&nbsp;<?php echo JTEXT::_('JERROR_LAYOUT_HOME_PAGE'); ?>
			</a>
		</div>

	<?php endif; ?>

</div>
<?php
if (version_compare(JVERSION, '3.0', 'lt'))
{
	JHtml::_('stylesheet', 'icagenda-front.j25.css', 'components/com_icagenda/add/css/');
}
