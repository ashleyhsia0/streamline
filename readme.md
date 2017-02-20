## About
This web application is a legacy tasking system that features dependencies.
It was built with a Laravel PHP backend using a MySQL database and a Bootstrap/VueJS frontend.

## Setup
1. Install [Composer](https://getcomposer.org/).
2. Git clone this repository using the command ```git clone https://github.com/ashleyhsia0/streamline.git```
3. Run the commands ```composer install``` and ```npm install```.
4. Create a database and then set up your database configurations using the ```.envexample``` file and rename it to ```.env```.
5. Run the command ```php artisan key:generate``` to generate a key for your app.
6. Run the command ```php artisan migrate``` to get the tables generated into your database.
6. To see the web app in your browser, run the command ```php artisan serve``` and go to ```localhost:8000```. Cheers!
