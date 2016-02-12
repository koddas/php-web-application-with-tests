# About this project

This is a simple mashup example that uses the
[Slim Framework](http://www.slimframework.com/). It insults the user with
phrases fetched from [FOAAS](http://foaas.herokuapp.com/), signed by the
Swedish namesday name for a given date, or today's date, should no date be
given. The name is fetched from [Svenska Dagar](http://api.dryg.net/).

The project uses the [Twig template engine](http://twig.sensiolabs.org/) for
rendering its views and the [Guzzle HTTP Client](http://docs.guzzlephp.org/)
for consuming REST API:s.

The data is presented as a simple web page in a calm font, or, if fed the
header *Accept: application/json* as a JSON response.

Please note that the project is written using PHP 5.4. It will probably run
just as fine* on some versions of PHP 5 as well, though

*) The embedded web server, mentioned below, requires PHP 5.4 to run, though.

# How do I build this project?

Being written in PHP, there is no real building involved. However, you will
need to manage the project's dependencies. For this, you need to have
PHP and [Composer](https://getcomposer.org/) installed. Once installed, you can
let Composer download all module dependencies by navigating to your project
directory from a terminal and type

    composer install

# How do I run this project?

Being a more recent PHP project, you have two ways of running this script;
either by letting your web server run it, or by using PHP's bundled development
web server.

## Running the project using an existing web server

For this to work, you need to have a web server up and running. Personally, I
prefer the [Apache HTTP Server](http://httpd.apache.org), running some recent
version of PHP.

Once you have a web server up and running (please refer to the Apache web site
for installation guidance), you may set up a
[virtual host](http://httpd.apache.org/docs/2.2/vhosts/), pointing to the
project directory. Please bear in mind that the document root needs to be the
/src directory, and, if you are running Apache HTTP Server on a Unix-like
system (e.g. Linux or OS X), that the web server must have permissions to read
all files and directories belonging to the project, including the project
directory itself.

Running Slim, you will need to enable URL rewriting (using the Apache HTTP
Server, you'll need to enable
[mod_rewrite](http://httpd.apache.org/docs/current/mod/mod_rewrite.html)).

Make sure to enable the use of .htaccess files by setting the AllowOverride
directive in your Apache conf file. Your directory configuration will look
something like this once you're done:

    <Directory /path/to/your/php-web-application>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

You're now ready to start your server. Running a Debian-based system, you may
your server by opening a terminal and type

    sudo service apache2 start

For any other operating system, please refer to Apache's manual.

## Running the project using the bundled web server

Running a PHP program is really simple. Open a terminal, navigate to your
project folder's *src* sub-folder and type

    php -S localhost:1234

You should now be up and running. The program runs at port 1234 as specified in
the command above. You can try it by trying to access *localhost:1234/insult*.

# The API

The API exposes two endpoints:

### /insult

Using this endpoint, you'll be insulted by today's Swedish namesday name. By
calling the endpoint with the *Accept: application/json* header, you'll be fed
a JSON response formed as

    {
        "message": string,
        "from": string
    }

### /insult/year/month/day

Using this endpoint, you'll be insulted by the specified date's Swedish
namesday name. By calling the endpoint with the *Accept: application/json*
header, you'll be fed a JSON response formed as

    {
        "message": string,
        "from": string
    }

The year is given as four digits, the month and day as two digits respectively.
As an example, calling my birthday (*localhost:1234/insult/1981/06/16*) will
return an insult by Axel (who, I presume, is no less than [@axelolsson](https://github.com/axelolsson)).

### /insult/name

Using this endpoint, you'll be insulted by a named person. By calling the
endpoint with the *Accept: application/json* header, you'll be fed a JSON
response formed as

    {
        "message": string,
        "from": string
    }