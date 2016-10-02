<?php
 
/**
 * Class to handle articles
 */
 
class Article
{
  // Properties
 
  /**
  * @var int The article ID from the database
  * @var int When the article is to be / was first published
  * @var int The article category ID
  * @var string Full articleTitle of the article
  * @var string A short articleSummary of the article
  * @var string The HTML articleContent of the article
  * @var string The filename extension of the article's full-size and thumbnail images (empty string means the article has no image)
  */
  public $articleId = null;
  public $articlePublicationDate = null;
  public $articleCategoryId = null;
  public $articleTitle = null;
  public $articleSummary = null;
  public $articleContent = null;
  public $articleImageExtension = "";
 
  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */
 
  public function __construct( $data=array() ) {
    if ( isset( $data['articleId'] ) ) $this->articleId = (int) $data['articleId'];
    if ( isset( $data['articlePublicationDate'] ) ) $this->articlePublicationDate = (int) $data['articlePublicationDate'];
	if ( isset( $data['articleCategoryId'] ) ) $this->articleCategoryId = (int) $data['articleCategoryId'];
    if ( isset( $data['articleTitle'] ) ) $this->articleTitle = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['articleTitle'] );
	if ( isset( $data['articleSummary'] ) ) $this->articleSummary = $data['articleSummary'];
    if ( isset( $data['articleContent'] ) ) $this->articleContent = $data['articleContent'];
    if ( isset( $data['articleImageExtension'] ) ) $this->articleImageExtension = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\$ a-zA-Z0-9()]/", "", $data['articleImageExtension'] );
  }
 
 
  /**
  * Sets the object's properties using the edit form post values in the supplied array
  *
  * @param assoc The form post values
  */
 
  public function storeFormValues( $params ) {
 
    // Store all the parameters
    $this->__construct( $params );
 
    // Parse and store the publication date
    if ( isset($params['articlePublicationDate']) ) {
      $articlePublicationDate = explode ( '-', $params['articlePublicationDate'] );
 
      if ( count($articlePublicationDate) == 3 ) {
        list ( $y, $m, $d ) = $articlePublicationDate;
        $this->articlePublicationDate = mktime ( 0, 0, 0, $m, $d, $y );
      }
    }
  }
 
 
  /**
  * Stores any image uploaded from the edit form
  *
  * @param assoc The 'image' element from the $_FILES array containing the file upload data
  */
 
  public function storeUploadedImage( $image ) {
 
    if ( $image['error'] == UPLOAD_ERR_OK )
    {
      // Does the Article object have an ID?
      if ( is_null( $this->articleId ) ) trigger_error( "Article::storeUploadedImage(): Attempt to upload an image for an Article object that does not have its ID property set.", E_USER_ERROR );
 
      // Delete any previous image(s) for this article
      $this->deleteImages();
 
      // Get and store the image filename extension
      $this->articleImageExtension = strtolower( strrchr( $image['name'], '.' ) );
 
      // Store the image
 
      $tempFilename = trim( $image['tmp_name'] ); 
 
      if ( is_uploaded_file ( $tempFilename ) ) {
        if ( !( move_uploaded_file( $tempFilename, $this->getImagePath() ) ) ) trigger_error( "Article::storeUploadedImage(): Couldn't move uploaded file.", E_USER_ERROR );
        if ( !( chmod( $this->getImagePath(), 0666 ) ) ) trigger_error( "Article::storeUploadedImage(): Couldn't set permissions on uploaded file.", E_USER_ERROR );
      }
 
      // Get the image size and type
      $attrs = getimagesize ( $this->getImagePath() );
      $imageWidth = $attrs[0];
      $imageHeight = $attrs[1];
      $imageType = $attrs[2];
 
      // Load the image into memory
      switch ( $imageType ) {
        case IMAGETYPE_GIF:
          $imageResource = imagecreatefromgif ( $this->getImagePath() );
          break;
        case IMAGETYPE_JPEG:
          $imageResource = imagecreatefromjpeg ( $this->getImagePath() );
          break;
        case IMAGETYPE_PNG:
          $imageResource = imagecreatefrompng ( $this->getImagePath() );
          break;
        default:
          trigger_error ( "Article::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR );
      }
 
      // Copy and resize the image to create the thumbnail
      $thumbHeight = intval ( $imageHeight / $imageWidth * ARTICLE_THUMB_WIDTH );
      $thumbResource = imagecreatetruecolor ( ARTICLE_THUMB_WIDTH, $thumbHeight );
      imagecopyresampled( $thumbResource, $imageResource, 0, 0, 0, 0, ARTICLE_THUMB_WIDTH, $thumbHeight, $imageWidth, $imageHeight );
 
      // Save the thumbnail
      switch ( $imageType ) {
        case IMAGETYPE_GIF:
          imagegif ( $thumbResource, $this->getImagePath( IMG_TYPE_THUMB ) );
          break;
        case IMAGETYPE_JPEG:
          imagejpeg ( $thumbResource, $this->getImagePath( IMG_TYPE_THUMB ), JPEG_QUALITY );
          break;
        case IMAGETYPE_PNG:
          imagepng ( $thumbResource, $this->getImagePath( IMG_TYPE_THUMB ) );
          break;
        default:
          trigger_error ( "Article::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR );
      }
 
      $this->update();
    }
  }
 
 
  /**
  * Deletes any images and/or thumbnails associated with the article
  */
 
  public function deleteImages() {
 
    // Delete all fullsize images for this article
    foreach (glob( ARTICLE_IMAGE_PATH . "/" . IMG_TYPE_FULLSIZE . "/" . $this->articleId . ".*") as $filename) {
      if ( !unlink( $filename ) ) trigger_error( "Article::deleteImages(): Couldn't delete image file.", E_USER_ERROR );
    }
     
    // Delete all thumbnail images for this article
    foreach (glob( ARTICLE_IMAGE_PATH . "/" . IMG_TYPE_THUMB . "/" . $this->articleId . ".*") as $filename) {
      if ( !unlink( $filename ) ) trigger_error( "Article::deleteImages(): Couldn't delete thumbnail file.", E_USER_ERROR );
    }
 
    // Remove the image filename extension from the object
    $this->articleImageExtension = "";
  }
 
 
  /**
  * Returns the relative path to the article's full-size or thumbnail image
  *
  * @param string The type of image path to retrieve (IMG_TYPE_FULLSIZE or IMG_TYPE_THUMB). Defaults to IMG_TYPE_FULLSIZE.
  * @return string|false The image's path, or false if an image hasn't been uploaded
  */
 
  public function getImagePath( $type=IMG_TYPE_FULLSIZE ) {
    return ( $this->articleId && $this->articleImageExtension ) ? ( ARTICLE_IMAGE_PATH . "/$type/" . $this->articleId . $this->articleImageExtension ) : false;
  }
 
 
  /**
  * Returns an Article object matching the given article ID
  *
  * @param int The article ID
  * @return Article|false The article object, or false if the record was not found or there was a problem
  */
 
  public static function getById( $articleId ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT *, UNIX_TIMESTAMP(articlePublicationDate) AS articlePublicationDate FROM articles WHERE articleId = :articleId";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":articleId", $articleId, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Article( $row );
  }
 
 
  /**
  * Returns all (or a range of) Article objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @param int Optional Return just articles in the category with this ID
  * @param string Optional column by which to order the articles (default="articlePublicationDate DESC")
  * @return Array|false A two-element array : results => array, a list of Article objects; totalRows => Total number of articles
  */
 
public static function getList( $numRows=1000000, $articleCategoryId=null, $order="articlePublicationDate DESC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $categoryClause = $articleCategoryId ? "WHERE articleCategoryId = :articleCategoryId" : "";
    $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(articlePublicationDate) AS articlePublicationDate
            FROM articles $categoryClause
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";
 
    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
	if ( $articleCategoryId ) $st->bindValue( ":articleCategoryId", $articleCategoryId, PDO::PARAM_INT );
    $st->execute();
    $list = array();
 
    while ( $row = $st->fetch() ) {
      $article = new Article( $row );
      $list[] = $article;
    }
 
    // Now get the total number of articles that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }
 
 
  /**
  * Inserts the current Article object into the database, and sets its ID property.
  */
 
  public function insert() {
 
    // Does the Article object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->articleId).", E_USER_ERROR );
 
    // Insert the Article
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO articles ( articlePublicationDate, articleCategoryId, articleTitle, articleSummary, articleContent, articleImageExtension ) VALUES ( FROM_UNIXTIME(:articlePublicationDate), :articleCategoryId, :articleTitle, :articleSummary, :articleContent, :articleImageExtension )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":articlePublicationDate", $this->articlePublicationDate, PDO::PARAM_INT );
	$st->bindValue( ":articleCategoryId", $this->articleCategoryId, PDO::PARAM_INT );
    $st->bindValue( ":articleTitle", $this->articleTitle, PDO::PARAM_STR );
    $st->bindValue( ":articleSummary", $this->articleSummary, PDO::PARAM_STR );
    $st->bindValue( ":articleContent", $this->articleContent, PDO::PARAM_STR );
    $st->bindValue( ":articleImageExtension", $this->articleImageExtension, PDO::PARAM_STR );
    $st->execute();
    $this->articleId = $conn->lastInsertId();
    $conn = null;
  }
 
 
  /**
  * Updates the current Article object in the database.
  */
 
  public function update() {
 
    // Does the Article object have an ID?
    if ( is_null( $this->articleId ) ) trigger_error ( "Article::update(): Attempt to update an Article object that does not have its ID property set.", E_USER_ERROR );
    
    // Update the Article
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE articles SET articlePublicationDate=FROM_UNIXTIME(:articlePublicationDate), articleCategoryId=:articleCategoryId, articleTitle=:articleTitle, articleSummary=:articleSummary, articleContent=:articleContent, articleImageExtension=:articleImageExtension WHERE articleId = :articleId";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":articlePublicationDate", $this->articlePublicationDate, PDO::PARAM_INT );
	$st->bindValue( ":articleCategoryId", $this->articleCategoryId, PDO::PARAM_INT );
    $st->bindValue( ":articleTitle", $this->articleTitle, PDO::PARAM_STR );
    $st->bindValue( ":articleSummary", $this->articleSummary, PDO::PARAM_STR );
    $st->bindValue( ":articleContent", $this->articleContent, PDO::PARAM_STR );
    $st->bindValue( ":articleImageExtension", $this->articleImageExtension, PDO::PARAM_STR );
    $st->bindValue( ":articleId", $this->articleId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
  /**
  * Deletes the current Article object from the database.
  */
 
  public function delete() {
 
    // Does the Article object have an ID?
    if ( is_null( $this->articleId ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the Article
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM articles WHERE articleId = :articleId LIMIT 1" );
    $st->bindValue( ":articleId", $this->articleId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>