<?php

declare(strict_types=1);

namespace App\StateMachine\State;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;

/**
 * @author Dmitry Samsonov <dmitry.samsonov@ecentria.com>
 */
final class FinalState implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();
        $mailer->sendEmail($user, 'Hey ' . $user->getName() . ', you are all set!');
        return self::STOP;
    }
}