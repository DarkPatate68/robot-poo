<?php
/**
 * Classe abstraite représentant un mail envoyé par le club.
 * @author Siméon Capy
 * @version 1.0
 */
namespace Library\Entities;
 
class MailEnvoi extends \Library\Mail
{
  protected $destinataire,
  			$type;
  
  public static $TYPE = array('NORMAL', 'NEWSLETTER', 'PARTENAIRE', 'OFFICIEL');
   
  // ----------------------------------------
  // ************** MÉTHODES ****************
  // ----------------------------------------
   
  	public function envoyer()
  	{
  		$passage_ligne = '\n';
  		//=====Déclaration des messages au format texte et au format HTML.
  		$message_txt = $this->construireBrut();
  		$message_html = $this->construireHTML();
  		//==========
  			
  		//=====Création de la boundary
  		$boundary = "-----=".md5(rand());
  		//==========
  			
  		//=====Définition du sujet.
  		$sujet = $this->objet;
  		//=========
  			
  		//=====Création du header de l'e-mail.
  		$header = "From: \"Club robotique de l'INSA de Strasbourg\"<club-robotique@insa-strasbourg.fr>".$passage_ligne;
  		$header.= "Reply-to: \"Club robotique de l'INSA de Strasbourg\" <club-robotique@insa-strasbourg.fr>".$passage_ligne;
  		$header.= "MIME-Version: 1.0".$passage_ligne;
  		$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
  		//==========
  			
  		//=====Création du message.
  		$message = $passage_ligne."--".$boundary.$passage_ligne;
  		//=====Ajout du message au format texte.
  		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
  		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
  		$message.= $passage_ligne.$message_txt.$passage_ligne;
  		//==========
  		$message.= $passage_ligne."--".$boundary.$passage_ligne;
  		//=====Ajout du message au format HTML
  		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
  		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
  		$message.= $passage_ligne.$message_html.$passage_ligne;
  		//==========
  		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
  		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
  		//==========
  			
  		//=====Envoi de l'e-mail.
  		mail($this->destinataire,$this->objet,$message,$header);
  		//==========
  	}
  	
  	public function construireHTML()
  	{
  		
  	}
  	
  	public function construireBrut()
  	{
  		
  	}
   
  // ---------------------------------------
  // ************** SETTERS ****************
  // ---------------------------------------
  
	public function setDestinataire($dest)
	{
		$this->destinataire = (string) $dest;
	}
	
	public function setType($type)
	{
		if(in_array($type, $self::TYPE))
			$this->type = $type;
		else
			$this->type = 'NORMAL';
	}

		   
  // ---------------------------------------
  // ************** GETTERS ****************
  // ---------------------------------------
   
	public function destinataire()
	{
		return $this->destinataire;
	}
	
	public function type()
	{
		return $this->type;
	}
}