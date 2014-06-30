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
	public function build($archive = null, $modification = false, $selectionne = false)
	{
		$modification = (bool) $modification;
		if($selectionne !== false)
			$selectionne = (string) $selectionne;
	    
	    $this->form->add(new \Library\HiddenField(array(
														'name' => 'editeur',
														)));
		if($archive !== null)
		{
		     $this->form->add(new \Library\ListField(array(
															'label' => 'Archive ',
															'name' => 'archive',
		                                                    'disabled' => $modification,
		     												'selected' => $selectionne,
															'options' => $archive
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