<?php
namespace Metis\Form;
use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;

class LoginForm extends Form
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('login');
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        $this->add([
            'type'  => 'password',
            'name' => 'code',
            'options' => [
                'label' => 'Access Code',
            ],
            'attributes' => [
              'placeholder' => 'access code'
            ]
        ]);

        $this->add([
            'type'  => 'checkbox',
            'name' => 'cookie',
            'options' => [
                'label' => 'This site uses cookies. By continuing to browse the site, you are agreeing to our use of cookies.',
                'use_hidden_element' => true,
                'checked_value' => 'accept',
                'unchecked_value' => 'refuse'
            ],
        ]);

        // Add the CSRF field
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                'timeout' => 600
                ]
            ],
        ]);

        // Add the Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'access',
                'id' => 'submit',
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Add input for "password" field
        $inputFilter->add([
                'name'     => 'code',
                'required' => true,
                'filters'  => [
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ]);

        $inputFilter->add([
                'name'     => 'cookie',
                'required' => false,
                'filters'  => [
                ],
                'validators' => [
                    [
                        'name'    => 'InArray',
                        'options' => [
                          'haystack' => ['accept'],
                          'message' => 'please accept before proceeding'
                        ]
                    ],
                ],
            ]);

    }
}
