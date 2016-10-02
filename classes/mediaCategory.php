<?php
 
/**
 * Class to handle article mediaCategories
 */
 
class MediaCategory
{
  // Properties
 
  /**
  * @var int The mediaCategory ID from the database
  * @var int The parent mediaCategory ID from the database
  * @var string Name of the mediaCategory
  * @var string A short mediaCategoryDescription of the mediaCategory
  */
  public $mediaCategoryId = null;
  public $mediaCategoryParentId = null;
  public $mediaCategoryName = null;
  public $mediaCategoryDescription = null;
 
  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */
 
  public function __construct( $data=array() ) {
    if ( isset( $data['mediaCategoryId'] ) ) $this->mediaCategoryId = (int) $data['mediaCategoryId'];
    if ( isset( $data['mediaCategoryParentId'] ) ) $this->mediaCategoryParentId = (int) $data['mediaCategoryParentId'];
    if ( isset( $data['mediaCategoryName'] ) ) $this->mediaCategoryName = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['mediaCategoryName'] );
	if ( isset( $data['mediaCategoryDescription'] ) ) $this->mediaCategoryDescription = $data['mediaCategoryDescription'];
  }
 
 
  /**
  * Sets the object's properties using the edit form post values in the supplied array
  *
  * @param assoc The form post values
  */
 
  public function storeFormValues ( $params ) {
 
    // Store all the parameters
    $this->__construct( $params );
  }
 
 
  /**
  * Returns a MediaCategory object matching the given mediaCategory ID
  *
  * @param int The mediaCategory ID
  * @return MediaCategory|false The mediaCategory object, or false if the record was not found or there was a problem
  */
 
  public static function getById( $mediaCategoryId ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM mediaCategories WHERE mediaCategoryId = :mediaCategoryId";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":mediaCategoryId", $mediaCategoryId, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new MediaCategory( $row );
  }
 
  /**
  * Returns all (or a range of) MediaCategory objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @param string Optional column by which to order the mediaCategories (default="mediaCategoryName ASC")
  * @return Array|false A two-element array : results => array, a list of MediaCategory objects; totalRows => Total number of mediaCategories
  */
 
  public static function getList( $numRows=1000000, $order="mediaCategoryName ASC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM mediaCategories
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";
 
    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();
 
    while ( $row = $st->fetch() ) {
      $mediaCategory = new MediaCategory( $row );
      $list[] = $mediaCategory;
    }
	
    // Now get the total number of mediaCategories that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );

  }
 
 
  /**
  * Inserts the current MediaCategory object into the database, and sets its ID property.
  */
 
  public function insert() {
 
    // Does the MediaCategory object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "MediaCategory::insert(): Attempt to insert a MediaCategory object that already has its ID property set (to $this->mediaCategoryId).", E_USER_ERROR );
 
    // Insert the MediaCategory
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO mediaCategories ( mediaCategoryParentId, mediaCategoryName, mediaCategoryDescription ) VALUES ( :mediaCategoryParentId, :mediaCategoryName, :mediaCategoryDescription )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":mediaCategoryParentId", $this->mediaCategoryParentId, PDO::PARAM_INT );
    $st->bindValue( ":mediaCategoryName", $this->mediaCategoryName, PDO::PARAM_STR );
    $st->bindValue( ":mediaCategoryDescription", $this->mediaCategoryDescription, PDO::PARAM_STR );
    $st->execute();
    $this->mediaCategoryId = $conn->lastInsertId();
    $conn = null;
  }
 
 
  /**
  * Updates the current MediaCategory object in the database.
  */
 
  public function update() {
 
    // Does the MediaCategory object have an ID?
    if ( is_null( $this->mediaCategoryId ) ) trigger_error ( "MediaCategory::update(): Attempt to update a MediaCategory object that does not have its ID property set.", E_USER_ERROR );
    
    // Update the MediaCategory
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE mediaCategories SET mediaCategoryParentId=:mediaCategoryParentId, mediaCategoryName=:mediaCategoryName, mediaCategoryDescription=:mediaCategoryDescription WHERE mediaCategoryId = :mediaCategoryId";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":mediaCategoryParentId", $this->mediaCategoryParentId, PDO::PARAM_INT );
    $st->bindValue( ":mediaCategoryName", $this->mediaCategoryName, PDO::PARAM_STR );
    $st->bindValue( ":mediaCategoryDescription", $this->mediaCategoryDescription, PDO::PARAM_STR );
    $st->bindValue( ":mediaCategoryId", $this->mediaCategoryId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
  /**
  * Deletes the current MediaCategory object from the database.
  */
 
  public function delete() {
 
    // Does the MediaCategory object have an ID?
    if ( is_null( $this->mediaCategoryId ) ) trigger_error ( "MediaCategory::delete(): Attempt to delete a MediaCategory object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the MediaCategory
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM mediaCategories WHERE mediaCategoryId = :mediaCategoryId LIMIT 1" );
    $st->bindValue( ":mediaCategoryId", $this->mediaCategoryId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>