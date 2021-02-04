<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.13 2018-03-28
 *
 * @package     iCagenda.Admin
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.5.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

?>
<?php if (version_compare(JVERSION, '3.0', 'lt')) : ?>

<form
	action="<?php echo JRoute::_('index.php?option=com_icagenda&task=registrations.display&format=raw'); ?>"
	method="post"
	name="adminForm"
	id="download-form"
	class="form-validate">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_ICAGENDA_REGISTRATIONS_DOWNLOAD'); ?></legend>
		<?php foreach ($this->form->getFieldset() as $field) : ?>
		<div class="control-group">
			<?php if (!$field->hidden) : ?>
			<div class="control-label">
				<?php echo $field->label; ?>
			</div>
			<?php endif; ?>
			<div class="controls">
				<?php echo $field->input; ?>
			</div>
		</div>
		<?php endforeach; ?>
		<div class="clr"></div>
		<button type="button" class="btn" onclick="this.form.submit();window.top.setTimeout('window.parent.jModalClose()', 700);"><?php echo JText::_('COM_ICAGENDA_REGISTRATIONS_EXPORT'); ?></button>
		<!--button type="button" class="btn" onclick="window.parent.jModalClose()"><?php echo JText::_('COM_ICAGENDA_CANCEL'); ?></button-->
	</fieldset>
</form>

<?php else : ?>

<?php
JHtml::_('bootstrap.tooltip', '.hasTooltip', array('placement' => 'bottom'));
?>
<div class="container-popup">
	<form
		class="form-horizontal form-validate"
		id="download-form"
		name="adminForm"
		action="<?php echo JRoute::_('index.php?option=com_icagenda&task=registrations.display&format=raw'); ?>"
		method="post">

		<?php foreach ($this->form->getFieldset() as $field) : ?>
			<?php echo $this->form->renderField($field->fieldname); ?>
		<?php endforeach; ?>

		<button class="hidden"
			id="closeBtn"
			type="button"
			onclick="window.parent.jQuery('#modal-download').modal('hide');">
		</button>
		<button class="hidden"
			id="exportBtn"
			type="button"
			onclick="this.form.submit();window.top.setTimeout('window.parent.jQuery(\'#downloadModal\').modal(\'hide\')', 700);">
		</button>
	</form>
</div>

<?php endif; ?>
