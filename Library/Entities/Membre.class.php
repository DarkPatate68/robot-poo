<?php

namespace Library\Entities;
 
class Membre extends \Library\Entity
{
	 protected	$nom,
				$prenom,
				$pseudo,
				$usuel,
				$avatar,
				$section,
				$actif,
				$valide, // lire validÉ, un membre validé a été approuvé par le bureau
				$biographie,
				$dateInscription,
				$courriel,
				$groupe,
				$groupeObjet,
				$tshirt,
				$telephone,
				$mdp;
				
	const ERR_NOM = 1;
	const ERR_PRENOM = 2;
	const ERR_PSEUDO = 3;
	const ERR_USUEL = 4;
	const ERR_SECTION = 5;
	const ERR_BIOGRAPHIE = 6;
	const ERR_INSCRIPTION = 7;
	const ERR_COURRIEL = 8;
	const ERR_GROUPE = 9;
	const ERR_AVATAR = 10;
	const ERR_MDP = 11;
	const ERR_TSHIRT = 11;
	
	const USUEL_PRENOM_NOM = 'USUEL_PRENOM_NOM';
	const USUEL_PSEUDO = 'USUEL_PSEUDO';
	
	const AVATAR_DEFAUT = '0.png';
				
	/**
	* Constructeur de la classe qui assigne les données spécifiées en paramètre aux attributs correspondants.
	* @param $valeurs array Les valeurs à assigner
	* @return void
	*/
	public function __construct($valeurs = array())
	{
		if (!empty($valeurs)) // Si on a spécifié des valeurs, alors on hydrate l'objet.
		{
			if(!($valeurs['dateInscription'] instanceof \DateTime))
				$valeurs['dateInscription'] = new \DateTime($valeurs['dateInscription']);
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
   * Méthode permettant de savoir si la news est valide.
   * @return bool
   */
  public function estValide()
  {
    return !((empty($this->nom) && empty($this->prenom) && empty($this->pseudo)) || empty($this->dateInscription) || empty($this->courriel));
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
  
  public function setPrenom($prenom)
  {
    if (!is_string($prenom) || empty($prenom))
      $this->erreurs[] = self::ERR_PRENOM;
    else
      $this->prenom = $prenom;
  }
  
  public function setPseudo($pseudo)
  {
    if (!is_string($pseudo) || empty($pseudo))
      $this->erreurs[] = self::ERR_PRENOM;
    else
      $this->pseudo = $pseudo;
  }
  
  public function setUsuel($usuel)
  {
    /*if ($usuel == self::USUEL_PRENOM_NOM || $usuel == 'USUEL_PRENOM_NOM')
		$this->usuel = $this->prenom . ' ' . $this->nom;
    else if($usuel == self::USUEL_PSEUDO || $usuel == 'USUEL_PSEUDO')
		$this->usuel = $this->pseudo;
	else
		$this->erreurs[] = self::ERR_USUEL;*/
		
	if (!is_string($usuel) || empty($usuel))
      $this->erreurs[] = self::ERR_USUEL;
    else
      $this->usuel = $usuel;
  }
  
  public function setAvatar($avatar)
  {
	if (!is_string($avatar))
      $this->erreurs[] = self::ERR_AVATAR;
    else
	{
	 if(empty($avatar))
		$this->avatar = self::AVATAR_DEFAUT;
	 else
		$this->avatar = $avatar;
	}
  }
  
  public function setSection($section)
  {
    if (Section::valide($section))
		$this->section = $section;
    else
		$this->erreurs[] = self::ERR_SECTION;
  }
  
  public function setActif($actif)
  {
	$this->actif = (bool) $actif;
  }
  
  public function setValide($valide)
  {
	$this->valide = (bool) $valide;
  }
  
  public function setBiographie($bio)
  {
    if (!is_string($bio))
      $this->erreurs[] = self::ERR_BIOGRAPHIE;
    else
      $this->biographie = $bio;
  }
  
  public function setDateInscription(\DateTime $date)
  {
    $this->dateInscription = $date;
  }
  
  public function setCourriel($courriel)
  {
	if(is_string($courriel) && preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,}$#', $courriel))
		$this->courriel = $courriel;
	else
		$this->erreurs[] = self::ERR_COURRIEL;
  }
  
  public function setGroupe($groupe)
  {
	$this->groupe = $groupe;
  }
  
  public function setGroupeObjet(\Library\Entities\Groupe $groupe)
  {
	$this->groupeObjet = $groupe;
  }
  
  public function setTshirt($tshirt)
  {
    if (Tshirt::valide($tshirt))
		$this->tshirt = $tshirt;
    else
	{
		$this->erreurs[] = self::ERR_TSHIRT;
		$this->tshirt = 'NON DÉFINI';
	}
  }
  
  public function setTelephone($telephone)
  {
	if (preg_match("#^(\+33|0)[1-9]([-. ]?[0-9]{2}){4}$#", $telephone))
		$this->telephone = $telephone;
	else
		$this->telephone = '';
  }
  
  public function setMdp($mdp)
  {
	$this->mdp = (string) $mdp;
  }
  
  public function setMdpChiffre($mdp)
  {
	$mdp = \Applications\Backend\Modules\Connexion\ConnexionController::chiffrer((string) $mdp);;
	$this->mdp = (string) $mdp;
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
	
	public function prenom()
	{
		return $this->prenom;
	}
	
	public function pseudo()
	{
		return $this->pseudo;
	}
	
	public function usuel()
	{
		if ($this->usuel == self::USUEL_PRENOM_NOM || $this->usuel == 'USUEL_PRENOM_NOM')
			return $this->prenom . ' ' . $this->nom;
		else// if($usuel == self::USUEL_PSEUDO || $usuel == 'USUEL_PSEUDO')
			return $this->pseudo;
		//return $this->usuel;
	}
	
	public function usuelCode()
	{
		return $this->usuel;
	}
	
	public function avatar()
	{
		return $this->avatar;
	}
	
	public function section()
	{
		return $this->section;
	}
	
	public function actif()
	{
		return $this->actif;
	}
	
	public function valide()
	{
		return $this->valide;
	}
	
	public function biographie()
	{
		return $this->biographie;
	}
	
	public function dateInscription()
	{
		return $this->dateInscription;
	}
	
	public function courriel()
	{
		return $this->courriel;
	}
	
	public function groupe()
	{
		return $this->groupe;
	}
	
	public function groupeObjet()
	{
		return $this->groupeObjet;
	}
	
	public function tshirt()
	{
		return $this->tshirt;
	}
	
	public function telephone()
	{
		return $this->telephone;
	}
	
	public function mdp()
	{
		return $this->mdp;
	}
}