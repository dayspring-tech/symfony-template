<?php

namespace Dayspring\SecurityBundle\Model;

use Dayspring\SecurityBundle\Model\om\BaseUser;
use PropelPDO;
use Symfony\Component\Security\Core\User\UserInterface;

class User extends BaseUser implements UserInterface
{
    public function eraseCredentials()
    {
    }

    public function getRoles($criteria = null, PropelPDO $con = null)
    {
        $dbRoles = parent::getRoles($criteria, $con);

        $roles = [];
        foreach ($dbRoles as $r) {
            $roles[] = $r->getRoleName();
        }

        return $roles;
    }


}
