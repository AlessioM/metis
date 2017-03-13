<?php
namespace Metis\Service;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Metis\Entities\Person;
use Metis\Entities\LogEntry;

class LoginAdapter implements AdapterInterface
{
    private $code;
    private $em;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function authenticate()
    {
       $person = $this->em->getRepository('Metis\Entities\Person')->findOneBy(['code' => $this->code]);

       if ($person) {

         $info = [];
         $remote = new \Zend\Http\PhpEnvironment\RemoteAddress;
         $info[] = $remote->getIpAddress();

         $log = new LogEntry();
         $log->setPerson($person);
         $log->setEntry(sprintf("logged in (%s)" ,implode(', ', $info)));
         $log->setLogtime(new \DateTime());
         $person->getLogEntries()->add($log);
         $this->em->flush();

         return new Result(
           Result::SUCCESS,
           $person->getId(),
           ['Authenticated successfully.']);
       } else {


         return new Result(
                Result::FAILURE_CREDENTIAL_INVALID,
                null,
                ['Invalid credentials.']);
      }
    }
}
