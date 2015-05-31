<?php
namespace Library\FormBuilder;

class NewsFormBuilder extends \Library\FormBuilder
{
	public function build()
	{
		$idTextEdit = 'contenu';
		$this->form->add(new \Library\HiddenField(array(
														'name' => 'auteur',
														)))
					->add(new \Library\HiddenField(array(
														'name' => 'editeur',
														)))
					->add(new \Library\HiddenField(array(
														'name' => 'archive',
														)))
					->add(new \Library\LineEditField(array(
															'label' => 'Titre',
															'name' => 'titre',
															'maxLength' => 255,
															'validators' => array(
															new \Library\MaxLengthValidator('Le titre spécifié est trop long (255 caractères maximum)', 255),
															new \Library\NotNullValidator('Merci de spécifier le titre de la news')
															)
															)))
					->add(new \Library\TextEditField(array(
														'label' => 'Contenu',
														'name' => $idTextEdit,
														'rows' => 8,
														'cols' => 60,
														'width' => 99,
														'height' => 250,
														'validators' => array(
														new \Library\NotNullValidator('Merci de spécifier le contenu de la news')
														)
														)))
					->add(new \Library\CheckboxField(array(
														'label' => 'News privée',
														'name' => 'privee',
														'title' => 'Seuls les membres du club robot validés pourront lire cette news'
														)));
					
					return $idTextEdit;
	}
}