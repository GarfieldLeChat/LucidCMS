<div id="adminHeader">
  <h2>lucidCMS Admin</h2>
  <p>You are logged in as <b><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars( $_SESSION['username']) ?></b>.</p>
  
  <nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="admin.php"><span class="glyphicon glyphicon-home"></span></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-list"></span> Site Menus <b class="caret"></b></a>
      <ul class="dropdown-menu">
      <li><a href="admin.php?action=listNavigation"><span class="glyphicon glyphicon-th-list"></span> Edit Menus</a></li>
      <li><a href="admin.php?action=newNavigation"><span class="glyphicon glyphicon-plus"></span> Add New Menu</a></li>
      </ul>
      </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-file"></span>Content <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="admin.php?action=listArticles"><span class="glyphicon glyphicon-list-alt"></span> Edit Articles</a></li>
            <li><a href="admin.php?action=newArticle"><span class="glyphicon glyphicon-plus"></span> Add New Article</a></li>
            <li class="divider"></li>
            <li><a href="admin.php?action=listCategories"><span class="glyphicon glyphicon-tasks"></span> Edit Categories</a></li>
            <li><a href="admin.php?action=newCategory"><span class="glyphicon glyphicon-plus"></span> Add New Category</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-film"></span> Media <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="admin.php?action=listMedia"><span class="glyphicon glyphicon-picture"></span> Edit Media</a></li>
            <li><a href="admin.php?action=newMedia"><span class="glyphicon glyphicon-plus"></span> Add New Media</a></li>
            <li class="divider"></li>
            <li><a href="admin.php?action=listMediaCategories"><span class="glyphicon glyphicon-tasks"></span> Edit Media Categories</a></li>
            <li><a href="admin.php?action=newMediaCategory"><span class="glyphicon glyphicon-plus"></span> Add New Media Category</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-pencil"></span> Forms <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="admin.php?action=listForm"><span class="glyphicon glyphicon-edit"></span> Edit Form</a></li>
            <li><a href="admin.php?action=newForm"><span class="glyphicon glyphicon-plus"></span> Add New Form</a></li>
            <li class="divider"></li>
            <li><a href="admin.php?action=listFormCategories"><span class="glyphicon glyphicon-tasks"></span> Edit Form Categories</a></li>
            <li><a href="admin.php?action=newFormCategory"><span class="glyphicon glyphicon-plus"></span> Add New Form Category</a></li>
            </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-compressed"></span> Slugs <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="admin.php?action=listSlug"><span class="glyphicon glyphicon-transfer"></span> Edit Slug</a></li>
            <li><a href="admin.php?action=newSlug"><span class="glyphicon glyphicon-plus"></span> Add New Slug</a></li>
            <li class="divider"></li>
            <li><a href="admin.php?action=listSlugCategories"><span class="glyphicon glyphicon-tasks"></span> Edit Slug Categories</a></li>
            <li><a href="admin.php?action=newSlugCategory"><span class="glyphicon glyphicon-plus"></span> Add New Slug Category</a></li>
          </ul>
          </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
          <li><a href="admin.php?action=logout"><span class="glyphicon glyphicon-off"></span> Log Out</a></li>
      </ul>
      <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">	
        </div>
        <span class="glyphicon glyphicon-search"></span>
        <button type="submit" class="btn btn-default">Search</button>
      </form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</div>