<?php
namespace Library\Models;
 
abstract class ArchiveManager extends \Library\Manager
{
  /**
   * Méthode permettant d'ajouter une année.
   * @param $année string La nouvelle année
   * @return void
   */
  abstract public function add($annee);
   
  /**
   * Méthode renvoyant le nombre d'année total.
   * @return int
   */
  abstract public function count();
   
  /**
   * Méthode permettant de supprimer une année.
   * @param $id int L'identifiant de l'année à supprimer
   * @return void
   */
  abstract public function delete($id);
   
  /**
   * Méthode retournant une liste des années demandée.
   * @return array La liste des années.
   */
  abstract public function getListe();
   
  /**
   * Méthode retournant une news précise.
   * @param $id int L'identifiant de l'année à récupérer, si id = -1, alors on charge l'année en cours
   * @return array L'année et le TS
   */
  abstract public function getUnique($id = -1);
}