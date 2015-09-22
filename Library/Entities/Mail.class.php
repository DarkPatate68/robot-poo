<?php
/**
 * Classe abstraite représentant un mail, deux classes filles existent, une pour un message reçu, l'autre pour un message envoyé.
 * @author Siméon Capy
 * @version 1.0
 */
namespace Library\Entities;
 
abstract class Mail extends \Library\Entity
{
  protected $id,
			$date,
            $objet,
            $texte,
  			$corbeille;
   
    
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
	
	public function setDate(\DateTime $date)
	{
		$this->date = $date;
	}

	public function setObjet($objet)
	{
		$this->objet = (string) $objet;
	}

	public function setTexte($texte)
	{
		$this->texte = $texte;
	}
	
	public function setCorbeille($corbeille)
	{
		$this->corbeille = (bool) $corbeille;
	}

	   
  // ---------------------------------------
  // ************** GETTERS ****************
  // ---------------------------------------
   
	public function id()
	{
		return $this->id;
	}
	
	public function date()
	{
		return $this->date;
	}

	public function objet()
	{
		return $this->objet;
	}

	public function texte()
	{
		return $this->texte;
	}
	
	public function corbeille()
	{
		return $this->corbeille;
	}
}