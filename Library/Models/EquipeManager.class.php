<?php
namespace Library\Models;
 
use Library\Entities\Equipe;
/**
 * Classe abstraite représentant le manager de PageArchivable indépendament de la méthode de connexion à la BdD.
 * @author Siméon
 * @date 29/06/2014
 *
 */
abstract class EquipeManager extends \Library\Manager
{
	/**
	* Méthode permettant d'ajouter une page.
	* @param PageArchivable $page La page à ajouter
	* @return void
	*/
	abstract public function add(\Library\Entities\PageArchivable $page);

	/**
	* Méthode renvoyant le nombre de personnes dans toutes les équipes.
	* Méthodes génériques mais inutile. Ici la donnée envoyée ne représente rien de concret.
	* @return int 
	*/
	abstract public function count();
	
	/**
	* Méthode renvoyant le nombre de personne dans l'équipe de l'année indiquée.
	* @param string $annee L'année concernés
	* @return int
	*/
	abstract public function countAnnee($annee);

	/**
	* Méthode permettant de supprimer une personne à partir de son identifiant.
	* @param int $id L'identifiant de la personne à supprimer dans la liste des équipes
	* @return void
	*/
	abstract public function delete($id);
	
	/**
	* Méthode permettant de supprimer un membre à partir de l'année et de son id (de membre)
	* @param int $membre L'id (membre) de la personne à supprimer
	* @param string $archive L'archive de la personne à supprimer
	* @return void
	*/
	abstract public function deleteByMembreAndArchive($membre, $archive);
	

	/**
	* Méthode retournant la liste de toutes les personnes.
	* @param \Library\Models\MembreManager $membreManager Permet de chercher les informations du memebre correspondants (nom, classe...)
	* @return array La liste des personne. Chaque entrée est une instance de Equipe.
	*/
	abstract public function getListe(\Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode retournant une liste des tous les membres de l'équipe de l'année indiquée.
	* @param string $archive
	* @param \Library\Models\MembreManager $membreManager Permet de chercher les informations du memebre correspondants (nom, classe...) 
	* @return array La liste des personnes.
	*/
	abstract public function getListeByArchive($archive, \Library\Models\MembreManager $membreManager = null);
	
	/**
	 * Retourne la liste de toutes les années possédant une équipe.
	 * @return array Liste des années
	 */
	abstract public function getListeAnnees();
	

	/**
	* Méthode retournant une personne précise.
	* @param int $id L'identifiant de la personne à récupérer
	* @param \Library\Models\MembreManager $membreManager Permet de chercher les informations du memebre correspondants (nom, classe...)
	* @return Equipe La personne demandée
	*/
	abstract public function getUnique($id, \Library\Models\MembreManager $membreManager = null);
	
	/**
	* Méthode retournant une personne à partir de son id et de l'archive
	* @param int $membre L'id de la personne à récupérer
	* @param string $archive L'année
	* @param \Library\Models\MembreManager $membreManager Permet de chercher les informations du memebre correspondants (nom, classe...)
	* @return array[PageArchivable] La liste de la personne demandé
	*/
	abstract public function getUniqueByMembreAndArchive($membre, $archive, \Library\Models\MembreManager $membreManager = null);
	
	
	/**
	 * Retourne l'ID de la personne correspondante
	 * @param int $membre
	 * @param string $archive
	 * @return int L'identifiant
	 */
	abstract public function getId($membre, $archive);
	
	/**
	* Méthode permettant de modifier une personne.
	* @param Equipe $equipe La personne à modifier
	* @return void
	*/
	abstract public function update(\Library\Entities\PageArchivable $page);
	
		
	/**
   * Méthode permettant d'enregistrer une equipe.
   * @param Equipe $equipe la personne à enregistrer
   * @see self::add()
   * @see self::update()
   * @return void
   */
	public function save(\Library\Entities\Equipe $equipe)
	{
		/*if ($page->estValide())
		{*/
		  ($equipe->id() > 0) ? $this->update($equipe) : $this->add($equipe);
		/*}
		else
		{
		  throw new RuntimeException('La page doit être valide pour être enregistrée');
		}*/
	}
}