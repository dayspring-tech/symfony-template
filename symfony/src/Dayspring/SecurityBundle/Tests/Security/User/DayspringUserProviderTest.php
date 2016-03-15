<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/15/16
 * Time: 1:30 PM
 */

namespace Dayspring\SecurityBundle\Tests\Security\User;

use Dayspring\SecurityBundle\Model\User;
use Dayspring\SecurityBundle\Security\User\DayspringUserProvider;
use Dayspring\UnitTestBundle\Framework\Test\DatabaseTestCase;

class DayspringUserProviderTest extends DatabaseTestCase
{

    /**
     * @var DayspringUserProvider $userProvider
     */
    protected $userProvider;

    protected function setUp()
    {
        parent::setUp();

        $this->userProvider = new DayspringUserProvider();
    }


    public function testLoadUserByUsername()
    {
        $user = $this->userProvider->loadUserByUsername('testuser');

        $this->assertEquals(1, $user->getId());
    }

    public function testRefreshUser()
    {
        $user = new User();
        $user->setUsername('testuser');

        $refreshedUser = $this->userProvider->refreshUser($user);

        $this->assertEquals(1, $refreshedUser->getId());
        $this->assertEquals('testuser', $refreshedUser->getUsername());
    }

    public function testSupportsClass()
    {
        $user = new User();

        $this->assertTrue($this->userProvider->supportsClass(get_class($user)));
    }
}