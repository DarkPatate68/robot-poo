<?php
namespace Library\Entities;
 
class Commentaire extends \Library\Entity
{
	protected $news,
			  $auteur,
			  $contenu,
			  $dateAjout,
			  $editeur,
			  $dateModif,
			  $moderation,
			  $supprime;
   
	const AUTEUR_INVALIDE = 1;
	const CONTENU_INVALIDE = 2;
	const MODERATION_INVALIDE = 3;

	public function estValide()
	{
		return !(empty($this->auteur) || empty($this->contenu));
	}

	// SETTERS

	public function setNews($news)
	{
		$this->news = (int) $news;
	}

	public function setAuteur(\Library\Entities\Membre $auteur)
	{
		$this->auteur = $auteur;
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

	public function setDateAjout(\DateTime $date)
	{
		$this->dateAjout = $date;
	}
	
	public function setEditeur(\Library\Entities\Membre $editeur)
	{
		$this->editeur = $editeur;
	}
	
	public function setDateModif(\DateTime $date)
	{
		$this->dateModif = $date;
	}
	
	public function setModeration($moderation)
	{
		if (!is_string($moderation))
		{
			$this->erreurs[] = self::MODERATION_INVALIDE;
		}
		else
		{
			$this->moderation = $moderation;
		}
	}
	
	public function setSupprime($supprime)
	{
		$this->supprime = (bool) $supprime;
	}

	// GETTERS

	public function news()
	{
		return $this->news;
	}

	public function auteur()
	{
		return $this->auteur;
	}

	public function contenu()
	{
		return $this->contenu;
	}

	public function dateAjout()
	{
		return $this->dateAjout;
	}
	
	public function editeur()
	{
		return $this->editeur;
	}
	
	public function dateModif()
	{
		return $this->dateModif;
	}
	
	public function moderation()
	{
		return $this->moderation;
	}
	
	public function supprime()
	{
		return $this->supprime;
	}
}