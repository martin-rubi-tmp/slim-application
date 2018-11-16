<?php

namespace Tests\Functional;

use Youbim\Collections\UsersCollection;
use Youbim\Models\User;
use Youbim\Collections\RedBeanCollection;

class ListUsersTest extends BaseTestCase
{
    static public function setUpBeforeClass()
    {
        /// Initialize endpoints
        if( ! RedBeanCollection::is_connected() ) {
            $settings = require __DIR__ . '/../../src/settings.php';
            $mysql = $settings['settings']['mysql'];
            RedBeanCollection::setup_connection( $mysql['connect'], $mysql['user'], $mysql['password'] );
        }
    }

    protected function populate_users()
    {
        UsersCollection::truncate();
        UsersCollection::add(
            new User( "Bart", "Simpson", "bart.simpson@thesimpsons.org", "555 123" )
        );
        UsersCollection::add(
            new User( "Lisa", "Simpson", "lisa.simpson@thesimpsons.org", "555 123" )
        );
    }

    public function testListsAllTheUsers()
    {
        $this->populate_users();

        $response = $this->runApp('GET', '/users');

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode( $response->getBody(), true);

        $this->assertEquals([
            "success" => true,
            "data" => [
                "users" => [
                    [
                        "name" => "Bart", 
                        "last_name" => "Simpson", 
                        "email" => "bart.simpson@thesimpsons.org", 
                        "phone_number" => "555 123"
                    ],
                    [
                        "name" => "Lisa", 
                        "last_name" => "Simpson", 
                        "email" => "lisa.simpson@thesimpsons.org", 
                        "phone_number" => "555 123"
                    ]
                ]
            ]
        ], $json);
    }
}