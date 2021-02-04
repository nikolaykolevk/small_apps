<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.5 2018-10-30
 *
 * @package     iCagenda
 * @link        https://icagenda.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2019 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

// System Installation/Update, component iCagenda https://icagenda.joomlic.com
class com_icagendaInstallerScript
{
	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * preflight runs before anything else and while the extracted files are in the uploaded temp folder.
	 * If preflight returns false, Joomla will abort the update and undo everything already done.
	 */
	private $ictype = 'core';

	/** @var array The list of extra modules and plugins to install */
	private $installation_queue = array(
		// modules => { (folder) => { (module) => { (position), (published) } }* }*
		'modules' => array(
			'admin' => array(
			),
			'site' => array(
				'mod_iccalendar'	=> array('', 0),
			)
		),
		// plugins => { (folder) => { (element) => { (name), (published) } }* }*
		'plugins' => array(
			'actionlog' => array(
				'icagenda'       => array('Action Log - iCagenda', 1),
			),
			'installer' => array(
				'icagenda'       => array('Installer - iCagenda', 1),
			),
			'privacy' => array(
				'icagenda'       => array('Privacy - iCagenda', 1),
			),
			'quickicon' => array(
				'icagendaupdate' => array('Quick Icon - iCagenda :: Update Notification', 1),
			),
			'system' => array(
				'ic_autologin'   => array('System - iCagenda :: Autologin', 1),
				'ic_library'     => array('System - iC Library', 1),
			),
			'search' => array(
				'icagenda'       => array('Search - iCagenda', 1),
			)
		)
	);

	/** @var array Obsolete files and folders to remove from the iCagenda oldest releases*/
	private $icagendaRemoveFiles = array(
		'files'	=> array(
			'components/com_icagenda/views/list/tmpl/search.php',
			'components/com_icagenda/views/list/tmpl/search.xml',
			'modules/mod_iccalendar/js/bottomcenter_function.js',
			'modules/mod_iccalendar/js/center_function.js',
			'modules/mod_iccalendar/js/left_function.js',
			'modules/mod_iccalendar/js/right_function.js',
			'modules/mod_iccalendar/js/topcenter_function.js',
			'components/com_icagenda/helpers/icmodcalendar.php',
			'administrator/components/com_icagenda/models/fields/eventtitle.php',
			'components/com_icagenda/themes/packs/ic_rounded/ic_rounded_alldates.php',
			'media/com_icagenda/icicons/lte-ie7.js',
			'media/com_icagenda/icicons/fonts/iCicons.dev.svg',
			'media/com_icagenda/icicons/selection.json',
			'modules/mod_iccalendar/js/function.js',
			'modules/mod_iccalendar/js/function_312.js',
			'modules/mod_iccalendar/js/function_316.js',
			'modules/mod_iccalendar/js/ictip.js',
			'components/com_icagenda/themes/packs/default/default_list.php',
			'components/com_icagenda/themes/packs/ic_rounded/ic_rounded_list.php',
			'media/com_icagenda/images/iconicagenda48 - copie.png',
			'administrator/components/com_icagenda/views/event/tmpl/ajaxfile.php',
			'administrator/components/com_icagenda/views/registration/tmpl/default.php',
			'administrator/components/com_icagenda/models/fields/modal/time.php',
			'administrator/components/com_icagenda/UPDATELOGS.php',
			'administrator/components/com_icagenda/sql/install.mysql.utf8.sql',
			'administrator/components/com_icagenda/sql/uninstall.mysql.utf8.sql',
			'administrator/components/com_icagenda/models/fields/custom_field.php',
			'administrator/components/com_icagenda/tables/mail.php',
			'administrator/components/com_icagenda/models/fields/modal/mailinglist.php',
			'components/com_icagenda/add/elements/icsetvar.php',
			'components/com_icagenda/helpers/iCalcreator.class.php',
			'components/com_icagenda/helpers/ichelper.php',
			'components/com_icagenda/helpers/icmodel.php',
			'components/com_icagenda/helpers/media_css.class.php',
			'components/com_icagenda/views/list/tmpl/actions.php',
			'components/com_icagenda/views/list/tmpl/default_vcal.php',
			'components/com_icagenda/views/list/tmpl/event.php',
			'components/com_icagenda/views/list/tmpl/registration.php',
			'media/com_icagenda/images/video_poster_icagenda.jpg',
			'media/com_icagenda/images/youtube_iCagenda.png',
			'media/com_icagenda/js/jsevt.js',
		),
		'folders' => array(
			'modules/mod_iccalendar/tmpl',
			'components/com_icagenda/css',
			'modules/mod_ic_event_list/language',
			'modules/mod_iccalendar/language',
			'administrator/components/com_icagenda/add/js',
			'components/com_icagenda/add/js',
			'media/com_icagenda/scripts',
			'components/com_icagenda/js',
			'administrator/components/com_icagenda/add/css',
			'components/com_icagenda/add/css',
			'administrator/components/com_icagenda/add/image',
			'components/com_icagenda/add/image',
			'administrator/components/com_icagenda/globalization',
			'administrator/components/com_icagenda/add',
			'components/com_icagenda/views/events',
			'libraries/ic_library/form/rules',
		)
	);


	private function _removeObsoleteFilesAndFolders($icagendaRemoveFiles)
	{
		// Remove files
		jimport('joomla.filesystem.file');

		if ( ! empty($icagendaRemoveFiles['files']))
		{
			foreach ($icagendaRemoveFiles['files'] as $file)
			{
				$f = JPATH_ROOT . '/' . $file;

				if ( ! JFile::exists($f)) continue;

				JFile::delete($f);
			}
		}

		// Remove folders
		jimport('joomla.filesystem.file');

		if ( ! empty($icagendaRemoveFiles['folders']))
		{
			foreach ($icagendaRemoveFiles['folders'] as $folder)
			{
				$f = JPATH_ROOT . '/' . $folder;

				if ( ! JFolder::exists($f)) continue;

				JFolder::delete($f);
			}
		}
	}


