<?php

use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class UserTest extends TestCase
{

    // use DatabaseTransactions;

    /**
     * A create user test.
     *
     * @return void
     */

    private $fakeUser;
    private $user;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->user = [
            'name' => $this->getFakeUser()->name,
            'email' => $this->getFakeUser()->email,
            'password' => '123',
            'password_confirmation' => '123'
        ];
    }

    public function testCreateUser(){

        $this->post('/api/v'.getenv('APP_VERSION').'/users', $this->user);
        $responseData = (array) json_decode($this->response->content());
        $this->createdUser = new User($responseData);
        $this->assertEquals(201, $this->response->status());

        $this->assertArrayHasKey('name',$responseData);
        $this->assertArrayHasKey('email',$responseData);
        $this->assertArrayHasKey('id',$responseData);

        $this->seeInDatabase('users',[
            'name' => $responseData['name'],
            'email' => $responseData['email']
        ]);
    }

    public function testUpdateUserWithoutPass(){
        $user = User::first();
        $data = [
            "name" => $this->getFakeUser(true)->name,
            "email" => $this->getFakeUser()->email,
        ];

        $this->put('/api/v'.getenv('APP_VERSION').'/users/'.$user->id, $data);
        $responseData = (array) json_decode($this->response->content());
 
        $this->assertResponseOk();

        $this->assertArrayHasKey('name',$responseData);
        $this->assertArrayHasKey('email',$responseData);
        $this->assertArrayHasKey('id',$responseData);

        $this->notSeeInDatabase('users',[
            'name' => $user->name,
            'email' => $user->email,
            'id' => $user->id
        ]);
    }

    public function testUpdateUserWithPass(){
        $user = User::first();
        $data = [
            "name" => $this->getFakeUser(true)->name,
            "email" => $this->getFakeUser()->email,
            "password" => '321',
            "password_confirmation" => '321'
        ];

        $this->put('/api/v'.getenv('APP_VERSION').'/users/'.$user->id, $data);
        $responseData = (array) json_decode($this->response->content());
 
        $this->assertResponseOk();

        $this->assertArrayHasKey('name',$responseData);
        $this->assertArrayHasKey('email',$responseData);
        $this->assertArrayHasKey('id',$responseData);

        $this->notSeeInDatabase('users',[
            'name' => $user->name,
            'email' => $user->email,
            'id' => $user->id
        ]);
    }

    public function testShowAllUsers(){
        $this->get('/api/v'.getenv('APP_VERSION').'/users?limit=10&page=1');
        $this->assertResponseOk();
        $this->seeJsonStructure([
            '*' => [
                'id',
                'name',
                'email'
            ]
        ]);
    }



    public function testDeleteUser(){
        $user = User::first();
        
        $this->delete('/api/v'.getenv('APP_VERSION').'/users/'.$user->id);
        $this->assertResponseOk();
        $this->assertEquals("Deleted Successfully",$this->response->content());
    }

    public function testShowAllTrashedUsers(){
        $this->get('/api/v'.getenv('APP_VERSION').'/users/trashed?limit=10&page=1');
        $this->assertResponseOk();
        $responseData = (array) json_decode($this->response->content());
      
        foreach ($responseData as $key => $data) {
            $this->assertNotNull($data->deleted_at);
        }
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
