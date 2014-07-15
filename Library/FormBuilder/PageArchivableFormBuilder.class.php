<?php
namespace Library\FormBuilder;

/**
 * Classe du formulaire pour la création et la modification de page archivable seule (sans URl et sans titre)
 * @author Siméon
 * @date 01/07/2014
 * @version 1.0.0
 *
 */
class PageArchivableFormBuilder extends \Library\FormBuilder
{
	/**
	 * (non-PHPdoc)
	 * @see \Library\FormBuilder::build()
	 * @param array $archive Liste des archives à afficher dans le select
	 * @param bool $modification Si le formulaire est utilisé pour modifier ou créer une page. Le select sera désactivé en fonction.
	 * @param string $selectionne L'archive qui est sélectionnée, si elle vaut false (valeur par défaut), elle n'est pas prise en compte.
	 * @return void
	 */
	public function build($archive = null, $modification = false, $selectionne = false, $creation = false)
	{
		$modification = (bool) $modification;
		if($selectionne !== false)
			$selectionne = (string) $selectionne;
	    
	    $this->form->add(new \Library\HiddenField(array(
														'name' => 'editeur'
														)))
				   ->add(new \Library\LineEditField(array(
													   'label' => 'Titre : ',
													   'name' => 'titre',
													   'maxLength' => 255,
					                                   'disabled' => !$creation,
													   'validators' => array(
														   new \Library\MaxLengthValidator('Le titre spécifié est trop long (255 caractères maximum)', 255),
														   new \Library\NotNullValidator('Merci de spécifier le titre de la page')
														    )
														)))
				   ->add(new \Library\LineEditField(array(
                                				       'label' => 'URL : ',
                                				       'name' => 'url',
                                				       'maxLength' => 255,
                                				       'disabled' => !$creation,
                                				       'validators' => array(
                                				           new \Library\MaxLengthValidator('L\'URL spécifiée est trop longue (255 caractères maximum)', 255),
                                				           new \Library\NotNullValidator('Merci de spécifier l\'URL de la page')
                                				       )
                                				        )));
		if($archive !== null)
		{
		     $this->form->add(new \Library\ListField(array(
															'label' => 'Archive :',
															'name' => 'archive',
		                                                    'disabled' => $modification,
		     												'selected' => $selectionne,
															'options' => $archive
															)));
		     if($modification)
				$this->form->add(new \Library\HiddenField(array(
															    'name' => 'archive',
						                                        'value' => $selectionne
															)));
		}
		$this->form->add(new \Library\TextEditField(array(
											'label' => 'Texte',
											'name' => 'texte',
											'rows' => 8,
											'cols' => 60,
		                                    'width' => 100,
											'validators' => array(
											new \Library\NotNullValidator('Merci de spécifier le contenu de la page')
											)
											)));
	}
}