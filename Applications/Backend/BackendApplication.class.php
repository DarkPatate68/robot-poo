<?php
namespace Applications\Backend;
 
class BackendApplication extends \Library\Application
{
	public function __construct()
	{
		parent::__construct();

		$this->name = 'Backend';
	}

	public function run()
	{
		if ($this->user->isAuthenticated() || $this->httpRequest->requestURI() == $GLOBALS['PREFIXE'] . '/membre/inscription')
		{
			$controller = $this->getController();
		}
		else
		{
			$this->exitMemberZone(); // Si le lien demande à sortir de la zone membre (membre/pre/) on le fait !
			$controller = new Modules\Connexion\ConnexionController($this, 'Connexion', 'connexion');
		}

		$controller->execute();

		$controller->page()->addVar('user', $this->user);
		$this->httpResponse->setPage($controller->page());
		$this->httpResponse->send();
	}
}