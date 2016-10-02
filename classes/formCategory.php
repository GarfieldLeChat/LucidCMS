<?php
 
/**
 * Class to handle article formCategories
 */
 
class FormCategory
{
  // Properties
 
  /**
  * @var int The formCategory ID from the database
  * @var int The parent formCategory ID from the database
  * @var string Name of the formCategory
  * @var string A short formCategoryDescription of the formCategory
  */
  public $formCategoryId = null;
  public $formCategoryParentId = null;
  public $formCategoryName = null;
  public $formCategoryDescription = null;
 
  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */
 
  public function __construct( $data=array() ) {
    if ( isset( $data['formCategoryId'] ) ) $this->formCategoryId = (int) $data['formCategoryId'];
    if ( isset( $data['formCategoryParentId'] ) ) $this->formCategoryParentId = (int) $data['formCategoryParentId'];
    if ( isset( $data['formCategoryName'] ) ) $this->formCategoryName = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['formCategoryName'] );
	if ( isset( $data['formCategoryDescription'] ) ) $this->formCategoryDescription = $data['formCategoryDescription'];
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
  * Returns a FormCategory object matching the given formCategory ID
  *
  * @param int The formCategory ID
  * @return FormCategory|false The formCategory object, or false if the record was not found or there was a problem
  */
 
  public static function getById( $formCategoryId ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM formCategories WHERE formCategoryId = :formCategoryId";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":formCategoryId", $formCategoryId, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new FormCategory( $row );
  }
 
  /**
  * Returns all (or a range of) FormCategory objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @param string Optional column by which to order the formCategories (default="formCategoryName ASC")
  * @return Array|false A two-element array : results => array, a list of FormCategory objects; totalRows => Total number of formCategories
  */
 
  public static function getList( $numRows=1000000, $order="formCategoryName ASC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM formCategories
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";
 
    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();
 
    while ( $row = $st->fetch() ) {
      $formCategory = new FormCategory( $row );
      $list[] = $formCategory;
    }
	
    // Now get the total number of formCategories that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );

  }
 
 
  /**
  * Inserts the current FormCategory object into the database, and sets its ID property.
  */
 
  public function insert() {
 
    // Does the FormCategory object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "FormCategory::insert(): Attempt to insert a FormCategory object that already has its ID property set (to $this->formCategoryId).", E_USER_ERROR );
 
    // Insert the FormCategory
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO formCategories ( formCategoryParentId, formCategoryName, formCategoryDescription ) VALUES ( :formCategoryParentId, :formCategoryName, :formCategoryDescription )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":formCategoryParentId", $this->formCategoryParentId, PDO::PARAM_INT );
    $st->bindValue( ":formCategoryName", $this->formCategoryName, PDO::PARAM_STR );
    $st->bindValue( ":formCategoryDescription", $this->formCategoryDescription, PDO::PARAM_STR );
    $st->execute();
    $this->formCategoryId = $conn->lastInsertId();
    $conn = null;
  }
 
 
  /**
  * Updates the current FormCategory object in the database.
  */
 
  public function update() {
 
    // Does the FormCategory object have an ID?
    if ( is_null( $this->formCategoryId ) ) trigger_error ( "FormCategory::update(): Attempt to update a FormCategory object that does not have its ID property set.", E_USER_ERROR );
    
    // Update the FormCategory
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE formCategories SET formCategoryParentId=:formCategoryParentId, formCategoryName=:formCategoryName, formCategoryDescription=:formCategoryDescription WHERE formCategoryId = :formCategoryId";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":formCategoryParentId", $this->formCategoryParentId, PDO::PARAM_INT );
    $st->bindValue( ":formCategoryName", $this->formCategoryName, PDO::PARAM_STR );
    $st->bindValue( ":formCategoryDescription", $this->formCategoryDescription, PDO::PARAM_STR );
    $st->bindValue( ":formCategoryId", $this->formCategoryId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
  /**
  * Deletes the current FormCategory object from the database.
  */
 
  public function delete() {
 
    // Does the FormCategory object have an ID?
    if ( is_null( $this->formCategoryId ) ) trigger_error ( "FormCategory::delete(): Attempt to delete a FormCategory object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the FormCategory
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM formCategories WHERE formCategoryId = :formCategoryId LIMIT 1" );
    $st->bindValue( ":formCategoryId", $this->formCategoryId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>