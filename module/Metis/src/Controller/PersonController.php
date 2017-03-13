<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Metis\Controller;

use Zend\View\Model\ViewModel;
use Metis\Entities\Person;
use Metis\Forms\PersonForm;
use Zend\Form\Annotation\AnnotationBuilder;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class PersonController extends AbstractAdminController
{
    public function indexAction()
    {
      $dql = "SELECT p FROM Metis\Entities\Person p ORDER BY p.name ASC";

      $query = $this->em->createQuery($dql);
      $persons = $query->getResult();

      $data = ["persons" => $persons];

      return $data;
    }

    public function createAction()
    {
      $person = new Person();

      $builder    = new AnnotationBuilder();
      $form       = $builder->createForm($person);

      $form->add([
        'name' => 'send',
        'attributes' => [
            'type'  => 'submit',
            'value' => 'Save',
          ],
      ]);

      $form->setHydrator(new DoctrineObject($this->em));
      $form->bind($person);

      $request = $this->getRequest();
      if ($request->isPost()){
        $form->setData($request->getPost());
        if ($form->isValid()){
            $this->em->persist($person);
            $this->em->flush();
            return $this->redirect()->toRoute('person');
        }
      }

      return ['form'=>$form, 'person'=>$person];
    }

    private function getPersonByUrl()
    {
      $personID = $this->params()->fromRoute('id');
      $person     = new Person();
      $person = $this->em->find("Metis\Entities\Person", $personID);
      if($person == Null) {
        // Redirect to list of persons
        return $this->redirect()->toRoute('person');
      }

      return $person;
    }

    public function deleteAction()
    {
      $person = $this->getPersonByUrl();

      $request = $this->getRequest();
      if ($request->isPost()) {
        $del = $request->getPost('del', 'No');

        if ($del == 'Yes') {
          $this->em->remove($person);
          $this->em->flush();
        }

        // Redirect to list of albums
        return $this->redirect()->toRoute('person');
      }
      return ['person'=>$person];
    }

    public function editAction()
    {
      $person = $this->getPersonByUrl();
      $builder    = new AnnotationBuilder();
      $form       = $builder->createForm($person);

      $form->add([
        'name' => 'send',
        'attributes' => [
            'type'  => 'submit',
            'value' => 'Save',
          ],
      ]);

      $form->setHydrator(new DoctrineObject($this->em));
      $form->bind($person);

      $request = $this->getRequest();
      if ($request->isPost()){
        $form->setData($request->getPost());
        if ($form->isValid()){
            $this->em->persist($person);
            $this->em->flush();
            return $this->redirect()->toRoute('person');
        }
      }

      return ['form'=>$form, 'person'=>$person];
    }

    public function logAction()
    {
      $person = $this->getPersonByUrl();
      $log = $person->getLogEntries();

      return ['person'=>$person, 'log' => $log];
    }
}
