<?php

namespace App\Security\Voter;

use App\Entity\Customer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CustomerVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const DELETE = 'POST_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Customer;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Customer */
        $customer = $subject;

        switch ($attribute) {
            case self::EDIT:
                // return $this->canEdit($customer, $user);
                break;
            case self::DELETE:
                return $this->canDelete($customer, $user);
        }

        return false;
    }

    /**
     * Check whether the User is the owner of the Customer.
     */
    public function canDelete(Customer $customer, UserInterface $user): bool
    {
        return $user === $customer->getUser();
    }
}
