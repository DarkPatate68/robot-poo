<?php
namespace Applications\Backend\Modules\EspaceMembre;
 
class EspaceMembreController extends \Library\BackController
{
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$this->page->addVar('title', 'Votre espace membre');
		$this->page->addVar('categorieCSS', 'membres');
		$this->page->addVar('user', $this->app->user());
		$this->page->addVar('design', 'espaceMembre.css');
		
		$xml = new \DOMDocument;
		$xml->load(__DIR__.'/../../Config/modules.xml');

		$modules = $xml->getElementsByTagName('module');
		$modulePage = array();
		
		
		// On parcourt les routes du fichier XML.
		foreach ($modules as $module)
		{
			$vars = array('url' => $module->getAttribute('url'), 
						  'nom' => $module->getAttribute('nom'), 
						  'image' => $module->getAttribute('image'), 
						  'droit' => $module->getAttribute('droit'),
						  'groupe' => $module->getAttribute('groupe')); // groupe d'icone du panneau de l'espace membre (profil gestion, admin...)
			$modulePage[] = $vars;
		}
		
		$this->page->addVar('modules', $modulePage);
	}
}