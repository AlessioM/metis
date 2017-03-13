<?php

namespace Metis\Entities;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * @ORM\Entity @ORM\Table(name="log")
 **/
class LogEntry extends BaseEntity
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
    **/
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="logEntries")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /** @ORM\Column(type="datetime")
    */
    private $logtime;

    /** @ORM\Column(type="string")
    */
    private $entry;



    /**
     * Get the value of Logtime
     *
     * @return mixed
     */
    public function getLogtime()
    {
        return $this->logtime;
    }

    /**
     * Set the value of Logtime
     *
     * @param mixed logtime
     *
     * @return self
     */
    public function setLogtime($logtime)
    {
        $this->logtime = $logtime;

        return $this;
    }

    /**
     * Get the value of Entry
     *
     * @return mixed
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Set the value of Entry
     *
     * @param mixed entry
     *
     * @return self
     */
    public function setEntry($entry)
    {
        $this->entry = $entry;

        return $this;
    }


    /**
     * Get the value of Id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Get the value of Person
     *
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set the value of Person
     *
     * @param mixed person
     *
     * @return self
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

}
