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
    public const VIEW = 'POST_VIEW';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::VIEW])
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
            case self::VIEW:
                return $this->canView($customer, $user);
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
        return $this->canView($customer, $user);
    }

    /**
     * Check whether the User is the owner of the Customer.
     */
    public function canView(Customer $customer, UserInterface $user): bool
    {
        return $user === $customer->getUser();
    }
}
