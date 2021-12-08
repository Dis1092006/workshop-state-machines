<?php

declare(strict_types=1);

namespace App\StateMachine\State;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;
use App\WorldClock;

class AddYourName implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();

        if (!empty($user->getName())) {
            $stateMachine->setState(new WelcomeNewUser());
            return self::CONTINUE;
        }

        if ($user->getLastSentAt() > WorldClock::getDateTimeRelativeFakeTime('-24hours')) {
            echo 'Wait for the next day for the user ' . $user->getId() . PHP_EOL;
            return self::STOP;
        }

        $mailer->sendEmail($user, 'Add your name');
        return self::STOP;
    }
}