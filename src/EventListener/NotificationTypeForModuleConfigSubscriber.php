<?php

declare(strict_types=1);

namespace Terminal42\NotificationCenterBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Terminal42\NotificationCenterBundle\Event\GetNotificationTypeForModuleConfigEvent;
use Terminal42\NotificationCenterBundle\NotificationType\LostPasswordNotificationType;
use Terminal42\NotificationCenterBundle\NotificationType\MemberActivationNotificationType;
use Terminal42\NotificationCenterBundle\NotificationType\MemberPersonalDataNotificationType;
use Terminal42\NotificationCenterBundle\NotificationType\MemberRegistrationNotificationType;

class NotificationTypeForModuleConfigSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            GetNotificationTypeForModuleConfigEvent::class => '__invoke',
        ];
    }

    public function __invoke(GetNotificationTypeForModuleConfigEvent $event): void
    {
        if ('lostPasswordNotificationCenter' === $event->getModuleConfig()->getType() && 'nc_notification' === $event->getField()) {
            $event->setNotificationType(LostPasswordNotificationType::NAME);

            return;
        }

        if ('registrationNotificationCenter' === $event->getModuleConfig()->getType()) {
            if ('nc_notification' === $event->getField()) {
                $event->setNotificationType(MemberRegistrationNotificationType::NAME);

                return;
            }

            if ('nc_activation_notification' === $event->getField()) {
                $event->setNotificationType(MemberActivationNotificationType::NAME);

                return;
            }
        }

        if ('personalData' === $event->getModuleConfig()->getType()) {
            $event->setNotificationType(MemberPersonalDataNotificationType::NAME);

            return;
        }
    }
}