	function preflight ($type, $parent)
	{
		$app = JFactory::getApplication();

		$jversion = new JVersion();

		// Installing component manifest file version
		$this->release = $parent->get("manifest")->version;

		// Manifest file minimum Joomla version
		$this->minimum_joomla_release = $parent->get("manifest")->attributes()->version;

		// Load translations
		$language = JFactory::getLanguage();
		$language->load('com_icagenda.sys', JPATH_ADMINISTRATOR, 'en-GB', true);
		$language->load('com_icagenda.sys', JPATH_ADMINISTRATOR, null, true);

//		if (version_compare(phpversion(), '5.3.0', '<'))
//		{
//			$app->enqueueMessage('<span class="icon-warning"></span><b> '.JText::sprintf('COM_ICAGENDA_YOUR_PHP_VERSION_IS', phpversion()).'</b><br />'.JText::_('COM_ICAGENDA_PHP_VERSION_JOOMLA_RECOMMENDED').' ( '.JText::_('IC_READMORE').': <a href="http://www.joomla.org/technical-requirements.html" target="_blanck">http://www.joomla.org/technical-requirements.html</a> )<br />'.JText::_('COM_ICAGENDA_PHP_VERSION_ICAGENDA_RECOMMENDATION').'', 'warning');
//		}

		echo '<hr />';

		echo '<p>';
		echo '<img src="../media/com_icagenda/images/iconicagenda48.png" />';

		if ($this->ictype != 'core') echo '<span style="font-weight: bold; font-size: 20px; color: #555;"> ' . strtoupper($this->ictype) . '</span>';

		echo '<br />';
		echo '<span style="font-size: 16px; color: #555; margin-left: 70px;">' . JText::_('COM_ICAGENDA_XML_DESCRIPTION') . '</span>';
		echo '</p>';

		echo '<hr />';

		echo '<p>';
		echo '<div style="float: left; margin-right: 30px;">';
		echo '<img src="../media/com_icagenda/images/logo_icagenda.png" />';
		echo '</div>';

		echo '<span style="text-transform:uppercase; letter-spacing: 3px; font-size: 14px">'
			. JText::_('COM_ICAGENDA_WELCOME')
			. '</span>'
			. '<br />';

		if ($type == 'install')
		{
			echo '<span style="text-transform:uppercase; font-size: 14px; font-weight: bold;">'
				. JText::sprintf('COM_ICAGENDA_WELCOME_1', '<strong>iCagenda</strong>') . ' ' . $this->release
				. ' ' . JText::_('COM_ICAGENDA_WELCOME_2') . '</span>'
				. '<br /><br />';
		}

		// Abort if the current Joomla release is older
		if (version_compare($jversion->getShortVersion(), $this->minimum_joomla_release, 'lt'))
		{
			$app->enqueueMessage(' ' . JText::_('COM_ICAGENDA_INSTALL_ERROR_JOOMLA_VERSION') . ' ' . $this->minimum_joomla_release, 'warning');

			return false;
		}

		// Abort if Joomla 3 release is prior to 3.2.3
		if (version_compare(JVERSION, '3.0.0', 'ge')
			&& version_compare(JVERSION, '3.2.3', 'lt'))
		{
			$app->enqueueMessage(JText::_('COM_ICAGENDA_INSTALL_ERROR_JOOMLA_VERSION') . ' ' . '3.2.3', 'error');

			return false;
		}

		// Extension Update
		if ($type == 'update')
		{
			echo '<span style="font-size: 14px; font-weight: bold;">'
				. JText::_('COM_ICAGENDA') . ' : '
				. JText::_('COM_ICAGENDA_UPDATE') . ' ' . $this->release . ' !'
				. '</span>'
				. '<br /><br />';
		}

		echo '<div class="small">';
		echo JText::_('COM_ICAGENDA_FEATURES_BACKEND') . '<br />';
		echo JText::_('COM_ICAGENDA_FEATURES_FRONTEND');
		echo '</div>';

		echo '</p>';

		echo '<div style="clear: both"></div>';

		echo '<hr />';

		$translationPacks =  array(
			'af'    => 'Afrikaans (South Africa)',
			'ar'    => 'Arabic (Unitag)',
			'eu_es' => 'Basque (Spain)',
			'bg'    => 'Bulgarian (Bulgaria)',
			'ca'    => 'Catalan (Spain)',
			'zh'    => 'Chinese (China)',
			'tw'    => 'Chinese (Taiwan)',
			'hr'    => 'Croatian (Croatia)',
			'cz'    => 'Czech (Czech Republic)',
			'dk'    => 'Danish (Denmark)',
			'nl'    => 'Dutch (Netherlands)',
			'en'    => 'English (United Kingdom)',
			'us'    => 'English (United States)',
			'eo'    => 'Esperanto',
			'et'    => 'Estonian (Estonia)',
			'fi'    => 'Finnish (Finland)',
			'fr'    => 'French (France)',
			'gl'    => 'Galician (Spain)',
			'de'    => 'German (Germany)',
			'el'    => 'Greek (Greece)',
			'hu'    => 'Hungarian (Hungary)',
			'it'    => 'Italian (Italy)',
			'ja'    => 'Japanese (Japan)',
			'lv'    => 'Latvian (Latvia)',
			'lt'    => 'Lithuanian (Lithuania)',
			'none'  => 'Luxembourgish (Luxembourg)',
			'mk'    => 'Macedonian (Macedonia)',
			'no'    => 'Norwegian Bokmål (Norway)',
			'fa_ir' => 'Persian (Iran)',
			'pl'    => 'Polish (Poland)',
			'pt_br' => 'Portuguese (Brazil)',
			'pt'    => 'Portuguese (Portugal)',
			'ro'    => 'Romanian (Romania)',
			'ru'    => 'Russian (Russia)',
			'sr'    => 'Serbian (latin)',
			'sk'    => 'Slovak (Slovakia)',
			'sl'    => 'Slovenian (Slovenia)',
			'es'    => 'Spanish (Spain)',
			'sv'    => 'Swedish (Sweden)',
			'th'    => 'Thai (Thailand)',
			'tr'    => 'Turkish (Turkey)',
			'uk'    => 'Ukrainian (Ukraine)',
		);

		echo '<div style="font-size: 18px; font-weight: bold; color: #555; margin-bottom: 10px;">'
			. JText::sprintf('COM_ICAGENDA_FEATURES_TRANSLATION_PACKS', count($translationPacks))
			. '</div>';

		echo '<p>';

		foreach ($translationPacks as $code => $lang)
		{
			$flagIcon = ($code == 'none') ? 'icon-16-language.png' : $code . '.gif';

			echo '<span rel="tooltip" data-placement="top" class="editlinktip hasTip" style="margin: 2px;" title="' . $lang . '">'
				. '<img src="../media/mod_languages/images/' . $flagIcon . '" border="0" alt="Tooltip"/>'
				. '</span>';
		}

		echo '<br /><br />';
		echo '<a href="http://icagenda.joomlic.com/resources/translations" target="_blank" class="btn">'
			. JText::_('COM_ICAGENDA_TRANSLATION_PACKS_DONWLOAD')
			. '</a>';

		echo '</p>';

		echo '<hr />';

		echo '<div style="font-size: 18px; font-weight: bold; color: #555; margin-bottom: 10px;">'
			. JText::_('COM_ICAGENDA_INSTALL_LABEL')
			. '</div>';
	}

