<?php
namespace Library\FormBuilder;

/**
 * Classe du formulaire pour la création d'un mail
 * @author Siméon
 * @date 20/09/2015
 * @version 1.0.0
 *
 */
class MessagerieEcrireFormBuilder extends \Library\FormBuilder
{
	/**
	 * (non-PHPdoc)
	 * @see \Library\FormBuilder::build()
	 * @param array $archive Liste des archives à afficher dans le select
	 * @param bool $modification Si le formulaire est utilisé pour modifier ou créer une page. Le select sera désactivé en fonction.
	 * @param string $selectionne L'archive qui est sélectionnée, si elle vaut false (valeur par défaut), elle n'est pas prise en compte.
	 * @return void
	 */
	public function build()
	{
	    
		$this->form->add(new \Library\LineEditField(array(
													   'label' => 'Objet : ',
													   'name' => 'objet',
													   'maxLength' => 255,
														'width' => 300,
													   'validators' => array(
														   new \Library\MaxLengthValidator('L\'objet spécifié est trop long (255 caractères maximum)', 255),
														   new \Library\NotNullValidator('Merci de spécifier un objet au courriel')
														    )
														)))
                   ->add(new \Library\TextEditField(array(
											'name' => 'texte',
											'rows' => 15,
											'cols' => 120,
		                                    'width' => 99,
											'height' => 250,
											'validators' => array(
											new \Library\NotNullValidator('Merci de spécifier le contenu du courriel')
											)
											)));
		
	}
}