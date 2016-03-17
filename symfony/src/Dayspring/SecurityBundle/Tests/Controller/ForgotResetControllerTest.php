<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 12:25 PM
 */

namespace Dayspring\SecurityBundle\Tests\Controller;

use Dayspring\SecurityBundle\Model\User;
use Dayspring\SecurityBundle\Model\UserQuery;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class ForgotResetControllerTest extends WebTestCase
{

    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        parent::setUp();

        $this->client = self::createClient();
    }

    public function testForgotPassword()
    {
        $crawler = $this->client->request("GET", "/forgot-password");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Forgot Password")')->count()
        );

        $form = $crawler->selectButton("Submit")->form();
        $form['form[email]'] = 'testuser@example.com';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Check your email for instructions on how to reset your")')->count()
        );
    }

    public function testForgotPasswordUnknownUser()
    {
        $crawler = $this->client->request("GET", "/forgot-password");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Forgot Password")')->count()
        );

        $form = $crawler->selectButton("Submit")->form();
        $form['form[email]'] = 'foobar@doesnotexist.com';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("does not exist.")')->count()
        );
    }

    public function testResetPasswordBadToken()
    {
        $this->client->request("GET", "/reset-password/badtoken");

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testResetPassword()
    {
        $user = new User();
        $user->setEmail(sprintf("test+%s@test.com", microtime()));
        $user->setPassword("myoldpassword");
        $token = $user->generateResetToken();

        $crawler = $this->client->request("GET", "/reset-password/".$token);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Reset Password")')->count()
        );

        $form = $crawler->selectButton("Save")->form();
        $form['reset_password[password][first]'] = 'Welcome1';
        $form['reset_password[password][second]'] = 'Welcome1';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("New password has been saved")')->count()
        );

        $user->reload();
        $this->assertNotEquals("myoldpassword", $user->getPassword());
    }

    public function testResetPasswordValidationError()
    {
        $user = new User();
        $user->setEmail(sprintf("test+%s@test.com", microtime()));
        $user->setPassword("myoldpassword");
        $token = $user->generateResetToken();

        $crawler = $this->client->request("GET", "/reset-password/".$token);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Reset Password")')->count()
        );

        $form = $crawler->selectButton("Save")->form();
        $form['reset_password[password][first]'] = 'Welcome1';
        $form['reset_password[password][second]'] = 'doesnotmatch';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("The password fields must match")')->count()
        );

        $user->reload();
        $this->assertEquals("myoldpassword", $user->getPassword());
    }

    public function testChangePasswordNotLoggedIn()
    {
        $crawler = $this->client->request("GET", "/account/change-password");

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Log In")')->count()
        );
    }

    public function testChangePassword()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');

        $user = new User();
        $user->setEmail(sprintf("test+%s@test.com", microtime()));
        $encoded = $encoder->encodePassword($user, 'password');
        $user->setPassword($encoded);
        $user->save();

        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = $user->getUsername();
        $form['_password'] = 'password';

        $crawler = $this->client->submit($form);

        $crawler = $this->client->request("GET", "/account/change-password");
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Change Password")')->count()
        );

        $form = $crawler->selectButton('Save')->form();
        $form['change_password[password]'] = 'password';
        $form['change_password[newPassword][first]'] = 'Welcome1';
        $form['change_password[newPassword][second]'] = 'Welcome1';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("New password has been saved")')->count()
        );

        $user->reload();
        $this->assertNotEquals($encoded, $user->getPassword());
    }

    public function testChangePasswordNoMatch()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');

        $user = new User();
        $user->setEmail(sprintf("test+%s@test.com", microtime()));
        $encoded = $encoder->encodePassword($user, 'password');
        $user->setPassword($encoded);
        $user->save();

        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = $user->getUsername();
        $form['_password'] = 'password';

        $crawler = $this->client->submit($form);

        $crawler = $this->client->request("GET", "/account/change-password");
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Change Password")')->count()
        );

        $form = $crawler->selectButton('Save')->form();
        $form['change_password[password]'] = 'password';
        $form['change_password[newPassword][first]'] = 'Welcome1';
        $form['change_password[newPassword][second]'] = 'doesnotmatch';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("The password fields must match")')->count()
        );

        $user->reload();
        $this->assertEquals($encoded, $user->getPassword());
    }


}
