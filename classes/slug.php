<?php
 
/**
 * Class to handle slug
 */
 
class Slug
{
  // Properties
 
  /**
  * @var int The slug ID from the database
  * @var int When the slug is to be / was first published
  * @var int The slug category ID
  * @var string Full slugTitle of the slug
  * @var string A short slugSummary of the slug
  * @var string The HTML slugContent of the slug
  * @var string The filename extension of the slug's full-size and thumbnail images (empty string means the slug has no image)
  */
  public $slugId = null;
  public $slugCategoryId = null;
  public $slugTitle = null;
  public $slugContent = null;
 
  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */
 
  public function __construct( $data=array() ) {
    if ( isset( $data['slugId'] ) ) $this->slugId = (int) $data['slugId'];
	if ( isset( $data['slugCategoryId'] ) ) $this->slugCategoryId = (int) $data['slugCategoryId'];
    if ( isset( $data['slugTitle'] ) ) $this->slugTitle = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['slugTitle'] );
    if ( isset( $data['slugContent'] ) ) $this->slugContent = $data['slugContent'];
  }
 
 
  /**
  * Sets the object's properties using the edit form post values in the supplied array
  *
  * @param assoc The form post values
  */
 
  public function storeFormValues( $params ) {
 
    // Store all the parameters
    $this->__construct( $params );
  }
 
  /**
  * Returns an Slug object matching the given slug ID
  *
  * @param int The slug ID
  * @return Slug|false The slug object, or false if the record was not found or there was a problem
  */
 
  public static function getById( $slugId ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM slug WHERE slugId = :slugId";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":slugId", $slugId, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Slug( $row );
  }
 
 
  /**
  * Returns all (or a range of) Slug objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @param int Optional Return just slug in the category with this ID
  * @param string Optional column by which to order the slug (default="slugPublicationDate DESC")
  * @return Array|false A two-element array : results => array, a list of Slug objects; totalRows => Total number of slug
  */
 
public static function getList( $numRows=1000000, $slugCategoryId=null, $order="slugPublicationDate DESC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $categoryClause = $slugCategoryId ? "WHERE slugCategoryId = :slugCategoryId" : "";
    $sql = "SELECT SQL_CALC_FOUND_ROWS *
            FROM slug $categoryClause
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";
 
    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
	if ( $slugCategoryId ) $st->bindValue( ":slugCategoryId", $slugCategoryId, PDO::PARAM_INT );
    $st->execute();
    $list = array();
 
    while ( $row = $st->fetch() ) {
      $slug = new Slug( $row );
      $list[] = $slug;
    }
 
    // Now get the total number of slug that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }
 
 
  /**
  * Inserts the current Slug object into the database, and sets its ID property.
  */
 
  public function insert() {
 
    // Does the Slug object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Slug::insert(): Attempt to insert an Slug object that already has its ID property set (to $this->slugId).", E_USER_ERROR );
 
    // Insert the Slug
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO slug (slugCategoryId, slugTitle, slugContent ) VALUES ( :slugCategoryId, :slugTitle, :slugContent )";
    $st = $conn->prepare ( $sql );
	$st->bindValue( ":slugCategoryId", $this->slugCategoryId, PDO::PARAM_INT );
    $st->bindValue( ":slugTitle", $this->slugTitle, PDO::PARAM_STR );
    $st->bindValue( ":slugContent", $this->slugContent, PDO::PARAM_STR );
    $st->execute();
    $this->slugId = $conn->lastInsertId();
    $conn = null;
  }
 
 
  /**
  * Updates the current Slug object in the database.
  */
 
  public function update() {
 
    // Does the Slug object have an ID?
    if ( is_null( $this->slugId ) ) trigger_error ( "Slug::update(): Attempt to update an Slug object that does not have its ID property set.", E_USER_ERROR );
    
    // Update the Slug
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE slug SET slugCategoryId=:slugCategoryId, slugTitle=:slugTitle, slugContent=:slugContent WHERE slugId = :slugId";
    $st = $conn->prepare ( $sql );
	$st->bindValue( ":slugCategoryId", $this->slugCategoryId, PDO::PARAM_INT );
    $st->bindValue( ":slugTitle", $this->slugTitle, PDO::PARAM_STR );
    $st->bindValue( ":slugContent", $this->slugContent, PDO::PARAM_STR );
    $st->bindValue( ":slugId", $this->slugId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
  /**
  * Deletes the current Slug object from the database.
  */
 
  public function delete() {
 
    // Does the Slug object have an ID?
    if ( is_null( $this->slugId ) ) trigger_error ( "Slug::delete(): Attempt to delete an Slug object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the Slug
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM slug WHERE slugId = :slugId LIMIT 1" );
    $st->bindValue( ":slugId", $this->slugId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>