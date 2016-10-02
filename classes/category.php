<?php
 
/**
 * Class to handle article categories
 */
 
class Category
{
  // Properties
 
  /**
  * @var int The category ID from the database
  * @var int The parent category ID from the database
  * @var string Name of the category
  * @var string A short categoryDescription of the category
  */
  public $categoryId = null;
  public $categoryParentId = null;
  public $categoryName = null;
  public $categoryDescription = null;
 
  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */
 
  public function __construct( $data=array() ) {
    if ( isset( $data['categoryId'] ) ) $this->categoryId = (int) $data['categoryId'];
    if ( isset( $data['categoryParentId'] ) ) $this->categoryParentId = (int) $data['categoryParentId'];
    if ( isset( $data['categoryName'] ) ) $this->categoryName = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['categoryName'] );
	if ( isset( $data['categoryDescription'] ) ) $this->categoryDescription = $data['categoryDescription'];
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
  * Returns a Category object matching the given category ID
  *
  * @param int The category ID
  * @return Category|false The category object, or false if the record was not found or there was a problem
  */
 
  public static function getById( $categoryId ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM categories WHERE categoryId = :categoryId";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":categoryId", $categoryId, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Category( $row );
  }
 
  /**
  * Returns all (or a range of) Category objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @param string Optional column by which to order the categories (default="categoryName ASC")
  * @return Array|false A two-element array : results => array, a list of Category objects; totalRows => Total number of categories
  */
 
  public static function getList( $numRows=1000000, $order="categoryName ASC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM categories
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";
 
    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();
 
    while ( $row = $st->fetch() ) {
      $category = new Category( $row );
      $list[] = $category;
    }
	
    // Now get the total number of categories that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );

  }
 
 
  /**
  * Inserts the current Category object into the database, and sets its ID property.
  */
 
  public function insert() {
 
    // Does the Category object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Category::insert(): Attempt to insert a Category object that already has its ID property set (to $this->categoryId).", E_USER_ERROR );
 
    // Insert the Category
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO categories ( categoryParentId, categoryName, categoryDescription ) VALUES ( :categoryParentId, :categoryName, :categoryDescription )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":categoryParentId", $this->categoryParentId, PDO::PARAM_INT );
    $st->bindValue( ":categoryName", $this->categoryName, PDO::PARAM_STR );
    $st->bindValue( ":categoryDescription", $this->categoryDescription, PDO::PARAM_STR );
    $st->execute();
    $this->categoryId = $conn->lastInsertId();
    $conn = null;
  }
 
 
  /**
  * Updates the current Category object in the database.
  */
 
  public function update() {
 
    // Does the Category object have an ID?
    if ( is_null( $this->categoryId ) ) trigger_error ( "Category::update(): Attempt to update a Category object that does not have its ID property set.", E_USER_ERROR );
    
    // Update the Category
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE categories SET categoryParentId=:categoryParentId, categoryName=:categoryName, categoryDescription=:categoryDescription WHERE categoryId = :categoryId";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":categoryParentId", $this->categoryParentId, PDO::PARAM_INT );
    $st->bindValue( ":categoryName", $this->categoryName, PDO::PARAM_STR );
    $st->bindValue( ":categoryDescription", $this->categoryDescription, PDO::PARAM_STR );
    $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
  /**
  * Deletes the current Category object from the database.
  */
 
  public function delete() {
 
    // Does the Category object have an ID?
    if ( is_null( $this->categoryId ) ) trigger_error ( "Category::delete(): Attempt to delete a Category object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the Category
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM categories WHERE categoryId = :categoryId LIMIT 1" );
    $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>