<?php
namespace Library;

class User extends ApplicationComponent
{
	protected $membre;
	public static $TYPE_FLASH = array('INFO', 'ERREUR', 'ATTENTION', 'QUESTION');
	
	public function __construct(\Library\Application $app, \Library\Entities\Membre $membre, \Library\Entities\Groupe $groupe, $deconnecter = false)
	{
		$this->membre = $membre;
		$this->membre->setGroupeObjet($groupe);
		
		/*$_SESSION['membre'] = $membre;
		$_SESSION['groupe'] = $groupe;*/
		
		$_SESSION['id_mbr'] = $membre->id();
		
		if($deconnecter)
			unset($_SESSION['auth']);
		
	}
	
	public function getAttribute($attr)
	{
		return isset($_SESSION[$attr]) ? $_SESSION[$attr] : null;
	}

	public function getFlash()
	{
		$flash = $_SESSION['flash'];
		unset($_SESSION['flash']);

		return $flash;
	}
	
	public function membre()
	{
		return $this->membre;
	}

	public function hasFlash()
	{
		return isset($_SESSION['flash']);
	}

	public function isAuthenticated()
	{
		return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
	}

	public function setAttribute($attr, $value)
	{
		$_SESSION[$attr] = $value;
	}

	public function setAuthenticated(\Library\Entities\Membre $membre, $authenticated = true)
	{
		if (!is_bool($authenticated))
		{
			throw new \InvalidArgumentException('La valeur spécifiée à la méthode User::setAuthenticated() doit être un boolean');
		}
		
		$_SESSION['auth'] = $authenticated;
		$this->membre = $membre;
		/*$membre->setMdp(''); // Pour que le MdP ne se ballade pas dans la variable SESSION
		$_SESSION['membre'] = $membre;
		$_SESSION['groupe'] = $membre->groupeObjet();*/
		
		$_SESSION['id_mbr'] = $membre->id();
	}

	public function setFlash($value, $type = 'INFO')
	{
		if(in_array($type, self::$TYPE_FLASH))
			$_SESSION['flash'] = $type . '_' . $value;
		else
			$_SESSION['flash'] = 'INFO_' . $value;
	}
	
	public function setMembre(\Library\Entities\Membre $membre)
	{
		$this->membre = $membre;
	}
}