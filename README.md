# Nextia Tech Practice

Nextia Tech Practice by Efraín Cárdenas

## Requirements

* Docker and Docker-Compose

## Installation

* Be sure that Docker Daemon is running.
* Go to root folder and build the Docker image with "docker-compose build".
* Up the instance with "docker-compose up -d".
* Execute the migrations with "docker-compose run --rm api php artisan migrate"
* You can check the results with PHPPgAdmin, mounted on http://localhost:8080/

## Endpoints

- Users
	- Sign up: 	POST http://localhost:8000/api/v1/signup/
	- Login: 	POST http://localhost:8000/api/v1/login/
- Bienes
	- Load CSV: 	GET http://localhost:8000/api/v1/bien/seed
	- List Bienes: 	GET http://localhost:8000/api/v1/bien/list/
	- Get Bien: 	GET http://localhost:8000/api/v1/bien/view/{id}
	- Create Bien: 	POST http://localhost:8000/api/v1/bien/create/
	- Get Many: 	GET http://localhost:8000/api/v1/bien/viewmany?id[]=3&id[]=4
	- Update Bien: 	PUT http://localhost:8000/api/v1/bien/update/{id}
	- Delete Bien: 	DELETE http://localhost:8000/api/v1/bien/delete/{id}
