# 											Documentation

The portal **backend** was implemented in plain php7 as requested and developed in PhpStorm. I used the built-in server that the IDE offers to run and test the application so my requests were made to 'http://localhost:8000'.

The portal **frontend** was implemented with VueJS framework and all its content is in the folder 'frontend' of the main project.

### Backend

The **starting point** of the portal backend app is the file 'public/frontendController.php' where all the routes are specified.

Each request that reaches the server is received by this file and routed to execute a php script contained in the folder 'controllers'.

Controller files manipulate data and requests accordingly. There are files to update already existing database records and other to create new or return data to the  frontend views. For almost every error or failure occurs in requests received, these controllers send back to front an error message with some basic info. Otherwise, a successful response is given with a message or with data requested.

'config' folder contains 2 files: one to create database connection and one to set headers for http responses sent from the server.

'helpers' folder contains 2 files with functions that help general implementation parts over the application.

### Database

MySQL database was used for the portal with 2 tables. One for users and one for submissions made by the employees.

In 'database-files' folder there is an image of the EER diagram to help you create the 2 tables.

### About login

Login part of the portal is very important as it is responsible for giving access to users and authenticating them across the portal.

My Login process implementation verifies that credentials provided from frontend are valid and if so, it returns user info back to frontend.

Along with the user info, it also sends a token. All this data are stored in local storage of the frontend to keep him signed in across the app.

About token, I chose to make a very basic implementation, sending a static one. Of course you are free to use a more sophisticated solution like JWT tokens.

- To **test** Login as **administrator** create an admin user manually in the database and login with these credentials
- To **test** Login as employee create an simple user manually in the database and login with these credentials

### About Email

For the email functionality, I used a third party library called 'PHPMailer' which I installed via **composer**. I also used composer to install another library called 'DOTENV' with which I was able to configure a .env file with my environment variables used across the portal. 

composer.json file includes the **dependencies** which you have to install in order to use the backend with full functionality.

In my **.env** file, I stored values for database configuration and also the values for my mail server.

To **test email functionality** for the portal I used my **personal gmail account**.

### Frontend

The frontend was developed and configured in VueJS framework. It can be started with: `npm run serve` and the app will be served in 'http://localhost:8080'.
All files are inside frontend folder  which is a git-submodule so make sure to run: git clone --recurse-submodules <url>.

Frontend has a router.js file which is responsible to control navigation across the portal like the frontController.php in the backend.

All views are contained in the 'views' folder. Most of the other folders contain files that helped me implement the logic and the visual part of the frontend. 