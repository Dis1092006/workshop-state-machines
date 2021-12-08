<?php

declare(strict_types=1);

namespace App\StateMachine\State;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;

/**
 * @author Dmitry Samsonov <dmitry.samsonov@ecentria.com>
 */
final class WelcomeNewUser implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();
        $userName = $user->getName();

        if (empty($userName)) {
            $stateMachine->setState(new AddYourName());
            return self::CONTINUE;
        }

        $mailer->sendEmail($user, 'Welcome ' . $user->getName() . '!');
        $stateMachine->setState(new AddYourEmail());
        return self::CONTINUE;
    }
}