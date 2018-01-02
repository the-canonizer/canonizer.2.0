## Canonizer 2.0

   Canonizer.com is a wiki system that solves the critical liabilities of Wikipedia. It solves petty "edit wars" by providing contributors the ability to create and join camps and present their views without having them immediately erased. It also provides ways to standardize definitions and vocabulary, especially important in new fields.


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

## Prerequisites
Things you need on your machine to install this project

1. Git: You should have git installed on your machine, If you do not have git installed on your system please visit https://www.atlassian.com/git/tutorials/install-git follow instructions and installe it.

2. Composer: Composer is an application-level package manager for the PHP programming language that provides a standard format for managing dependencies of PHP software and required libraries. 

To install composer visit - https://getcomposer.org/

3. php version required is >=5.6

## Installing
1. Clone Project from github - https://github.com/the-canonizer/canonizer.2.0
Command: git clone https://github.com/the-canonizer/canonizer.2.0.git

2. Run composer 
php composer update

composer will install all dependecies and all laravel packages.

3. Database & its Cnfiguration
 path to DB - database/canonizer2.sql

 Composer should have created .env file for you where you have to set the DB credentials once you upload dastabase to your phpmyadmin.

 Note: If .env file is no beuign generated automatically, you can create it by copying .env.example

 Now you are ready to go
localhost/project_directory/public it should lead you to home page now.

You can set virtual host if you want to avoid "public" in url.

## Coding Standards To Follow

1. For DB changes: create migration for any db change. Do not forget to add migration file in your commit.

2. All dependencies should be added through composer only.

## Deployement
1. create  your own branch for task you have done with "your name or username" (developer name or username)
example:  git checkout -b dev-jackson

2. Commit changes 

3. push the branch
git push origin dev-jackson

Thats it!

## Contributing

Thank you for considering contributing to the Canonizer! 

## License
canonizer2.0
