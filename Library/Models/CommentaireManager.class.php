<?php
namespace Library\Models;
 
use \Library\Entities\Commentaire;
 
abstract class CommentaireManager extends \Library\Manager
{
	/**
   * Méthode renvoyant le nombre de news total.
   * @return int
   */
  abstract public function count($idNews);
  
  /**
   * Méthode permettant d'ajouter un commentaire
   * @param $comment Le commentaire à ajouter
   * @return void
   */
  abstract protected function add(Commentaire $commentaire);
   
  /**
   * Méthode permettant d'enregistrer un commentaire.
   * @param $comment Le commentaire à enregistrer
   * @return void
   */
  public function save(Commentaire $commentaire)
  {
    if ($commentaire->estValide())
    {
      $commentaire->isNew() ? $this->add($commentaire) : $this->update($commentaire);
    }
    else
    {
      throw new \RuntimeException('Le commentaire doit être validé pour être enregistré');
    }
  }
  
  /**
   * Méthode permettant de récupérer une liste de commentaires.
   * @param $news La news sur laquelle on veut récupérer les commentaires
   * @return array
   */
  abstract public function getListe($news, \Library\Models\MembreManager $membreManager = null);
  
  /**
   * Méthode permettant de modifier un commentaire.
   * @param $comment Le commentaire à modifier
   * @return void
   */
  abstract protected function update(Commentaire $commentaire);
  
  /**
   * Méthode permettant d'obtenir un commentaire spécifique.
   * @param $id L'identifiant du commentaire
   * @return Comment
   */
  abstract public function get($id);
}