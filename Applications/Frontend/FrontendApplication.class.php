<?php
namespace Applications\Frontend;
 
class FrontendApplication extends \Library\Application
{
	public function __construct()
	{
		parent::__construct();

		$this->name = 'Frontend';
	}

	public function run()
	{
		$controller = $this->getController();
		$controller->execute();

		$controller->page()->addVar('user', $this->user);
		$this->httpResponse->setPage($controller->page());
		
		$this->httpResponse->send();
	}
}