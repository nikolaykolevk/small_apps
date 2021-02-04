<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.8 2019-01-10
 *
 * @package     iCagenda.Admin
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * Custom Field Table class
 */
class iCagendaTablecustomfield extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database connector object
	 *
	 * @since   3.4.0
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__icagenda_customfields', 'id', $db);

		if (version_compare(JVERSION, '3.7', 'ge'))
		{
			// Set the alias for 'published' since the column is called 'state'
			$this->setColumnAlias('published', 'state');
		}
	}

	/**
	 * Overloaded bind function.
	 *
	 * @param   array        Named array
	 * @return  null|string  null is operation was satisfactory, otherwise returns an error
	 * @see     JTable:bind
	 * @since   3.4.0
	 */
	public function bind($array, $ignore = '')
	{
		// Set Creator infos
		$user = JFactory::getUser();
		$userId	= $user->get('id');

		if ($array['created_by'] == '0')
		{
			$array['created_by'] = (int)$userId;
		}

		// Set Params
		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		// Force system slugs for core fields and protect from usage in other field types
		$coreFields = array('core_name', 'core_email', 'core_phone', 'core_date', 'core_people');

		if (in_array($array['type'], $coreFields))
		{
			$array['slug'] = $array['type'];
		}
		elseif (in_array($array['slug'], $coreFields)
			&& ! in_array($array['type'], $coreFields))
		{
			$array['slug'] = $array['slug'] . '_copy';
		}

		// Set Placeholder to Options, and unset placeholder (if input type with hint)
		$hintFields = array('text', 'url', 'tel', 'email', 'core_name', 'core_email', 'core_phone');

		if (isset($array['placeholder'])
			&& in_array($array['type'], $hintFields))
		{
			$array['options'] = $array['placeholder'];
			unset($array['placeholder']);
		}

		// Set Class to Options, and unset spacer_class (if input type is spacer)
		$spacerFields = array('spacer_label', 'spacer_description');

		if (isset($array['spacer_class'])
			&& in_array($array['type'], $spacerFields))
		{
			$array['options'] = $array['spacer_class'];
			unset($array['spacer_class']);
		}

		// Set Groups
		if ( ! isset($array['groups']))
		{
			$array['groups'] = '';
		}
		elseif (is_array($array['groups']))
		{
			$array['groups'] = implode(',', $array['groups']);
		}

		return parent::bind($array, $ignore);
	}

	/**
	* Overloaded check function
	* @since   3.4.0
	*/
	public function check()
	{
		// Import Joomla 2.5
		jimport( 'joomla.filter.output' );

		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering')
			&& $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}

		// URL alias
		if (empty($this->alias))
		{
			$this->alias = $this->title;
		}

		$this->alias = JFilterOutput::stringURLSafe($this->alias);

		// Alias is not generated if non-latin characters, so we fix it by using created date, or title if unicode is activated, as alias
		if ($this->alias == null || empty($this->alias))
		{
			if (JFactory::getConfig()->get('unicodeslugs') == 1)
			{
				$this->alias = JFilterOutput::stringURLUnicodeSlug($this->title);
			}
			else
			{
				$this->alias = JFilterOutput::stringURLSafe($this->created);
			}
		}

		// Slug auto-create
		$slug_empty = empty($this->slug) ? true : false;

		if ($slug_empty)
		{
			$this->slug = $this->title;
		}

		$this->slug = iCFilterOutput::stringToSlug($this->slug);

		// Slug is not generated if non-latin characters, so we fix it by using created date as a slug
		if ($this->slug == null)
		{
			$this->slug = iCFilterOutput::stringToSlug($this->created);
		}

		// Check if Slug already exists
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('slug')
			->from($db->qn('#__icagenda_customfields'))
			->where($db->qn('slug') . ' = ' . $db->q($this->slug));

		if (!empty($this->id))
		{
			$query->where('id <> ' . (int) $this->id);
		}

		$db->setQuery($query);
		$slug_exists = $db->loadResult();

		if ($slug_exists)
		{
			$error_slug = $slug_empty
						? JText::sprintf('COM_ICAGENDA_CUSTOMFIELD_DATABASE_ERROR_AUTO_SLUG',
										'<strong>' . $this->title . '</strong>', '<strong>' . $this->slug . '</strong>')
						: '<strong>' . JText::_('COM_ICAGENDA_CUSTOMFIELD_DATABASE_ERROR_UNIQUE_SLUG') . '</strong>';

			$this->setError($error_slug . '<br /><br /><span class="iCicon-info-circle"></span> <i>'
							. JTEXT::_('COM_ICAGENDA_CUSTOMFIELD_SLUG_DESC').'</i>');

			return false;
		}

		return parent::check();
	}


	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed       An optional array of primary key values to update.  If not
	 *                      set the instance property value is used.
	 * @param   integer     The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer     The user id of the user performing the operation.
	 * @return  boolean     True on success.
	 * @since   3.4.0
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else
			{
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k.'='.implode(' OR '.$k.'=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time'))
		{
			$checkin = '';
		}
		else
		{
			$checkin = ' AND (checked_out = 0 OR checked_out = '.(int) $userId.')';
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE '.$this->_tbl.'' .
			' SET state = '.(int) $state .
			' WHERE ('.$where.')' .
			$checkin
		);
		$this->_db->query();

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin the rows.
			foreach($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->state = $state;
		}

		$this->setError('');
		return true;
	}
}
