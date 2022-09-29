<?php

declare(strict_types=1);

namespace Terminal42\NotificationCenterBundle\EventListener\Backend\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Terminal42\NotificationCenterBundle\MessageType\FormGeneratorMessageType;
use Terminal42\NotificationCenterBundle\NotificationCenter;

class FormListener
{
    public function __construct(private NotificationCenter $notificationCenter)
    {
    }

    /**
     * @return array<string>
     */
    #[AsCallback(table: 'tl_form', target: 'fields.nc_notification.options')]
    public function onNotificationOptionsCallback(DataContainer $dc): array
    {
        return $this->notificationCenter->getNotificationsForMessageType(FormGeneratorMessageType::NAME);
    }
}