	/*
	 * $parent is the class calling this method.
	 * install runs after the database scripts are executed.
	 * If the extension is new, the install method is run.
	 * If install returns false, Joomla will abort the install and undo everything already done.
	 */
	function install( $parent )
	{
		// Load language
		JFactory::getLanguage()->load('com_installer', JPATH_ADMINISTRATOR);

		$module_type    = JText::_( 'COM_INSTALLER_TYPE_TYPE_MODULE' );
		$plugin_type    = JText::_( 'COM_INSTALLER_TYPE_TYPE_PLUGIN' );
		$library_type   = JText::_( 'COM_INSTALLER_TYPE_TYPE_LIBRARY' );

		// Addons install (library, modules, plugins)
		$db             = JFactory::getDbo();
		$manifest       = $parent->get("manifest");
		$parent         = $parent->getParent();
		$source         = $parent->getPath("source");
		$installer      = new JInstaller();

//		$installLibraries = array();
//		$installModules = array();
//		$installPlugins = array();

		echo '<div><i>' . JText::_('JTOOLBAR_INSTALL') . '</i></div>';

		// Proceed Libraries Install
		if (is_object($manifest->libraries) && isset($manifest->libraries->library))
		{
			foreach ($manifest->libraries->library as $library)
			{
				$attributes         = $library->attributes();

				// Install Library
				$lib                = $source . '/' . $attributes['folder'] . '/' . $attributes['library'];
				$installer->install($lib);

//				$installLibraries[] = $attributes['library'];
				$installed_lib      = '<strong>' . $attributes['name'] . '</strong>';

				$libraryLabel   = version_compare(JVERSION, '3.0.0', 'ge')
								? '<span class="label label-warning">' . $library_type . '</span>'
								: '<span style="color:orange">[' . $library_type . ']</span>';

				echo '<div>' . $libraryLabel . ' ' . JText::sprintf( 'COM_INSTALLER_INSTALL_SUCCESS', $installed_lib )
					. ' &#8680; <span style="color: green; font-weight: bold;">' . JText::_( 'JPUBLISHED' ) . '</span></div>';
			}
		}

		// Proceed Modules Install
		if (is_object($manifest->modules) && isset($manifest->modules->module))
		{
			foreach ($manifest->modules->module AS $module)
			{
				$attributes         = $module->attributes();

				// Install Module
				$mod                = $source . '/' . $attributes['folder'] . '/' . $attributes['module'];
				$installer->install($mod);

//				$installModules[]   =  $attributes['module'];
				$installed_mod      = '<strong>' . $attributes['name'] . '</strong>';

				$moduleLabel        = version_compare(JVERSION, '3.0.0', 'ge')
									? '<span class="label label-danger label-important">' . $module_type . '</span>'
									: '<span style="color:red">[' . $module_type . ']</span>';

				echo '<div>' . $moduleLabel . ' ' . JText::sprintf('COM_INSTALLER_INSTALL_SUCCESS', $installed_mod)
					. ' &#8680; <span style="color: red; font-weight: bold;">' . JText::_( 'JUNPUBLISHED' ) . '</span></div>';
			}
		}

		// Proceed Plugins Install
		$this->_installAddons($parent, $source);

		echo '<br /><br />';

//		echo '<span style="text-transform:uppercase; font-size: 8px"><b>' . JText::_('COM_ICAGENDA_INSTALL') . $this->release . '</b> | </span>';
		// You can have the backend jump directly to the newly installed component configuration page
		// $parent->getParent()->setRedirectURL('index.php?option=com_democompupdate');


		// Get Joomla Images PATH setting
		$params         = JComponentHelper::getParams('com_media');
		$image_path     = $params->get('image_path');

		// Create Folder iCagenda in ROOT/IMAGES_PATH/icagenda
		$folder[0][0]  = 'icagenda/' ;
		$folder[0][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folder[0][0];
		$folder[1][0]  = 'icagenda/files/';
		$folder[1][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folder[1][0];
		$folder[2][0]  = 'icagenda/thumbs/';
		$folder[2][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folder[2][0];
		$folder[3][0]  = 'icagenda/thumbs/system/';
		$folder[3][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folder[3][0];
		$folder[4][0]  = 'icagenda/thumbs/themes/';
		$folder[4][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folder[4][0];
		$folder[5][0]  = 'icagenda/thumbs/copy/';
		$folder[5][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folder[5][0];
		$folder[6][0]  = 'icagenda/feature_icons/';
		$folder[6][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folder[6][0];
		$folder[7][0]  = 'icagenda/feature_icons/16_bit';
		$folder[7][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folder[7][0];
		$folder[8][0]  = 'icagenda/feature_icons/24_bit';
		$folder[8][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folder[8][0];
		$folder[9][0]  = 'icagenda/feature_icons/32_bit';
		$folder[9][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folder[9][0];
		$folder[10][0] = 'icagenda/feature_icons/48_bit';
		$folder[10][1] = JPATH_ROOT.'/'.$image_path.'/'.$folder[10][0];
		$folder[11][0] = 'icagenda/feature_icons/64_bit';
		$folder[11][1] = JPATH_ROOT.'/'.$image_path.'/'.$folder[11][0];


		$message = '<div><i>'.JText::_('COM_ICAGENDA_FOLDER_CREATION').'</i></div>';
		$error   = array();

		foreach ($folder as $key => $value)
		{
			if ( ! JFolder::exists( $value[1]))
			{
				if (JFolder::create( $value[1], 0755 ))
				{

					$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
					JFile::write($value[1]."/index.html", $data);
					$message .= '<div><b><span style="color:#009933">'.JText::_('COM_ICAGENDA_FOLDER').'</span> ' . $image_path.'/'.$value[0]
								.' <span style="color:#009933">'.JText::_('COM_ICAGENDA_CREATED').'</span></b></div>';
					$error[] = 0;
				}
				else
				{
					$message .= '<div><b><span style="color:#CC0033">'.JText::_('COM_ICAGENDA_FOLDER').'</span> ' . $image_path.'/'.$value[0]
								.' <span style="color:#CC0033">'.JText::_('COM_ICAGENDA_CREATION_FAILED').'</span></b> '.JText::_('COM_ICAGENDA_PLEASE_CREATE_MANUALLY').'</div>';
					$error[] = 1;
				}
			}
			else //Folder exist
			{
				$message .= '<div><b><span style="color:#009933">'.JText::_('COM_ICAGENDA_FOLDER').'</span> ' . $image_path.'/'.$value[0]
							.' <span style="color:#009933">'.JText::_('COM_ICAGENDA_EXISTS').'</span></b></div>';
				$error[] = 0;
			}
		}

		$message.= '<br /><br />';

		echo $message;
	}

	/*
	 * $parent is the class calling this method.
	 * update runs after the database scripts are executed.
	 * If the extension exists, then the update method is run.
	 * If this returns false, Joomla will abort the update and undo everything already done.
	 */
	function update( $parent )
	{
		// Load language
		JFactory::getLanguage()->load('com_installer', JPATH_ADMINISTRATOR);
		$module_type = JText::_( 'COM_INSTALLER_TYPE_TYPE_MODULE' );
		$plugin_type = JText::_( 'COM_INSTALLER_TYPE_TYPE_PLUGIN' );
		$library_type = JText::_( 'COM_INSTALLER_TYPE_TYPE_LIBRARY' );

		// Addons update (library, modules, plugins)
		$db = JFactory::getDbo();
		$manifest = $parent->get("manifest");
		$parent = $parent->getParent();
		$source = $parent->getPath("source");

		$installer = new JInstaller();

		$installLibraries = array();
		$installModules = array();
		$installPlugins = array();

		echo '<div><i>'.JText::_('COM_INSTALLER_TOOLBAR_UPDATE').'</i></div>';

		// Pre-test iC Library
		$query = $db->getQuery(true);
		$query->select('p.enabled')
			->from('`#__extensions` AS p')
			->where($db->qn('type').' = '.$db->q('library'))
			->where($db->qn('element').' = '.$db->q('lib_ic_library'));
		$db->setQuery($query);

		$ic_library_ok = $db->loadResult();

		// Proceed Libraries Update
		if (is_object($manifest->libraries) && isset($manifest->libraries->library))
		{
			foreach ($manifest->libraries->library as $library)
			{
				$attributes         = $library->attributes();

				// Install Library
				$lib                = $source . '/' . $attributes['folder'] . '/' . $attributes['library'];
				$installer->install($lib);

				$element            = $attributes['element'];
				$installLibraries[] = $attributes['library'];
				$installed_lib      = '<strong>' . $attributes['name'] . '</strong>';

				$libraryLabel   = version_compare(JVERSION, '3.0.0', 'ge')
								? '<span class="label label-warning">' . $library_type . '</span>'
								: '<span style="color:orange">[' . $library_type . ']</span>';

				if (($ic_library_ok == '1') && ($element == 'lib_ic_library'))
				{
					echo '<div>' . $libraryLabel . ' ' . JText::sprintf( 'COM_INSTALLER_MSG_UPDATE_SUCCESS', $installed_lib ) . '</div>';
				}
				else
				{
					echo '<div>' . $libraryLabel . ' ' . JText::sprintf( 'COM_INSTALLER_INSTALL_SUCCESS', $installed_lib )
						. ' &#8680; <span style="color: green; font-weight: bold;">' . JText::_( 'JPUBLISHED' ) . '</span></div>';
				}
			}
		}

		// Proceed Modules Update
		if (is_object($manifest->modules) && isset($manifest->modules->module))
		{
			foreach ($manifest->modules->module AS $module)
			{
				$attributes         = $module->attributes();

				// Install Module
				$mod                = $source . '/' . $attributes['folder'] . '/' . $attributes['module'];
				$installer->install($mod);

				$installModules[]   =  $attributes['module'];
				$installed_mod      = '<strong>' . $attributes['name'] . '</strong>';

				$moduleLabel        = version_compare(JVERSION, '3.0.0', 'ge')
									? '<span class="label label-danger label-important">' . $module_type . '</span>'
									: '<span style="color:red">[' . $module_type . ']</span>';

				echo '<div>' . $moduleLabel . ' ' . JText::sprintf('COM_INSTALLER_MSG_UPDATE_SUCCESS', $installed_mod) . '</div>';
			}
		}

		// Proceed Plugins Update
		$this->_installAddons($parent, $source);

		echo '<br /><br />';

//		echo '<span style="text-transform:uppercase; font-size: 8px">' . JText::_('COM_ICAGENDA_UPDATE') . $this->release . ' | </span>';
		// You can have the backend jump directly to the newly updated component configuration page
		// $parent->getParent()->setRedirectURL('index.php?option=com_democompupdate');


		// Get Joomla Images PATH setting
		$params     = JComponentHelper::getParams('com_media');
		$image_path = $params->get('image_path');

		// Create Folder iCagenda in ROOT/IMAGES_PATH/icagenda
		$folderimg[0][0]  = 'icagenda/' ;
		$folderimg[0][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[0][0];
		$folderimg[1][0]  = 'icagenda/files/';
		$folderimg[1][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[1][0];
		$folderimg[2][0]  = 'icagenda/thumbs/';
		$folderimg[2][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[2][0];
		$folderimg[3][0]  = 'icagenda/thumbs/system/';
		$folderimg[3][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[3][0];
		$folderimg[4][0]  = 'icagenda/thumbs/themes/';
		$folderimg[4][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[4][0];
		$folderimg[5][0]  = 'icagenda/thumbs/copy/';
		$folderimg[5][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[5][0];
		$folderimg[6][0]  = 'icagenda/feature_icons/';
		$folderimg[6][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[6][0];
		$folderimg[7][0]  = 'icagenda/feature_icons/16_bit';
		$folderimg[7][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[7][0];
		$folderimg[8][0]  = 'icagenda/feature_icons/24_bit';
		$folderimg[8][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[8][0];
		$folderimg[9][0]  = 'icagenda/feature_icons/32_bit';
		$folderimg[9][1]  = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[9][0];
		$folderimg[10][0] = 'icagenda/feature_icons/48_bit';
		$folderimg[10][1] = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[10][0];
		$folderimg[11][0] = 'icagenda/feature_icons/64_bit';
		$folderimg[11][1] = JPATH_ROOT.'/'.$image_path.'/'.$folderimg[11][0];


		$message = '<div><i>'.JText::_('COM_ICAGENDA_FOLDER_CREATION').'</i></div>';
		$error	 = array();
		foreach ($folderimg as $key => $value)
		{
			if (!JFolder::exists( $value[1]))
			{
				if (JFolder::create( $value[1], 0755 ))
				{

					$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
					JFile::write($value[1]."/index.html", $data);
					$message .= '<div><b><span style="color:#009933">'.JText::_('COM_ICAGENDA_FOLDER').'</span> ' . $image_path.'/'.$value[0]
								.' <span style="color:#009933">'.JText::_('COM_ICAGENDA_CREATED').'</span></b></div>';
					$error[] = 0;
				}
				else
				{
					$message .= '<div><b><span style="color:#CC0033">'.JText::_('COM_ICAGENDA_FOLDER').'</span> ' . $image_path.'/'.$value[0]
								.' <span style="color:#CC0033">'.JText::_('COM_ICAGENDA_CREATION_FAILED').'</span></b> '.JText::_('COM_ICAGENDA_PLEASE_CREATE_MANUALLY').'</div>';
					$error[] = 1;
				}
			}
			else//Folder exist
			{
				$message .= '<div><b><span style="color:#009933">'.JText::_('COM_ICAGENDA_FOLDER').'</span> ' . $image_path.'/'.$value[0]
							.' <span style="color:#009933">'.JText::_('COM_ICAGENDA_EXISTS').'</span></b></div>';
				$error[] = 0;
			}
		}

		$message.= '<br /><br />';

		echo $message;
	}


	/**
	 * Installs subextensions (modules, plugins) bundled with the main extension
	 * NOTE: Currently installing only plugins (3.4.0). Modules install to be added later.
	 *
	 * @param JInstaller $parent
	 *
	 * @return JObject The subextension installation status
	 */
	private function _installAddons($parent, $source)
	{
		// Load language
		JFactory::getLanguage()->load('com_installer', JPATH_ADMINISTRATOR);

		$module_type	= JText::_( 'COM_INSTALLER_TYPE_TYPE_MODULE' );
		$plugin_type	= JText::_( 'COM_INSTALLER_TYPE_TYPE_PLUGIN' );
		$library_type	= JText::_( 'COM_INSTALLER_TYPE_TYPE_LIBRARY' );

		$db = JFactory::getDbo();

		$status = new JObject();

		/*
		 * PLUGINS UPDATE/INSTALL
		 */
		$status->plugins = array();

		// Plugins installation process
		if (count($this->installation_queue['plugins']))
		{
			foreach ($this->installation_queue['plugins'] AS $folder => $plugins)
			{
				if (count($plugins))
				{
					foreach ($plugins AS $plugin => $pluginPreferences)
					{
						$path = "$source/plugins/$folder/$plugin";

						if ( ! is_dir($path))
						{
							$path = "$source/plugins/$folder/plg_$plugin";
						}

						if ( ! is_dir($path))
						{
							$path = "$source/plugins/$plugin";
						}

						if ( ! is_dir($path))
						{
							$path = "$source/plugins/plg_$plugin";
						}

						if ( ! is_dir($path))
						{
							continue;
						}

						// Was the plugin already installed?
						$query = $db->getQuery(true)
							->select('COUNT(*)')
							->from($db->qn('#__extensions'))
							->where($db->qn('element') . ' = ' . $db->q($plugin))
							->where($db->qn('folder') . ' = ' . $db->q($folder));
						$db->setQuery($query);

						try
						{
							$count = $db->loadResult();
						}
						catch (Exception $exc)
						{
							$count = 0;
						}

						$installer = new JInstaller;
						$result = $installer->install($path);

						$status->plugins[] = array('name' => 'plg_' . $plugin, 'group' => $folder, 'result' => $result);

						list($pluginName, $pluginPublished) = $pluginPreferences;

						// We publish the plugin if not already installed, and default is published
						if ($pluginPublished && ! $count)
						{
							$query = $db->getQuery(true)
								->update($db->qn('#__extensions'))
								->set($db->qn('enabled') . ' = ' . $db->q('1'))
								->where($db->qn('element') . ' = ' . $db->q($plugin))
								->where($db->qn('folder') . ' = ' . $db->q($folder));
							$db->setQuery($query);

							try
							{
								$db->execute();
							}
							catch (Exception $exc)
							{
								// Nothing
							}
						}

						// Display plugin status
						$pluginStatus = '';

						$pluginName = '<strong>' . $pluginName . '</strong>';

						$pluginLabel   = version_compare(JVERSION, '3.0.0', 'ge')
										? '<span class="label label-info">' . $plugin_type . '</span>'
										: '<span style="color:blue">[' . $plugin_type . ']</span>';

						$pluginStatus.= '<div>' . $pluginLabel . ' ';

						// Plugin update
						if ($count)
						{
							$pluginStatus.= JText::sprintf('COM_INSTALLER_MSG_UPDATE_SUCCESS', $pluginName);
						}

						// Plugin first install
						else
						{
							$pluginStatus.= JText::sprintf('COM_INSTALLER_INSTALL_SUCCESS', $pluginName);
							$pluginStatus.= ' &#8680; <span style="color: green; font-weight: bold;">' . JText::_('JPUBLISHED') . '</span>';
						}

						$pluginStatus.= '</div>';

						echo $pluginStatus;
					}
				}
			}
		}

		return $status;
	}


	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * postflight is run after the extension is registered in the database.
	 */
	function postflight($type, $parent)
	{
		// Updating Params to ensure a correct value
		jimport('joomla.application.component.helper'); // Import component helper library

		$this->release  = $parent->get("manifest")->version;
		$icagendaParams = JComponentHelper::getParams('com_icagenda');
		$oldRelease     = $icagendaParams->get('release'); // Since 3.6.0
		$oldSys         = $icagendaParams->get('icsys');

		// Fix old versions created date missing (runs if version previously installed is before 3.3.7)
		// update database to set a valid created date for events created with versions of iCagenda < 3.1.5,
		// and set in this order : modified date if valid or next/last date if valid or, at the end, will use current date.
		// (this fix is to prevent wrong 'Created on 30 November -0001' in search results)
		if (version_compare($oldRelease, '3.3.7', 'le'))
		{
			$db    = JFactory::getDbo();
			$date  = JFactory::getDate();

			$null_created = '0000-00-00 00:00:00';

			$query = $db->getQuery(true);

			$query->select('e.id, e.created, e.modified, e.next')
				->from('`#__icagenda_events` AS e')
				->where($db->qn('e.created').' = '.$db->q($null_created));
			$db->setQuery($query);

			$list_created_null = $db->loadObjectList();

			foreach ($list_created_null AS $cn)
			{
				if ($cn->modified != $null_created)
				{
					$new_created = $cn->modified;
				}
				elseif ($cn->next != $null_created)
				{
					$new_created = $cn->next;
				}
				else
				{
					$new_created = $date->toSql();
				}
				$query = $db->getQuery(true)
					->update($db->qn('#__icagenda_events'))
					->set($db->qn('created').' = '.$db->q($new_created))
					->where($db->qn('id').' = '.intval($cn->id));
				$db->setQuery($query);
				$db->execute();
			}
		}

		// Fix for 'period' value in db when registration mode was set on "for all dates" (versions 3.6.0 to 3.6.2)
		// Due to possible older issue, good to run this check/update for all version before 3.6
		if (version_compare($oldRelease, '3.6.6', 'lt'))
		{
			$db = JFactory::getDbo();
			$db->setQuery('SELECT id, params FROM #__icagenda_events');
			$listEvtParams = $db->loadObjectList();

			foreach ($listEvtParams as $event)
			{
				$evtID     = $event->id;
				$evtParams = json_decode($event->params, true);

				if ($evtParams['typeReg'] == 2)
				{
					$query = $db->getQuery(true)
						->update($db->qn('#__icagenda_registration'))
						->set($db->qn('period') . ' = 1')
						->where($db->qn('eventid') . ' = ' . intval($evtID))
						->where($db->qn('date') . ' = ""');
					$db->setQuery($query);
					$db->execute();
				}
			}
		}

		// Remove obsolete files and folders
		$icagendaRemoveFiles = $this->icagendaRemoveFiles;

		$this->_removeObsoleteFilesAndFolders($icagendaRemoveFiles);

		// Always create or modify these parameters
		$ictype_label = ($this->ictype == 'core') ? '' : ' <strong>' . strtoupper($this->ictype) . '</strong>';

		$params['version'] = $ictype_label . ' [' . $this->release . ']';
		$params['release'] = $this->release;
		$params['author']  = 'JoomliC';
		$params['icsys']   = $this->ictype;

		if ($this->ictype == 'core') $params['copy'] = '1';

		if ($oldSys !== $params['icsys'])
		{
			// Get com_icagenda extension_id
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('extension_id')
				->from('#__extensions')
				->where('element = "com_icagenda"');
			$db->setQuery($query);

			$eid = $db->loadResult();

			if ($eid)
			{
				$this->removeObsoleteUpdateSites($eid);
			}

		}

		if (($type == 'install' || $oldSys !== $params['icsys']) && $this->ictype != 'core')
		{
			$params['copy'] = NULL;
		}

		// Define the following parameters only if it is an original install // To be checked!
		if ($type == 'install')
		{
			$params['atlist'] = '1';
			$params['atevent'] = '1';
			$params['atfloat'] = '2';
			$params['aticon'] = '2';
			$params['arrowtext'] = '1';
			$params['statutReg'] = '1';
			$params['maxRlist'] = '5';
			$params['navposition'] = '0';
			$params['targetLink'] = '1';
			$params['participantList'] = '0';
			$params['participantSlide'] = '1';
			$params['participantDisplay'] = '1';
			$params['fullListColumns'] = 'tiers';
			$params['regEmailUser'] = '1';
			$params['timeformat'] = '1';
			$params['ShortDescLimit'] = '100';
			$params['limitRegEmail'] = '1';
			$params['limitRegDate'] = '1';
			$params['phoneRequired'] = '2';
			$params['headerList'] = '1';
		}

		$extparticipantList   = $icagendaParams->get('participantList');
		$extparticipantSlide  = $icagendaParams->get('participantSlide');
		$extstatutReg         = $icagendaParams->get('statutReg');
		$extlimitRegEmail     = $icagendaParams->get('limitRegEmail');
		$extlimitRegDate      = $icagendaParams->get('limitRegDate');
		$extphoneRequired     = $icagendaParams->get('phoneRequired');
		$extregEmailUser      = $icagendaParams->get('regEmailUser');
		$largewidththreshold  = $icagendaParams->get('largewidththreshold', '1201');
		$mediumwidththreshold = $icagendaParams->get('mediumwidththreshold', '769');
		$smallwidththreshold  = $icagendaParams->get('smallwidththreshold', '481');

		$params['largewidththreshold']  = $largewidththreshold;
		$params['mediumwidththreshold'] = $mediumwidththreshold;
		$params['smallwidththreshold']  = $smallwidththreshold;

		if ($extparticipantList == '2')
		{
			$params['participantList'] = '0';
		}

		if ($extparticipantSlide == '2')
		{
			$params['participantSlide'] = '0';
		}

		if ($extstatutReg == '2')
		{
			$params['statutReg'] = '0';
		}

		if ($extlimitRegEmail == '2')
		{
			$params['limitRegEmail'] = '0';
		}

		if ($extlimitRegDate == '2')
		{
			$params['limitRegDate'] = '0';
		}

		if ($extphoneRequired == '2')
		{
			$params['phoneRequired'] = '0';
		}

		if ($extregEmailUser == '2')
		{
			$params['regEmailUser'] = '0';
		}

		// Update 3.1.1
		$emailRequired = $icagendaParams->get('emailRequired');

		if ($emailRequired == '')
		{
			$params['emailRequired'] = '1';
		}

		// Update 3.4.1
		$datesDisplay_global = $icagendaParams->get('datesDisplay_global');
		$reg_captcha         = $icagendaParams->get('reg_captcha', '');
		$submit_captcha      = $icagendaParams->get('submit_captcha', '');
		$captcha             = $icagendaParams->get('captcha', '');

		if ($datesDisplay_global)
		{
			$params['datesDisplay'] = $datesDisplay_global;
		}

		if (in_array($reg_captcha, array('', '0'))
			&& in_array($submit_captcha, array('', '0'))
			)
		{
			$params['captcha'] = $captcha;
		}
		elseif ( ! in_array($reg_captcha, array('', '0', '1')))
		{
			$params['captcha'] = $reg_captcha;
		}
		elseif ( ! in_array($submit_captcha, array('', '0', '1')))
		{
			$params['captcha'] = $submit_captcha;
		}
		else
		{
			$params['captcha'] = $captcha;
		}

		$params['reg_captcha']    = (in_array($reg_captcha, array('', '0'))) ? '0' : '1';
		$params['submit_captcha'] = (in_array($submit_captcha, array('', '0'))) ? '0' : '1';

		// Update 3.6.9
		$filters_mode = $icagendaParams->get('filters_mode');

		if (version_compare($oldRelease, '3.6.9', 'lt')
			&& ! $filters_mode)
		{
			$params['filters_mode'] = '2';
		}
		
		// Patch for 3.6.9
		if (version_compare($oldRelease, '3.6.8', 'gt')
			&& version_compare($oldRelease, '3.6.10', 'lt'))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->update($db->qn('#__icagenda_events'))
				->set($db->qn('startdate') . ' = "0000-00-00 00:00:00"')
				->where($db->qn('startdate') . ' = "1970-01-01 01:00:00"')
				->update($db->qn('#__icagenda_events'))
				->set($db->qn('enddate') . ' = "0000-00-00 00:00:00"')
				->where($db->qn('enddate') . ' = "1970-01-01 01:00:00"')
				->update($db->qn('#__icagenda_events'))
				->set($db->qn('next') . ' = "0000-00-00 00:00:00"')
				->where($db->qn('next') . ' = "1970-01-01 01:00:00"');
			$db->setQuery($query);
			$db->execute();
		}

		// Update 3.7.0
		$terms_Type = $icagendaParams->get('terms_Type');

		if ($terms_Type == '')
		{
			$terms_Type = 3;
		}

		$params['terms_type'] = $terms_Type;

		if (version_compare($oldRelease, '3.7.0', 'lt'))
		{
			$accessParticipantList = $icagendaParams->get('accessParticipantList');

			$params['participant_name_visibility']  = ($accessParticipantList < 3 ) ? $accessParticipantList : '';
			$params['participant_gravatar_consent'] = '0';
		}

		// Update 3.7.2
		if (version_compare($oldRelease, '3.7.2', 'lt'))
		{
			$params['maps_service'] = '3';
		}

		// UPDATE PARAMS
		$this->setParams($params);

		// Set default Access Permissions for iCagenda component
		$rules['core.manage']                   = array('6' => 1);
		$rules['icagenda.access.categories']    = array('7' => 1);
		$rules['icagenda.access.events']        = array('6' => 1);
		$rules['icagenda.access.registrations'] = array('7' => 1);
		$rules['icagenda.access.newsletter']    = array('7' => 1);
		$rules['icagenda.access.themes']        = array('7' => 1);
		$rules['icagenda.access.customfields']  = array('7' => 1);
		$rules['icagenda.access.features']      = array('7' => 1);

		// UPDATE RULES
		$this->setRules( $rules );

		$this->clean();

		$usageStats = $icagendaParams->get('usage_stats', '1');

		$sendSystemInfo = $this->getSystemInfo($type, $parent);

		if ($sendSystemInfo && $usageStats)
		{
			echo $sendSystemInfo;
		}

		echo '<span style="font-size: 11px; font-style: italic; font-weight: bold;">Jooml!C &#8226; iCagenda &#8226; <a href="http://www.joomlic.com" target="_blank">www.joomlic.com</a></span>';

		echo '<hr />';
	}

	// Delete unused update site
	/*
	 * Delete unused update site
	 *
	 * $eid  int  extension_id
	 */
	private function removeObsoleteUpdateSites($eid)
	{
		if ($eid)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->delete('#__update_sites_extensions')
				->where('extension_id = ' . $eid);
			$db->setQuery($query);
			$db->execute();

			// Delete any unused update sites
			$query->clear()
				->select('update_site_id')
				->from('#__update_sites_extensions');
			$db->setQuery($query);
			$results = $db->loadColumn();

			if (is_array($results))
			{
				// So we need to delete the update sites and their associated updates
				$updatesite_delete = $db->getQuery(true);
				$updatesite_delete->delete('#__update_sites');
				$updatesite_query = $db->getQuery(true);
				$updatesite_query->select('update_site_id')
					->from('#__update_sites');

				// If we get results back then we can exclude them
				if (count($results))
				{
					$updatesite_query->where('update_site_id NOT IN (' . implode(',', $results) . ')');
					$updatesite_delete->where('update_site_id NOT IN (' . implode(',', $results) . ')');
				}

				// So let's find what update sites we're about to nuke and remove their associated extensions
				$db->setQuery($updatesite_query);
				$update_sites_pending_delete = $db->loadColumn();

				if (is_array($update_sites_pending_delete) && count($update_sites_pending_delete))
				{
					// Nuke any pending updates with this site before we delete it
					// TODO: investigate alternative of using a query after the delete below with a query and not in like above
					$query->clear()
						->delete('#__updates')
						->where('update_site_id IN (' . implode(',', $update_sites_pending_delete) . ')');
					$db->setQuery($query);
					$db->execute();
				}

				// Note: this might wipe out the entire table if there are no extensions linked
				$db->setQuery($updatesite_delete);
				$db->execute();
			}

			// Last but not least we wipe out any pending updates for the extension
			$query->clear()
				->delete('#__updates')
				->where('extension_id = ' . $eid);
			$db->setQuery($query);
			$db->execute();
		}
	}

	/*
	 * $parent is the class calling this method
	 * uninstall runs before any other action is taken (file removal or database processing).
	 */
	function uninstall( $parent )
	{
		echo '<p>' . JText::_('COM_ICAGENDA_UNINSTALL') . '</p>';
	}


	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam( $name )
	{
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE element = "com_icagenda"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}


	/*
	 * sets parameter values in the component's row of the extension table
	 */
	function setParams( $param_array )
	{
		if ( count($param_array) > 0 )
		{
			// read the existing component value(s)
			$db = JFactory::getDbo();
			$db->setQuery('SELECT params FROM #__extensions WHERE element = "com_icagenda"');
			$params = json_decode( $db->loadResult(), true );
			// add the new variable(s) to the existing one(s)
			foreach ( $param_array as $name => $value )
			{
				$params[ (string) $name ] = (string) $value;
			}
			// store the combined new and existing values back as a JSON string
			$paramsString = json_encode( $params );
			$db->setQuery('UPDATE #__extensions SET params = ' .
				$db->quote( $paramsString ) .
				' WHERE element = "com_icagenda"' );
				$db->query();
		}
	}


	/*
	 * sets access permissions values (rules) in the component's row of the assets table
	 */
	function setRules( $rule_array )
	{
		if ( count($rule_array) > 0 )
		{
			// read the existing rules values
			$db = JFactory::getDbo();
			$db->setQuery('SELECT rules FROM #__assets WHERE name = "com_icagenda"');
			$rules = json_decode( $db->loadResult(), true );
			// add the new variable(s) to the existing one(s)
			foreach ( $rule_array as $name => $value )
			{
				if (!array_key_exists($name, $rules))
				{
					$rules[ (string) $name ] = (array) $value;
				}
			}
			// store the combined new and existing values back as a JSON string
			$rulesString = json_encode( $rules );
			$db->setQuery('UPDATE #__assets SET rules = ' .
				$db->quote( stripslashes($rulesString) ) .
				' WHERE name = "com_icagenda"' );
				$db->query();
		}
	}

	/**
	 * Purge the cache.
	 *
	 * @return  void
	 */
	public function purgeCache()
	{
		$app = JFactory::getApplication();

		$ret = $this->clean();

		$msg = JText::_('COM_ICAGENDA_CACHE_EXPIRED_ITEMS_HAVE_BEEN_PURGED');
		$msgType = 'message';

		if ($ret === false)
		{
			$msg = JText::_('COM_ICAGENDA_CACHE_EXPIRED_ITEMS_PURGING_ERROR');
			$msgType = 'error';
		}

		$app->redirect('index.php?option=com_icagenda&view=icagenda', $msg, $msgType);
	}

	/**
	 * Clean out a cache group as named by param.
	 * If no param is passed clean all cache groups.
	 *
	 * @param   string  $group  Cache group name.
	 *
	 * @return  void
	 */
	public function clean($group = '')
	{
		$cache = JFactory::getCache('');
		$cache->clean($group);
	}

	/**
	 * Send site system information
	 * Adapted from Nicholas K. Dionysopoulos's code (Akeeba - www.akeebabackup.com).
	 */
	public function getSystemInfo($type, $parent)
	{
		$this->release = $parent->get( "manifest" )->version;

		// Do not system info on localhost
		if ((strpos(JUri::root(), 'localhost') !== false)
			|| (strpos(JUri::root(), '127.0.0.1') !== false))
		{
			return false;
		}

		// Set site ID
		$siteId = md5(JUri::base());

		// If info file is missing, stop it!
		if ( ! file_exists(JPATH_ROOT . '/administrator/components/com_icagenda/assets/jcms/info.php'))
		{
			return false;
		}

		if ( ! class_exists('iCagendaSystemInfo', false))
		{
			require_once JPATH_ROOT . '/administrator/components/com_icagenda/assets/jcms/info.php';
		}

		if ( ! class_exists('iCagendaSystemInfo', false))
		{
			return false;
		}

		$params = JComponentHelper::getParams('com_icagenda');

		// Stop if system info is turned off
		if ( ! $params->get('system_info', 1))
		{
			return false;
		}

		$db = JFactory::getDbo();
		$stats = new iCagendaSystemInfo();

		$stats->setSiteId($siteId);

		// Get iCagenda release
		$ic_parts = explode('.', $this->release);
		$ic_major = $ic_parts[0];
		$ic_minor = isset($ic_parts[1]) ? $ic_parts[1] : '';
		$ic_revision = isset($ic_parts[2]) ? $ic_parts[2] : '';

		// Get PHP version
		list($php_major, $php_minor, $php_revision) = explode('.', phpversion());
		$php_qualifier = strpos($php_revision, '~') !== false ? substr($php_revision, strpos($php_revision, '~')) : '';

		// Get Joomla version
		list($cms_major, $cms_minor, $cms_revision) = explode('.', JVERSION);

		// Get Database version
		list($db_major, $db_minor, $db_revision) = explode('.', $db->getVersion());
		$db_qualifier = strpos($db_revision, '~') !== false ? substr($db_revision, strpos($db_revision, '~')) : '';

		// Get Database type
		$db_driver = get_class($db);

		if (stripos($db_driver, 'mysql') !== false)
		{
			$db_type = '1';
		}
		elseif (stripos($db_driver, 'sqlsrv') !== false || stripos($db_driver, 'sqlazure'))
		{
			$db_type = '2';
		}
		elseif (stripos($db_driver, 'postgresql') !== false)
		{
			$db_type = '3';
		}
		else
		{
			$db_type = '0';
		}

		$installtype	= ($type == 'install') ? '1' : '2';
		$ictype			= $this->ictype;

		$stats->setValue('ins', $installtype); // software_install

		// Version : major(x).minor(y).revision/patch(z)

		$stats->setValue('swn', 'iCagenda'); // software_name
		$stats->setValue('swt', $ictype); // software_type
		$stats->setValue('swx', $ic_major); // software_major
		$stats->setValue('swy', $ic_minor); // software_minor
		$stats->setValue('swz', $ic_revision); // software_revision

		$stats->setValue('cmst', 1); // cms_type
		$stats->setValue('cmsx', $cms_major); // cms_major
		$stats->setValue('cmsy', $cms_minor); // cms_minor
		$stats->setValue('cmsz', $cms_revision); // cms_revision

		$stats->setValue('phpx', $php_major); // php_major
		$stats->setValue('phpy', $php_minor); // php_minor
		$stats->setValue('phpz', $php_revision); // php_revision
		$stats->setValue('phpq', $php_qualifier); // php_qualifiers

		$stats->setValue('dbt', $db_type); // db_type
		$stats->setValue('dbx', $db_major); // db_major
		$stats->setValue('dby', $db_minor); // db_minor
		$stats->setValue('dbz', $db_revision); // db_revision
		$stats->setValue('dbq', $db_qualifier); // db_qualifiers

		$return = $stats->sendInfo(true);

		return $return;
	}
}
