<?php
namespace Library\Models;
 
use Library\Entities\PageArchivable;
/**
 * Classe abstraite représentant le manager de PageArchivable indépendament de la méthode de connexion à la BdD.
 * @author Siméon
 * @date 29/06/2014
 *
 */
abstract class PageArchivableManager extends \Library\Manager
{
	/**
	* Méthode permettant d'ajouter une page.
	* @param PageArchivable $page La page à ajouter
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
	* @param string $url L'url des pages concernés
	* @return int
	*/
	abstract public function countUrl($url);

	/**
	* Méthode permettant de supprimer une page à partir de son identifiant.
	* @param int $id L'identifiant de la page à supprimer
	* @return void
	*/
	abstract public function delete($id);
	
	/**
	* Méthode permettant de supprimer toutes les pages avec un même URL
	* @param string $url L'url de la (des) pages à supprimer
	* @return void
	*/
	abstract public function deleteByUrl($url);
	
	/**
	* Méthode permettant de supprimer la page correspondant à l'URL et à l'archive
	* @param string $url L'url de la page à supprimer
	* @param string $archive L'archive de la page à supprimer
	* @return void
	*/
	abstract public function deleteByUrlAndArchive($url, $archive);
	
	/**
	* Méthode permettant de supprimer la page correspondant à l'URL et au titre
	* @deprecated L'effet est le même que self::deleteByUrl car toutes les pages ayant le même URL ont le même titre
	* @param string $url L'url de la page à supprimer
	* @param string $titre Le titre de la page à supprimer
	* @return void
	*/
	abstract public function deleteByUrlAndTitre($url, $titre);

	/**
	* Méthode retournant la liste de toutes les pages.
	* @param \Library\Models\MembreManager $membreManager Permet de chercher les informations du memebre correspondants (nom, classe...)
	* @return array La liste des pages. Chaque entrée est une instance de PageFixe.
	*/
	abstract public function getListe(\Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode retournant une liste des années de toutes les pages ayant le même URL.
	* @param string $url L'url des pages.
	* @return array La liste des années. Attention, c'est un tableau à deux dimensions.
	*/
	abstract public function getListeAnnees($url);
	

	/**
	* Méthode retournant une page précise.
	* @param int $id L'identifiant de la page à récupérer
	* @param \Library\Models\MembreManager $membreManager Permet de chercher les informations du memebre correspondants (nom, classe...)
	* @return PageArchivable La page demandée
	*/
	abstract public function getUnique($id, \Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode retournant une groupe de page
	* @param string $url L'url de la (des) pages à récupérer
	* @param \Library\Models\MembreManager $membreManager Permet de chercher les informations du memebre correspondants (nom, classe...)
	* @return array[PageArchivable] La liste des pages demandées
	*/
	abstract public function getUniqueByUrl($url, \Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode retournant une page précise à partir de son URL et de son archive.
	* @param string $url L'url de la page à récupérer
	* @param strings $archive L'année de la page
	* @param \Library\Models\MembreManager $membreManager Permet de chercher les informations du memebre correspondants (nom, classe...)
	* @return PageArchivable La page demandée
	*/
	abstract public function getUniqueByUrlAndArchive($url, $archive, \Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode retournant un groupe de page.
	* @deprecated L'effet est le même que self::getUniqueByUrl car toutes les pages ayant le même URL ont le même titre
	* @param string $url L'url de la page à récupérer
	* @param string $titre Le titre
	* @param \Library\Models\MembreManager $membreManager Permet de chercher les informations du memebre correspondants (nom, classe...)
	* @return Membre Le membre demandé
	*/
	abstract public function getUniqueByUrlAndTitre($url, $titre, \Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode permettant de modifier une page.
	* @param PageArchivable $page La page à modifier
	* @return void
	*/
	abstract public function update(\Library\Entities\PageArchivable $page);
	
	/**
	* Méthode permettant de changer le titre et/ou l'url d'un groupe de page
	* @param string $ancUrl L'ancien URL
	* @param string $ancTitre L'ancien titre
	* @param string $nvUrl Le nouvel URL
	* @param string $nvTitre Le nouveau titre
	* @return void
	*/
	abstract public function updateUrlAndTitre($ancUrl, $nvUrl, $ancTitre, $nvTitre);
	
	/**
   * Méthode permettant d'enregistrer une page.
   * @param PageArchivable $page la page à enregistrer
   * @see self::add()
   * @see self::update()
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