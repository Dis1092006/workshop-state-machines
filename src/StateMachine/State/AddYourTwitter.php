<?php

/*
 * This file is part of OpticsPlanet, Inc. software
 *
 * (c) 2021, OpticsPlanet, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\StateMachine\State;

use App\Service\MailerService;
use App\StateMachine\StateMachineInterface;
use App\WorldClock;

/**
 * @author Dmitry Samsonov <dmitry.samsonov@ecentria.com>
 */
final class AddYourTwitter implements StateInterface
{
    public function send(StateMachineInterface $stateMachine, MailerService $mailer): int
    {
        $user = $stateMachine->getUser();

        if (!$user->getTwitter() === null) {
            $stateMachine->setState(new FinalState());
            return self::CONTINUE;
        }

        if ($user->getLastSentAt() > WorldClock::getDateTimeRelativeFakeTime('-24hours')) {
            echo 'Wait for the next day for the user ' . $user->getId() . PHP_EOL;
            return self::STOP;
        }

        $mailer->sendEmail($user, 'Add your twitter');
        return self::STOP;
    }
}