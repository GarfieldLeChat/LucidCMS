<?php
 
require( "config.php" );
session_start();
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";
 
if ( $action != "login" && $action != "logout" && !$username ) {
  login();
  exit;
}
 
switch ( $action ) {
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  case 'newArticle':
    newArticle();
    break;
  case 'editArticle':
    editArticle();
    break;
  case 'deleteArticle':
    deleteArticle();
    break;
  case 'listCategories':
    listCategories();
    break;
  case 'newCategory':
    newCategory();
    break;
  case 'editCategory':
    editCategory();
    break;
  case 'deleteCategory':
    deleteCategory();
    break;
 case 'listNavigation':
    listNavigation();
    break;
  case 'newNavigation':
    newNavigation();
    break;
  case 'editNavigation':
    editNavigation();
    break;
  case 'deleteNavBar':
    deleteNavigation();
    break;
  case 'listMedia':
    listMedia();
    break;
  case 'newMedia':
    newMedia();
    break;
  case 'editMedia':
    editMedia();
    break;
  case 'deleteMedia':
    deleteMedia();
    break;
  case 'listMediaCategories':
    listMediaCategories();
    break;
  case 'newMediaCategory':
    newMediaCategory();
    break;
  case 'editMediaCategory':
    editMediaCategory();
    break;
  case 'deleteMediaCategory':
    deleteMediaCategory();
    break;
  case 'listForm':
    listForm();
    break;
  case 'newForm':
    newForm();
    break;
  case 'editForm':
    editForm();
    break;
  case 'deleteForm':
    deleteForm();
    break;
  case 'listFormCategories':
    listFormCategories();
    break;
  case 'newFormCategory':
    newFormCategory();
    break;
  case 'editFormCategory':
    editFormCategory();
    break;
  case 'deleteFormCategory':
    deleteFormCategory();
    break;
  case 'listSlug':
    listSlug();
    break;
  case 'newSlug':
    newSlug();
    break;
  case 'editSlug':
    editSlug();
    break;
  case 'deleteSlug':
    deleteSlug();
    break;
  case 'listSlugCategories':
    listSlugCategories();
    break;
  case 'newSlugCategory':
    newSlugCategory();
    break;
  case 'editSlugCategory':
    editSlugCategory();
    break;
  case 'deleteSlugCategory':
    deleteSlugCategory();
    break;
  default:
    listArticles();
}
 
// Login
function login() {
 
  $results = array();
  $results['pageTitle'] = "Admin Login | lucidCMS";
 
  if ( isset( $_POST['login'] ) ) {
 
    // User has posted the login form: attempt to log the user in
 
    if ( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD ) {
 
      // Login successful: Create a session and redirect to the admin homepage
      $_SESSION['username'] = ADMIN_USERNAME;
      header( "Location: admin.php" );
 
    } else {
 
      // Login failed: display an error message to the user
      $results['errorMessage'] = "Incorrect username or password. Please try again.";
      require( TEMPLATE_PATH . "/admin/loginForm.php" );
    }
 
  } else {
 
    // User has not posted the login form yet: display the form
    require( TEMPLATE_PATH . "/admin/loginForm.php" );
  }
 
}
 
// Logout
function logout() {
  unset( $_SESSION['username'] );
  header( "Location: admin.php" );
}

// Articles

// List Articles 
function listArticles() {
  $results = array();
  $data = Article::getList();
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $data = Category::getList();
  $results['categories'] = array();
  foreach ( $data['results'] as $category ) $results['categories'][$category->categoryId] = $category;
  $results['pageTitle'] = "All Articles";
 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "articleNotFound" ) $results['errorMessage'] = "Error: Article not found.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "articleDeleted" ) $results['statusMessage'] = "Article deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listArticles.php" );
}
 
