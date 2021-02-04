<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.0 2018-05-23
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.7.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('checkboxes');

/**
 * Form Field class for the Joomla Platform.
 * Displays options as a list of check boxes.
 * Multiselect may be forced to be true.
 *
 * @see    JFormFieldCheckbox
 * @since   3.7.0
 */
class icagendaFormFieldTerms extends JFormFieldCheckboxes
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   3.7.0
	 */
	protected $type = 'terms';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 * @since   3.7.0
	 */
	protected function getLabel()
	{
		$app = JFactory::getApplication();

		$model = new iCagendaModelRegistration();
		$item  = $model->getItem();

		// Get the site name
		$sitename = $app->getCfg('sitename');

		// Terms options
		$terms_type   = $item->params->get('terms_type', '');
		$termsArticle = $item->params->get('termsArticle', '');
		$termsContent = $item->params->get('termsContent', '');

		$DEFAULT_STRING = JText::_('COM_ICAGENDA_REGISTRATION_TERMS');

		$default = str_replace('[SITENAME]', $sitename, $DEFAULT_STRING);
		$article = 'index.php?option=com_content&view=article&id=' . $termsArticle . '&tmpl=component';
		$custom  = $termsContent;

		$html = '';

		$legend  = '<legend class="ic-terms-legend" id="' . $this->id . '-lbl" for="' . $this->name . '">';
		$legend .= '	' . JText::_('COM_ICAGENDA_REGISTRATION_CONSENT_TERMS_LABEL');
		$legend .= '</legend>';

		$html .= $legend;

		$html .= '<div class="ic-terms-text">';

		if ($terms_type == 1)
		{
			$html .= '<iframe src="' . htmlentities($article) . '" width="100%" height="200"></iframe>';
		}
		elseif ($terms_type == 2)
		{
			$html .= $custom;
		}
		elseif ($terms_type == 3)
		{
			$html .= $default;
		}
		else
		{
			return '</div>' . $legend . '<div>';
		}

		return '</div>' . $html;
	}

	/**
	 * Method to get the field input markup for check boxes.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.7.0
	 */
	protected function getInput()
	{
		// True if the field has 'value' set. In other words, it has been stored, don't use the default values.
		$hasValue = (isset($this->value) && ! empty($this->value));

		// Initialize some field attributes.
		$class          = ! empty($this->class) ? ' class="checkboxes ' . trim($this->class) . '"' : ' class="checkboxes"';
		$checkedOptions = explode(',', (string) $this->checkedOptions);
		$required       = $this->required ? ' required aria-required="true"' : '';
		$autofocus      = $this->autofocus ? ' autofocus' : '';

		// Including fallback code for HTML5 non supported browsers.
		JHtml::_('jquery.framework');
		JHtml::_('script', 'system/html5fallback.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

		// Get the field options.
		$options = $this->getOptions();

		/**
		 * The format of the input tag to be filled in using sprintf.
		 *     %1 - id
		 *     %2 - name
		 *     %3 - value
		 *     %4 = any other attributes
		 */
		$format = '<input type="checkbox" id="%1$s" name="%2$s" value="%3$s" %4$s />';

		// Start the checkbox field output.
		$html = '<fieldset id="' . $this->id . '"' . $class . $required . $autofocus . '>';


		$app    = JFactory::getApplication();
		$params = $app->getParams();

		$terms_type   = $params->get('terms_type', '');
		$termsArticle = $params->get('termsArticle', '');
		$termsContent = $params->get('termsContent', '');


		if ($params->get('terms_mode', '') == 1) // IN DEV.
		{
			if (in_array($terms_type, array(1,2,3)))
			{
				// Terms Modal link
				$terms_link = '<a data-toggle="modal"'
					. ' role="button"'
					. ' href="#ModalTerms' . $this->id . '">'
					. $this->title
					. '</a>';
			}
			else
			{
				$terms_link = JText::sprintf('COM_ICAGENDA_REGISTRATION_CONSENT_TERMS_OF_THIS_WEBSITE', $this->title);
			}

			if ($terms_type == 1)
			{
				// Terms Modal: Article
				$html .= JHtml::_(
					'bootstrap.renderModal',
					'ModalTerms' . $this->id,
					array(
						'title'       => $this->title,
						'url'         => 'index.php?option=com_content&view=article&id=' . $termsArticle . '&tmpl=component',
						'height'      => '400px',
						'width'       => '800px',
						'bodyHeight'  => '70',
						'modalWidth'  => '80',
						'footer'      => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">' . JText::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
					)
				);
			}
			elseif ($terms_type == 2)
			{
				// Terms Modal: Custom Content
				$termsContentModal = array(
					'selector' => 'ModalTerms' . $this->id,
					'params'   => array(
						'title'  => $this->title,
						'footer' => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">' . JText::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
					),
					'body'     => $termsContent,
				);

				$html .= JLayoutHelper::render('joomla.modal.main', $termsContentModal);
			}
			elseif ($terms_type == 3)
			{
				// Terms Modal: Custom Content
				$termsContentModal = array(
					'selector' => 'ModalTerms' . $this->id,
					'params'   => array(
						'title'  => $this->title,
						'footer' => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">' . JText::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
					),
					'body'     => JText::_('COM_ICAGENDA_REGISTRATION_TERMS'),
				);

				$html .= JLayoutHelper::render('joomla.modal.main', $termsContentModal);
			}
		}
		else
		{
			$terms_link = JText::sprintf('COM_ICAGENDA_REGISTRATION_CONSENT_TERMS_OF_THIS_WEBSITE', $this->title);
		}


		foreach ($options as $i => $option)
		{
			// Initialize some option attributes.
			$checked = in_array((string) $option->value, $checkedOptions, true) ? 'checked' : '';

			// In case there is no stored value, use the option's default state.
			$checked        = ( ! $hasValue && $option->checked) ? 'checked' : $checked;
			$optionClass    = ! empty($option->class) ? 'class="' . $option->class . '"' : '';
			$optionDisabled = ! empty($option->disable) || $this->disabled ? 'disabled' : '';

			// Initialize some JavaScript option attributes.
			$onclick  = ! empty($option->onclick) ? 'onclick="' . $option->onclick . '"' : '';
			$onchange = ! empty($option->onchange) ? 'onchange="' . $option->onchange . '"' : '';

			$oid        = $this->id . $i;
			$value      = htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8');
			$attributes = array_filter(array($checked, $optionClass, $optionDisabled, $onchange, $onclick));

//			$html .= '<label for="' . $oid . '" class="checkbox">';
			$html .= '<label class="checkbox ic-terms-consent">';
			$html .= sprintf($format, $oid, $this->name, $value, implode(' ', $attributes));

			$html .= ' ' . JText::sprintf($option->text, $terms_link) . '</label>';
		}

		// End the checkbox field output.
		$html .= '</fieldset>';

		return $html;
	}
}
