
# Headless CMS

## About

Welcome, this is a headless CMS app build with [Fusio](https://github.com/apioo/fusio) an open source API management
solution. At first it is a great learning resource to show how to build and structure a complex API with Fusio. It
contains many comments and should show you a way how to structure your app. It can be used as a blueprint to start a new
API project. More information about Fusio at the website: https://www.fusio-project.org/

## Installation

* Run `composer install` to install all required dependencies
* Enter the correct database credentials, api host url and apps url at the `.env` file
* Run the command `php bin/fusio migrate`
  * This command installs the Fusio and app tables at the provided database
* Run the command `php bin/fusio adduser`
  * This command adds a new administrator account
* Run the command `php bin/fusio login`
  * To authenticate with the account which you have created
* Run the command `php bin/fusio deploy`
  * This command reads the .yaml files at the `resources/` folder and creates the fitting resources.

Note this repository does not contain the Fusio backend app, since we develop the complete API via source files. If you
want to use the backend app you need to install it from the marketplace via: `php bin/fusio marketplace:install fusio`

## Architecture

* `resources` - contains all API configuration files
  * `operations` - folder which contains operation configurations
  * `config.yaml` - contains the Fusio system config
  * `container.php` - contains the [Symfony DI](https://symfony.com/doc/current/components/dependency_injection.html) container configuration
  * `events.yaml` - contains a list of events which are triggered by the app. User can then register HTTP callbacks to receives those events
  * `operations.yaml` - contains all available operations with a reference to a operation file inside the `operations/` folder
  * `typeschema.json` - contains the [TypeSchema](https://typeschema.org/) specification to generate the model classes under `src/Model`
* `src` - contains all PHP source files
  * `Action` - contains all action classes which are used at the defined operations
  * `Migrations` - contains all migration files to setup the database structure (`php bin/fusio migration:generate`)
  * `Model` - contains the generated model classes (`php bin/fusio generate:model`)
  * `Service` - contains the service classes which handle the business logic of your API
  * `Table` - contains all table classes (`php bin/fusio generate:table`)
  * `View` - contains custom views to return the collection and entity response
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
