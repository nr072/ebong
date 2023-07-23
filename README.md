# ebong

## Initialize

- Clone the project from git repo
~~~shell
git clone https://github.com/nr072/ebong.git
~~~
- Go inside the project root directory
~~~shell
cd ebong
~~~
- Switch to the master branch
~~~shell
git checkout master
~~~
- Copy .env.example file to .env file
~~~shell
cp .env.example .env
~~~
- Create database "ebong" (you can change database name)
- Set database credentials in the .env file
~~~shell
DB_DATABASE = ebong (or the name of the database you have chosen)
DB_USERNAME = root
DB_PASSWORD =
~~~
- Install php dependencies
~~~shell
composer install
~~~
- Generate key
~~~shell
php artisan key:generate
~~~
- Install front-end dependencies
~~~shell
npm install && npm run build
~~~
- Run migration and seeder
~~~shell
php artisan migrate:fresh --seed
~~~
- Run server
~~~shell
php artisan serve
~~~
- Visit localhost:8000 in your favorite browser