<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-26
 *
 * @package     iCagenda.Admin
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.3.3
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

/**
 * Supports a modal article picker.
 */
class JFormFieldModal_iclink_article extends JFormField
{
	/**
	 * The form field type.
	 */
	protected $type = 'modal_iclink_article';

	/**
	 * Method to get the field input markup.
	 */
	protected function getInput()
	{
		jimport('joomla.application.component.helper');

		$icagendaParams = JComponentHelper::getParams('com_icagenda');

		$Explode  = explode('_', $this->name);
		$TypeName = $Explode[0] . ']';

		$replace  = array("jform", "params", "[", "]");
		$name     = str_replace($replace, "", $TypeName);

		$allowEdit  = ((string) $this->element['edit'] == 'true') ? true : false;
		$allowClear = ((string) $this->element['clear'] != 'false') ? true : false;

		// Load language
		JFactory::getLanguage()->load('com_content', JPATH_ADMINISTRATOR);

		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal');

		// Build the script.
		$script = array();

		// Select button script
		$script[] = '	function jSelectArticle_' . $this->id . '(id, title, catid, object) {';
		$script[] = '		document.getElementById("' . $this->id . '_id").value = id;';
		$script[] = '		document.getElementById("' . $this->id . '_name").value = title;';

		if ($allowEdit)
		{
			$script[] = '		jQuery("#' . $this->id . '_edit").removeClass("hidden");';
		}

		if ($allowClear)
		{
			$script[] = '		jQuery("#' . $this->id . '_clear").removeClass("hidden");';
		}

		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Clear button script
		static $scriptClear;

		if ($allowClear && !$scriptClear)
		{
			$scriptClear = true;

			$script[] = '	function jClearArticle(id) {';
			$script[] = '		document.getElementById(id + "_id").value = "";';
			$script[] = '		document.getElementById(id + "_name").value = "' . htmlspecialchars(JText::_('COM_CONTENT_SELECT_AN_ARTICLE', true), ENT_COMPAT, 'UTF-8') . '";';
			$script[] = '		jQuery("#"+id + "_clear").addClass("hidden");';
			$script[] = '		if (document.getElementById(id + "_edit")) {';
			$script[] = '			jQuery("#"+id + "_edit").addClass("hidden");';
			$script[] = '		}';
			$script[] = '		return false;';
			$script[] = '	}';
		}

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html = array();
		$link = 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=jSelectArticle_' . $this->id;

		if (isset($this->element['language']))
		{
			$link .= '&amp;forcedLanguage=' . $this->element['language'];
		}

		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT title' .
			' FROM #__content' .
			' WHERE id = ' . (int) $this->value
		);

		try
		{
			$title = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			throw new Exception($e->getMessage(), 500, $e);
		}

		if (empty($title))
		{
			$title = JText::_('COM_CONTENT_SELECT_AN_ARTICLE');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The active article id field.
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		// The current article display field.
		$html[] = '<div id="' . $name . '_article"><fieldset class="span9 iCleft"><div>&nbsp;</div><span class="input-append">';
		$html[] = '<input type="text" class="input-medium" style="margin:0px" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />';

		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			$html[] = '<a class="modal btn hasTooltip" title="' . JText::_('COM_CONTENT_CHANGE_ARTICLE') . '"  href="' . $link . '&amp;' . JSession::getFormToken() . '=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">' . JText::_('JSELECT') . '</a>';
		}
		else
		{
			$html[] = '<a class="modal btn hasTooltip" title="' . JHtml::tooltipText('COM_CONTENT_CHANGE_ARTICLE') . '"  href="' . $link . '&amp;' . JSession::getFormToken() . '=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> ' . JText::_('JSELECT') . '</a>';
		}

		// Edit article button
		if ($allowEdit)
		{
			if (version_compare(JVERSION, '3.0', 'lt'))
			{
				$html[] = '<a class="btn hasTooltip' . ($value ? '' : ' hidden') . '" href="index.php?option=com_content&view=article&layout=edit&id=' . $value . '" target="_blank" title="' . JText::_('COM_CONTENT_EDIT_ARTICLE') . '" alt="' . JText::_('COM_CONTENT_EDIT_ARTICLE') . '" >' . JText::_('JACTION_EDIT') . '</a>';
			}
			else
			{
				$html[] = '<a class="btn hasTooltip' . ($value ? '' : ' hidden') . '" href="index.php?option=com_content&layout=modal&tmpl=component&task=article.edit&id=' . $value . '" target="_blank" title="' . JHtml::tooltipText('COM_CONTENT_EDIT_ARTICLE') . '" ><span class="icon-edit"></span> ' . JText::_('JACTION_EDIT') . '</a>';
			}
		}

		// Clear article button
		if ($allowClear)
		{
			if (version_compare(JVERSION, '3.0', 'lt'))
			{
				$html[] = '<a id="' . $this->id . '_clear" class="btn' . ($value ? '' : ' hidden') . '" onclick="return jClearArticle(\'' . $this->id . '\')">' . JText::_('JCLEAR') . '</a>';
			}
			else
			{
				$html[] = '<button id="' . $this->id . '_clear" class="btn' . ($value ? '' : ' hidden') . '" onclick="return jClearArticle(\'' . $this->id . '\')"><span class="icon-remove"></span> ' . JText::_('JCLEAR') . '</button>';
			}
		}

		$html[] = '</span>';

		// class='required' for client side validation
		$class = '';

		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}


		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" /></fieldset></div>';

		return implode("\n", $html);
	}
}
