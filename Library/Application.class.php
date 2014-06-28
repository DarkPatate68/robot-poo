<?php
namespace Library;

session_start();

// Permet de charger dynamiquement la bonne URL dans les liens qui doivent être absolus
$tab = explode('Web/', $_SERVER['REQUEST_URI']);
$GLOBALS['PREFIXE'] = $tab[0] . 'Web';

abstract class Application
{
	protected $httpRequest;
	protected $httpResponse;
	protected $name;
	protected $user;
	protected $config;
	protected $anneeEnCours;
	protected $timestamp;
	protected $listeAnnees;

	public function __construct()
	{
		$this->httpRequest = new HTTPRequest($this);
		$this->httpResponse = new HTTPResponse($this);
		$this->name = 'Frontend'; // Mise à Frontend dans le cas où la connexion avec la BDD échoue la ligne suivante
		$this->config = new Config($this);
		$managers = new Managers('PDO', PDOFactory::getMysqlConnection($this->httpResponse));
		$this->name = ''; // Vrai valeur initiale de name
		
		// Création de l'utilisateur, par session, cookie, ou anonyme.
		if((isset($_SESSION['membre']) && isset($_SESSION['groupe'])) && $_SESSION['membre'] instanceof \Library\Entities\Membre && $_SESSION['groupe'] instanceof \Library\Entities\Groupe)
		{
			$this->user = new User($this, $_SESSION['membre'], $_SESSION['groupe'], $_SESSION['membre']->id() == 0);
		}
		else
		{
			if($this->httpRequest->cookieExists('pseudo') && $this->httpRequest->cookieExists('mdp'))
			{
				$membre = $managers->getManagerOf('Membre')->connexion($this->httpRequest->cookieData('pseudo'), $this->httpRequest->cookieData('mdp'));
				if($membre !== false)
				{
					$groupe = $managers->getManagerOf('Groupe')->getUnique($membre->groupe());			
					$this->user = new User($this, $membre, $groupe, $membre->id() == 0);
				}
				else // Si les données du cookie sont invalides : utilisateur anonyme
					$this->user = new User($this, $managers->getManagerOf('Membre')->getUnique(0), $managers->getManagerOf('Groupe')->getUnique(0), true);
			}
			else // Chargement utilisateur anonyme		
				$this->user = new User($this, $managers->getManagerOf('Membre')->getUnique(0), $managers->getManagerOf('Groupe')->getUnique(0), true);
		}
		
		// Chargement de l'année et du timestamp (TS)
		$this->listeAnnees = $managers->getManagerOf('Archive')->getListe();
		$archive = $managers->getManagerOf('Archive')->getUnique();
		$this->anneeEnCours = (string) $archive['anneeScolaire'];
		$this->timestamp = (int) $archive['timestamp'];
	}
	
	public function getController()
	{
		$this->exitMemberZone();
		
		$router = new \Library\Router;

		$xml = new \DOMDocument;
		$xml->load(__DIR__.'/../Applications/'.$this->name.'/Config/routes.xml');

		$routes = $xml->getElementsByTagName('route');

		// On parcourt les routes du fichier XML.
		foreach ($routes as $route)
		{
			$vars = array();

			// On regarde si des variables sont présentes dans l'URL.
			if ($route->hasAttribute('vars'))
				$vars = explode(',', $route->getAttribute('vars'));

			// On ajoute la route au routeur.
			$router->addRoute(new Route($GLOBALS['PREFIXE'] . $route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars));
		}

		try
		{
			// On récupère la route correspondante à l'URL.
			$matchedRoute = $router->getRoute($this->httpRequest->requestURI());
		}
		catch (\RuntimeException $e)
		{
			if ($e->getCode() == \Library\Router::NO_ROUTE)
			{
				// Si aucune route ne correspond, c'est que la page demandée n'existe pas.
				$this->httpResponse->redirect404();
			}
		}

		// On ajoute les variables de l'URL au tableau $_GET.
		$_GET = array_merge($_GET, $matchedRoute->vars());

		// On instancie le contrôleur.
		$controllerClass = 'Applications\\'.$this->name.'\\Modules\\'.$matchedRoute->module().'\\'.$matchedRoute->module().'Controller';
		return new $controllerClass($this, $matchedRoute->module(), $matchedRoute->action());
	}
	
	public function exitMemberZone()
	{
		if(strpos($this->httpRequest->requestURI(), 'membre/pre/') !== false)
		{	
			$this->httpResponse->redirect(str_replace('membre/pre/', '', $this->httpRequest->requestURI()));
			//preg_replace('#(.+)/.+/pre/(.*)#', '$1/$2', $this->httpRequest->requestURI())
		}
	}

	abstract public function run();

	public function httpRequest()
	{
		return $this->httpRequest;
	}

	public function httpResponse()
	{
		return $this->httpResponse;
	}

	public function name()
	{
		return $this->name;
	}
	
	public function user()
	{
		return $this->user;
	}
	
	public function config()
	{
		return $this->config;
	}
	
	public function anneeEnCours()
	{
		return $this->anneeEnCours;
	}
	
	public function timestamp()
	{
		return $this->timestamp;
	}
	
	public function listeAnnees()
	{
		return $this->listeAnnees;
	}
}