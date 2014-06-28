<?php
namespace Library\FormBuilder;

class CommentaireFormBuilder extends \Library\FormBuilder
{
	public function build($captcha = false, $hidden = false)
	{
		$captcha = (bool) $captcha;
		$hidden = (bool) $hidden;
		
													
		$this->form->add(new \Library\TextEditField(array(
															'label' => 'Contenu',
															'name' => 'contenu',
															'rows' => 7,
															'cols' => 50,
															'validators' => array(new \Library\NotNullValidator('Merci de spécifier votre commentaire'))
															)));
															
		if($captcha)
		{
			$this->form->add(new \Library\CaptchaField(array(
															'name' => 'captcha',
															'validators' => array(new \Library\NotNullValidator('Les chiffres ne sont pas identiques'))
															)));
		}
		if($hidden)
		{
			$this->form->add(new \Library\HiddenField(array(
															'name' => 'news',
															)))
						->add(new \Library\HiddenField(array(
														'name' => 'auteur',
														)))
						->add(new \Library\HiddenField(array(
														'name' => 'editeur',
														)));
		}
	}
}