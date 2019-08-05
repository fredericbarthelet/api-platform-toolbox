<?php

namespace App\Serializer;

use App\Entity\Form;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class FormNormalizer 
implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    /**
     * @var array|bool[]
     */
    private $hasAlreadyNormalize = [];

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function normalize(
        $data,
        $format = null,
        array $context = []
    ) {
        $this->hasAlreadyNormalize[$data->getId()] = true;

        $constraintViolations = $this->validator->validate(
            $data,
            null,
            [
                Form::FORM_COMPLETE_VALIDATION_GROUP,
            ]
        );
        $isComplete = 0 === $constraintViolations->count();

        if (!$this->serializer instanceof NormalizerInterface) {
            throw new \InvalidArgumentException(
                'The serializer must implement the NormalizerInterface'
            );
        }

        $normalizedForm = $this->serializer->normalize(
            $data,
            $format,
            $context
        );
        $normalizedForm[Form::EXTRA_ATTRIBUTE_COMPLETE] = $isComplete;

        return $normalizedForm;
    }

    public function supportsNormalization($data, $format = null)
    {
        if (!$data instanceof Form) {
            return false;
        }

        if (
            isset($this->hasAlreadyNormalize[$data->getId()])
            && $this->hasAlreadyNormalize[$data->getId()]
        ) {
            return false;
        }

        return true;
    }
}
