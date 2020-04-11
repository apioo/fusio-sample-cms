
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

* `resources`
  * `routes`
  * `schema`
  * `config.yaml`
  * `connections.yaml`
  * `event.yaml`
  * `routes.yaml`
  * `schemas.yaml`
* `src`
  * `Action`
  * `Dependency`
  * `Migrations`
  * `Schema`
  * `Service`
