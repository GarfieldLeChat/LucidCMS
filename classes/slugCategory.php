<?php
 
/**
 * Class to handle article slugCategories
 */
 
class SlugCategory
{
  // Properties
 
  /**
  * @var int The slugCategory ID from the database
  * @var int The parent slugCategory ID from the database
  * @var string Name of the slugCategory
  * @var string A short slugCategoryDescription of the slugCategory
  */
  public $slugCategoryId = null;
  public $slugCategoryParentId = null;
  public $slugCategoryName = null;
  public $slugCategoryDescription = null;
 
  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */
 
  public function __construct( $data=array() ) {
    if ( isset( $data['slugCategoryId'] ) ) $this->slugCategoryId = (int) $data['slugCategoryId'];
    if ( isset( $data['slugCategoryParentId'] ) ) $this->slugCategoryParentId = (int) $data['slugCategoryParentId'];
    if ( isset( $data['slugCategoryName'] ) ) $this->slugCategoryName = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['slugCategoryName'] );
	if ( isset( $data['slugCategoryDescription'] ) ) $this->slugCategoryDescription = $data['slugCategoryDescription'];
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
  * Returns a SlugCategory object matching the given slugCategory ID
  *
  * @param int The slugCategory ID
  * @return SlugCategory|false The slugCategory object, or false if the record was not found or there was a problem
  */
 
  public static function getById( $slugCategoryId ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM slugCategories WHERE slugCategoryId = :slugCategoryId";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":slugCategoryId", $slugCategoryId, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new SlugCategory( $row );
  }
 
  /**
  * Returns all (or a range of) SlugCategory objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @param string Optional column by which to order the slugCategories (default="slugCategoryName ASC")
  * @return Array|false A two-element array : results => array, a list of SlugCategory objects; totalRows => Total number of slugCategories
  */
 
  public static function getList( $numRows=1000000, $order="slugCategoryName ASC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM slugCategories
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";
 
    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();
 
    while ( $row = $st->fetch() ) {
      $slugCategory = new SlugCategory( $row );
      $list[] = $slugCategory;
    }
	
    // Now get the total number of slugCategories that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );

  }
 
 
  /**
  * Inserts the current SlugCategory object into the database, and sets its ID property.
  */
 
  public function insert() {
 
    // Does the SlugCategory object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "SlugCategory::insert(): Attempt to insert a SlugCategory object that already has its ID property set (to $this->slugCategoryId).", E_USER_ERROR );
 
    // Insert the SlugCategory
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO slugCategories ( slugCategoryParentId, slugCategoryName, slugCategoryDescription ) VALUES ( :slugCategoryParentId, :slugCategoryName, :slugCategoryDescription )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":slugCategoryParentId", $this->slugCategoryParentId, PDO::PARAM_INT );
    $st->bindValue( ":slugCategoryName", $this->slugCategoryName, PDO::PARAM_STR );
    $st->bindValue( ":slugCategoryDescription", $this->slugCategoryDescription, PDO::PARAM_STR );
    $st->execute();
    $this->slugCategoryId = $conn->lastInsertId();
    $conn = null;
  }
 
 
  /**
  * Updates the current SlugCategory object in the database.
  */
 
  public function update() {
 
    // Does the SlugCategory object have an ID?
    if ( is_null( $this->slugCategoryId ) ) trigger_error ( "SlugCategory::update(): Attempt to update a SlugCategory object that does not have its ID property set.", E_USER_ERROR );
    
    // Update the SlugCategory
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE slugCategories SET slugCategoryParentId=:slugCategoryParentId, slugCategoryName=:slugCategoryName, slugCategoryDescription=:slugCategoryDescription WHERE slugCategoryId = :slugCategoryId";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":slugCategoryParentId", $this->slugCategoryParentId, PDO::PARAM_INT );
    $st->bindValue( ":slugCategoryName", $this->slugCategoryName, PDO::PARAM_STR );
    $st->bindValue( ":slugCategoryDescription", $this->slugCategoryDescription, PDO::PARAM_STR );
    $st->bindValue( ":slugCategoryId", $this->slugCategoryId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
  /**
  * Deletes the current SlugCategory object from the database.
  */
 
  public function delete() {
 
    // Does the SlugCategory object have an ID?
    if ( is_null( $this->slugCategoryId ) ) trigger_error ( "SlugCategory::delete(): Attempt to delete a SlugCategory object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the SlugCategory
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM slugCategories WHERE slugCategoryId = :slugCategoryId LIMIT 1" );
    $st->bindValue( ":slugCategoryId", $this->slugCategoryId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>