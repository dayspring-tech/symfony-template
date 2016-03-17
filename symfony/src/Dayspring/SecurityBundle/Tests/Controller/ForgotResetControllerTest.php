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

        $form['resetPassword[password][first]'] = 'Welcome1';
        $form['resetPassword[password][second]'] = 'Welcome1';
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

        $form['resetPassword[password][first]'] = 'Welcome1';
        $form['resetPassword[password][second]'] = 'doesnotmatch';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("The password fields must match")')->count()
        );

        $user->reload();
        $this->assertEquals("myoldpassword", $user->getPassword());
    }

}
