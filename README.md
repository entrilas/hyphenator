# English Language Hyphenator-PHP

Hyphenator for English language made in plain PHP.

## Description

Hyphenator for English language made in plain PHP. Application contains three different usage types:

* CLI
* REST API
* Internet Application

The main purpose of this application was to create efficient algorithm, which can hyphenate large file of data and output correct result.
During the development of the application, it was necessary to learn and implement these principles:

- OOP Principles.
- PSR Standards.
- REGEX.
- MySQL and PDO.
- REST Api.
- Designs patterns.
- Dependency injections.
- Composer usage.
- Composer package creation.
- Clean code, SOLID, DRY, KISS principles.
- PHPUnit, Codeception testing.
- Frontend basics.
- Dockerizing application.
- ...


## Installation 

Before using the application it is must to do these steps:

* It is dockerized application, use it by running docker environment.
* Migrate tables to database by command, which is implemented: php index.php migrate
* In config folder there are settings for application, which you can setup yourself:

- config.json file is responsible for global application settings, for example:
- 'USE_DATABASE' option is responsible for using database or not so if you are planning to use CLI, REST API, Internet Application at the same time
you should put this option to true...
- database.json file is responsible for database connection information.
logger.json file is responsible for logging data. Either you want to log information to file/console you can setup it yourself...


## Getting Started

Usage in Console :

* php index.php [flag] [data] 

[flag] types:
* word
* sentence (sentence must be in "sentence")
* file (must provide absolute path)
* patterns
* migrate

Examples :
* php index.php word computer
* php index.php sentence "computer is the best device."
* php index.php file "/home/pc/Desktop/hyphenator/resources/text.txt"
* php index.php patterns "/home/pc/Desktop/hyphenator/resources/patterns.txt"
* php index.php migrate

REST API Usage :

Endpoints :

* GET /api/words
* POST /api/words/{id}
* PUT /api/words/{id}
* GET /api/words/{id}

* GET /api/patterns
* POST /api/patterns/{id}
* PUT /api/patterns/{id}
* GET /api/patterns/{id}


## Authors

Contributors names and contact info

- Arnas Dulinskas  
- [@LinkedIn](https://www.linkedin.com/in/arnas-dulinskas-b148481b6)
- Application was created during [@Visma](https://www.visma.lt/) Internship, 2022

    
