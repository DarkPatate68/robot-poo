<?php
/**
 * Classe abstraite représentant un contact du club.
 * @author Siméon Capy
 * @version 1.0
 */
namespace Library\Entities;
 
abstract class MailContact
{
  protected $id,
  			$nom,
  			$adresse,
  			$commentaire;
   
  // ----------------------------------------
  // ************** MÉTHODES ****************
  // ----------------------------------------
   
		// Néant
   
  // ---------------------------------------
  // ************** SETTERS ****************
  // ---------------------------------------
  
	public function setId($id)
	{
		$this->id = (int) $id;
	}
  
	public function setNom($nom)
	{
		$this->nom = (string) $nom;
	}
	
	public function setAdresse($adr)
	{
		$this->adresse = (string) $adr;
	}
	
	public function setCommentaire($com)
	{
		$this->commentaire = (string) $com;
	}

		   
  // ---------------------------------------
  // ************** GETTERS ****************
  // ---------------------------------------
   
	public function id()
	{
		return $this->id;
	}
	
	public function nom()
	{
		return $this->nom;
	}
	
	public function adresse()
	{
		return $this->adresse;
	}
	
	public function commentaire()
	{
		return $this->commentaire;
	}
}