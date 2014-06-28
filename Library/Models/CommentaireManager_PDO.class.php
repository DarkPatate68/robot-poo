<?php
namespace Library\Models;
 
use \Library\Entities\Commentaire;
 
class CommentaireManager_PDO extends CommentaireManager
{
	const NOM_TABLE = 'nv_news_commentaire';
	
	public function count($idNews)
	{
		$idNews = (int) $idNews;
		return $this->dao->query('SELECT COUNT(*) FROM ' . self::NOM_TABLE . ' WHERE news=' . $idNews)->fetchColumn();
	}
  
	protected function add(Commentaire $commentaire)
	{
		$req = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' SET news = :news, auteur = :auteur, contenu = :contenu, dateAjout = NOW(), editeur = :auteur, dateModif = NOW(), moderation = "", supprime = 0');

		$req->bindValue(':news', $commentaire->news(), \PDO::PARAM_INT);
		$req->bindValue(':auteur', $commentaire->auteur()->id());
		$req->bindValue(':contenu', $commentaire->contenu());

		$req->execute();

		$commentaire->setId($this->dao->lastInsertId());
	}
	
	public function getListe($news, \Library\Models\MembreManager $membreManager = null, $debut = -1, $limite = -1)
	{
		if (!ctype_digit($news))
		{
			throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
		}
		
		$sql = 'SELECT id, news, auteur, contenu, dateAjout, editeur, dateModif, moderation, supprime FROM ' . self::NOM_TABLE . ' WHERE news = :news';
		$sql .=	' ORDER BY dateAjout';
		// On vérifie l'intégrité des paramètres fournis.
		if ($debut != -1 || $limite != -1)
		{
		  $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}

		$req = $this->dao->prepare($sql);
		$req->bindValue(':news', $news, \PDO::PARAM_INT);
		$req->execute();

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Commentaire');

		$commentaires = $req->fetchAll();

		foreach ($commentaires as $commentaire)
		{
			$commentaire->setDateAjout(new \DateTime($commentaire->dateAjout()));
			$commentaire->setDateModif(new \DateTime($commentaire->dateModif()));
		}
		
		if($membreManager !== NULL)
		{
			foreach($commentaires as $commentaire)
			{
				$commentaire->setAuteur($membreManager->getUnique($commentaire->auteur()));
				if($commentaire->auteur()->id() == $commentaire->editeur())
					$commentaire->setEditeur($commentaire->auteur());
				else
					$commentaire->setEditeur($membreManager->getUnique($commentaire->editeur()));
			}
		}

		return $commentaires;
	}
	
	protected function update(Commentaire $commentaire)
	{
		$req = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' SET auteur = :auteur, contenu = :contenu, editeur = :editeur, dateModif = NOW(), moderation = :moderation, supprime = :supprime WHERE id = :id');

		$req->bindValue(':auteur', $commentaire->auteur()->id());
		$req->bindValue(':editeur', $commentaire->editeur()->id());
		$req->bindValue(':contenu', $commentaire->contenu());
		$req->bindValue(':id', $commentaire->id(), \PDO::PARAM_INT);
		$req->bindValue(':moderation', $commentaire->moderation());
		$req->bindValue(':supprime', $commentaire->supprime(), \PDO::PARAM_BOOL);

		$req->execute();
	}

	public function get($id, \Library\Models\MembreManager $membreManager = null)
	{
		$req = $this->dao->prepare('SELECT id, news, auteur, contenu, editeur, dateModif, moderation, supprime FROM ' . self::NOM_TABLE . ' WHERE id = :id');
		$req->bindValue(':id', (int) $id, \PDO::PARAM_INT);
		$req->execute();

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Commentaire');

		$commentaire = $req->fetch();
		
		$commentaire->setDateAjout(new \DateTime($commentaire->dateAjout()));
		$commentaire->setDateModif(new \DateTime($commentaire->dateModif()));
		
		if($membreManager !== NULL)
		{
			$commentaire->setAuteur($membreManager->getUnique($commentaire->auteur()));
			if($commentaire->auteur()->id() == $commentaire->editeur())
				$commentaire->setEditeur($commentaire->auteur());
			else
				$commentaire->setEditeur($membreManager->getUnique($commentaire->editeur()));
		}
			
		return $commentaire;
	}
}