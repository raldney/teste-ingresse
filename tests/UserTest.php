<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * A create user test.
     *
     * @return void
     */

    private $fakeUser;

    public function testCreateUser(){
        
        $data = [
            "name" => $this->getFakeUser()->name,
            "email" => $this->getFakeUser()->email,
            "password" => "123"
        ];

        $this->post('/api/users', $data);
        
        $responseData = (array) json_decode($this->response->content());
        
        $this->assertEquals(201, $this->response->status());

        $this->assertArrayHasKey('name',$responseData);
        $this->assertArrayHasKey('email',$responseData);
        $this->assertArrayHasKey('id',$responseData);

        $this->seeInDatabase('users',[
            'name' => $responseData['name'],
            'email' => $responseData['email']
        ]);
    }

    /**
     * Method to generate fake user to test.
     */
    public function getFakeUser($regenerate = false){
        if($this->fakeUser == null || $regenerate)
            return Faker\Factory::create();
        return $this->fakeUser;
    }
}
