# Modular CMS

## Introduction
A simple modular CMS-like solution.

A lot of inspiration has come from the Laravel framework and a little bit from WordPress


## Working principle
The CMS is based on a simple MVC pattern. 
The CMS is divided into modules. 
Each module has its own controller(s), model(s) and view(s).

All incoming requests are handled by the index.php file. 
The index.php file will load the core files and the modules.

The core files are the files that are needed to run the APP. 

Each module has its own controller, model and view. Module is in his own folder.
The modules are defined in the config.php file and not loaded dynamically (maybe in the future). 

Module has its initialization class (example: modules/news/NewsInit.php) where are defined the routes and the hooks.

Simple example of a request processing:
```
1. index.php
2. load core files
3. load modules
4. module initialization
5. load routes
6. router finds controller and action
7. call actions
8. action response processing
```


Directory structure:
```
├── config
│   ├── config.php                 application configuration, modules, etc.
│   └── routes.php                 routing definition
├── core
│   ├── controllers                controllers
│   ├── views                      templates
│   │   └── ...
│   ├── Autoload.php               project autoloader
│   ├── BaseController.php         base controller
│   └── Router.php                 routing handler
├── logs
│   └── ...                        log files
├── modules
│   ├── news                       module folder for the News module
│   │   ├── NewsInit.php           module initialization class
│   │   ├── controllers            module controllers
│   │   │   └── NewsController.php
│   │   ├── models                 module models
│   │   │   └── NewsModel.php
│   │   └── views                  module templates
│   │       └── ...
│   └── ...                        other modules
├── core.php                       minimal config/setup for running the app
└── index.php                      entry point for the request
```