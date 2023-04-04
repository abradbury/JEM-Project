<?php
/**
 * @version 2.3.17
 * @package JEM
 * @copyright (C) 2013-2023 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license https://www.gnu.org/licenses/gpl-3.0 GNU/GPL
 */

defined('_JEXEC') or die;

require_once (JPATH_COMPONENT_SITE.'/classes/controller.form.class.php');

/**
 * Controller: Venue
 */
class JemControllerVenue extends JemControllerForm
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 */
	protected $text_prefix = 'COM_JEM_VENUE';


	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 * Here used to trigger the jem plugins, mainly the mailer.
	 *
	 * @param   JModel(Legacy)  $model      The data model object.
	 * @param   array           $validData  The validated data.
	 *
	 * @return  void
	 */
	protected function _postSaveHook($model, $validData = array())
	{
		$isNew = $model->getState('venue.new');
		$id    = $model->getState('venue.id');

		// trigger all jem plugins
		JPluginHelper::importPlugin('jem');
		$dispatcher = JemFactory::getDispatcher();
		$dispatcher->triggerEvent('onVenueEdited', array($id, $isNew));

		// but show warning if mailer is disabled
		if (!JPluginHelper::isEnabled('jem', 'mailer')) {
			\Joomla\CMS\Factory::getApplication()->enqueueMessage(JText::_('COM_JEM_GLOBAL_MAILERPLUGIN_DISABLED'), 'notice');
		}
	}

}
