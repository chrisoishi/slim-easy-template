# SLIM EASY TEMPLATE

Easy template for SLIM V4 with pre configurations to start dev your API quickly. 

The objective this template is use SLIM FRAMEWORK V4 with struct folders and basic helpers integrations of models, controller, database and auth (in future, maybe more).

This template is also way to implements API efficent, organized and scalable. You don't need many time to understand this template, if you already used SLIM.

You can use  the Skeleton template also for develop with SLIM, this template is official: https://github.com/slimphp/Slim-Skeleton.

i used some ideias of skeleton template and laravel framework to make this.

## Installation to test
 - Run: 
>composer create-project chrisoishi/slim-easy-template slim_project_name

- Edit .env file with your database informations

- Run migrations of auth system:
>vendor\bin\phinx migrate

- Run local server:
> composer serve

## Struct folders 
```
- app
    - controllers 
        - all controllers here
    - helpers 
        - all functions helpers here
    - middlewares 
        - all middlewares here
	- models
	    -  all models database tables here
	- modules
	    - similiar packages of packgist, but simple and for use especifcs a your projects
- configs
- db
    - all migrations database here
- public
```	

## Database
This template based in MySQL database and use Medoo to manage data. 

More informations about Medoo -> https://medoo.in/

**To configure you database, edit de .env file.**

## Migrations
For migrations, this template use phinx extension ( for docs https://phinx.org/)

The default folder migrations is in db\migrations

## Models
This template, use classes of ApiSupport, avaiable in modules folder. This classes has develop by me =D (more informations about this classes will be at the end)

Basically you need create a file in models folder with the simple code:

```
<?php
namespace  App\Models;
use App\Modules\ApiSupport\Model;
class  User  extends  Model
{
	const table = "users"; #this name of table in database
	const pk = "id"; # this field name of primary key
	const  protected = ["password"]; # this array of fields name to not send in response
	const cast = ["id"=>"interger"] #this array to cast field to necessary type
}
```
The class Model extended, have many functions to manage data in database, i recommend to check this class file for learn the features of this class.

## Controllers
The controllers also use the classes of ApiSupport, but are similar a examples in docs of SLIM.

```
The
<?php
namespace App\Controllers;
use App\Modules\ApiSupport\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller{
	public function home(Request $request, Response $response, array $args): Response{
		$response->getBody()->write("Hello world"));
		return $response;
	}
}
```


### Validations request
To validations your request data, please check the example in AuthController. The validations use respect/validation package (for docs access https://github.com/Respect/Validation)

## Middlewares

Has a example the middleware for authenticaton in this template. You also can check for more details about middlewares in SLIM docs.  

## Authentication
This template have a basic model authentication, but efficient. Have implemeted register,login, login with token and refresh token functions.

Are 3 files implemented:
- **AuthMiddleware class**: to use in routes for enable authentication requirement
- **Auth class**: with functions to register login login with token
- **AuthToken class**:: model to manage the AuthTokens ( generate, refresh, expirations...)

You can edit as you want auth implements, just check the auth files.

**To test authtentication, just check the routes file (configs/routes.php) to preview auth routes name.**

Steps:
- Login with email and password, and you received a token and refresh token
- use the token in authorization head in your requests -> **Bearer token**
- if you token is expired use the refresh token to get new token.

### Register new user
Use the route "auth/register" with data:
    
- email
- name
- password
- password_confirm

### Login

Use the route "auth/login" with data:
- email
- password

### Adding AuthMiddleware in your requests

This is a example.

>$app->get('/', HomeController::class . ":home")->add(new AuthMiddleware());

Check the routes files in "configs/routes.php"


### Getting user logged in code
To get user logged after your request passed AuthMiddleware. use de **Auth::$user** code

>Auth::$user #return the User classs model


## ApiSupport
This modules contains my implementations helpers to manage Models, Controller, Auth, Middleware, many features still need to be done, but this classes is working.

If you need new functions in these classes, you can easily implement them, as they are not complex, they were made just to help.

In future, i will create a docs for this classes.

## Tips

If you need manage Dates use Carbon, this packages  has been added. https://carbon.nesbot.com/docs/


Thanks for read or use:)
