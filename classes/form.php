<?php
 
/**
 * Class to handle form
 */
 
class Form
{
  // Properties
 
  /**
  * @var int The form ID from the database
  * @var int When the form is to be / was first published
  * @var int The form category ID
  * @var string Full formTitle of the form
  * @var string A short formSummary of the form
  * @var string The HTML formContent of the form
  * @var string The filename extension of the form's full-size and thumbnail images (empty string means the form has no image)
  */
  public $formId = null;
  public $formCategoryId = null;
  public $formTitle = null;
  public $formSummary = null;
  public $formContent = null;
 
  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */
 
  public function __construct( $data=array() ) {
    if ( isset( $data['formId'] ) ) $this->formId = (int) $data['formId'];
	if ( isset( $data['formCategoryId'] ) ) $this->formCategoryId = (int) $data['formCategoryId'];
    if ( isset( $data['formTitle'] ) ) $this->formTitle = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['formTitle'] );
	if ( isset( $data['formSummary'] ) ) $this->formSummary = $data['formSummary'];
    if ( isset( $data['formContent'] ) ) $this->formContent = $data['formContent'];
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
  * Returns an Form object matching the given form ID
  *
  * @param int The form ID
  * @return Form|false The form object, or false if the record was not found or there was a problem
  */
 
  public static function getById( $formId ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM form WHERE formId = :formId";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":formId", $formId, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Form( $row );
  }
 
 
  /**
  * Returns all (or a range of) Form objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @param int Optional Return just form in the category with this ID
  * @param string Optional column by which to order the form (default="formPublicationDate DESC")
  * @return Array|false A two-element array : results => array, a list of Form objects; totalRows => Total number of form
  */
 
public static function getList( $numRows=1000000, $formCategoryId=null, $order="formPublicationDate DESC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $categoryClause = $formCategoryId ? "WHERE formCategoryId = :formCategoryId" : "";
    $sql = "SELECT SQL_CALC_FOUND_ROWS *
            FROM form $categoryClause
            ORDER BY " . mysql_escape_string($order) . " LIMIT :numRows";
 
    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
	if ( $formCategoryId ) $st->bindValue( ":formCategoryId", $formCategoryId, PDO::PARAM_INT );
    $st->execute();
    $list = array();
 
    while ( $row = $st->fetch() ) {
      $form = new Form( $row );
      $list[] = $form;
    }
 
    // Now get the total number of form that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }
 
 
  /**
  * Inserts the current Form object into the database, and sets its ID property.
  */
 
  public function insert() {
 
    // Does the Form object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Form::insert(): Attempt to insert an Form object that already has its ID property set (to $this->formId).", E_USER_ERROR );
 
    // Insert the Form
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO form ( formCategoryId, formTitle, formSummary, formContent ) VALUES ( :formCategoryId, :formTitle, :formSummary, :formContent )";
    $st = $conn->prepare ( $sql );
	$st->bindValue( ":formCategoryId", $this->formCategoryId, PDO::PARAM_INT );
    $st->bindValue( ":formTitle", $this->formTitle, PDO::PARAM_STR );
    $st->bindValue( ":formSummary", $this->formSummary, PDO::PARAM_STR );
    $st->bindValue( ":formContent", $this->formContent, PDO::PARAM_STR );
    $st->execute();
    $this->formId = $conn->lastInsertId();
    $conn = null;
  }
 
 
  /**
  * Updates the current Form object in the database.
  */
 
  public function update() {
 
    // Does the Form object have an ID?
    if ( is_null( $this->formId ) ) trigger_error ( "Form::update(): Attempt to update an Form object that does not have its ID property set.", E_USER_ERROR );
    
    // Update the Form
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE form SET formCategoryId=:formCategoryId, formTitle=:formTitle, formSummary=:formSummary, formContent=:formContent WHERE formId = :formId";
    $st = $conn->prepare ( $sql );
	$st->bindValue( ":formCategoryId", $this->formCategoryId, PDO::PARAM_INT );
    $st->bindValue( ":formTitle", $this->formTitle, PDO::PARAM_STR );
    $st->bindValue( ":formSummary", $this->formSummary, PDO::PARAM_STR );
    $st->bindValue( ":formContent", $this->formContent, PDO::PARAM_STR );
    $st->bindValue( ":formId", $this->formId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
  /**
  * Deletes the current Form object from the database.
  */
 
  public function delete() {
 
    // Does the Form object have an ID?
    if ( is_null( $this->formId ) ) trigger_error ( "Form::delete(): Attempt to delete an Form object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the Form
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM form WHERE formId = :formId LIMIT 1" );
    $st->bindValue( ":formId", $this->formId, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>