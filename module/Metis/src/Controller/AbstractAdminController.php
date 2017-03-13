<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Metis\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Metis\Entities\Person;
use Metis\Forms\PersonForm;
use Zend\Form\Annotation\AnnotationBuilder;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Mvc\MvcEvent;

/**
* base class for all controllers that should only be accessible by users
* with admin rights.
* This is only a simple implementation, use Zend ACL for more fine grainde
* access rights
*/
class AbstractAdminController extends AbstractActionController
{
  /**
   * Entity manager.
   * @var Doctrine\ORM\EntityManager
   */
  protected $em;

  public function __construct(\Doctrine\ORM\EntityManager $entityManager,
                \Zend\Authentication\AuthenticationService $authService)
  {
     $this->em = $entityManager;
     $this->authService = $authService;
  }

  /**
  * checks if currently logged in user has admin rights
  * if not redirects to home Page
  */
  public function onDispatch(MvcEvent $e)
  {
    $person = $this->em->getRepository('Metis\Entities\Person')->find($this->authService->getIdentity());

    if($person->getRole() != 'admin') {
      $this->redirect()->toRoute('home');
    } else {
      return parent::onDispatch($e);
    }

  }
}
