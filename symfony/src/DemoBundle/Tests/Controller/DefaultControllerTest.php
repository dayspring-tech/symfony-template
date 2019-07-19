<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/18/16
 * Time: 9:31 AM
 */

namespace DemoBundle\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function var_dump;

class DefaultControllerTest extends WebTestCase
{

    public function testIndexAction()
    {
        $client = self::createClient();

        $crawler = $client->request("GET", "/_demo/");
var_dump($client->getResponse()->getContent());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Theme example")')->count()
        );
    }
}