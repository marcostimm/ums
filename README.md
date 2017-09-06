# User Management System using Symfony 3
Example Usage for a User Management System with Authentication and user groups using Symfony 3. This example also expose a API methods for external manipulation just adding a prefix `api\` (e.g. http://127.0.0.1:8000/api/user) and this will give a jSON result.

## Getting Started
For this project I used PHP Symfony 3 Framework with a MySQL database. For a quick start just create a new container with [Docker Kitematic](https://kitematic.com/) with *MySQL 5.7* oficial image. Don't forgot do set MYSQL_ROOT_PASSWORD and configure this password (and port) in `app\config\parameters.yml` file.

## Installing
Just run the composer install command
```
composer install
```
IMPORTANT: Configure your MySQL Volume path to a folder *mysql* on this repository. 

This project use a Many-to-many relationships between User and Group, since a Group contains multiple User, and a User can belong to multiple Group. Like model below:
![model](https://marcos.im/git/umsmodel.png)

## Running
You can use PHP's built-in Web Server through the Symfony command: 

````
php bin/console server:run
````
And you should see something like: `[OK] Server listening on http://127.0.0.1:8000`.                                                                         

## TODO
Create a API Key Authenticator

## Author
* **Marcos Timm Rossow** - *Initial work* - [/marcostimm](https://github.com/marcostimm)

## License
This project is licensed under the MIT License