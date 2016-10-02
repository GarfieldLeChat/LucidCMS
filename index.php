<?php
 
require( "config.php" );
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
 
switch ( $action ) {
  case 'archive':
    archive();
    break;
  case 'viewArticle':
    viewArticle();
    break;
  case 'content':
	  Content();
	  break;
  case 'navigation':
	  Navigation();
	  break;
  default:
    homepage();
}
 
 
function archive() {
  $results = array();
  $categoryId = ( isset( $_GET['categoryId'] ) && $_GET['categoryId'] ) ? (int)$_GET['categoryId'] : null;
  $results['category'] = Category::getById( $categoryId );
  $data = Article::getList( 100000, $results['category'] ? $results['category']->categoryId : null );
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $data = Category::getList();
  $results['categories'] = array();
  foreach ( $data['results'] as $category ) $results['categories'][$category->categoryId] = $category;
  $results['pageHeading'] = $results['category'] ?  $results['category']->categoryName : "Article Archive";
  $results['pageTitle'] = $results['pageHeading'] . " | lucidCMS";
  require( TEMPLATE_PATH . "/archive.php" );
}
 
function viewArticle() {
  if ( !isset($_GET["articleId"]) || !$_GET["articleId"] ) {
    homepage();
    return;
  }
 
  $results = array();
  $results['article'] = Article::getById( (int)$_GET["articleId"] );
  $results['category'] = Category::getById( $results['article']->articleCategoryId );
  $results['pageTitle'] = $results['article']->articleTitle . " | lucidCMS";
  require( TEMPLATE_PATH . "/viewArticle.php" );
}
 
function homepage() {
  $results = array();
  $data = Article::getList( HOMEPAGE_NUM_ARTICLES );
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $data = Category::getList();
  $results['categories'] = array();
  foreach ( $data['results'] as $category ) $results['categories'][$category->categoryId] = $category; 
  $results['pageTitle'] = "lucidCMS";
  require( TEMPLATE_PATH . "/homepage.php" );
}

function navigation() {
  $results = array();
  $data = Navigation::getList( HOMEPAGE_NUM_ARTICLES );
  $results['navigation'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $data = Category::getList();
  $results['navigation'] = array();
  foreach ( $data['results'] as $nav ) $results['navigation'][$nav->navBarId] = $nav; 
  $results['pageTitle'] = "lucidCMS";
  require( TEMPLATE_PATH . "/include/nav.php" );
}

function content() {
  $results = array();
  $data = Content::getList( HOMEPAGE_NUM_ARTICLES );
  $results['content'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "lucidCMS";
  $data = Navigation::getList( HOMEPAGE_NUM_ARTICLES );
  $results['navigation'] = $data['results'];
  require( TEMPLATE_PATH . "/content.php" );
}

/*function navigation() {
  $results = array();
  $data = Article::getList( HOMEPAGE_NUM_ARTICLES );
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $data = Category::getList();
  $results['categories'] = array();
  foreach ( $data['results'] as $category ) $results['categories'][$category->id] = $category; 
  $results['pageTitle'] = "lucidCMS";
  require( TEMPLATE_PATH . "/homepage.php" );
}*/
 
?>