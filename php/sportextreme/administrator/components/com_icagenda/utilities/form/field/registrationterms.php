<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.3 2016-09-05
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
 * Registration form: Terms and Conditions
 */
class icagendaFormFieldRegistrationTerms extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since   3.6.0
	 */
	protected $type = 'Registrationterms';

	protected $terms = 0;

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 * @since   3.6.0
	 */
	protected function getLabel()
	{
		$app		= JFactory::getApplication();

		$model		= new iCagendaModelRegistration();
		$item		= $model->getItem();

		// Get the site name
		$sitename	= $app->getCfg('sitename');

		// Terms options
		$terms_Type		= $item->params->get('terms_Type', '');
		$termsArticle	= $item->params->get('termsArticle', '');
		$termsContent	= $item->params->get('termsContent', '');

		$DEFAULT_STRING	= JText::_('COM_ICAGENDA_REGISTRATION_TERMS');
		$default		= str_replace('[SITENAME]', $sitename, $DEFAULT_STRING);
		$article		= 'index.php?option=com_content&view=article&id=' . $termsArticle . '&tmpl=component';
		$custom			= $termsContent;

		$html = '';
		$html.= '<div class="controls ic-registration-terms-title">' . JText::_('COM_ICAGENDA_TERMS_AND_CONDITIONS') . '</div>';
		$html.= '<div class="ic-tos-text">';

		if ($terms_Type == 1)
		{
			$html.= '<iframe src="' . htmlentities($article) . '" width="98%" height="150"></iframe>';
		}
		elseif ($terms_Type == 2)
		{
			$html.= $custom;
		}
		else
		{
			$html.= $default;
		}

		return '</div>' . $html;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 * @since   3.6.0
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$class		= ! empty($this->class) ? ' class="' . $this->class . '"' : '';
		$disabled	= $this->disabled ? ' disabled' : '';
		$value		= ! empty($this->default) ? $this->default : '0';
		$required	= $this->required ? ' required aria-required="true"' : '';
		$autofocus	= $this->autofocus ? ' autofocus' : '';
		$checked	= $this->checked || ! empty($this->value) ? ' checked' : '';

		// Initialize JavaScript field attributes.
		$onclick	= ! empty($this->onclick) ? ' onclick="' . $this->onclick . '"' : '';
		$onchange	= ! empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		$html = '';

		// Terms of Service
		$checkbox = '<input type="checkbox" name="' . $this->name . '" id="' . $this->id . '" value="'
					. htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"' . $class . $checked . $disabled . $onclick . $onchange
					. $required . $autofocus . ' />';

		$html.= '</div><hr>';
		$html.= '<div class="controls checkbox">';
		$html.= '<label id="' . $this->id . '-lbl" for="' . $this->name . '" class="ic-terms-agree">'
				. $checkbox . JText::_('COM_ICAGENDA_TERMS_AND_CONDITIONS_AGREE') . ' *</label>';
		$html.= '</div>';
		$html.= '<hr><div>';

		return $html;
	}
}
