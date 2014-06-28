<?php
namespace Library\Models;
 
abstract class PartenaireManager extends \Library\Manager
{
	/**
	* Méthode permettant d'ajouter un partenaire.
	* @param $partenaire Partenaire Le partenaire à ajouter
	* @return void
	*/
	abstract public function add(\Library\Entities\Partenaire $partenaire);

	/**
	* Méthode renvoyant le nombre de partenaire.
	* @return int
	*/
	abstract public function count();

	/**
	* Méthode permettant de supprimer un partenaire.
	* @param $id int L'identifiant du partenaire à supprimer
	* @return void
	*/
	abstract public function delete($id);

	/**
	* Méthode retournant une liste des partenaires demandées.
	* @return array La liste des partenaire. Chaque entrée est une instance de Partenaire.
	*/
	abstract public function getListe();
	

	/**
	* Méthode retournant un partenaire précis.
	* @param $id int L'identifiant du partenaire à récupérer
	* @return Partenaire Le partenaire demandé
	*/
	abstract public function getUnique($id);
	
	/**
	* Méthode permettant de modifier un partenaire.
	* @param $partenaire Partenaire le partenaire à modifier
	* @return void
	*/
	abstract public function update(\Library\Entities\Partenaire $partenaire);
	
	/**
   * Méthode permettant d'enregistrer un partenaire.
   * @param $partenaire Partenaire le partenaire à enregistrer
   * @see self::add()
   * @see self::modify()
   * @return void
   */
	public function save(\Library\Entities\Partenaire $partenaire)
	{
		if ($partenaire->estValide())
		{
		  ($partenaire->id() > 0) ? $this->update($partenaire) : $this->add($partenaire);
		}
		else
		{
		  throw new RuntimeException('Le partenaire doit être valide pour être enregistré');
		}
	}
}