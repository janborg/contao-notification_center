<?php

declare(strict_types=1);

namespace Terminal42\NotificationCenterBundle\Test\Token\Definition;

use PHPUnit\Framework\TestCase;
use Terminal42\NotificationCenterBundle\Token\Definition\AbstractTokenDefinition;
use Terminal42\NotificationCenterBundle\Token\Definition\EmailToken;
use Terminal42\NotificationCenterBundle\Token\Definition\Factory\ChainTokenDefinitionFactory;
use Terminal42\NotificationCenterBundle\Token\Definition\Factory\CoreTokenDefinitionFactory;
use Terminal42\NotificationCenterBundle\Token\Definition\Factory\TokenDefinitionFactoryInterface;
use Terminal42\NotificationCenterBundle\Token\Definition\TokenDefinitionInterface;
use Terminal42\NotificationCenterBundle\Token\StringToken;
use Terminal42\NotificationCenterBundle\Token\TokenInterface;

class ChainTokenDefinitionFactoryTest extends TestCase
{
    public function testFactoryWorksAsExpected(): void
    {
        $factory = new ChainTokenDefinitionFactory();

        $this->assertFalse($factory->supports(EmailToken::DEFINITION_NAME));

        $factory->addFactory(new CoreTokenDefinitionFactory());

        $this->assertTrue($factory->supports(EmailToken::DEFINITION_NAME));

        $definition = $factory->create(EmailToken::DEFINITION_NAME, 'form_email', 'foobar');

        $this->assertInstanceOf(EmailToken::class, $definition);
        $this->assertSame('form_email', $definition->getTokenName());
        $this->assertSame('foobar', $definition->getTranslationKey());

        $this->assertFalse($factory->supports('iban-token-definition'));

        $factory->addFactory($this->createExampleFactoryWithDependencyInjection('form_iban', 'foobar', 'iban-validator-service-id'));

        $this->assertTrue($factory->supports('iban-token-definition'));

        $definition = $factory->create('iban-token-definition', 'form_iban', 'foobar');

        $this->assertSame('form_iban', $definition->getTokenName());
        $this->assertSame('foobar', $definition->getTranslationKey());

        $token = $definition->createToken('form_iban', 'CH...');
        $this->assertSame('i-would-call-service: iban-validator-service-id and return value: CH...', $token->getParserValue());
    }

    private function createExampleFactoryWithDependencyInjection(string $tokenName, string $translationKey, string $serviceDummy): TokenDefinitionFactoryInterface
    {
        $diDefinition = new class($tokenName, $translationKey, $serviceDummy) extends AbstractTokenDefinition {
            public function __construct(string $tokenName, string $translationKey, private string $serviceDummy)
            {
                parent::__construct($tokenName, $translationKey);
            }

            public function getDefinitionName(): string
            {
                return 'iban-token-definition';
            }

            public function createToken(string $tokenName, mixed $value): TokenInterface
            {
                return new StringToken('i-would-call-service: '.$this->serviceDummy.' and return value: '.$value, $tokenName, $this->getDefinitionName());
            }
        };

        return new class($diDefinition) implements TokenDefinitionFactoryInterface {
            public function __construct(private TokenDefinitionInterface $diDefinition)
            {
            }

            public function supports(string $definitionName): bool
            {
                return $definitionName === $this->diDefinition->getDefinitionName();
            }

            public function create(string $definitionName, string $tokenName, string $translationKey): TokenDefinitionInterface
            {
                return $this->diDefinition;
            }
        };
    }
}
