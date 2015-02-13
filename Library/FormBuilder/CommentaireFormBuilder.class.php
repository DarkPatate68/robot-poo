<?php
namespace Library\FormBuilder;

/**
 * Classe du formulaire pour la rédaction de commentaire (pour une news). Il présente un champs pour le
 * contenu, un champ pour un éventuel captcha si l'utilisateur est anonyme et plusieurs champs caché nécessaire
 * au traitement (id de la new, id de l'auteur et id de l'éditeur).
 * @author Siméon
 *
 */
class CommentaireFormBuilder extends \Library\FormBuilder
{
    /**
     * Construit le formulaire
     * @param bool $captcha Présence du captcha ou non
     * @param bool $hidden Précense des champs cachés ou non
     * @see \Library\FormBuilder::build()
     */
	public function build($captcha = false, $hidden = false)
	{
		$captcha = (bool) $captcha;
		$hidden = (bool) $hidden;
		
													
		$this->form->add(new \Library\TextEditField(array(
															'label' => 'Contenu',
															'name' => 'contenu',
															'rows' => 10,
															'cols' => 50,
		                                                    'width' => 99,
															'height' => 250,
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