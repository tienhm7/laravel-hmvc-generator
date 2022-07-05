[![Latest Stable Version](https://poser.pugx.org/tienhm7/laravel-hmvc-generator/v/stable)](https://packagist.org/packages/tienhm7/laravel-hmvc-generator)
[![Total Downloads](https://poser.pugx.org/tienhm7/laravel-hmvc-generator/downloads)](https://packagist.org/packages/tienhm7/laravel-hmvc-generator)
[![Latest Unstable Version](https://poser.pugx.org/tienhm7/laravel-hmvc-generator/v/unstable)](https://packagist.org/packages/tienhm7/laravel-hmvc-generator)
[![composer.lock](https://poser.pugx.org/tienhm7/laravel-hmvc-generator/composerlock)](https://packagist.org/packages/tienhm7/laravel-hmvc-generator)
[![License](https://poser.pugx.org/tienhm7/laravel-hmvc-generator/license)](https://packagist.org/packages/tienhm7/laravel-hmvc-generator)
# HMVC_Generator
A Laravel package to create and manage your large laravel app using modules [HMVC]


### Folder Structure
- Modules
	- User
        - Config/
        - Database/
            - Migrations/
        - Http/
            - Controllers/
                - UserController.php
            - Middleware/
                - UserMiddleware.php
            - Requests/
                - UserRequest.php
        - Models/
            - User.php
        - Providers/
            - UserServiceProvider.php
        - Lang/
            - en/
            - vi/
        - Views/
            - index.blade.php
        - Routes/
            - web.php  "All Routes under "users" prefix"
            - api.php  "All Routes under "api/users" prefix"
	
### Artisan Commands
- To create a new module you can simply run :
```
php artisan module:make <module_name>
```
- Create new Controller for the specified module :
```
php artisan module:make-controller <controller_name> --module=<module_name>
```
- Create new Model for the specified module :
```
php artisan module:make-model <model_name> --module=<module_name>
```
- Create new Middleware for the specified module :
```
php artisan module:make-middleware <middleware_name> --module=<module_name>
```
- Create new Request for the specified module :
```
php artisan module:make-request <request_name> --module=<module_name>
```
- Create new Migration for the specified module :
```
php artisan module:make-migration <migration_name> --module=<module_name> --table=<table_name>

// Example:
php artisan module:make-migration create_posts_table --module=Post
```

### Routes
> **api.php** => These routes are loaded by the <module_name>ServiceProvider within a group which is assigned the "api" middleware group and "api/<module_name>" prefix

> **web.php** => These routes are loaded by the <module_name>ServiceProvider within a group which contains the "web" middleware group and "<module_name>" prefix.

### Views
> Calling View: view('<module_name>::view_file_name')

## You need to add module service provider to the list of providers in the config/app.php file and run
```
composer dump-autoload 
```
