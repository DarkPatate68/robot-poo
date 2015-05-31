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
     * @param \Library\Application $app Représente l'application
     * @param bool $captcha Présence du captcha ou non
     * @param bool $hidden Précense des champs cachés ou non
     * @see \Library\FormBuilder::build()
     */
	public function build(\Library\Application $app = null, $captcha = false, $hidden = false)
	{
		$captcha = (bool) $captcha;
		$hidden = (bool) $hidden;
		$idTextEdit = 'contenu';
		
													
		$this->form->add(new \Library\TextEditField(array(
															'label' => 'Contenu',
															'name' => $idTextEdit,
															'rows' => 10,
															'cols' => 50,
		                                                    'width' => 99,
															'height' => 250,
															'validators' => array(new \Library\NotNullValidator('Merci de spécifier votre commentaire')),
															'app' => $app
															)));
															
		if($captcha)
		{
			$this->form->add(new \Library\CaptchaField(array(
															'name' => 'captcha',
															'validators' => array(new \Library\NotNullValidator('Les chiffres ne sont pas identiques')),
															'labelWidth' => 350
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
		
		return $idTextEdit;
	}
}