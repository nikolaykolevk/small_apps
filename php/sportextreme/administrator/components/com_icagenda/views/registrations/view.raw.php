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

/**
 * View class for a list of registrations.
 *
 * @since	3.5.0
 */
class icagendaViewRegistrations extends JViewLegacy
{
	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$basename		= $this->get('BaseName');
		$filetype		= $this->get('FileType');
		$mimetype		= $this->get('MimeType');
		$content		= $this->get('Content');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$document = JFactory::getDocument();
		$document->setMimeEncoding($mimetype);

		// Joomla 3
		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			JFactory::getApplication()
				->setHeader(
					'Content-disposition',
					'attachment; filename="' . $basename . '.' . $filetype . '"; creation-date="' . JFactory::getDate()->toRFC822() . '"',
					true
				);
		}
		// Joomla 2.5
		else
		{
			JResponse::setHeader('Content-disposition', 'attachment; filename="' . $basename . '.' . $filetype . '"; creation-date="' . JFactory::getDate()->toRFC822() . '"', true);
		}

		echo $content;
	}
}
