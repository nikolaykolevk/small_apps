<?php
/**
 *------------------------------------------------------------------------------
 *  iCagenda v3 by Jooml!C - Events Management Extension for Joomla! 2.5 / 3.x
 *------------------------------------------------------------------------------
 * @package     com_icagenda
 * @copyright   Copyright (c)2012-2019 Cyril Rezé, Jooml!C - All rights reserved
 *
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      doorknob
 * @link        http://www.joomlic.com
 *
 * @version     3.4.0 2014-12-05
 * @since       3.4.0
 *------------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

jimport('joomla.application.component.modeladmin');

/**
 * iCagenda model.
 */
class iCagendaModelfeature extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	3.4.0
	 */
	protected $text_prefix = 'COM_ICAGENDA';

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	3.4.0
	 */
	public function getTable($type = 'Feature', $prefix = 'iCagendaTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	3.4.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_icagenda.feature', 'feature', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	3.4.0
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_icagenda.edit.feature.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	3.4.0
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			//Do any procesing on fields here if needed
		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	3.4.0
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (@$table->ordering === '')
			{
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__icagenda_feature');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}
		}
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.4.0
	 */
	public function save($data)
	{
		$date = JFactory::getDate();

		if (empty($data['created']))
		{
			$data['created'] = !empty($data['created']) ? $data['created'] : $date->toSql();
		}

		// Generates Alias if empty
		// Alias is not generated if non-latin characters, so we fix it by using created date, or title if unicode is activated, as alias
		if ($data['alias'] == null || empty($data['alias']))
		{
			$data['alias'] = JFilterOutput::stringURLSafe($data['title']);

			if ($data['alias'] == null || empty($data['alias']))
			{
				if (JFactory::getConfig()->get('unicodeslugs') == 1)
				{
					$data['alias'] = JFilterOutput::stringURLUnicodeSlug($data['title']);
				}
				else
				{
					$data['alias'] = JFilterOutput::stringURLSafe($data['created']);
				}
			}
		}

		$return = parent::save($data);

		return $return;
	}
}
