# Symfony-Pokemon-API

<p align="center">
  <img src="https://img.shields.io/badge/version-0.4.0-blue.svg">
</p>

# Introduction

This project involves creating a Pokémon API with Symfony. The aim is to discover tools like API Platform, CommitLint, Conventional-Changelog...

## :sparkles: Current Features

The Symfony-Pokemon-API is evolving rapidly, and as of version 0.3.0, it includes a variety of features aimed at providing a robust and flexible API for Pokémon data management. Here are the key features currently implemented:

- **User Authentication System:** A robust user authentication system, including a custom login form, logout route, and detailed handling of authentication errors and successes.

- **API Token Authentication:** Secure access to the API through access token authentication, ensuring that only authorized users can perform certain actions.

- **Automatic API Token Generation:** Tokens are automatically generated upon user creation, streamlining the process of securing new accounts.
Comprehensive Fixtures: Includes predefined fixtures for Pokémon, types, and users, making it easy to populate the database with sample data for testing and development.

- **OpenAPI Specification:** Utilization of OpenApiFactoryDecorator for enhanced API documentation and client generation capabilities.

- **Docker Integration:** A Makefile is provided for quick Docker setup, facilitating easy project setup and container management.

- **Extensive Testing:** Comprehensive tests covering critical functionalities including the creation, modification, and deletion of Pokémon entries, as well as testing for user and type data management.

These features collectively make the Symfony-Pokemon-API a powerful tool for managing Pokémon data and a great starting point for developers looking to explore advanced Symfony capabilities, API development, and security best practices.


## :soon: Upcoming Features

Coming soon.

## :zap: Usage

### :electric_plug: Installation

Before you begin, ensure you have the following prerequisites installed:
- [NodeJS](https://nodejs.org/en) - Node.js® is an open-source, cross-platform JavaScript runtime environment.
- [PHP]() - A popular general-purpose scripting language that is especially suited to web development.
- [Composer](https://getcomposer.org/) - A Dependency Manager for PHP
- [Symfony CLI](https://symfony.com/download) - The Symfony CLI is a developer tool to help you build, run, and manage your Symfony applications directly from your terminal.
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) - The #1 containerization software for developers and teams

Next, configure your environment files:

Copy `.env` to `.env.local` and `.env.test` to `.env.test.local`.

Update the following settings in `.env.local` and `.env.test.local`:

```
APP_SECRET=fake_app_secret
```

You can generate a APP_SECRET [on coderstoolbox website](https://coderstoolbox.online/toolbox/generate-symfony-secret).

After, install the project with the command:

```shell
make setup
```

Finaly, dum

###  :arrow_forward: Running the Project

To start the project, use:
```shell
make start
```

This command starts all necessary Docker containers.

To launch fixture data in database, use:

```shell
make fixture
```

Finaly, go to : [http://localhost:8002/](http://localhost:8002/)

Enjoy !

###  :package: Other Commands

Each command helps with different aspects of development and maintenance of the project:

- `make help` displays the current version of the Symfony-Pokemon-API project, checks for the latest version available, and lists all available make commands with a brief description for each. This is useful for getting a quick overview of how to interact with the project setup.

- `make stop` stops all Docker containers related to the project. This is useful for freeing up system resources or preparing the environment for a fresh restart.

- `make rebuild` performs a series of tasks to rebuild the project:
  1. Stops all running Docker containers to ensure a clean state.
  2. Clears the Symfony cache to ensure the latest changes are used.
  3. Installs dependencies through Composer and npm to update any PHP and JavaScript packages the project depends on.
  4. Rebuilds and restarts the Docker containers to apply changes. This is particularly useful when you've made changes to Docker configurations or when you want to ensure that all components are updated and in sync.

- `make test` runs PHPUnit tests, allowing you to verify that the application's functionality remains intact after changes. Running tests is a critical part of the development process, ensuring code quality and preventing regressions.

- `make fixture` loads fixtures into the database, which is essential for initializing the database with a predefined set of data for testing or development purposes. This command is crucial for ensuring that the application can run with a known state of data.

- `make migration` generates a new migration file based on schema changes. It's used to prepare database updates.

- `make migrate` applies the migration to the database, updating its schema according to the latest changes.

Each of these commands is designed to simplify the development workflow and ensure a smooth experience when working with the Symfony Pokemon API project.

fixture, migration and migrate operate outside of Docker by temporarily adjusting the MYSQL_HOST environment variable to 0.0.0.0, allowing for external database access during their execution. This ensures seamless operation even in environments where direct database access requires specific configurations.


##  :camera: Gallery

<div align="center">
  <a href="https://i.postimg.cc/59FNGTy9/Screenshot-2024-03-07-at-08-07-11.png" target="_blank">
    <img src="https://i.postimg.cc/59FNGTy9/Screenshot-2024-03-07-at-08-07-11.png" width="45%">
  </a>
  <a href="https://i.postimg.cc/J4V4WxHN/Screenshot-2024-03-07-at-09-22-46.png" target="_blank">
    <img src="https://i.postimg.cc/J4V4WxHN/Screenshot-2024-03-07-at-09-22-46.png" width="45%">
  </a>
</div>


## :hammer_and_wrench: Built With

- [Symfony](https://symfony.com/) - The programming language used
- [API Platform](https://api-platform.com/) - A powerful API framework for Symfony
- [Vue](https://vuejs.org/) - The Progressive JavaScript Framework

## :label: Versioning

For version management, this project adheres to [Semantic Versioning (SemVer)](http://semver.org/). This approach ensures that version numbers are assigned in a meaningful way, reflecting the nature of changes between releases.

You can find a detailed version history on the [Releases](https://github.com/julienhouyet/Symfony-Pokemon-API/releases) page of this repository.

## :memo: Changelog

To see a list of recent changes, go to [CHANGELOG.md](CHANGELOG.md).