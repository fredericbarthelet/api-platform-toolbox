<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={Dog::DOG_READ}},
 *     denormalizationContext={"groups"={Dog::DOG_WRITE}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 */
class Dog
{
    const DOG_READ = 'dog_read';
    const DOG_WRITE = 'dog_write';

    /**
     * @var string
     * @Groups({Dog::DOG_READ})
     */
    private $name;

    /**
     * @var int
     * @Groups({Dog::DOG_READ})
     */
    private $age;

    /**
     * @var string
     * @Groups({Dog::DOG_READ, Dog::DOG_WRITE})
     */
    private $color;
}