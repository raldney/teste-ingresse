# Teste Ingresse

[![Build Status](https://travis-ci.org/raldney/teste-ingresse.svg?branch=master)](https://travis-ci.org/raldney/teste-ingresse)
[![codecov.io](https://codecov.io/github/raldney/teste-ingresse/coverage.svg?branch=master)](https://codecov.io/github/raldney/teste-ingresse?branch=master)

Docker running Ubuntu, Nginx, PHP-FPM, REDIS, MySQL and PHPMyAdmin.




1. [Install prerequisites](#install-prerequisites)

    Before installing project make sure the following prerequisites have been met.

2. [Clone the project](#clone-the-project)

    We’ll download the code from its repository on GitHub.

3. [Run the application](#run-the-application)

    By this point we’ll have all the project pieces in place.

4. [EndPoints](#endpoints)

    User resources

## Install prerequisites

For now, this project has been mainly created for Unix `(Linux/MacOS)`. Perhaps it could work on Windows.

All requisites should be available for your distribution. The most important are :

* [Git](https://git-scm.com/downloads)
* [Docker](https://docs.docker.com/engine/installation/)
* [Docker Compose](https://docs.docker.com/compose/install/)

Check if `docker-compose` is already installed by entering the following command : 

```sh
which docker-compose
```

Check Docker Compose compatibility :

* [Compose file version 3 reference](https://docs.docker.com/compose/compose-file/)

### Images to use

* [Ubuntu](https://hub.docker.com/_/ubuntu/)
* [MySQL](https://hub.docker.com/_/mysql/)
* [Redis](https://hub.docker.com/_/redis/)
* [PHPMyAdmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin/)

You should be careful when installing third party web servers such as MySQL or Nginx.

This project use the following ports :

| Server     | Port |
|------------|------|
| MySQL      | 8989 |
| PHPMyAdmin | 8080 |
| Nginx      | 8000 |
| Redis      | 6379 |

___

## Clone the project

To install [Git](http://git-scm.com/book/en/v2/Getting-Started-Installing-Git), download it and install following the instructions :

```sh
git clone https://github.com/raldney/teste-ingresse.git
```

Go to the project directory :

```sh
cd teste-ingresse
```
___

## Run the application

1. Start the application :

    ```sh
    sudo docker-compose up -d
    ```

    **Please wait this might take a several minutes...**

    ```sh
    sudo docker-compose logs -f # Follow log output
    ```

3. Open your favorite browser :

    * [http://localhost:8000](http://localhost:8000/)
    * [http://localhost:8080](http://localhost:8080/) PHPMyAdmin (host: mysql, username: root, password: )
    
    OR 
    * [http://0.0.0.0:8000](http://0.0.0.0:8000/)
    * [http://0.0.0.0:8080](http://0.0.0.0:8080/) PHPMyAdmin (host: mysql, username: root, password: )

4. Stop and clear services

    ```sh
    sudo docker-compose down -v
    ```

___


## EndPoints
	PATH /api/v1

### EndPoint - GET Users
	GET /users

#### Description
	Return all users
#### Parameters
- **limit**  — Users per page.
- **page**  — Number of page.

#### Example
	GET /api/v1/users?limit=10&page=1
**Result**
``` json
[
	{
		"id":1,
		"name":"admin",
		"email":"admin@admin.com",
		"created_at":"2018-09-11 01:38:37",
		"updated_at":"2018-09-11 01:38:37",
		"deleted_at":null
	}
]

```
___


### EndPoint - GET User
	GET /user/{id}

#### Description
	Return a user

#### Example
	GET /api/v1/users/1
**Result**
``` json
[
	{
		"id":1,
		"name":"admin",
		"email":"admin@admin.com",
		"created_at":"2018-09-11 01:38:37",
		"updated_at":"2018-09-11 01:38:37",
		"deleted_at":null
	}
]
```
___ 

### EndPoint - GET User trashed
	GET /users/trashed

#### Description
	Return all users that was deleted
	
#### Parameters
- **limit**  — Users per page.
- **page**  — Number of page.

#### Example
	GET /api/v1/users/trashed?limit=10&page=1
**Result**
``` json
[
	{
		"id":1,
		"name":"admin",
		"email":"admin@admin.com",
		"created_at":"2018-09-11 01:38:37",
		"updated_at":"2018-09-11 01:38:37",
		"deleted_at":"2018-09-11 05:38:37"
	}
]
```
___


### EndPoint - POST User
	POST /users

#### Description
	Create a user
	
#### Parameters
	All parameters should be pass by body in json format
- **name** (required) — Name of user.
- **email** (required | unique) — E-mail.
- **password** (required) — password.
- **password_confirmation** (required) — Confirmation password.

#### Example
	POST /api/v1/users
**Result**
``` json
[
	{
		"name": "raldney",
		"email": "raldney.alves@gmail.com3",
		"updated_at": "2018-09-10 15:35:20",
		"created_at": "2018-09-10 15:35:20",
		"id": 33
	}
]
```
___


### EndPoint - PUT User
	PUT /users/{id}

#### Description
	Update a user
	
#### Parameters
	All parameters should be pass by body in json format
- **name** (required) — Name of user.
- **email** (required | unique) — E-mail.

	Send password confirmation when you want to update
	
- **password** (required) — password.
- **password_confirmation** (required) — Confirmation password.

#### Example
	PUT /api/v1/users/33?name=raldney&email=raldney.alves@gmail.com5
	OR
	PUT /api/v1/users/33 (Parameters by body)
**Result**
``` json
[
	{
		"name": "raldney",
		"email": "raldney.alves@gmail.com5",
		"updated_at": "2018-09-10 15:50:20",
		"created_at": "2018-09-10 15:35:20",
		"id": 33
	}
]
```
___

### EndPoint - Delete User
	Delete /users/{id}

#### Description
	Delete a user

## Errors
All known errors cause the resource to return HTTP error code header together with a JSON array containing at least 'status' and 'error' keys describing the source of error.

- **401 Unauthorized** — The specified user was not found.

#### Example
	DELETE /api/v1/users/33
**Result**
``` json
[
	{
		"name": "raldney",
		"email": "raldney.alves@gmail.com5",
		"updated_at": "2018-09-10 15:50:20",
		"created_at": "2018-09-10 15:35:20",
		"deleted_at": "2018-09-10 15:55:20",
		"id": 33
	}
]
```

