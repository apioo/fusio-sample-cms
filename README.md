
# Headless CMS

## About

Welcome, this is a headless CMS app build with [Fusio](https://github.com/apioo/fusio)
an open source API management solution. At first it is a learning resource to
show how to build and structure a complex API with Fusio. It contains many
comments and should show you a way how to structure your app. It can be used as
a blueprint to start a new API project. More information about Fusio at the
website: https://www.fusio-project.org/

### Installation

* Enter the correct database credentials at the `.env` file
* Run the command `php bin/fusio install`
  * This command install the Fusio tables at the provided database
* Run the command `php bin/fusio deploy`
  * This command reads the .yaml files at the `resources/` folder and creates
    the fitting resources.
* Run the command `php bin/fusio migration:migrate --connection=System`
  * This command executes the migrations defined at the `src/Migrations/System`
    folder. In this case we use the System connection but you can also execute
    migrations on different connections 

Note this repository does not contain the Fusio backend app, since we develop
the complete API via source files. If you want to use the backend app you need
to install it from the marketplace via: `php bin/fusio marketplace:install fusio`

### Structure

* `resources` - contains all API configuration files
  * `routes` - folder which contains route configurations
  * `schema` - folder which contains schema configurations
  * `config.yaml` - contains the Fusio system config
  * `connections.yaml` - contains a list of all available connections to remote systems i.e. a database or message-queue
  * `event.yaml` - contains a list of events which are triggered by the app. User can then register HTTP callbacks to receives those events
  * `routes.yaml` - contains an index of all available routes with a reference to a route file inside the `routes/` folder
  * `schemas.yaml` - contains an index of all available schemas with a reference either to a schema file inside the `schema/` folder or a PHP schema class
* `src` - contains all PHP source files
  * `Action` - contains all action classes which are used at the defined routes
  * `Dependency` - contains a custom DI container to register services for the app
  * `Migrations` - contains all migration files to setup the database structure
  * `Schema` - contains schema classes which are received at the action
  * `Service` - contains the service classes which handle the business logic of your API
* `tests` - contains all PHP test files
  * `Api` - contains all API integration tests. Theses tests trigger the API endpoint like if you call them via a HTTP client but without the need to setup an actual HTTP server
    * `Page` - contains the page related endpoint tests
    * `Post` - contains the post related endpoint tests

#### Summary

With theses few source files we have created a production ready API with all
modern features like:

* OpenAPI documentation
* Developer portal (login/registration with social login)
* Schema validation
* OAuth2 authorization with scopes for specific parts of the API
* Rate limiting
* Pub/sub support (using CloudEvents)
* SDK generation
* Monetization
* Testing

Please let us know if there is documentation missing or if you like to handle
a specific use case.
