<?php
namespace Library\Entities;
 
class Commentaire extends \Library\Entity
{
	protected $news, /**< (int) Id de la news auquel se rapporte le commentaire */ 
			  $auteur, /**< (\Library\Entities\Membre) Auteur du commentaire */
			  $contenu, /**< (string) Contenu du commentaire */
			  $dateAjout, /**< (\DateTime) date de création du commentaire */
			  $editeur, /**< (\Library\Entities\Membre) Dernier éditeur du commentaire */
			  $dateModif, /**< (\DateTime) date de la dernière modification du commentaire */
			  $moderation, /**< (string) Message de modération */
			  $supprime; /**< (bool) Si le message est supprimé (le message de modération est alors affiché) ou non */
   
	const AUTEUR_INVALIDE = 1; /**< Code renvoyé si l'auteur est invalide (mauvais type) */
	const CONTENU_INVALIDE = 2; /**< Code renvoyé si le contenu est invalide (pas une chaîne de caractère) */
	const MODERATION_INVALIDE = 3; /**< Code renvoyé si le message de modération est invalide (pas une chaîne de caractère) */

	/**
	 * Renvoie vrai si le commentaire est valide, faux sinon.
	 * @return boolean
	 */
	public function estValide()
	{
		return !(empty($this->auteur) || empty($this->contenu));
	}

	// SETTERS

	/**
	 * Accesseur en écriture de l'ID de la news.
	 * @param int $news
	 * @return void
	 */
	public function setNews($news)
	{
		$this->news = (int) $news;
	}

	/**
	 * Accesseur en écriture du membre
	 * @param \Library\Entities\Membre $auteur
	 * @return void
	 */
	public function setAuteur(\Library\Entities\Membre $auteur)
	{
		$this->auteur = $auteur;
	}

	/**
	 * Acesseur en écriture du contenu. Ajoute self::CONTENU_INVALIDE à la variable self::erreurs si ce n'est pas un @b string
	 * @param string $contenu
	 * @return void
	 */
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

	/**
	 * Accesseur en écriture de la date d'ajout du commentaire
	 * @param \DateTime $date
	 * @return void
	 */
	public function setDateAjout(\DateTime $date)
	{
		$this->dateAjout = $date;
	}
	
	/**
	 * Accesseur en écriture du dernier éditeur du commentaire
	 * @param \Library\Entities\Membre $editeur
	 * @return void
	 */
	public function setEditeur(\Library\Entities\Membre $editeur)
	{
		$this->editeur = $editeur;
	}
	
	/**
	 * Accesseur en écriture de la dernière date de modification
	 * @param \DateTime $date
	 * @return void
	 */
	public function setDateModif(\DateTime $date)
	{
		$this->dateModif = $date;
	}
	
	/**
	 * Accesseur en ecriture du message de modération. Ajoute self::MODERATION_INVALIDE à la variable self::erreurs si ce n'est pas un @b string
	 * @param string $moderation
	 * @return void
	 */
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