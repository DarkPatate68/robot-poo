<?php
namespace Library;
 
class Managers
{
	protected $api = null; // Méthode de connexion à la BDD : PDO, MySQLi…
	protected $dao = null; // Objet d'accès à la BDD (Database Access Object)
	protected $managers = array();

	public function __construct($api, $dao)
	{
		$this->api = $api;
		$this->dao = $dao;
	}

	public function getManagerOf($module)
	{
		if (!is_string($module) || empty($module))
		{
			throw new \InvalidArgumentException('Le module spécifié est invalide');
		}

		if (!isset($this->managers[$module]))
		{
			$manager = '\\Library\\Models\\'.$module.'Manager_'.$this->api;
			$this->managers[$module] = new $manager($this->dao);
		}

		return $this->managers[$module];
	}
}