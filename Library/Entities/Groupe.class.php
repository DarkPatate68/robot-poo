<?php

namespace Library\Entities;
 
class Groupe extends \Library\Entity
{
	 protected 	$nom,
				$droits = array();
				
	const ERR_NOM = 1;
	const ERR_DROIT = 2;
	
				
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
   
  /**
   * Méthode assignant les valeurs spécifiées aux attributs correspondant.
   * @param $donnees array Les données à assigner
   * @return void
   */
  public function hydrate(array $donnees)
  {
    foreach ($donnees as $attribut => $valeur)
    {
		if($attribut == 'id' || $attribut == 'nom')
		{
			$methode = 'set'.ucfirst($attribut);
			
			  if (is_callable(array($this, $methode)))
				$this->$methode($valeur);
		}
		else
		{
			$this->setDroits($attribut, $valeur);
		}

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
   * Méthode permettant de savoir si le groupe est valide.
   * @return bool
   */
  public function estValide()
  {
    return !(empty($this->nom) && empty($droits));
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
  
  public function setDroits($categorie, $droit)
  {
    $this->droits[(string) $categorie] = (bool) $droit;
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
	
	public function droits($categorie = false)
	{
		if(!$categorie)
			return $this->droits;
		else if(!is_string($categorie) || !array_key_exists($categorie, $this->droits))
			throw new \InvalidArgumentException('La catégorie d\'administration n\'existe pas, ou n\'est pas une chaîne de caractère');
		else
			return $this->droits[$categorie];
	}
}