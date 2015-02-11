<?php
namespace Applications\Backend\Modules\EspaceMembre;
 
class EspaceMembreController extends \Library\BackController
{
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$user = $this->app->user();
		
		$this->page->addVar('title', 'Votre espace membre');
		$this->page->addVar('categorieCSS', 'membres');
		$this->page->addVar('user', $user);
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
		
		foreach ($modulePage as $module)
		{
			try {
				$user->membre()->groupeObjet()->droits($module['droit']);
			}
			catch (\Exception $e)
			{
				$this->app->httpResponse()->redirect418($e->getMessage());
			}
		}
		
		// Recherche du manuel du zCode
		$listeFichiers = scandir('fichiers/autre/documents');
		$version;
		$fichierZCode = array();
		foreach ($listeFichiers as $fichier)
		{
			if (preg_match("#^manuel_zcode_v([0-9.]+).pdf$#", $fichier,$version))
			{
				$fichierZCode[$version[1]] = $fichier;
			}
		}
		
		uasort($fichierZCode,'version_compare'); // Trie le tableau par numéro de vesion
		
		$this->page->addVar('fichier', end($fichierZCode));
		$this->page->addVar('version', key($fichierZCode));
	}
}