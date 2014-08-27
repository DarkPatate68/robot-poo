<?php
namespace Library\Models;
 
abstract class NewsManager extends \Library\Manager
{
  /**
   * Méthode permettant d'ajouter une news.
   * @param $news News La news à ajouter
   * @return void
   */
  abstract protected function add(\Library\Entities\News $news);
   
  /**
   * Méthode renvoyant le nombre de news total.
   * @return int
   */
  abstract public function count();
   
  /**
   * Méthode permettant de supprimer une news.
   * @param $id int L'identifiant de la news à supprimer
   * @return void
   */
  abstract public function delete($id);
   
  /**
   * Méthode retournant une liste de news demandée.
   * @param $debut int La première news à sélectionner
   * @param $limite int Le nombre de news à sélectionner
   * @return array La liste des news. Chaque entrée est une instance de News.
   */
  abstract public function getListe($debut = -1, $limite = -1, $privee = false, \Library\Models\MembreManager $membreManager = null);
   
  /**
   * Méthode retournant une news précise.
   * @param $id int L'identifiant de la news à récupérer
   * @return News La news demandée
   */
  abstract public function getUnique($id, \Library\Models\MembreManager $membreManager = null);
   
  /**
   * Méthode permettant d'enregistrer une news.
   * @param $news News la news à enregistrer
   * @see self::add()
   * @see self::modify()
   * @return void
   */
  public function save(\Library\Entities\News $news)
  {
    if ($news->estValide())
    {
      $news->existe() ? $this->update($news) : $this->add($news);
    }
    else
    {
      throw new RuntimeException('La news doit être valide pour être enregistrée');
    }
  }
   
  /**
   * Méthode permettant de modifier une news.
   * @param $news news la news à modifier
   * @return void
   */
  abstract protected function update(\Library\Entities\News $news);
  
  /**
   * Fait passer l'attribut changement de la dernière news à 1
   * @return bool Réussite ou échec de la requête
   */
  abstract public function archivage();
}