<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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

    const FORM_COMPLETE_VALIDATION_GROUP = 'form_complete';

    const EXTRA_ATTRIBUTE_COMPLETE = 'complete';

    /**
     * @var string|null
     * @Groups({Form::FORM_READ})
     * @Assert\NotBlank(
     *      groups={Form::FORM_COMPLETE_VALIDATION_GROUP}
     * )
     */
    private $firstName;

    /**
     * @var string|null
     * @Groups({Form::FORM_READ})
     * @Assert\NotBlank(
     *      groups={Form::FORM_COMPLETE_VALIDATION_GROUP}
     * )
     */
    private $lastName;

    /**
     * @var int|null
     * @Groups({Form::FORM_READ})
     * @Assert\Positive(
     *      groups={Form::FORM_COMPLETE_VALIDATION_GROUP}
     * )
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