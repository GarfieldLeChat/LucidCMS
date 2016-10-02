<?php
ini_set( "display_errors", true );
date_default_timezone_set( "Europe/London" );  // http://www.php.net/manual/en/timezones.php
define( "DB_DSN", "mysql:host=localhost;dbname=lucidCMS" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );
define( "CLASS_PATH", "classes" );
define( "TEMPLATE_PATH", "templates" );
define( "HOMEPAGE_NUM_ARTICLES", 5 );
define( "ADMIN_USERNAME", "admin" );
define( "ADMIN_PASSWORD", "password" );
define( "ARTICLE_IMAGE_PATH", "images/articles" );
define( "IMG_TYPE_FULLSIZE", "fullsize" );
define( "IMG_TYPE_THUMB", "thumb" );
define( "ARTICLE_THUMB_WIDTH", 120 );
define( "MEDIA_IMAGE_PATH", "images/media" );
define( "MEDIA_THUMB_WIDTH", 120 );
define( "FORM_IMAGE_PATH", "images/form" );
define( "FORM_THUMB_WIDTH", 120 );
define( "SLUG_IMAGE_PATH", "images/slug" );
define( "SLUG_THUMB_WIDTH", 120 );
define( "JPEG_QUALITY", 100 );
require( CLASS_PATH . "/article.php" );
require( CLASS_PATH . "/category.php" );
require( CLASS_PATH . "/form.php" );
require( CLASS_PATH . "/formCategory.php" );
require( CLASS_PATH . "/linkType.php" );
require( CLASS_PATH . "/media.php" );
require( CLASS_PATH . "/mediaCategory.php" );
require( CLASS_PATH . "/nav.php" );
require( CLASS_PATH . "/slug.php" );
require( CLASS_PATH . "/slugCategory.php" );
 
function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.";
  error_log( $exception->getMessage() );
}
 
set_exception_handler( 'handleException' );
?>