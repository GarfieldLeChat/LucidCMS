Basic CMS in alpha stage

Installation can be completed via the install Dir running the installer mySQL file.

Admin default username :admin
Admin default password: password

Obivously this is purely for development purposes.

admin via http://localhost/admin

install via installer directory using the mysql script there.

and connect to your DB via the config.php 

lines 4 - 6 


define( "DB_DSN", "mysql:host=localhost;dbname=lucidCMS" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );

where: 
localhost == your host name
dbname == your DB name
DB_USERNAME == your DB user name
DB_PASSWORD == your DB password 

Note this is far from completed in terms of any security and should ABSOLUTELY NOT be used on any type of production database at this time.


