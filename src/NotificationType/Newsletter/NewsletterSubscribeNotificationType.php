<?php

declare(strict_types=1);

namespace Terminal42\NotificationCenterBundle\NotificationType\Newsletter;

use Terminal42\NotificationCenterBundle\NotificationType\NotificationTypeInterface;
use Terminal42\NotificationCenterBundle\Token\Definition\EmailToken;
use Terminal42\NotificationCenterBundle\Token\Definition\Factory\TokenDefinitionFactoryInterface;
use Terminal42\NotificationCenterBundle\Token\Definition\TextToken;

class NewsletterSubscribeNotificationType implements NotificationTypeInterface
{
    public const NAME = 'newsletter_subscribe';

    public function __construct(private TokenDefinitionFactoryInterface $factory)
    {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getTokenDefinitions(): array
    {
        return [
            $this->factory->create(EmailToken::DEFINITION_NAME, 'recipient_email', 'newsletter.recipient_email'),
            $this->factory->create(TextToken::DEFINITION_NAME, 'link', 'newsletter.link'),
            $this->factory->create(TextToken::DEFINITION_NAME, 'token', 'newsletter.token'),
            $this->factory->create(TextToken::DEFINITION_NAME, 'channels', 'newsletter.channels'),
            $this->factory->create(TextToken::DEFINITION_NAME, 'channel_ids', 'newsletter.channel_ids'),
        ];
    }
}
