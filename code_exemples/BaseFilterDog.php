<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource
 * @ApiFilter(OrderFilter::class, properties={"name"})
 * @ApiFilter(SearchFilter::class, properties={"name": "ipartial"})
 * @ApiFilter(ExistsFilter::class, properties={"owner"})
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