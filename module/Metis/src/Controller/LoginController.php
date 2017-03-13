<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Metis\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Metis\Form\LoginForm;
use Zend\Authentication\Result;

class LoginController extends AbstractActionController
{
    public function __construct(\Doctrine\ORM\EntityManager $entityManager,
        \Zend\Authentication\AuthenticationService $authService,
        \Metis\Service\LoginAdapter $loginAdapter)
    {
        $this->em = $entityManager;
        $this->authService = $authService;
        $this->loginAdapter = $loginAdapter;
    }

    public function indexAction()
    {
         $this->layout('layout/auth.phtml');
         $loginError = false;

         $form = new LoginForm();

         if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if($form->isValid()) {
              //form is valid, check auth
              $this->loginAdapter->setCode($data['code']);
              $result = $this->authService->authenticate($this->loginAdapter);

              if ($result->getCode() == Result::SUCCESS) {
                return $this->redirect()->toRoute('home');
              } else {
                $loginError = true;
              }
            }
          }

         return [
             'loginError' => $loginError,
             'form' => $form
         ];
    }

    public function logoutAction()
    {
        $this->authService->clearIdentity();
        return $this->redirect()->toRoute('login');
    }
}
