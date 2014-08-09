<?php
namespace Library;
 
class HTTPResponse extends ApplicationComponent
{
	protected $page;

	public function __construct(Application $app)
	{
		parent::__construct($app);
	}
   
	public function addHeader($header)
	{
		header($header);
	}

	public function redirect($location)
	{
		header('Location: '.$location);
		exit;
	}

	public function redirect404()
	{
		$this->page = new Page($this->app);
		$this->page->setContentFile(__DIR__.'/../Errors/404.php');
		$this->page->addVar('title', 'Erreur 404 — fichier non trouvé');
		$this->page->addVar('categorieCSS', 'erreur');
		
		$this->addHeader('HTTP/1.0 404 Not Found');
		
		$this->send();
	}
	
	public function redirect401()
	{
		$this->page = new Page($this->app);
		$this->page->setContentFile(__DIR__.'/../Errors/401.html');
		
		$this->page->addVar('title', 'Erreur 401 — autorisation requise');
		$this->page->addVar('categorieCSS', 'erreur');
		
		$this->addHeader('HTTP/1.0 401 Unauthorized');
		
		$this->send();
	}
	
	public function redirect403()
	{
		$this->page = new Page($this->app);
		$this->page->setContentFile(__DIR__.'/../Errors/403.php');
		
		$this->page->addVar('title', 'Erreur 403 — autorisation refusée');
		$this->page->addVar('categorieCSS', 'erreur');
		
		$this->addHeader('HTTP/1.0 403 Forbidden');
		
		$this->send();
	}
	
	public function redirect418($variable)
	{
		$this->page = new Page($this->app);
		$this->page->setContentFile(__DIR__.'/../Errors/418.php');
		
		$this->page->addVar('title', 'Erreur 418 — je suis une théière !');
		$this->page->addVar('categorieCSS', 'erreur');
		
		$this->addHeader('HTTP/1.0 418 I\'m a teapot');
		$this->page->addVar('texte', $variable);
				
		$this->send();
	}
	
	public function redirectBDD($variable)
	{
		$this->page = new Page($this->app);
		$this->page->setContentFile(__DIR__.'/../Errors/BDD.php');
		
		$this->page->addVar('title', 'Erreur de connexion avec la base de données');
		$this->page->addVar('categorieCSS', 'erreur');
		
		$this->page->addVar('ERREUR_BDD', 'true');
		$this->page->addVar('texte', $variable);
		
		$this->send();
	}

	public function send()
	{
		// Actuellement, cette ligne a peu de sens dans votre esprit.
		// Promis, vous saurez vraiment ce qu'elle fait d'ici la fin du chapitre
		// (bien que je suis sûr que les noms choisis sont assez explicites !).
		exit($this->page->getGeneratedPage());
	}

	public function setPage(Page $page)
	{
		$this->page = $page;
	}

	// Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true.
	public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
	{
		setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}
}