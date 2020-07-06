## Canonizer 2.0

   Canonizer.com is a wiki system that solves the critical liabilities of Wikipedia. It solves petty "edit wars" by providing contributors the ability to create and join camps and present their views without having them immediately erased. It also provides ways to standardize definitions and vocabulary, especially important in new fields.


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

## Prerequisites
Things you need on your machine to install this project

1. Git: You should have git installed on your machine, If you do not have git installed on your system please visit https://www.atlassian.com/git/tutorials/install-git follow instructions and install it.

2. Composer: Composer is an application-level package manager for the PHP programming language that provides a standard format for managing dependencies of PHP software and required libraries. 

To install composer visit - https://getcomposer.org/

3. php version required is >=5.6

## Installing
1. Clone Project from github - https://github.com/the-canonizer/canonizer.2.0
Command: git clone https://github.com/the-canonizer/canonizer.2.0.git

2. Run composer after connecting to your repository root directory (where composer.phar exists)
php composer.phar update

composer will install all dependencies and all laravel packages.

3. Database & its Configuration
 path to DB - database/canonizer2.sql

 Composer should have created .env file for you where you have to set the DB credentials once you upload database to your phpmyadmin.

 Note: If .env file is not being generated automatically, you can create it by copying .env.example

 Now you are ready to go
localhost/project_directory/public it should lead you to home page now.

You can set virtual host if you want to avoid "public" in url.

## Installing on Mac OS:

1. Apache comes installed with the Mac OS, but not MySQL. An easy installation package is available online here: https://dev.mysql.com/doc/refman/5.6/en/osx-installation-pkg.html 

2. You can use MySQL Workbench as a GUI interface to set up your database. Workbench us available here: https://dev.mysql.com/downloads/workbench/

3. Sourcetree is the best GUI interface for Git on Mac. You can download it here: https://downloads.atlassian.com/software/sourcetree/Sourcetree_2.7.1d.zip?_ga=2.264683343.1572218970.1521135631-982429275.1520535016

4. When setting up your local version of canonizer, you'll first need to create the canonizer.2.0 directory as a subdirectory of /Library/WebServer/Documents/ and then place the .env file in the canonizer.2.0 directory. 

5. Open Sourcetree. Under the "File" menu option, select "New." Select "Clone from URL."

Source URL: https://github.com/the-canonizer/canonizer.2.0.git
Destination Path: /Library/WebServer/Documents/canonizer.2.0
Name: canonizer.2.0

Click "Advanced Options" and select the branch you want to clone. 

6. Open Terminal. You'll need to change permissions on all files in the canonizer.2.0 directory and subdirectory. The following two commands will allow you to do that:
cd /Library/WebServer/Documents/canonizer.2.0
sudo chmod -R 777

7. Install and update composer using the method described above. 


##File upload
To enable file upload feature need to activate storage as documented by laravel using command 
php artisan storage:link. For more info visit - https://laravel.com/docs/5.6/filesystem

## Coding Standards To Follow

1. For DB changes: create migration for any db change. Do not forget to add migration file in your commit.

2. All dependencies should be added through composer only.

## Deployment
1. create  your own branch for task you have done with "your name or username" (developer name or username)
example:  git checkout -b dev-jackson

2. Commit changes 

3. push the branch
git push origin dev-jackson

## Setup Development Environment ( Apache, Mysql, Php)

   ## Setup on Windows

   1. Download Xampp having php > = 7  from  link https://www.apachefriends.org/download.html 
   2. After download install Xampp in your system lets say c://Xampp.
   3. After installing xampp open xampp application and start apache server and mysql server
   4. clone the canonizer code to xampp/htdocs directory from the link mentioned above 
   5. create a database in your mysql, set the same name in .env file and  run the migrations in the project( got to xampp/htdocs/canonizer in terminal and run php artisan migrate)

   Thats it! Canonizer will work on your local system

   ## Setup on linux ( Lamp Stack Setup)
   1. Update your linux system with following command

   		 sudo apt-get update

   2.  Install Apache Server

       sudo apt-get install apache2 apache2-doc apache2-npm-prefork apache2-utils libexpat1 ssl-cert

   3. Install Mysql-server

       sudo apt-get install mysql-server mysql-client libmysqlclient-dev

   4. Install php ( php 7 or the latest version of php)

       sudo apt-get install libapache2-mod-php7.0 php7.0 php7.0-common php7.0-curl php7.0-dev php7.0-gd php-pear php-imagick php7.0-mcrypt php7.0-mysql php7.0-ps php7.0-xsl

   5. Install phpmyadmin(Optional as it provide GUI to access database)

      sudo apt-get install phpmyadmin

   6. clone the canonizer code to /var/www/html directory from the link mentioned above 
   
   7. create a database in your mysql, set the same name in .env file and  run the migrations in the project( got to var/www/html/canonizer in terminal and run php artisan migrate)



Thats it!

## Robots Setting
There are two files in public directory robots.txt  and robots-production.txt.
Copy the content of robots-production.txt file in robots.txt file and remove the 
robots-production.txt file to enable robots file.

## Contributing

Thank you for considering contributing to the Canonizer! 

## License
MIT License

Copyright (c) 2006-2018 Canonizer.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

