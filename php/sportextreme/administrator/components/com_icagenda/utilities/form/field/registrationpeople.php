<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.13 2017-10-14
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
 * Application :        SITE
 * Registration form :  Nb of people select form field
 */
class icagendaFormFieldRegistrationPeople extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since   3.6.0
	 */
	protected $type = 'registrationpeople';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 * @since   3.6.0
	 */
	protected function getInput()
	{
		$model				= new iCagendaModelRegistration();
		$item				= $model->getItem();
		$tickets			= isset($item->default_tickets) ? $item->default_tickets : $item->tickets;

		$options = array();
		$i = '';

		// Ajax update of number of tickets bookable per registration, depending on date selected.
		JFactory::getDocument()->addScriptDeclaration('
			jQuery(document).ready(function($) {
				var date = $("#jform_date");
				$(date).change(function() {
					var selectedDate = $(date).val().replace(/ /g, "space").replace(/:/g, "_");
					$.ajax({
						type: "POST",
						url: "index.php?option=com_icagenda&task=registration.ticketsBookable",
						data: {
								eventID: "' . $item->id . '",
								regDate: selectedDate,
								typeReg: "' . $item->params->get('typeReg', '1') . '",
								maxReg: "' . $item->params->get('maxReg', '1000000') . '",
								tickets: "' . $item->tickets . '"
							},
						dataType: "text"
					})
					.done(function( data ) {
						var tickets = parseInt(data),
							sel = $( "#' . $this->id . '" );
						sel.empty();
						for (var i = 1; i <= tickets; i++) {
							$selected = (i == "' . $this->value . '") ? " selected" : "";
							sel.append($("<option"+$selected+">").attr("value",i).text(i));
						}
					})
					.fail(function( data ) {
						if ( data.responseCode ) {
							console.log( data.responseCode );
						}
					});
				})
			});
		');

		for ($i = 1; $i <= $tickets; $i++)
		{
			$options[] = JHtml::_('select.option', $i, $i);
		}

		if ($tickets > 1)
		{
			return JHtml::_('select.genericlist', $options, $this->name, 'class="select-large"', 'value', 'text', $this->value, $this->id);
		}
		else
		{
			return '<input type="hidden" name="' . $this->name . '" value="1" />';
		}
	}
}
