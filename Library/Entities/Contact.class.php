<?php
/**
 * Classe abstraite représentant un conatct de la messagerie.
 * @author Siméon Capy
 * @version 1.0
 */
namespace Library\Entities;
 
abstract class Contact extends \Library\Entity
{
  protected $id,
			$nom,
            $adresse,
            $commentaire;
   
    
  /**
   * Constructeur de la classe qui assigne les données spécifiées en paramètre aux attributs correspondants.
   * @param $valeurs array Les valeurs à assigner
   * @return void
   */
	public function __construct($valeurs = array())
	{
		if (!empty($valeurs)) // Si on a spécifié des valeurs, alors on hydrate l'objet.
		{
			$this->hydrate($valeurs);
		}
	}
  
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

	public function setAdresse($add)
	{
		$this->adresse = (string) $add;
	}

	public function setCommentaire($com)
	{
		$this->commentaire = $com;
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