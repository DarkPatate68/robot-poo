<?php
/**
 * Classe représentant une page fixe (page qui n'est pas archivable), pour le club robotique de l'INSA de Strasbourg (CRIS)
 * Dans toute la partie programation, elles sont appelées PAGE FIXE ; du côté utilisateur, elles sont appelées PAGE(S) NON-ARCHIVABLE(S)
 * @author Siméon Capy
 * @version 1.0
 */
namespace Library\Entities;
 
class PageFixe extends \Library\Entity
{
  protected $id,
			$url,
            $titre,
            $texte,
            $dateModif,
            $editeur;
   
    
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
}