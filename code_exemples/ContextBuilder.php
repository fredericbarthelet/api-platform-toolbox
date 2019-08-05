<?php

namespace App\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\Dog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

final class DogContextBuilder implements SerializerContextBuilderInterface
{
    /**
     * @var SerializerContextBuilderInterface;
     */
    private $serializerContextBuilder;

    /**
     * @var Security
     */
    private $security;

    public function __construct(
        SerializerContextBuilderInterface $serializerContextBuilder,
        Security $security
    ) {
        $this->serializerContextBuilder = $serializerContextBuilder;
        $this->security = $security;
    }

    public function createFromRequest(
        Request $request,
        bool $normalization,
        array $extractedAttributes = null
    ): array {
        $context = $this->serializerContextBuilder->createFromRequest(
            $request,
            $normalization,
            $extractedAttributes
        );

        /**
         * $context = [
         *  'groups' => [Dog::DOG_READ],     Array of serialization groups
         *  'resource_class' => Dog::class,  Serialization class
         * ]
         */

        $resourceIsDog = Dog::class === $context['resource_class'];
        $userIsDoctor = $this->security->isGranted(User::ROLE_DOCTOR);
        $isNormalizing = true === $normalization;

        if ($resourceIsDog && $userIsDoctor && $isNormalizing) {
            $context['groups'][] = Dog::DOG_READ_MEDICAL_DATA;
        }

        return $context;
    }
}