// New Article
function newArticle() {
 
  $results = array();
  $results['pageTitle'] = "New Article";
  $results['formAction'] = "newArticle";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the article edit form: save the new article
    $article = new Article;
    $article->storeFormValues( $_POST );
    $article->insert();
    if ( isset( $_FILES['image'] ) ) $article->storeUploadedImage( $_FILES['image'] );
    header( "Location: admin.php?status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the article list
    header( "Location: admin.php" );
  } else {
 
    // User has not posted the article edit form yet: display the form
    $results['article'] = new Article;
	$data = Category::getList();
    $results['categories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }
 
}
 
// Edit Article 
function editArticle() {
 
  $results = array();
  $results['pageTitle'] = "Edit Article";
  $results['formAction'] = "editArticle";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the article edit form: save the article changes
 
    if ( !$article = Article::getById( (int)$_POST['articleId'] ) ) {
      header( "Location: admin.php?error=articleNotFound" );
      return;
    }
 
    $article->storeFormValues( $_POST );
    if ( isset($_POST['deleteImage']) && $_POST['deleteImage'] == "yes" ) $article->deleteImages();
    $article->update();
    if ( isset( $_FILES['image'] ) ) $article->storeUploadedImage( $_FILES['image'] );
    header( "Location: admin.php?status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the article list
    header( "Location: admin.php" );
  } else {
 
    // User has not posted the article edit form yet: display the form
    $results['article'] = Article::getById( (int)$_GET['articleId'] );
	$data = Category::getList();
    $results['categories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }
 
}
 
// Delete Article
function deleteArticle() {
 
  if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
    header( "Location: admin.php?error=articleNotFound" );
    return;
  }
 
  $article->deleteImages();
  $article->delete();
  header( "Location: admin.php?status=articleDeleted" );
}

// List Categories
function listCategories() {
  $results = array();
  $data = Category::getList();
  $results['categories'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Article Categories";
 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "categoryNotFound" ) $results['errorMessage'] = "Error: Category not found.";
    if ( $_GET['error'] == "categoryContainsArticles" ) $results['errorMessage'] = "Error: Category contains articles. Delete the articles, or assign them to another category, before deleting this category.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "categoryDeleted" ) $results['statusMessage'] = "Category deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listCategories.php" );
}

// New Category
function newCategory() {
 
  $results = array();
  $results['pageTitle'] = "New Article Category";
  $results['formAction'] = "newCategory";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the category edit form: save the new category
    $category = new Category;
    $category->storeFormValues( $_POST );
    $category->insert();
    header( "Location: admin.php?action=listCategories&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the category list
    header( "Location: admin.php?action=listCategories" );
  } else {
 
    // User has not posted the category edit form yet: display the form
    $results['category'] = new Category;
	$data = Category::getList();
	$results['categories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editCategory.php" );
  }
 
}
 
// Edit Category
function editCategory() {
 
  $results = array();
  $results['pageTitle'] = "Edit Article Category";
  $results['formAction'] = "editCategory";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the category edit form: save the category changes
 
    if ( !$category = Category::getById( (int)$_POST['categoryId'] ) ) {
      header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
      return;
    }
 
    $category->storeFormValues( $_POST );
    $category->update();
    header( "Location: admin.php?action=listCategories&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the category list
    header( "Location: admin.php?action=listCategories" );
  } else {
 
    // User has not posted the category edit form yet: display the form
    $results['category'] = Category::getById( (int)$_GET['categoryId'] );
	$data = Category::getList();
	$results['categories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editCategory.php" );
  }
 
}

// Delete Cateogry 
function deleteCategory() {
 
  if ( !$category = Category::getById( (int)$_GET['categoryId'] ) ) {
    header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
    return;
  }
 
  $articles = Article::getList( 1000000, $category->categoryId );
 
  if ( $articles['totalRows'] > 0 ) {
    header( "Location: admin.php?action=listCategories&error=categoryContainsArticles" );
    return;
  }
 
  $category->delete();
  header( "Location: admin.php?action=listCategories&status=categoryDeleted" );
}

// Nagivation
function listNavigation() {
  $results = array();
  $data = Navigation::getList();
  $results['navigations'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Article Navigation";
  
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "navigationNotFound" ) $results['errorMessage'] = "Error: Navigation not found.";
    if ( $_GET['error'] == "navigationContainsArticles" ) $results['errorMessage'] = "Error: Navigation contains articles. Delete the articles, or assign them to another navigation, before deleting this navigation.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "navigationDeleted" ) $results['statusMessage'] = "Navigation deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listNav.php" );
}

// New Navigation
function newNavigation() {
 
  $results = array();
  $results['pageTitle'] = "New Menu Item";
  $results['formAction'] = "newNavigation";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the navigation edit form: save the new navigation
    $navBar = new Navigation;
    $navBar->storeFormValues( $_POST );
    $navBar->insert();
    header( "Location: admin.php?action=listNavigation&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the navigation list
    header( "Location: admin.php?action=listNavigation" );
  } else {
 
    // User has not posted the navigation edit form yet: display the form
    $results['navigation'] = new Navigation;
	$data = Navigation::getList();
	$results['navigations'] = $data['results'];
	$data = LinkType::getList();
    $results['linktype'] = $data['results'];
	$data = Category::getList();
	$results['categories'] = $data['results'];
	$data = Article::getList();
	$results['articles'] = $data['results'];
	$data = Form::getList();
	$results['form'] = $data['results'];
	$data = Media::getList();
	$results['media'] = $data['results'];
	$data = Slug::getList();
	$results['slug'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editNav.php" );
  }
 
}

// Edit Navigation 
function editNavigation() {
 
  $results = array();
  $results['pageTitle'] = "Edit Article Navigation";
  $results['formAction'] = "editNavigation";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the navigation edit form: save the navigation changes
 
    if ( !$navBar = Navigation::getById( (int)$_POST['navBarId'] ) ) {
      header( "Location: admin.php?action=listNavigation&error=navigationNotFound" );
      return;
    }
 
    $navBar->storeFormValues( $_POST );
    $navBar->update();
    header( "Location: admin.php?action=listNavigation&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the navigation list
    header( "Location: admin.php?action=listNavigation" );
  } else {
 
    // User has not posted the navigation edit form yet: display the form
    $results['navigation'] = Navigation::getById( (int)$_GET['navBarId'] );
	$data = Navigation::getList();
	$results['navigations'] = $data['results'];
	$data = LinkType::getList();
    $results['linktype'] = $data['results'];
	$data = Category::getList();
	$results['categories'] = $data['results'];
	$data = Article::getList();
	$results['articles'] = $data['results'];
	$data = Form::getList();
	$results['form'] = $data['results'];
	$data = Media::getList();
	$results['media'] = $data['results'];
	$data = Slug::getList();
	$results['slug'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editNav.php" );
  }
 
}

// Delete Navigation 
function deleteNavigation() {
 
  if ( !$navBar = Navigation::getById( (int)$_GET['navBarId'] ) ) {
    header( "Location: admin.php?action=listNavigation&error=navigationNotFound" );
    return;
  }
 
  $navBar->delete();
  header( "Location: admin.php?action=listNavigation&status=navigationDeleted" );
}

// Media 

// List Media 
function listMedia() {
  $results = array();
  $data = Media::getList();
  $results['media'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $data = MediaCategory::getList();
  $results['mediaCategories'] = array();
  foreach ( $data['results'] as $mediaCategory ) $results['mediaCategories'][$mediaCategory->mediaCategoryId] = $mediaCategory;
  $results['pageTitle'] = "All Medias";
 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "mediaNotFound" ) $results['errorMessage'] = "Error: Media not found.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "mediaDeleted" ) $results['statusMessage'] = "Media deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listMedia.php" );
}

// New Media
function newMedia() {
 
  $results = array();
  $results['pageTitle'] = "New Media Item";
  $results['formAction'] = "newMedia";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the media edit form: save the new media
    $media = new Media;
    $media->storeFormValues( $_POST );
    $media->insert();
    if ( isset( $_FILES['image'] ) ) $media->storeUploadedImage( $_FILES['image'] );
    header( "Location: admin.php?action=listMedia&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the media list
    header( "Location: admin.php" );
  } else {
 
    // User has not posted the media edit form yet: display the form
    $results['media'] = new Media;
	$data = MediaCategory::getList();
    $results['mediaCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editMedia.php" );
  }
 
}
 
// Edit Media 
function editMedia() {
 
  $results = array();
  $results['pageTitle'] = "Edit Media";
  $results['formAction'] = "editMedia";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the media edit form: save the media changes
 
    if ( !$media = Media::getById( (int)$_POST['mediaId'] ) ) {
      header( "Location: admin.php?action=listMedia&error=mediaNotFound" );
      return;
    }
 
    $media->storeFormValues( $_POST );
    if ( isset($_POST['deleteImage']) && $_POST['deleteImage'] == "yes" ) $media->deleteImages();
    $media->update();
    if ( isset( $_FILES['image'] ) ) $media->storeUploadedImage( $_FILES['image'] );
    header( "Location: admin.php?action=listMedia&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the media list
    header( "Location: admin.php" );
  } else {
 
    // User has not posted the media edit form yet: display the form
    $results['media'] = Media::getById( (int)$_GET['mediaId'] );
	$data = MediaCategory::getList();
    $results['mediaCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editMedia.php" );
  }
 
}
 
// Delete Media
function deleteMedia() {
 
  if ( !$media = Media::getById( (int)$_GET['mediaId'] ) ) {
    header( "Location: admin.php?action=listMedia&error=mediaNotFound" );
    return;
  }
 
  $media->deleteImages();
  $media->delete();
  header( "Location: admin.php?action=listMedia&status=mediaDeleted" );
}

// List MediaCategories
function listMediaCategories() {
  $results = array();
  $data = MediaCategory::getList();
  $results['mediaCategories'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Media Categories";
 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "mediaCategoryNotFound" ) $results['errorMessage'] = "Error: mediaCategory not found.";
    if ( $_GET['error'] == "mediaCategoryContainsMedias" ) $results['errorMessage'] = "Error: mediaCategory contains medias. Delete the medias, or assign them to another mediaCategory, before deleting this mediaCategory.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "mediaCategoryDeleted" ) $results['statusMessage'] = "mediaCategory deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listMediaCategories.php" );
}
 
// New Media Category
function newMediaCategory() {
 
  $results = array();
  $results['pageTitle'] = "New Media Category";
  $results['formAction'] = "newMediaCategory";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the mediaCategory edit form: save the new mediaCategory
    $mediaCategory = new MediaCategory;
    $mediaCategory->storeFormValues( $_POST );
    $mediaCategory->insert();
    header( "Location: admin.php?action=listMediaCategories&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the mediaCategory list
    header( "Location: admin.php?action=listMediaCategories" );
  } else {
 
    // User has not posted the mediaCategory edit form yet: display the form
    $results['mediaCategory'] = new MediaCategory;
	$data = MediaCategory::getList();
	$results['mediaCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editMediaCategory.php" );
  }
 
}
 
// Edit mediaCategory
function editMediaCategory() {
 
  $results = array();
  $results['pageTitle'] = "Edit Media mediaCategory";
  $results['formAction'] = "editmediaCategory";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the mediaCategory edit form: save the mediaCategory changes
 
    if ( !$mediaCategory = MediaCategory::getById( (int)$_POST['mediaCategoryId'] ) ) {
      header( "Location: admin.php?action=listMediaCategories&error=mediaCategoryNotFound" );
      return;
    }
 
    $mediaCategory->storeFormValues( $_POST );
    $mediaCategory->update();
    header( "Location: admin.php?action=listMediaCategories&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the mediaCategory list
    header( "Location: admin.php?action=listMediaCategories" );
  } else {
 
    // User has not posted the mediaCategory edit form yet: display the form
    $results['mediaCategory'] = MediaCategory::getById( (int)$_GET['mediaCategoryId'] );
	$data = mediaCategory::getList();
	$results['mediaCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editMediaCategory.php" );
  }
 
}

// Delete Media Cateogry 
function deleteMediaCategory() {
 
  if ( !$mediaCategory = MediaCategory::getById( (int)$_GET['mediaCategoryId'] ) ) {
    header( "Location: admin.php?action=listMediaCategories&error=mediaCategoryNotFound" );
    return;
  }
 
  $media = Media::getList( 1000000, $mediaCategory->mediaCategoryId );
 
  if ( $media['totalRows'] > 0 ) {
    header( "Location: admin.php?action=listMediaCategories&error=mediaCategoryContainsMedia" );
    return;
  }
 
  $mediaCategory->delete();
  header( "Location: admin.php?action=listMediaCategories&status=mediaCategoryDeleted" );
}

// Form 

// List Form 
function listForm() {
  $results = array();
  $data = Form::getList();
  $results['form'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $data = FormCategory::getList();
  $results['formCategories'] = array();
  foreach ( $data['results'] as $formCategory ) $results['formCategories'][$formCategory->formCategoryId] = $formCategory;
  $results['pageTitle'] = "All Forms";
 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "formNotFound" ) $results['errorMessage'] = "Error: Form not found.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "formDeleted" ) $results['statusMessage'] = "Form deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listForm.php" );
}

// New Form
function newForm() {
 
  $results = array();
  $results['pageTitle'] = "New Form Item";
  $results['formAction'] = "newForm";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the form edit form: save the new form
    $form = new Form;
    $form->storeFormValues( $_POST );
    $form->insert();
    if ( isset( $_FILES['image'] ) ) $form->storeUploadedImage( $_FILES['image'] );
    header( "Location: admin.php?action=listForm&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the form list
    header( "Location: admin.php" );
  } else {
 
    // User has not posted the form edit form yet: display the form
    $results['form'] = new Form;
	$data = FormCategory::getList();
    $results['formCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editForm.php" );
  }
 
}
 
// Edit Form 
function editForm() {
 
  $results = array();
  $results['pageTitle'] = "Edit Form";
  $results['formAction'] = "editForm";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the form edit form: save the form changes
 
    if ( !$form = Form::getById( (int)$_POST['formId'] ) ) {
      header( "Location: admin.php?action=listForm&error=formNotFound" );
      return;
    }
 
    $form->storeFormValues( $_POST );
    if ( isset($_POST['deleteImage']) && $_POST['deleteImage'] == "yes" ) $form->deleteImages();
    $form->update();
    if ( isset( $_FILES['image'] ) ) $form->storeUploadedImage( $_FILES['image'] );
    header( "Location: admin.php?action=listForm&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the form list
    header( "Location: admin.php" );
  } else {
 
    // User has not posted the form edit form yet: display the form
    $results['form'] = Form::getById( (int)$_GET['formId'] );
	$data = FormCategory::getList();
    $results['formCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editForm.php" );
  }
 
}
 
// Delete Form
function deleteForm() {
 
  if ( !$form = Form::getById( (int)$_GET['formId'] ) ) {
    header( "Location: admin.php?action=listForm&error=formNotFound" );
    return;
  }
 
  $form->delete();
  header( "Location: admin.php?action=listForm&status=formDeleted" );
}

// List Form Categories
function listFormCategories() {
  $results = array();
  $data = FormCategory::getList();
  $results['formCategories'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Form Categories";
 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "formCategoryNotFound" ) $results['errorMessage'] = "Error: formCategory not found.";
    if ( $_GET['error'] == "formCategoryContainsForms" ) $results['errorMessage'] = "Error: formCategory contains forms. Delete the forms, or assign them to another formCategory, before deleting this formCategory.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "formCategoryDeleted" ) $results['statusMessage'] = "formCategory deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listFormCategories.php" );
}

// New Form Category 
 
function newFormCategory() {
 
  $results = array();
  $results['pageTitle'] = "New Form Category";
  $results['formAction'] = "newFormCategory";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the formCategory edit form: save the new formCategory
    $formCategory = new FormCategory;
    $formCategory->storeFormValues( $_POST );
    $formCategory->insert();
    header( "Location: admin.php?action=listFormCategories&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the formCategory list
    header( "Location: admin.php?action=listFormCategories" );
  } else {
 
    // User has not posted the formCategory edit form yet: display the form
    $results['formCategory'] = new FormCategory;
	$data = FormCategory::getList();
	$results['formCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editFormCategory.php" );
  }
 
}
 
// Edit Form Category
function editFormCategory() {
 
  $results = array();
  $results['pageTitle'] = "Edit Form formCategory";
  $results['formAction'] = "editformCategory";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the formCategory edit form: save the formCategory changes
 
    if ( !$formCategory = FormCategory::getById( (int)$_POST['formCategoryId'] ) ) {
      header( "Location: admin.php?action=listFormCategories&error=formCategoryNotFound" );
      return;
    }
 
    $formCategory->storeFormValues( $_POST );
    $formCategory->update();
    header( "Location: admin.php?action=listFormCategories&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the formCategory list
    header( "Location: admin.php?action=listFormCategories" );
  } else {
 
    // User has not posted the formCategory edit form yet: display the form
    $results['formCategory'] = FormCategory::getById( (int)$_GET['formCategoryId'] );
	$data = formCategory::getList();
	$results['formCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editFormCategory.php" );
  }
 
}

// Delete Form Cateogry 
function deleteFormCategory() {
 
  if ( !$formCategory = FormCategory::getById( (int)$_GET['formCategoryId'] ) ) {
    header( "Location: admin.php?action=listFormCategories&error=formCategoryNotFound" );
    return;
  }
 
  $form = Form::getList( 1000000, $formCategory->formCategoryId );
 
  if ( $form['totalRows'] > 0 ) {
    header( "Location: admin.php?action=listFormCategories&error=formCategoryContainsForm" );
    return;
  }
 
  $formCategory->delete();
  header( "Location: admin.php?action=listFormCategories&status=formCategoryDeleted" );
}
// Slug 

// List Slug 
function listSlug() {
  $results = array();
  $data = Slug::getList();
  $results['slug'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $data = SlugCategory::getList();
  $results['slugCategories'] = array();
  foreach ( $data['results'] as $slugCategory ) $results['slugCategories'][$slugCategory->slugCategoryId] = $slugCategory;
  $results['pageTitle'] = "All Slugs";
 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "slugNotFound" ) $results['errorMessage'] = "Error: Slug not found.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "slugDeleted" ) $results['statusMessage'] = "Slug deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listSlug.php" );
}

// New Slug
function newSlug() {
 
  $results = array();
  $results['pageTitle'] = "New Slug Item";
  $results['formAction'] = "newSlug";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the slug edit form: save the new slug
    $slug = new Slug;
    $slug->storeFormValues( $_POST );
    $slug->insert();
    if ( isset( $_FILES['image'] ) ) $slug->storeUploadedImage( $_FILES['image'] );
    header( "Location: admin.php?action=listSlug&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the slug list
    header( "Location: admin.php" );
  } else {
 
    // User has not posted the slug edit form yet: display the form
    $results['slug'] = new Slug;
	$data = SlugCategory::getList();
    $results['slugCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editSlug.php" );
  }
 
}
 
// Edit Slug 
function editSlug() {
 
  $results = array();
  $results['pageTitle'] = "Edit Slug";
  $results['formAction'] = "editSlug";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the slug edit form: save the slug changes
 
    if ( !$slug = Slug::getById( (int)$_POST['slugId'] ) ) {
      header( "Location: admin.php?action=listSlug&error=slugNotFound" );
      return;
    }
 
    $slug->storeFormValues( $_POST );
    if ( isset($_POST['deleteImage']) && $_POST['deleteImage'] == "yes" ) $slug->deleteImages();
    $slug->update();
    if ( isset( $_FILES['image'] ) ) $slug->storeUploadedImage( $_FILES['image'] );
    header( "Location: admin.php?action=listSlug&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the slug list
    header( "Location: admin.php" );
  } else {
 
    // User has not posted the slug edit form yet: display the form
    $results['slug'] = Slug::getById( (int)$_GET['slugId'] );
	$data = SlugCategory::getList();
    $results['slugCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editSlug.php" );
  }
 
}
 
// Delete Slug
function deleteSlug() {
 
  if ( !$slug = Slug::getById( (int)$_GET['slugId'] ) ) {
    header( "Location: admin.php?action=listSlug&error=slugNotFound" );
    return;
  }
 
  $slug->delete();
  header( "Location: admin.php?action=listSlug&status=slugDeleted" );
}

// List Slug Categories
function listSlugCategories() {
  $results = array();
  $data = SlugCategory::getList();
  $results['slugCategories'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Slug Categories";
 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "slugCategoryNotFound" ) $results['errorMessage'] = "Error: slugCategory not found.";
    if ( $_GET['error'] == "slugCategoryContainsSlugs" ) $results['errorMessage'] = "Error: slugCategory contains slugs. Delete the slugs, or assign them to another slugCategory, before deleting this slugCategory.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "slugCategoryDeleted" ) $results['statusMessage'] = "slugCategory deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listSlugCategories.php" );
}

// New Slug Category
function newSlugCategory() {
 
  $results = array();
  $results['pageTitle'] = "New Slug Category";
  $results['formAction'] = "newSlugCategory";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the slugCategory edit form: save the new slugCategory
    $slugCategory = new SlugCategory;
    $slugCategory->storeFormValues( $_POST );
    $slugCategory->insert();
    header( "Location: admin.php?action=listSlugCategories&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the slugCategory list
    header( "Location: admin.php?action=listSlugCategories" );
  } else {
 
    // User has not posted the slugCategory edit form yet: display the form
    $results['slugCategory'] = new SlugCategory;
	$data = SlugCategory::getList();
	$results['slugCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editSlugCategory.php" );
  }
 
}
 
// Edit Slug Category
function editSlugCategory() {
 
  $results = array();
  $results['pageTitle'] = "Edit Slug slugCategory";
  $results['formAction'] = "editslugCategory";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the slugCategory edit form: save the slugCategory changes
 
    if ( !$slugCategory = SlugCategory::getById( (int)$_POST['slugCategoryId'] ) ) {
      header( "Location: admin.php?action=listSlugCategories&error=slugCategoryNotFound" );
      return;
    }
 
    $slugCategory->storeFormValues( $_POST );
    $slugCategory->update();
    header( "Location: admin.php?action=listSlugCategories&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the slugCategory list
    header( "Location: admin.php?action=listSlugCategories" );
  } else {
 
    // User has not posted the slugCategory edit form yet: display the form
    $results['slugCategory'] = SlugCategory::getById( (int)$_GET['slugCategoryId'] );
	$data = slugCategory::getList();
	$results['slugCategories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editSlugCategory.php" );
  }
 
}

// Delete Slug Cateogry 
function deleteSlugCategory() {
 
  if ( !$slugCategory = SlugCategory::getById( (int)$_GET['slugCategoryId'] ) ) {
    header( "Location: admin.php?action=listSlugCategories&error=slugCategoryNotFound" );
    return;
  }
 
  $slug = Slug::getList( 1000000, $slugCategory->slugCategoryId );
 
  if ( $slug['totalRows'] > 0 ) {
    header( "Location: admin.php?action=listSlugCategories&error=slugCategoryContainsSlug" );
    return;
  }
 
  $slugCategory->delete();
  header( "Location: admin.php?action=listSlugCategories&status=slugCategoryDeleted" );
}

?>