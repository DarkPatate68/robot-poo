<?php
namespace Library\Models;
 
abstract class PageArchivableManager extends \Library\Manager
{
	/**
	* Méthode permettant d'ajouter une page.
	* @param $page PageArchivable La page à ajouter
	* @return void
	*/
	abstract public function add(\Library\Entities\PageArchivable $page);

	/**
	* Méthode renvoyant le nombre de page archivables.
	* @return int Le nombre de page
	*/
	abstract public function count();
	
	/**
	* Méthode renvoyant le nombre de page archivables d'un même URL.
	* @param $url string Lurl des pages concernés
	* @return int
	*/
	abstract public function countUrl($url);

	/**
	* Méthode permettant de supprimer une page.
	* @param $id int L'identifiant de la page à supprimer
	* @return void
	*/
	abstract public function delete($id);
	
	/**
	* Méthode permettant de supprimer toutes les pages avec un même URL
	* @param $url string L'url de la page à supprimer
	* @return void
	*/
	abstract public function deleteByUrl($url);
	
	/**
	* Méthode permettant de supprimer la page correspondant à l'URL et à l'archive
	* @param $url string L'url de la page à supprimer
	* @param $archive string L'archive de la page à supprimer
	* @return void
	*/
	abstract public function deleteByUrlAndArchive($url, $archive);
	
	/**
	* Méthode permettant de supprimer la page correspondant à l'URL et au titre
	* @param $url string L'url de la page à supprimer
	* @param $titre string Le titre de la page à supprimer
	* @return void
	*/
	abstract public function deleteByUrlAndTitre($url, $titre);

	/**
	* Méthode retournant une liste des pages demandées.
	* @return array La liste des pages. Chaque entrée est une instance de PageFixe.
	*/
	abstract public function getListe(\Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode retournant une liste des années d'une page demandées.
	* @return array La liste des années.
	*/
	abstract public function getListeAnnees($url);
	

	/**
	* Méthode retournant une page précise.
	* @param $id int L'identifiant de la page à récupérer
	* @return Membre Le membre demandé
	*/
	abstract public function getUnique($id, \Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode retournant une groupe de page
	* @param $url string L'url de la page à récupérer
	* @return Membre Le membre demandé
	*/
	abstract public function getUniqueByUrl($url, \Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode retournant une page précises.
	* @param $url string L'url de la page à récupérer
	* @param $archive string L'annee
	* @return Membre Le membre demandé
	*/
	abstract public function getUniqueByUrlAndArchive($url, $archive, \Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode retournant une page précise.
	* @param $url string L'url de la page à récupérer
	* @param $titre string Le titre
	* @return Membre Le membre demandé
	*/
	abstract public function getUniqueByUrlAndTitre($url, $titre, \Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode permettant de modifier une page.
	* @param $page PageArchivable la page à modifier
	* @return void
	*/
	abstract public function update(\Library\Entities\PageArchivable $page);
	
	/**
	* Méthode permettant de changer le titre et/ou l'url d'un groupe de page
	* @param $ancUrl string L'ancien URL
	* @param $ancTitre string L'ancien titre
	* @param $nvUrl string Le nouvel URL
	* @param $nvTitre string Le nouveau titre
	* @return void
	*/
	abstract public function updateUrlAndTitre($ancUrl, $nvUrl, $ancTitre, $nvTitre);
	
	/**
   * Méthode permettant d'enregistrer une page.
   * @param $page PageArchivable la page à enregistrer
   * @see self::add()
   * @see self::modify()
   * @return void
   */
	public function save(\Library\Entities\PageArchivable $page)
	{
		/*if ($page->estValide())
		{*/
		  ($page->id() > 0) ? $this->update($page) : $this->add($page);
		/*}
		else
		{
		  throw new RuntimeException('La page doit être valide pour être enregistrée');
		}*/
	}
}