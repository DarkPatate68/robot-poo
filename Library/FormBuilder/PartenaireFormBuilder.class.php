<?php
namespace Library\FormBuilder;

class PartenaireFormBuilder extends \Library\FormBuilder
{
	public function build()
	{
		$this->form->add(new \Library\HiddenField(array(
														'name' => 'image'
														)))
					->add(new \Library\LineEditField(array(
															'label' => 'Nom du partenaire : ',
															'name' => 'nom',
															'maxLength' => 255,
															'validators' => array(
															new \Library\MaxLengthValidator('Le nom spécifié est trop long (255 caractères maximum)', 255),
															new \Library\NotNullValidator('Merci de spécifier le nom du partenaire')
															)
															)))
					->add(new \Library\TextEditField(array(
														'label' => 'Description',
														'name' => 'description',
														'rows' => 8,
														'cols' => 60,
														'validators' => array(
														new \Library\NotNullValidator('Merci de spécifier la description du partenaire')
														)
														)))
					->add(new \Library\FileField(array(
														'label' => 'Logo du partenaire : ',
														'name' => 'nouvelleImage',
														'title' => 'Pour garder l\'ancienne image, ne pas toucher'
														)));
	}
}