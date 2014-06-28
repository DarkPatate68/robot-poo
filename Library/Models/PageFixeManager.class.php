<?php
namespace Library\Models;
 
abstract class PageFixeManager extends \Library\Manager
{
	/**
	* Méthode permettant d'ajouter une page.
	* @param $page PageFixe La page à ajouter
	* @return void
	*/
	abstract public function add(\Library\Entities\PageFixe $page);

	/**
	* Méthode renvoyant le nombre de page fixes.
	* @return int
	*/
	abstract public function count();

	/**
	* Méthode permettant de supprimer une page.
	* @param $id int L'identifiant de la page à supprimer
	* @return void
	*/
	abstract public function delete($id);

	/**
	* Méthode retournant une liste des pages demandées.
	* @return array La liste des pages. Chaque entrée est une instance de PageFixe.
	*/
	abstract public function getListe(\Library\Models\MembreManager $membreManager = null);
	

	/**
	* Méthode retournant un membre précis.
	* @param $id int L'identifiant de la page à récupérer
	* @return Membre Le membre demandé
	*/
	abstract public function getUnique($id, \Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode retournant un membre précis.
	* @param $url string L'url de la page à récupérer
	* @return Membre Le membre demandé
	*/
	abstract public function getUniqueByUrl($url, \Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode permettant de modifier une page.
	* @param $page PageFixe la page à modifier
	* @return void
	*/
	abstract public function update(\Library\Entities\PageFixe $page);
	
	/**
   * Méthode permettant d'enregistrer une page.
   * @param $page PageFixe la page à enregistrer
   * @see self::add()
   * @see self::modify()
   * @return void
   */
	public function save(\Library\Entities\PageFixe $page)
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