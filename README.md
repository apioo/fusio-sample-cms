
# Headless CMS

## About

Welcome, this is a headless CMS app build with [Fusio](https://github.com/apioo/fusio) an open source API management
solution. At first it is a great learning resource to show how to build and structure a complex API with Fusio. It
contains many comments and should show you a way how to structure your app. It can be used as a blueprint to start a new
API project. More information about Fusio at the website: https://www.fusio-project.org/

## Installation

* Run `composer install` to install all required dependencies
* Enter the correct database credentials, api host url and apps url ( if you use fusio* apps ) at the `.env` file
* Run the command `php bin/fusio migrate`
  * This command install the Fusio and app tables at the provided database
* Run the command `php bin/fusio adduser`
  * This command adds a new administrator account
* Run the command `php bin/fusio login`
  * To authenticate with the account which you have created
* Run the command `php bin/fusio deploy`
  * This command reads the .yaml files at the `resources/` folder and creates the fitting resources.

Note this repository does not contain the Fusio backend app, since we develop the complete API via source files. If you
want to use the backend app you need to install it from the marketplace via: `php bin/fusio marketplace:install fusio`

## Architecture

We try to design the app framework independent so that it is possible to reuse your business logic also in another
context. For all write operations the app contains a simple design:

* Actions - invokes the service
  * Service - contains the business logic
    * Repository - contains all database interactions

For all read operations the action i.e. `Get` and `GetAll` contains the query logic since we use the 
`Builder` class to generate a nested response. If you want to be completely framework independent you can
also move this logic into a separate service/repository.

## Structure

* `resources` - contains all API configuration files
  * `operations` - folder which contains operation configurations
  * `config.yaml` - contains the Fusio system config
  * `container.php` - contains a list of events which are triggered by the app. User can then register HTTP callbacks to receives those events
  * `events.yaml` - contains a list of events which are triggered by the app. User can then register HTTP callbacks to receives those events
  * `operations.yaml` - contains an index of all available routes with a reference to a route file inside the `routes/` folder
  * `typeschema.json` - contains an index of all available routes with a reference to a route file inside the `routes/` folder
* `src` - contains all PHP source files
  * `Action` - contains all action classes which are used at the defined routes
  * `Migrations` - contains all migration files to setup the database structure
  * `Model` - contains the generated model classes
  * `Service` - contains the service classes which handle the business logic of your API
  * `Table` - contains the service classes which handle the business logic of your API
  * `View` - contains the service classes which handle the business logic of your API
* `tests` - contains all PHP test files
  * `Api` - contains all API integration tests. These tests trigger the API endpoint like if you call them via a HTTP client but without the need to setup an actual HTTP server

## Summary

With these few source files we have created a production ready API with all modern features like:

* OpenAPI documentation
* Developer portal (login/registration with social login)
* Schema validation
* OAuth2 authorization with scopes for specific parts of the API
* Rate limiting
* Pub/sub support (using CloudEvents)
* SDK generation
* Monetization
* Testing

Please let us know if there is documentation missing or if you like to handle a specific use case.
