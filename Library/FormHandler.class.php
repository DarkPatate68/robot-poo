<?php
namespace Library;

class FormHandler
{
	protected $form;
	protected $manager;
	protected $request;

	public function __construct(\Library\Form $form, \Library\Manager $manager, \Library\HTTPRequest $request)
	{
		$this->setForm($form);
		$this->setManager($manager);
		$this->setRequest($request);
	}

	public function process()
	{
		if($this->request->method() == 'POST' && $this->form->isValid())
		{
			$this->manager->save($this->form->entity());
			return true;
		}

		return false;
	}

	public function setForm(\Library\Form $form)
	{
		$this->form = $form;
	}

	public function setManager(\Library\Manager $manager)
	{
		$this->manager = $manager;
	}

	public function setRequest(\Library\HTTPRequest $request)
	{
		$this->request = $request;
	}
}