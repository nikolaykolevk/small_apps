<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-27
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

// Joomla 2.5 import
jimport('joomla.application.component.modelform');

/**
 * Download Model.
 */
class icagendaModelDownload extends JModelForm
{
	protected $_context = 'com_icagenda.registrations';

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   3.5.0
	 */
	protected function populateState()
	{
		$input = JFactory::getApplication()->input;

		$basename = $input->cookie->getString(JApplicationHelper::getHash($this->_context . '.basename'), '__SITE__');
		$this->setState('basename', $basename);

		$compressed = $input->cookie->getInt(JApplicationHelper::getHash($this->_context . '.compressed'), 1);
		$this->setState('compressed', $compressed);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   3.5.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_icagenda.download', 'download', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   3.5.0
	 */
	protected function loadFormData()
	{
		$data = array(
			'basename'   => $this->getState('basename'),
			'compressed' => $this->getState('compressed')
		);

		// Joomla 3
		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			$this->preprocessData('com_icagenda.download', $data);
		}

		return $data;
	}
}
