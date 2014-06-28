<?php

namespace Library\Entities;
 
class Partenaire extends \Library\Entity
{
	 protected	$nom,
				$description,
				$dateAjout,
				$dateModif,
				$image;
				
	const ERR_NOM = 1;
	const ERR_DESCRIPTION = 2;
	const ERR_IMAGE = 3;
	
	const IMAGE_DEFAUT = '0.png';
				
	/**
	* Constructeur de la classe qui assigne les données spécifiées en paramètre aux attributs correspondants.
	* @param $valeurs array Les valeurs à assigner
	* @return void
	*/
	public function __construct($valeurs = array())
	{
		if (!empty($valeurs)) // Si on a spécifié des valeurs, alors on hydrate l'objet.
		{
			if(!($valeurs['dateAjout'] instanceof \DateTime)) // Si la création de l'objet a été réalisée automatiquement par PDO, il faut alors créer l'objet DateTime
				$valeurs['dateAjout'] = new \DateTime($valeurs['dateAjout']);
			if(!($valeurs['dateModif'] instanceof \DateTime))
				$valeurs['dateModif'] = new \DateTime($valeurs['dateModif']);
			$this->hydrate($valeurs);
		}
	}
	   

  // ----------------------------------------
  // ************** MÉTHODES ****************
  // ----------------------------------------
  
  public function existe()
  {
    return !empty($this->id);
  }
   
  /**
   * Méthode permettant de savoir si le partenaire est valide.
   * @return bool
   */
  public function estValide()
  {
    return !(empty($this->nom) || empty($this->description)); // C'est l'essentiel
  }
  
  
  // ---------------------------------------
  // ************** SETTERS ****************
  // ---------------------------------------
  
  public function setId($id)
  {
	$this->id = (int) $id;
  }
  
  public function setNom($nom)
  {
    if (!is_string($nom) || empty($nom))
      $this->erreurs[] = self::ERR_NOM;
    else
      $this->nom = $nom;
  }
  
  public function setDescription($desc)
  {
    if (!is_string($desc) || empty($desc))
      $this->erreurs[] = self::ERR_DESCRIPTION;
    else
      $this->description = $desc;
  }
  
  public function setImage($avatar)
  {
	if (!is_string($avatar))
      $this->erreurs[] = self::ERR_IMAGE;
    else
	{
	 if(empty($avatar))
		$this->image = self::IMAGE_DEFAUT;
	 else
		$this->image = $avatar;
	}
  }
  
  public function setDateAjout(\DateTime $date)
  {
    $this->dateAjout = $date;
  }
  
  public function setDateModif(\DateTime $date)
  {
    $this->dateModif = $date;
  }
  
  // ---------------------------------------
  // ************** GETTERS ****************
  // ---------------------------------------
  
  public function erreurs()
  {
    return $this->erreurs;
  }
  
  public function id()
  {
	return $this->id;
  }
  
	public function nom()
	{
		return $this->nom;
	}
	
	public function description()
	{
		return $this->description;
	}
	
	public function image()
	{
		return $this->image;
	}
	
	public function dateAjout()
	{
		return $this->dateAjout;
	}
	
	public function dateModif()
	{
		return $this->dateModif;
	}
}