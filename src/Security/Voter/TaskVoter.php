<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $attribute
     * @param $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return \in_array($attribute, ['TASK_DELETE'], true)
            && $subject instanceof \App\Entity\Task;
    }

    /**
     * @param string $attribute
     * @param $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Task $task */
        $task = $subject;
        /** @var User $user */

        // ... (check conditions and return true to grant permission) ...
        if ($attribute == 'TASK_DELETE') {
            return $this->canDelete($task, $user);
        }

        return false;
    }


    /**
     * @param Task $task
     * @param User $user
     *
     * @return bool
     */
    private function canDelete(Task $task, User $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN') && (null === $task->getAuthor())) {
            return true;
        }

        return $user === $task->getAuthor();
    }
}
