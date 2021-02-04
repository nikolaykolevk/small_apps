<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-28
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form
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

//jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');

/**
 * Custom Field Groups multiple select form field
 */
class icagendaFormFieldCustomfieldGroups extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   3.6.0
	 */
	protected $type = 'customfieldgroups';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 * @since   3.6.0
	 */
	protected function getInput()
	{
		$jinput = JFactory::getApplication()->input;
		$view   = $jinput->get('view', '');
		$id     = $jinput->get('id', '');

		$value = isset($this->value) ? $this->value : '';

		$getOptions = $this->getOptions();

		$options = array();

		foreach ($getOptions as $opt)
		{
			$options[] = JHTML::_('select.option', $opt->value, $opt->option);
		}

		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->multiple ? ' multiple' : '';
		$attr .= ! empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= count($options)
				? ' data-placeholder="' . JText::_('COM_ICAGENDA_CUSTOMFIELD_GROUPS_SELECT') . '"'
				: ' data-placeholder="' . JText::_('COM_ICAGENDA_CUSTOMFIELD_GROUPS_NONE') . '"';

		$html = '<div id="groups-select">' . JHtml::_('select.genericlist', $options, $this->name, $attr, 'value', 'text', $value) . '</div>';

		// Custom Field Groups Manager
		$html.= '<div id="groups-manager" class="alert alert-info" style="margin-top: 10px; margin-left: -15px;">';

		$html.= '<div style="border-bottom: 1px solid #bce8f1; padding-bottom: 5px;">';
		$html.= '<strong>' . JText::_('COM_ICAGENDA_CUSTOMFIELD_GROUPS_MANAGER_LABEL') . '</strong>';
		$html.= '</div>';

		// Add a new group
		$html.= '<div style="margin-top: 10px;">';
		$html.= JText::_('COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_LABEL') . '<br />';
		$html.= '<div class="input-append" style="width:141px;">';
		$html.= '<input name="a" type="text" id="newgroup" style="width:100%;" /> ';
		$html.= '<button class="add-group btn btn-normal btn-success" type="button">' . JText::_('JACTION_CREATE') . '</button>';
		$html.= '</div>';
		$html.= '<div class="add-group-info" style="margin: 5px 0;"></div>';
		$html.= '</div>';

		// Delete an existing group
		$html.= '<div style="margin-top: 10px;">';
		$html.= JText::_('COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_LABEL') . '<br />';
		$html.= '<div class="input-append" style="width:155px;">';
		$html.= '<select id="deletegroup">';

		// Switch Joomla 3 / 2.5
		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			$html.= '<option value="">' . JText::_('JGLOBAL_SELECT_AN_OPTION') . '</option>';
		}
		else
		{
			$html.= '<option value="">- ' . JText::_('JSELECT') . ' -</option>';
		}

		foreach($getOptions as $opt)
		{
			$html.= '<option value="' . $opt->value . '">' . $opt->option . '</option>';
		}

		$html.= '</select>';
		$html.= '<button class="delete-group btn btn-danger" type="button">' . JText::_('JACTION_DELETE') . '</button>';
		$html.= '</div>';
		$html.= '<div class="delete-group-info" style="margin: 5px 0;"></div>';
		$html.= '</div>';

		$html.= '</div>';

		// ShowOn script for Joomla 2.5, extended to Joomla 3 up to version 3.4.1 to patch Joomla bug(*).
		// * Issue if usage of 'multiple' and 'showon' (not working) xml attributes for same form field in Joomla 3.2.4 > 3.4.1
		if (version_compare(JVERSION, '3.4.1', 'le'))
		{
			JFactory::getDocument()->addScriptDeclaration('
				jQuery(document).ready(function($) {
					var parent_form = $("#jform_parent_form");
					if (parent_form.val() == "1") {
						$("#cf_groups").show();
						$("#jform_groups-lbl").show();
						$("#groups-select").show();
						$("#groups-manager").show();
					} else {
						$("#cf_groups").hide();
						$("#jform_groups-lbl").hide();
						$("#groups-select").hide();
						$("#groups-manager").hide();
					}
					$(parent_form).on("change",function(e){
						if (parent_form.val() == "1") {
							$("#cf_groups").show(300);
							$("#jform_groups-lbl").show(300);
							$("#groups-select").show(300);
							$("#groups-manager").show(300);
						} else {
							$("#cf_groups").hide(300);
							$("#jform_groups-lbl").hide();
							$("#groups-select").hide(300);
							$("#groups-manager").hide(300);
						}
					});
				});
			');
		}
		?>

		<script>
			jQuery(document).ready(function($) {

				var view = '<?php echo $view; ?>',
					id = '<?php echo $id; ?>',
					fieldid = '<?php echo str_replace("jform_", "jform", $this->id); ?>',
					url = 'index.php?option=com_icagenda&task=customfield';

				$(".add-group").on("click",function(e){
					e.preventDefault();

					var newgroup = $('#newgroup').val();

					if (newgroup == "") {
						$(".add-group-info").html('<div class="alert alert-warning alert-small"><?php echo JText::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_WARNING"); ?></div>').show().delay(2000).fadeOut(500);
						$(".delete-group-info").hide();
					} else {
						newGroup(url, newgroup, fieldid)
					}
				});

				$(".delete-group").on("click",function(e){
					e.preventDefault();

					var deletegroup = $('#deletegroup').val();

					if (deletegroup == "") {
						$(".delete-group-info").html('<div class="alert alert-warning alert-small"><?php echo JText::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_WARNING"); ?></div>').show().delay(2000).fadeOut(500);
					} else {
						$.ajax({
							url: url+'.checkGroup',
							data : {
								group : deletegroup,
								id : id,
							},
							success: function( output ) {
								if (output) {
									$(".delete-group-info").html('<div class="alert alert-error alert-small"><p><?php echo JText::sprintf("COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_ERROR", "'+output+'"); ?></p><p><strong><?php echo JText::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_ALERT"); ?></strong></p><div id="force-delete" class="btn btn-success"><?php echo JText::_("JYES"); ?></div> <div id="no-delete" class="btn btn-danger"><?php echo JText::_("JNO"); ?></div></div>').show();
									$("#force-delete").on("click",function(){
										deleteGroup(url, deletegroup, id, fieldid)
									});
									$("#no-delete").on("click",function(){
										$('#deletegroup').val("").trigger("liszt:updated.chosen");
										$(".delete-group-info").fadeOut(500);
									});
								} else {
									deleteGroup(url, deletegroup, id, fieldid)
								}
							},
							error: function() {
								alert("An error occurred");
							}
						});
					}
				});

				function newGroup(url, newgroup, fieldid) {
					$.ajax({
						url: url+'.newGroup',
						data : {
							group : newgroup
						},
						success: function( value ) {
							if (value) {
								$(".add-group-info").html('<div class="alert alert-success alert-small"><?php echo JText::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_SUCCESS"); ?></div>').show().delay(2000).fadeOut(500);
								$('#'+fieldid).prepend('<option value="'+value+'" selected="selected">'+newgroup+'</option>');
								$('#deletegroup').append('<option value="'+value+'" style="font-weight: bold">'+newgroup+'</option>');
								$('#'+fieldid).attr("data-placeholder", "<?php echo JText::_('COM_ICAGENDA_CUSTOMFIELD_GROUPS_SELECT'); ?>");
								$('#'+fieldid).trigger("liszt:updated.chosen");
								$('#deletegroup').trigger("liszt:updated.chosen");
								$('#newgroup').val("");
							} else {
								$(".add-group-info").html('<div class="alert alert-error alert-small"><?php echo JText::sprintf("COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_ERROR", "<strong>'+newgroup+'</strong>"); ?></div>').show().delay(2000).fadeOut(500);
							}

							$('#deletegroup').val("").trigger("liszt:updated.chosen");
							$(".delete-group-info").hide();
						},
						error: function() {
							alert("An error occurred");
						}
					});
				}

				function deleteGroup(url, deletegroup, id, fieldid) {
					$.ajax({
						url: url+'.deleteGroup',
						data : {
							group : deletegroup,
							id : id,
						},
						success: function( value ) {
							if (value) {
								$(".delete-group-info").html('<div class="alert alert-success alert-small"><?php echo JText::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_SUCCESS"); ?></div>').show().delay(2000).fadeOut(500);
								$('#'+fieldid+' option[value="'+value+'"]').remove();
								$('#deletegroup option[value="'+value+'"]').remove();
								$('#'+fieldid).trigger("liszt:updated.chosen");
								$('#deletegroup').trigger("liszt:updated.chosen");
								$('#deletegroup').val("");
							}
						},
						error: function() {
							alert("An error occurred");
						}
					});
				}

			});
		</script>
		<?php
		$document = JFactory::getDocument();

		$document->addStyleDeclaration('
		');

		return $html;
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 * @since   3.6.0
	 */
	protected function getOptions()
	{
		$options = array();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('f.*');
		$query->from($db->qn('#__icagenda_filters') . ' AS f');
		$query->where($db->qn('type') . ' = "customfield"');
		$query->where($db->qn('filter') . ' = "groups"');
		$query->where($db->qn('state') . ' = 1');

		$query->order('f.option ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			throw new Exception($db->getErrorMsg(), 500);
		}

		return $options;
	}
}
