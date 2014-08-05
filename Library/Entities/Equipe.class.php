<?php
/**
 * Classe représentant une personne de l'équipe 
 * @author Siméon Capy
 * @version 1.0
 */
namespace Library\Entities;
 
/**
 * Attention, le nom de cette classe est un faux amis, elle représente UN membre d'une équipe. C'est un tableau de Equipe qui forme une équipe.
 * Cette classe regroupe l'id de la personne, l'année de sa présence dans l'équipe, sa fonction, une description et une photo.
 * Si lapersonne n'a pas de compte, une syntaxe particulière devra être employée dans la description pour afficher son prénom et nom.
 * @author Siméon
 *
 */
class Equipe extends \Library\Entity
{
  protected $id,
			$archive,
            $membre,
            $fonction,
            $description,
            $photo;
   
    
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
	
	public function setArchive($archive)
	{
		if(preg_match("#^[0-9]{4}/[0-9]{4}$#", $archive))
			$this->archive = $archive;
		else
			$this->archive = '0000/0000';
	}

	public function setMembre(\Library\Entities\Membre $membre)
	{
		$this->membre = $membre;
	}

	public function setFonction($fct)
	{
		$this->fonction = $fct;
	}

	public function setDescription($description)
	{
	$this->description = $description;
	}

	public function setPhoto($photo)
	{
		$this->photo = $photo;
	}

   
  // ---------------------------------------
  // ************** GETTERS ****************
  // ---------------------------------------
   
	public function id()
	{
		return $this->id;
	}
	
	public function archive()
	{
		return $this->archive;
	}

	public function membre()
	{
		return $this->membre;
	}

	public function fonction()
	{
		return $this->fonction;
	}

	public function description()
	{
		return $this->description;
	}

	public function photo()
	{
		return $this->photo;
	}
}