<?php

declare(strict_types=1);

namespace App;

use App\Service\Database;
use App\Service\MailerService;
use App\StateMachine\State;

class Worker
{
    private $db;
    private $mailer;

    public function __construct(Database $em, MailerService $mailer)
    {
        $this->db = $em;
        $this->mailer = $mailer;
    }

    public function run()
    {
        $users = $this->db->getAllUsers();

        foreach ($users as $user) {
            $stateMachine = new StateMachine\StateMachine($this->mailer, $user);
            $finished = $stateMachine->start(new State\AddYourName());
            if ($finished) {
                // remove from database
            }
        }

        $this->db->saveUsers($users);
    }
}
