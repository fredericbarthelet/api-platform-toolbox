<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={Form::FORM_READ}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 */
class Form
{
    const FORM_READ = 'form_read';

    /**
     * @var string
     * @Groups({Form::FORM_READ})
     */
    private $firstName;

    /**
     * @var string
     * @Groups({Form::FORM_READ})
     */
    private $lastName;

    /**
     * @var int
     * @Groups({Form::FORM_READ})
     */
    private $age;

    /**
     * @Groups({Form::FORM_READ})
     */
    public function getFullName(): string
    {
        return $this->lastname.' '.$this->firstname;
    }
}