<?php
 
/**
 * Class to handle navBar navBar
 */
 
class Navigation
{
  // Properties
 
  /**
  * @var int The navBar ID from the database
  * @var int The parent navBar ID from the database
  * @var string Name of the navBar
  * @var string Name of the navBar
  * @var string A short navBarURL of the navBar
  * @var int The article ID from the database
  * @var int The category ID from the database
  * @var int The form ID from the database
  * @var int The media ID from the database
  * @var int The menu ID from the database
  * @var int The slug ID from the database
  */
  public $navBarId = null;
  public $navBarParentId = null;
  public $navBarLinkType = null;
  public $navBarName = null;
  public $navBarURL = null;
  public $navBarArticleId = null;
  public $navBarCatId = null;
  public $navBarFormId = null;
  public $navBarMediaId = null;
  public $navBarMenuId = null;
  public $navBarSlugId = null;
  
 /*
navBarId   
navBarParentId
navBarLinkType
navBarName
navBarURL
navBarArticleId
navBarCatId
navBarFormId
navBarMediaId
navBarMenuId
navBarSlugId
*/  
  
  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */
 
  public function __construct( $data=array() ) {
    if ( isset( $data['navBarId'] ) ) $this->navBarId = (int) $data['navBarId'];
    if ( isset( $data['navBarParentId'] ) ) $this->navBarParentId = (int) $data['navBarParentId'];
    if ( isset( $data['navBarLinkType'] ) ) $this->navBarLinkType = (int) $data['navBarLinkType'];
    if ( isset( $data['navBarName'] ) ) $this->navBarName = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['navBarName'] );
	if ( isset( $data['navBarURL'] ) ) $this->navBarURL = $data['navBarURL'];
    if ( isset( $data['navBarArticleId'] ) ) $this->navBarArticleId = (int) $data['navBarArticleId'];
    if ( isset( $data['navBarCatId'] ) ) $this->navBarCatId = (int) $data['navBarCatId'];
    if ( isset( $data['navBarFormId'] ) ) $this->navBarFormId = (int) $data['navBarFormId'];
    if ( isset( $data['navBarMediaId'] ) ) $this->navBarMediaId = (int) $data['navBarMediaId'];
    if ( isset( $data['navBarMenuId'] ) ) $this->navBarMenuId = (int) $data['navBarMenuId'];
    if ( isset( $data['navBarSlugId'] ) ) $this->navBarSlugId = (int) $data['navBarSlugId'];
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
  * Returns a Navigation object matching the given navBar ID
  *
  * @param int The navBar ID
  * @return Navigation|false The navBar object, or false if the record was not found or there was a problem
  */
 
  public static function getById( $navBarId ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM navBar WHERE navBarId = :navBarId";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":navBarId", $navBarId, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Navigation( $row );
  }
 
  /**
  * Returns all (or a range of) Navigation objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @param string Optional column by which to order the navBar (default="navBarName ASC")
  * @return Array|false A two-element array : results => array, a list of Navigation objects; totalRows => Total number of navBar
  */
 
  public static function getList( $numRows=1000000, $order="navBarName ASC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM navBar
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";
 
    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();
 
    while ( $row = $st->fetch() ) {
      $navBar = new Navigation( $row );
      $list[] = $navBar;
    }
	
    // Now get the total number of navBar that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );

  }
 
 
  /**
  * Inserts the current Navigation object into the database, and sets its ID property.
  */
 
  public function insert() {
 
    // Does the Navigation object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Navigation::insert(): Attempt to insert a Navigation object that already has its ID property set (to $this->navBarId).", E_USER_ERROR );
 
    // Insert the Navigation
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO navBar ( navBarParentId, navBarLinkType, navBarName, navBarURL, navBarArticleId, navBarCatId, navBarFormId, navBarMediaId, navBarMenuId, navBarSlugId ) VALUES ( :navBarParentId, :navBarLinkType, :navBarName, :navBarURL, :navBarArticleId, :navBarCatId, :navBarFormId, :navBarMediaId, :navBarMenuId, :navBarSlugId )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":navBarParentId", $this->navBarParentId, PDO::PARAM_INT );
    $st->bindValue( ":navBarLinkType", $this->navBarLinkType, PDO::PARAM_STR );
    $st->bindValue( ":navBarName", $this->navBarName, PDO::PARAM_STR );
    $st->bindValue( ":navBarURL", $this->navBarURL, PDO::PARAM_STR );
	$st->bindValue( ":navBarArticleId", $this->navBarArticleId, PDO::PARAM_INT );
	$st->bindValue( ":navBarCatId", $this->navBarCatId, PDO::PARAM_INT );
	$st->bindValue( ":navBarFormId", $this->navBarFormId, PDO::PARAM_INT );
	$st->bindValue( ":navBarMediaId", $this->navBarMediaId, PDO::PARAM_INT );
	$st->bindValue( ":navBarMenuId", $this->navBarMenuId, PDO::PARAM_INT );
	$st->bindValue( ":navBarSlugId", $this->navBarSlugId, PDO::PARAM_INT );
    $st->execute();
    $this->navBarId = $conn->lastInsertId();
    $conn = null;
  }
 
 
  /**
  * Updates the current Navigation object in the database.
  */
 
  public function update() {
 
    // Does the Navigation object have an ID?
    if ( is_null( $this->navBarId ) ) trigger_error ( "Navigation::update(): Attempt to update a Navigation object that does not have its ID property set.", E_USER_ERROR );
    
    // Update the Navigation
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE navBar SET navBarParentId=:navBarParentId, navBarLinkType=:navBarLinkType, navBarName=:navBarName, navBarURL=:navBarURL, navBarArticleId=:navBarArticleId, navBarCatId=:navBarCatId, navBarFormId=:navBarFormId, navBarMediaId=:navBarMediaId, navBarMenuId=:navBarMenuId, navBarSlugId=:navBarSlugId WHERE navBarId = :navBarId";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":navBarParentId", $this->navBarParentId, PDO::PARAM_INT );
    $st->bindValue( ":navBarLinkType", $this->navBarLinkType, PDO::PARAM_STR );
    $st->bindValue( ":navBarName", $this->navBarName, PDO::PARAM_STR );
    $st->bindValue( ":navBarURL", $this->navBarURL, PDO::PARAM_STR );
	$st->bindValue( ":navBarArticleId", $this->navBarArticleId, PDO::PARAM_INT );
	$st->bindValue( ":navBarCatId", $this->navBarCatId, PDO::PARAM_INT );
	$st->bindValue( ":navBarFormId", $this->navBarFormId, PDO::PARAM_INT );
	$st->bindValue( ":navBarMediaId", $this->navBarMediaId, PDO::PARAM_INT );
	$st->bindValue( ":navBarMenuId", $this->navBarMenuId, PDO::PARAM_INT );
	$st->bindValue( ":navBarSlugId", $this->navBarSlugId, PDO::PARAM_INT );
    $st->bindValue( ":navBarId", $this->navBarId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
  /**
  * Deletes the current Navigation object from the database.
  */
 
  public function delete() {
 
    // Does the Navigation object have an ID?
    if ( is_null( $this->navBarId ) ) trigger_error ( "Navigation::delete(): Attempt to delete a Navigation object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the Navigation
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM navBar WHERE navBarId = :navBarId LIMIT 1" );
    $st->bindValue( ":navBarId", $this->navBarId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>