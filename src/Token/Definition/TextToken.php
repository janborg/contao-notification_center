<?php

declare(strict_types=1);

namespace Terminal42\NotificationCenterBundle\Token\Definition;

use Terminal42\NotificationCenterBundle\Token\TokenInterface;

class TextToken extends AbstractTokenDefinition
{
    public const DEFINITION_NAME = 'text';

    public function getDefinitionName(): string
    {
        return self::DEFINITION_NAME;
    }

    public function createToken(string $tokenName, mixed $value): TokenInterface
    {
        return $this->createTokenWithAllowedTypes(
            $tokenName,
            $value,
            self::DEFINITION_NAME,
            ['null', 'string']
        );
    }
}
