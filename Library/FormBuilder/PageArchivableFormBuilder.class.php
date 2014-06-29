<?php
namespace Library\FormBuilder;

/**
 * Classe du formulaire pour la création et la modification de page archivable seule (sans URl et sans titre)
 * @author Siméon
 *
 */
class PageArchivableFormBuilder extends \Library\FormBuilder
{
	public function build($archive = null, $modification = false)
	{
		$modification = (bool) $modification;
	    
	    $this->form->add(new \Library\HiddenField(array(
														'name' => 'editeur',
														)));
		if($archive !== null)
		{
		     $this->form->add(new \Library\ListField(array(
															'label' => 'Archive ',
															'name' => 'archive',
		                                                    'disabled' => $modification,
															'liste' => $archive
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