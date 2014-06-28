<?php
/**
 * Classe représentant une news, pour le club robotique de l'INSA de Strasbourg (CRIS)
 * @author Siméon Capy
 * @version 1.0
 */
namespace Library\Entities;
 
class News extends \Library\Entity
{
  protected $auteur,
            $titre,
            $contenu,
            $dateAjout,
            $dateModif,
			$editeur,  // Représente la dernière personne qui a modifié la news
			$privee,
			$archive,
			$changement; // Dernière news de l'année pour afficher la séparation
   
  /**
   * Constantes relatives aux erreurs possibles rencontrées lors de l'exécution de la méthode.
   */
  const AUTEUR_INVALIDE = 1;
  const TITRE_INVALIDE = 2;
  const CONTENU_INVALIDE = 3;
  const EDITEUR_INVALIDE = 4;
  const ARCHIVE_INVALIDE = 5;
   
   
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
   
  /**
   * Méthode permettant de savoir si la news est nouvelle.
   * @return bool
   */
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
    return !(empty($this->auteur) || empty($this->titre) || empty($this->contenu));
  }
  
  public function titreTiret()
  {
	return str_replace(array('\'', ' ', '.'), '-', self::supprAccent(mb_strtolower($this->titre, 'UTF-8')));
  }
  
  static function supprAccent($str, $charset='utf-8')
  {
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return $str;
  }
   
  // ---------------------------------------
  // ************** SETTERS ****************
  // ---------------------------------------
  
  public function setId($id)
  {
    $this->id = (int) $id;
  }
   
  public function setAuteur(\Library\Entities\Membre $auteur)
  {
    $this->auteur = $auteur;
  }
   
  public function setTitre($titre)
  {
    if (!is_string($titre) || empty($titre))
    {
      $this->erreurs[] = self::TITRE_INVALIDE;
    }
    else
    {
      $this->titre = $titre;
    }
  }
   
  public function setContenu($contenu)
  {
    if (!is_string($contenu) || empty($contenu))
    {
      $this->erreurs[] = self::CONTENU_INVALIDE;
    }
    else
    {
      $this->contenu = $contenu;
    }
  }
   
  public function setDateAjout(\DateTime $dateAjout)
  {
    $this->dateAjout = $dateAjout;
  }
   
  public function setDateModif(\DateTime $dateModif)
  {
    $this->dateModif = $dateModif;
  }
  
  public function setEditeur(\Library\Entities\Membre $editeur)
  {
    $this->editeur = $editeur;
  }
  
  public function setPrivee($privee)
  {
	$this->privee = (bool) $privee;
  }
  
  public function setArchive($archive)
  {
    if (!is_string($archive) || empty($archive))
    {
      $this->erreurs[] = self::ARCHIVE_INVALIDE;
    }
    else
    {
      $this->archive = $archive;
    }
  }
  
  public function setChangement($chngt)
  {
	$this->changement = (bool) $chngt;
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
   
  public function auteur()
  {
    return $this->auteur;
  }
  
  public function auteurId()
  {
    return $this->auteur->id();
  }
   
  public function titre()
  {
    return $this->titre;
  }
   
  public function contenu()
  {
    return $this->contenu;
  }
   
  public function dateAjout()
  {
    return $this->dateAjout;
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
  
  public function privee()
  {
    return $this->privee;
  }
  
  public function archive()
  {
    return $this->archive;
  }
  
  public function changement()
  {
    return $this->changement;
  }
}