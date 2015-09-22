<?php
namespace Library\Models;
 
use Library\Entities\MailReception;
use Library\Entities\Mail;
/**
 * Classe abstraite représentant le manager de MailReception indépendament de la méthode de connexion à la BdD.
 * @author Siméon
 * @date 08/07/2015
 *
 */
abstract class MailReceptionManager extends \Library\Manager
{
	/**
	* Méthode permettant d'ajouter un mail.
	* @param MailReception $mail Le mail à ajouter
	* @return void
	*/
	abstract public function add(\Library\Entities\MailReception $mail);

	/**
	* Méthode renvoyant le nombre de mail reçus.
	* @param  bool $nonLu Mettre à true pour compter uniquement les mails non lus.
	* @return int Le nombre de mail
	*/
	abstract public function count($nonLu = false);
	
	/**
	* Méthode permettant de supprimer définitivement les mails dans la corbeille.
	* @return void
	*/
	abstract public function delete();
	
	/**
	* Méthode permettant de mettre un mail dans la corbeille
	* @param int $id L'identifiant du mail
	* @param bool $retirer Mettre à true pour retirer le mail de la corbeille.
	* @return void
	*/
	abstract public function corbeille($id, $retirer = false);
	
	/**
	 * Méthode permettant de changer la valeur 'lu' d'un mail
	 * @param int $id L'identifiant du mail
	 * @param bool $valeur Mettre à true que mail soit lu.
	 * @return void
	 */
	abstract public function lu($id, $lu = true);
	
	
	/**
	* Méthode retournant une liste de mail demandé.
	* @param $debut int Le premier mail à sélectionner
	* @param $limite int Le nombre de mail à sélectionner
	* @param $corbeille bool True : sélectionne les mails dans la corbeille, False sélectionne les autres.
	* @return array La liste des mails. Chaque entrée est une instance de MailReception.
	*/
	abstract public function getListe($debut = -1, $limite = -1, $corbeille = false);

	/**
	* Méthode retournant un mail précis.
	* @param int $id L'identifiant de la page à récupérer
	* @return MailReception Le mail demandé
	*/
	abstract public function getUnique($id);
	
		
	///**
	//* Méthode permettant de modifier une page.
	//* @param PageArchivable $page La page à modifier
	//* @return void
	//*/
	//abstract public function update(\Library\Entities\PageArchivable $page);
	
		
	/**
   * Méthode permettant d'enregistrer un mail.
   * @param MailReception $mail le mail à enregistrer
   * @see self::add()
   * @see self::update()
   * @return void
   */
	public function save(\Library\Entities\MailReceptione $mail)
	{
		/*if ($page->estValide())
		{*/
		  ($mail->id() > 0) ? $this->update($mail) : $this->add($mail);
		/*}
		else
		{
		  throw new RuntimeException('La page doit être valide pour être enregistrée');
		}*/
	}
}