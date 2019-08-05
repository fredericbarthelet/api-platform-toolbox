<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get"={"access_control"="is_granted('ROLE_USER')"}
 *     },
 *     itemOperations={
 *         "get"={
 *             "access_control"=
 *                 "is_granted('ROLE_USER') and object.getOwner() == user"
 *         }
 *     }
 * )
 */
class Dog
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $owner;
}