<?php
namespace Library\Models;
 
use \Library\Entities\MailReception;

class MailReceptionManager_PDO extends MailReceptionManager
{
  /**
   * Attribut contenant l'instance représentant la BDD.
   * @type PDO
   */
     
  const NOM_TABLE = 'nv_mail_reception'; /**< Nom de la table correspondant au manager */
   
    
  /**
   * @see MailReceptionManager::add()
   */
  public function add(\Library\Entities\MailReception $mail)
  {
    $requete = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' 
									SET expediteur = :exp, 
										texte = :texte,
										lu = :lu,
										date = NOW(), 
										objet = :objet,
										corbeille = :corbeille');
     
    $requete->bindValue(':exp', $mail->expediteur());
    $requete->bindValue(':texte', $mail->texte());
	$requete->bindValue(':lu', $mail->lu());
	$requete->bindValue(':objet', $mail->objet());
	$requete->bindValue(':corbeille', $mail->corbeille());
     
    return $requete->execute();
  }
   
  /**
   * @see MailReceptionManager::count()
   */
  public function count($nonLu = false)
  {
  	$nonLuTxt = '';
  	if($nonLu)
  		$nonLuTxt = ' WHERE lu = 0 ';
    return $this->dao->query('SELECT COUNT(*) FROM ' . self::NOM_TABLE . $nonLuTxt . '')->fetchColumn();
  }
  
  /**
   * @see MailReceptionManager::delete()
   */
  public function delete()
  {
    $this->dao->exec('DELETE FROM ' . self::NOM_TABLE . ' 
					  WHERE corbeille = 1');
  }
  
  /**
   * @see MailReceptionManager::corbeille()
   */
  public function corbeille($id, $retirer = false)
  {
	$requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' 
									SET corbeille = :corb 
									WHERE id = :id');
    
	$requete->bindValue(':id', $id);
    $requete->bindValue(':corb', (int) !$retirer);    
     
    $requete->execute();
  }
  
  /**
   * @see MailReceptionManager::lu()
   */
  public function lu($id, $lu = true)
  {
  	$requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . '
									SET lu = :lu
									WHERE id = :id');
  
  	$requete->bindValue(':id', $id);
  	$requete->bindValue(':lu', (int) $lu);
  	 
  	$requete->execute();
  }
  
     
  /**
   * @see MailReceptionManager::getListe()
   */
  public function getListe($debut = -1, $limite = -1, $corbeille = false)
  {
    $sql = 'SELECT * 
			FROM ' . self::NOM_TABLE; 

	if($corbeille)
		$sql .= ' WHERE corbeille=1 ';
	else
		$sql .= ' WHERE corbeille=0 ';
		
	$sql .=	' ORDER BY date DESC';
							
    // On vérifie l'intégrité des paramètres fournis.
    if ($debut != -1 || $limite != -1)
    {
		$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }
     
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Library\Entities\MailReception');
	
	$listeMail = $requete->fetchAll();
	
	foreach ($listeMail as $mail)
    {
		$mail->setDate(new \DateTime($mail->date()));
    }
     
    $requete->closeCursor();
     
    return $listeMail;
  }
  
       
  /**
   * @see MailReceptionManager::getUnique()
   */
  public function getUnique($id)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\MailReception');
	
	if ($mail = $requete->fetch())
    {
      $mail->setDate(new \DateTime($mail->date()));      
      return $mail;
    }
	return false;
  }
  
//   /**
//    * @see MailReceptionManager::update()
//    */
//   public function update(\Library\Entities\MailReception $mail)
//   {
//     $requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' 
// 									SET titre = :titre, 
// 										texte = :texte,
// 										url = :url,
// 										dateModif = NOW(), 
// 										editeur = :editeur,
// 										archive = :archive
// 									WHERE id = :id');
    
// 	$requete->bindValue(':id', $mail->id());
//     $requete->bindValue(':titre', $mail->titre());
//     $requete->bindValue(':texte', $mail->texte());
// 	$requete->bindValue(':url', $mail->url());
// 	$requete->bindValue(':archive', $mail->archive());
// 	$requete->bindValue(':editeur', $mail->editeurId());
    
     
//     return $requete->execute();
//   }  
}