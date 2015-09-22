<?php
namespace Library\Models;
 
use \Library\Entities\MailEnvoi;

class MailEnvoiManager_PDO extends MailEnvoiManager
{
  /**
   * Attribut contenant l'instance représentant la BDD.
   * @type PDO
   */
     
  const NOM_TABLE = 'nv_mail_envoi'; /**< Nom de la table correspondant au manager */
   
    
  /**
   * @see MailEnvoiManager::add()
   */
  public function add(\Library\Entities\MailEnvoi $mail)
  {
    $requete = $this->dao->prepare('INSERT INTO ' . self::NOM_TABLE . ' 
									SET destinataire = :dest, 
										texte = :texte,
										type = :type,
										date = NOW(), 
										objet = :objet,
										corbeille = :corbeille');
     
    $requete->bindValue(':dest', $mail->destinataire());
    $requete->bindValue(':texte', $mail->texte());
	$requete->bindValue(':type', (string) $mail->type());
	$requete->bindValue(':objet', $mail->objet());
	$requete->bindValue(':corbeille', $mail->corbeille());
     
    return $requete->execute();
  }
   
  /**
   * @see MailEnvoiManager::count()
   */
  public function count()
  {
  	return $this->dao->query('SELECT COUNT(*) FROM ' . self::NOM_TABLE . '')->fetchColumn();
  }
  
  /**
   * @see MailEnvoiManager::delete()
   */
  public function delete()
  {
    $this->dao->exec('DELETE FROM ' . self::NOM_TABLE . ' 
					  WHERE corbeille = 1');
  }
  
  /**
   * @see MailEnvoiManager::corbeille()
   */
  public function corbeille($url, $retirer = false)
  {
	$requete = $this->dao->prepare('UPDATE ' . self::NOM_TABLE . ' 
									SET corbeille = :corb 
									WHERE id = :id');
    
	$requete->bindValue(':id', $mail->id());
    $requete->bindValue(':corb', (int) !$retirer);    
     
    $requete->execute();
  }
  
     
  /**
   * @see MailEnvoiManager::getListe()
   */
  public function getListe($debut = -1, $limite = -1, $corbeille = false, $type = 'TOUT')
  {
  	$type = (string) $type;
    $sql = 'SELECT * 
			FROM ' . self::NOM_TABLE; 

	if($corbeille)
		$sql .= ' WHERE corbeille=1 ';
	else
		$sql .= ' WHERE corbeille=0 ';
	
	if(in_array($type, \Library\Entities\MailEnvoi::$TYPE))
		$sql .= 'and type=' . $type . ' ';
		
	$sql .=	' ORDER BY date DESC';
							
    // On vérifie l'intégrité des paramètres fournis.
    if ($debut != -1 || $limite != -1)
    {
		$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }
     
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Library\Entities\MailEnvoi');
	
	$listeMail = $requete->fetchAll();
	
	foreach ($listeMail as $mail)
    {
		$mail->setDate(new \DateTime($news->date()));
    }
     
    $requete->closeCursor();
     
    return $listeMail;
  }
  
       
  /**
   * @see MailEnvoiManager::getUnique()
   */
  public function getUnique($id)
  {
    $requete = $this->dao->prepare('SELECT *
									FROM ' . self::NOM_TABLE . ' 
									WHERE id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    if($requete->execute() === false)
		return false;
     
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\MailEnvoi');
	
	if ($mail = $requete->fetch())
    {
      $mail->setDate(new \DateTime($mail->date()));      
      return $mail;
    }
	return false;
  }
  
//   /**
//    * @see MailEnvoiManager::update()
//    */
//   public function update(\Library\Entities\MailEnvoi $mail)
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