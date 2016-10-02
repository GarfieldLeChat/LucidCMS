<?php
 
/**
 * Class to handle article linkType
 */
 
class LinkType
{
  // Properties
 
  /**
  * @var int The linkType ID from the database
  * @var int The parent linkType ID from the database
  * @var string Name of the linkType
  */
  public $linkTypeId = null;
  public $linkTypeType = null;
  public $linkTypeName = null;
 
  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */
 
  public function __construct( $data=array() ) {
    if ( isset( $data['linkTypeId'] ) ) $this->linkTypeId = (int) $data['linkTypeId'];
    if ( isset( $data['linkTypeType'] ) ) $this->linkTypeType = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['linkTypeType'] );
    if ( isset( $data['linkTypeName'] ) ) $this->linkTypeName = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['linkTypeName'] );
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
  * Returns a LinkType object matching the given linkType ID
  *
  * @param int The linkType ID
  * @return LinkType|false The linkType object, or false if the record was not found or there was a problem
  */
 
  public static function getById( $linkTypeId ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM linkType WHERE linkTypeId = :linkTypeId";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":linkTypeId", $linkTypeId, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new LinkType( $row );
  }
 
  /**
  * Returns all (or a range of) LinkType objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @param string Optional column by which to order the linkType (default="linkTypeName ASC")
  * @return Array|false A two-element array : results => array, a list of LinkType objects; totalRows => Total number of linkType
  */
 
  public static function getList( $numRows=1000000, $order="linkTypeId ASC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM linkType 
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";
 
    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();
 
    while ( $row = $st->fetch() ) {
      $linkType = new LinkType( $row );
      $list[] = $linkType;
    }
	
    // Now get the total number of linkType that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );

  }
 
 
  /**
  * Inserts the current LinkType object into the database, and sets its ID property.
  */
 
  public function insert() {
 
    // Does the LinkType object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "LinkType::insert(): Attempt to insert a LinkType object that already has its ID property set (to $this->linkTypeId).", E_USER_ERROR );
 
    // Insert the LinkType
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO linkType ( linkTypeType, linkTypeName, linkTypeDescription ) VALUES ( :linkTypeType, :linkTypeName, :linkTypeDescription )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":linkTypeType", $this->linkTypeType, PDO::PARAM_STR );
    $st->bindValue( ":linkTypeName", $this->linkTypeName, PDO::PARAM_STR );
    $st->execute();
    $this->linkTypeId = $conn->lastInsertId();
    $conn = null;
  }
 
 
  /**
  * Updates the current LinkType object in the database.
  */
 
  public function update() {
 
    // Does the LinkType object have an ID?
    if ( is_null( $this->linkTypeId ) ) trigger_error ( "LinkType::update(): Attempt to update a LinkType object that does not have its ID property set.", E_USER_ERROR );
    
    // Update the LinkType
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE linkType SET linkTypeType=:linkTypeType, linkTypeName=:linkTypeName, linkTypeDescription=:linkTypeDescription WHERE linkTypeId = :linkTypeId";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":linkTypeType", $this->linkTypeType, PDO::PARAM_STR );
    $st->bindValue( ":linkTypeName", $this->linkTypeName, PDO::PARAM_STR );
    $st->bindValue( ":linkTypeId", $this->linkTypeId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
  /**
  * Deletes the current LinkType object from the database.
  */
 
  public function delete() {
 
    // Does the LinkType object have an ID?
    if ( is_null( $this->linkTypeId ) ) trigger_error ( "LinkType::delete(): Attempt to delete a LinkType object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the LinkType
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM linkType WHERE linkTypeId = :linkTypeId LIMIT 1" );
    $st->bindValue( ":linkTypeId", $this->linkTypeId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>