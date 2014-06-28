<?php
namespace Library\FormBuilder;

class PageFixeFormBuilder extends \Library\FormBuilder
{
	public function build($modifier = false)
	{
		$modifier = (bool) $modifier;
		if($modifier)
			$desactiverChamp = 'readonly';
		else
			$desactiverChamp = '';
		
		$this->form->add(new \Library\HiddenField(array(
														'name' => 'editeur',
														)))
					->add(new \Library\LineEditField(array(
															'label' => 'Titre : ',
															'name' => 'titre',
															'maxLength' => 255,
															'validators' => array(
															new \Library\MaxLengthValidator('Le titre spécifié est trop long (255 caractères maximum)', 255),
															new \Library\NotNullValidator('Merci de spécifier le titre de la page')
															)
															)))
					->add(new \Library\LineEditField(array(
															'label' => 'URL : <span class="prefixe">' . $GLOBALS['PREFIXE'] . '/</span>',
															'name' => 'url',
															'maxLength' => 255,
															'readonly' => $desactiverChamp,
															'validators' => array(
															new \Library\MaxLengthValidator('L\'url spécifié est trop long (255 caractères maximum)', 255),
															new \Library\NotNullValidator('L\'URL spécifiée est vide ou déjà utilisée'),
															new \Library\UrlValidator('Les caractères saisis sont invalides.<br/> 
																Seuls sont autorisés les caractères alphanumériques sans signes diacritiques et le trio "/", "-" et "_".')
															)
															)))
					->add(new \Library\TextEditField(array(
														'label' => 'Texte',
														'name' => 'texte',
														'rows' => 8,
														'cols' => 60,
														'validators' => array(
														new \Library\NotNullValidator('Merci de spécifier le contenu de la page')
														)
														)));
	}
}