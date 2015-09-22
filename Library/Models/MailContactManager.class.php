<?php
namespace Library\Models;
 
use Library\Entities\MailContact;
/**
 * Classe abstraite représentant le manager de MailContact indépendament de la méthode de connexion à la BdD.
 * @author Siméon
 * @date 11/07/2015
 *
 */
abstract class MailContactManager extends \Library\Manager
{
	/**
	* Méthode permettant d'ajouter un contact.
	* @param MailContact $contact Le contact à ajouter
	* @return void
	*/
	abstract public function add(\Library\Entities\MailContact $contact);

	/**
	* Méthode renvoyant le nombre de contact.
	* @return int Le nombre de contact
	*/
	abstract public function count();
	
	/**
	* Méthode permettant de supprimer définitivement un contact.
	* @param int $id id du contact à supprimer.
	* @return void
	*/
	abstract public function delete($id);
	
		
	/**
	* Méthode retournant une liste de contact demandé.
	* @param $debut int Le premiercontact à sélectionner
	* @param $limite int Le nombre de contact à sélectionner
	* @param $initial bool/string False sélectionne tout les contact. Sinon, sélectionne les contacts commençant par la chaîne de caractère envoyé.
	* @return array La liste des contact. Chaque entrée est une instance de MailContact.
	*/
	abstract public function getListe($debut = -1, $limite = -1, $initial = false);

	/**
	* Méthode retournant un contact précis.
	* @param int $id L'identifiant du contact à récupérer
	* @return MailContact Le contact demandé
	*/
	abstract public function getUnique($id);
	
		
	/**
	* Méthode permettant de modifier un contact.
	* @param MailContact $contact Le contact à modifier
	* @return void
	*/
	abstract public function update(\Library\Entities\MailContact $contact);
	
		
	/**
   * Méthode permettant d'enregistrer un contact.
   * @param MailContact $contact le contact à enregistrer
   * @see self::add()
   * @see self::update()
   * @return void
   */
	public function save(\Library\Entities\MailContact $contact)
	{
		/*if ($page->estValide())
		{*/
		  ($contact->id() > 0) ? $this->update($contact) : $this->add($contact);
		/*}
		else
		{
		  throw new RuntimeException('La page doit être valide pour être enregistrée');
		}*/
	}
}