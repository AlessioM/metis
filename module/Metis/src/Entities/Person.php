<?php

namespace Metis\Entities;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity @ORM\Table(name="person")
 **/
class Person extends BaseEntity
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
    * @Annotation\Type("Zend\Form\Element\Hidden")
    **/
    protected $id;

    /** @ORM\Column(type="string")
    * @Annotation\Type("Zend\Form\Element\Text")
    * @Annotation\Required({"required":"true"})
    * @Annotation\Filter({"name":"StripTags"})
    * @Annotation\Validator({"name":"StringLength", "options":{"min":"3"}})
    * @Annotation\Options({"label":"Name:"})
    **/
    protected $name;

    /** @ORM\Column(type="string")
    * @Annotation\Type("Zend\Form\Element\Text")
    * @Annotation\Options({"label":"Access Code:"})
    * @Annotation\Required({"required":"true"})
    * @Annotation\Validator({"name":"StringLength", "options":{"min":"6"}})
    * @Annotation\Filter({"name":"StripTags"})
    * @Annotation\Filter({"name":"StringToUpper"})
    **/
    protected $code;

    /** @ORM\Column(type="string", nullable=True)
    * @Annotation\Type("Zend\Form\Element\Textarea")
    * @Annotation\AllowEmpty()
    * @Annotation\Options({"label":"Comments:"})
    * @Annotation\Filter({"name":"StripTags"})
    **/
    protected $comments;


    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    /** @ORM\Column(type="string")
    * @Annotation\Type("Zend\Form\Element\Select")
    * @Annotation\Required({"required":"true" })
    * @Annotation\Filter({"name":"StripTags"})
    * @Annotation\Options({"label":"Role:",
    *                      "value_options" : {"admin":"Administrator", "user":"User"}})
    * @Annotation\Validator({"name":"InArray",
    *                        "options":{"haystack":{"admin","user"},
    *                        "messages":{"notInArray":"Please select a valid role"}}})
    * @Annotation\Attributes({"value":"user"})
    **/
    protected $role;


    /**
     * @Annotation\Exclude
     * @ORM\OneToMany(targetEntity="LogEntry", mappedBy="person", cascade={"persist"})
     */
    private $logEntries;

    public function __construct() {
       $this->logEntries = new ArrayCollection();
    }


    /* ###################### getter / setter ###################### */

    public function getId()
    {
      return $this->id;
    }

    public function getRole()
    {
      return $this->role;
    }

    public function setRole($role)
    {
        if (!in_array($role, array(self::ROLE_ADMIN, self::ROLE_USER))) {
            throw new \InvalidArgumentException("Invalid role");
        }
        $this->role = $role;

        return $this;
    }


    /**
     * Get the value of Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name
     *
     * @param string name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Get the value of Comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set the value of Comments
     *
     * @param string comments
     *
     * @return self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get the value of Code
     *
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of Code
     *
     * @param mixed code
     *
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }


    /**
     * Get the value of Log Entries
     *
     * @return mixed
     */
    public function getLogEntries()
    {
        return $this->logEntries;
    }

}
