<?php
/**
 * Classe représentant une page archivable, càd qui change en fonction de l'année choisie, pour le club robotique de l'INSA de Strasbourg (CRIS)
 * @author Siméon Capy
 * @version 1.0
 */
namespace Library\Entities;
 
class PageArchivable extends \Library\Entity
{
  protected $id,
			$url,
            $titre,
            $texte,
            $dateModif,
            $editeur,
			$archive;
   
    
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
	
	public function setUrl($url)
	{
		$this->url = (string) $url;
	}

	public function setTitre($titre)
	{
		$this->titre = (string) $titre;
	}

	public function setTexte($texte)
	{
		$this->texte = $texte;
	}

	public function setDateModif(\DateTime $dateModif)
	{
	$this->dateModif = $dateModif;
	}

	public function setEditeur(\Library\Entities\Membre $editeur)
	{
		$this->editeur = $editeur;
	}
	
	public function setArchive($archive)
	{
		if(preg_match("#^[1-9]{4}/[1-9]{4}$#", $archive))
			$this->texte = $archive;
		else
			$this->texte = '0000/0000';
	}

   
  // ---------------------------------------
  // ************** GETTERS ****************
  // ---------------------------------------
   
	public function id()
	{
		return $this->id;
	}
	
	public function url()
	{
		return $this->url;
	}

	public function titre()
	{
		return $this->titre;
	}

	public function texte()
	{
		return $this->texte;
	}

	public function dateModif()
	{
		return $this->dateModif;
	}

	public function editeur()
	{
		return $this->editeur;
	}

	public function editeurId()
	{
		return $this->editeur->id();
	}
	
	public function archive()
	{
		return $this->archive;
	}
}