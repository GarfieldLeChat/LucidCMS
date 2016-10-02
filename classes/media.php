<?php
 
/**
 * Class to handle media
 */
 
class Media
{
  // Properties
 
  /**
  * @var int The media ID from the database
  * @var int When the media is to be / was first published
  * @var int The media category ID
  * @var string Full mediaTitle of the media
  * @var string A short mediaSummary of the media
  * @var string The HTML mediaContent of the media
  * @var string The filename extension of the media's full-size and thumbnail images (empty string means the media has no image)
  */
  public $mediaId = null;
  public $mediaPublicationDate = null;
  public $mediaCategoryId = null;
  public $mediaTitle = null;
  public $mediaSummary = null;
  public $mediaContent = null;
  public $mediaImageExtension = "";
 
  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */
 
  public function __construct( $data=array() ) {
    if ( isset( $data['mediaId'] ) ) $this->mediaId = (int) $data['mediaId'];
    if ( isset( $data['mediaPublicationDate'] ) ) $this->mediaPublicationDate = (int) $data['mediaPublicationDate'];
	if ( isset( $data['mediaCategoryId'] ) ) $this->mediaCategoryId = (int) $data['mediaCategoryId'];
    if ( isset( $data['mediaTitle'] ) ) $this->mediaTitle = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['mediaTitle'] );
	if ( isset( $data['mediaSummary'] ) ) $this->mediaSummary = $data['mediaSummary'];
    if ( isset( $data['mediaContent'] ) ) $this->mediaContent = $data['mediaContent'];
    if ( isset( $data['mediaImageExtension'] ) ) $this->mediaImageExtension = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\$ a-zA-Z0-9()]/", "", $data['mediaImageExtension'] );
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
    if ( isset($params['mediaPublicationDate']) ) {
      $mediaPublicationDate = explode ( '-', $params['mediaPublicationDate'] );
 
      if ( count($mediaPublicationDate) == 3 ) {
        list ( $y, $m, $d ) = $mediaPublicationDate;
        $this->mediaPublicationDate = mktime ( 0, 0, 0, $m, $d, $y );
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
      // Does the Media object have an ID?
      if ( is_null( $this->mediaId ) ) trigger_error( "Media::storeUploadedImage(): Attempt to upload an image for an Media object that does not have its ID property set.", E_USER_ERROR );
 
      // Delete any previous image(s) for this media
      $this->deleteImages();
 
      // Get and store the image filename extension
      $this->mediaImageExtension = strtolower( strrchr( $image['name'], '.' ) );
 
      // Store the image
 
      $tempFilename = trim( $image['tmp_name'] ); 
 
      if ( is_uploaded_file ( $tempFilename ) ) {
        if ( !( move_uploaded_file( $tempFilename, $this->getImagePath() ) ) ) trigger_error( "Media::storeUploadedImage(): Couldn't move uploaded file.", E_USER_ERROR );
        if ( !( chmod( $this->getImagePath(), 0666 ) ) ) trigger_error( "Media::storeUploadedImage(): Couldn't set permissions on uploaded file.", E_USER_ERROR );
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
          trigger_error ( "Media::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR );
      }
 
      // Copy and resize the image to create the thumbnail
      $thumbHeight = intval ( $imageHeight / $imageWidth * MEDIA_THUMB_WIDTH );
      $thumbResource = imagecreatetruecolor ( MEDIA_THUMB_WIDTH, $thumbHeight );
      imagecopyresampled( $thumbResource, $imageResource, 0, 0, 0, 0, MEDIA_THUMB_WIDTH, $thumbHeight, $imageWidth, $imageHeight );
 
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
          trigger_error ( "Media::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR );
      }
 
      $this->update();
    }
  }
 
 
  /**
  * Deletes any images and/or thumbnails associated with the media
  */
 
  public function deleteImages() {
 
    // Delete all fullsize images for this media
    foreach (glob( MEDIA_IMAGE_PATH . "/" . IMG_TYPE_FULLSIZE . "/" . $this->mediaId . ".*") as $filename) {
      if ( !unlink( $filename ) ) trigger_error( "Media::deleteImages(): Couldn't delete image file.", E_USER_ERROR );
    }
     
    // Delete all thumbnail images for this media
    foreach (glob( MEDIA_IMAGE_PATH . "/" . IMG_TYPE_THUMB . "/" . $this->mediaId . ".*") as $filename) {
      if ( !unlink( $filename ) ) trigger_error( "Media::deleteImages(): Couldn't delete thumbnail file.", E_USER_ERROR );
    }
 
    // Remove the image filename extension from the object
    $this->mediaImageExtension = "";
  }
 
 
  /**
  * Returns the relative path to the media's full-size or thumbnail image
  *
  * @param string The type of image path to retrieve (IMG_TYPE_FULLSIZE or IMG_TYPE_THUMB). Defaults to IMG_TYPE_FULLSIZE.
  * @return string|false The image's path, or false if an image hasn't been uploaded
  */
 
  public function getImagePath( $type=IMG_TYPE_FULLSIZE ) {
    return ( $this->mediaId && $this->mediaImageExtension ) ? ( MEDIA_IMAGE_PATH . "/$type/" . $this->mediaId . $this->mediaImageExtension ) : false;
  }
 
 
  /**
  * Returns an Media object matching the given media ID
  *
  * @param int The media ID
  * @return Media|false The media object, or false if the record was not found or there was a problem
  */
 
  public static function getById( $mediaId ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT *, UNIX_TIMESTAMP(mediaPublicationDate) AS mediaPublicationDate FROM media WHERE mediaId = :mediaId";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":mediaId", $mediaId, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Media( $row );
  }
 
 
  /**
  * Returns all (or a range of) Media objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @param int Optional Return just media in the category with this ID
  * @param string Optional column by which to order the media (default="mediaPublicationDate DESC")
  * @return Array|false A two-element array : results => array, a list of Media objects; totalRows => Total number of media
  */
 
public static function getList( $numRows=1000000, $mediaCategoryId=null, $order="mediaPublicationDate DESC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $categoryClause = $mediaCategoryId ? "WHERE mediaCategoryId = :mediaCategoryId" : "";
    $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(mediaPublicationDate) AS mediaPublicationDate
            FROM media $categoryClause
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";
 
    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
	if ( $mediaCategoryId ) $st->bindValue( ":mediaCategoryId", $mediaCategoryId, PDO::PARAM_INT );
    $st->execute();
    $list = array();
 
    while ( $row = $st->fetch() ) {
      $media = new Media( $row );
      $list[] = $media;
    }
 
    // Now get the total number of media that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }
 
 
  /**
  * Inserts the current Media object into the database, and sets its ID property.
  */
 
  public function insert() {
 
    // Does the Media object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Media::insert(): Attempt to insert an Media object that already has its ID property set (to $this->mediaId).", E_USER_ERROR );
 
    // Insert the Media
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO media ( mediaPublicationDate, mediaCategoryId, mediaTitle, mediaSummary, mediaContent, mediaImageExtension ) VALUES ( FROM_UNIXTIME(:mediaPublicationDate), :mediaCategoryId, :mediaTitle, :mediaSummary, :mediaContent, :mediaImageExtension )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":mediaPublicationDate", $this->mediaPublicationDate, PDO::PARAM_INT );
	$st->bindValue( ":mediaCategoryId", $this->mediaCategoryId, PDO::PARAM_INT );
    $st->bindValue( ":mediaTitle", $this->mediaTitle, PDO::PARAM_STR );
    $st->bindValue( ":mediaSummary", $this->mediaSummary, PDO::PARAM_STR );
    $st->bindValue( ":mediaContent", $this->mediaContent, PDO::PARAM_STR );
    $st->bindValue( ":mediaImageExtension", $this->mediaImageExtension, PDO::PARAM_STR );
    $st->execute();
    $this->mediaId = $conn->lastInsertId();
    $conn = null;
  }
 
 
  /**
  * Updates the current Media object in the database.
  */
 
  public function update() {
 
    // Does the Media object have an ID?
    if ( is_null( $this->mediaId ) ) trigger_error ( "Media::update(): Attempt to update an Media object that does not have its ID property set.", E_USER_ERROR );
    
    // Update the Media
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE media SET mediaPublicationDate=FROM_UNIXTIME(:mediaPublicationDate), mediaCategoryId=:mediaCategoryId, mediaTitle=:mediaTitle, mediaSummary=:mediaSummary, mediaContent=:mediaContent, mediaImageExtension=:mediaImageExtension WHERE mediaId = :mediaId";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":mediaPublicationDate", $this->mediaPublicationDate, PDO::PARAM_INT );
	$st->bindValue( ":mediaCategoryId", $this->mediaCategoryId, PDO::PARAM_INT );
    $st->bindValue( ":mediaTitle", $this->mediaTitle, PDO::PARAM_STR );
    $st->bindValue( ":mediaSummary", $this->mediaSummary, PDO::PARAM_STR );
    $st->bindValue( ":mediaContent", $this->mediaContent, PDO::PARAM_STR );
    $st->bindValue( ":mediaImageExtension", $this->mediaImageExtension, PDO::PARAM_STR );
    $st->bindValue( ":mediaId", $this->mediaId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
  /**
  * Deletes the current Media object from the database.
  */
 
  public function delete() {
 
    // Does the Media object have an ID?
    if ( is_null( $this->mediaId ) ) trigger_error ( "Media::delete(): Attempt to delete an Media object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the Media
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM media WHERE mediaId = :mediaId LIMIT 1" );
    $st->bindValue( ":mediaId", $this->mediaId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>