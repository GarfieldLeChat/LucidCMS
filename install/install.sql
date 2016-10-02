## 
# Create categories Table
##

DROP TABLE IF EXISTS categories;
CREATE TABLE categories
(
  categoryId              smallint unsigned NOT NULL auto_increment,
  categoryParentId        smallint unsigned NOT NULL,
  categoryName            varchar(255) NOT NULL,                      # Name of the category
  categoryDescription     text NOT NULL,                              # A short description of the category
 
  PRIMARY KEY     (categoryId)
);

INSERT INTO `categories` (`categoryId`, `categoryParentId`,`categoryName`, `categoryDescription`) VALUES
(0, 0, 'Uncategorised', '<p>No Category assigned</p>\r\n');


##ALTER TABLE categories ADD parentName VARCHAR(255) AFTER parentId;

## 
# Create articles Table
##
 
DROP TABLE IF EXISTS articles;
CREATE TABLE articles
(
  articleId              smallint unsigned NOT NULL auto_increment,
  articlePublicationDate date NOT NULL,                              # When the article was published
  articleCategoryId      smallint unsigned NOT NULL,                 # The article category ID
  articleTitle           varchar(255) NOT NULL,                      # Full title of the article
  articleSummary         text NOT NULL,                              # A short summary of the article
  articleContent         mediumtext NOT NULL,                        # The HTML content of the article
  articleImageExtension  varchar(255) NOT NULL,                      # The filename extension of the article's full-size and thumbnail images
 
  PRIMARY KEY     (articleId)
);

## 
# Create navBar Table
##

DROP TABLE IF EXISTS navBar;
CREATE TABLE navBar
(
  navBarId              smallint unsigned NOT NULL auto_increment,
  navBarParentId        smallint unsigned NOT NULL,
  navBarLinkType        smallint unsigned NOT NULL,
  navBarName            varchar(255) NOT NULL,                      # Name of the Navigation
  navBarDescription     text NOT NULL,                              # A short description of the Navigation
  navBarURL	            text NOT NULL,                              # Link if provided
  navBarArticleId	    smallint unsigned NOT NULL,                 # ID of the Article if provided
  navBarCatId	        smallint unsigned NOT NULL,                 # ID of the Category if provided
  navBarFormId	        smallint unsigned NOT NULL,                 # ID of the Form if provided
  navBarMediaId	        smallint unsigned NOT NULL,                 # ID of the Media item if provided
  navBarMenuId          smallint unsigned NOT NULL,                 # ID of the Menu item / parent if provided
  navBarSlugId          smallint unsigned NOT NULL,                 # ID of the Slug if provided
   
  PRIMARY KEY     (navBarId)
);

## 
# Create linkType Table
##

DROP TABLE IF EXISTS linkType;
CREATE TABLE linkType
(
  linkTypeId              smallint unsigned NOT NULL auto_increment,
  linkTypeType            varchar(255) NOT NULL,                      # Name of the linkType
  linkTypeName            varchar(255) NOT NULL,
 
  PRIMARY KEY     (linkTypeId)
);

INSERT INTO `linkType` (`linkTypeId`, `linkTypeType`, `linkTypeName`) VALUES
(1, 'URL', 'Link | Youtube, Vimeo, Link, etc | displays the content in the page.'),
(2, 'Article', 'Article | Link to a specific article.'),
(3, 'Category', 'Category | Link to a specific category | links to the parent category archive and shows all items in it'),
(4, 'Form', 'Form | Link to a specific Form'),
(5, 'Media', 'Media | Link to a specific Media Item'),
(6, 'Menu', 'Menu | Link to a specific Menu'),
(7, 'Slug', 'Slug | Link to a specific Slug');

## 
# Create MediaCategories Table
##

DROP TABLE IF EXISTS mediaCategories;
CREATE TABLE mediaCategories
(
  mediaCategoryId              smallint unsigned NOT NULL auto_increment,
  mediaCategoryParentId        smallint unsigned NOT NULL,
  mediaCategoryName            varchar(255) NOT NULL,                      # Name of the mediaCategory
  mediaCategoryDescription     text NOT NULL,                              # A short description of the mediaCategory
 
  PRIMARY KEY     (mediaCategoryId)
);

INSERT INTO `mediaCategories` (`mediaCategoryId`, `mediaCategoryParentId`,`mediaCategoryName`, `mediaCategoryDescription`) VALUES
(0, 0, 'Uncategorised', '<p>No Category assigned</p>\r\n');

## 
# Create media Table
##

DROP TABLE IF EXISTS media;
CREATE TABLE media
(
  mediaId              smallint unsigned NOT NULL auto_increment,
  mediaPublicationDate date NOT NULL,                              # When the media was published
  mediaCategoryId      smallint unsigned NOT NULL,                 # The media category ID
  mediaTitle           varchar(255) NOT NULL,                      # Full title of the media
  mediaSummary         text NOT NULL,                              # A short summary of the media
  mediaContent         mediumtext NOT NULL,                        # The HTML content of the media
  mediaImageExtension  varchar(255) NOT NULL,                      # The filename extension of the media's full-size and thumbnail images
 
  PRIMARY KEY     (mediaId)
);

## 
# Create mediaCategories Table
##

DROP TABLE IF EXISTS formCategories;
CREATE TABLE formCategories
(
  formCategoryId              smallint unsigned NOT NULL auto_increment,
  formCategoryParentId        smallint unsigned NOT NULL,
  formCategoryName            varchar(255) NOT NULL,                      # Name of the formCategory
  formCategoryDescription     text NOT NULL,                              # A short description of the formCategory
 
  PRIMARY KEY     (formCategoryId)
);

INSERT INTO `formCategories` (`formCategoryId`, `formCategoryParentId`,`formCategoryName`, `formCategoryDescription`) VALUES
(0, 0, 'Uncategorised', '<p>No Category assigned</p>\r\n');
 
## 
# Create form Table
##

DROP TABLE IF EXISTS form;
CREATE TABLE form
(
  formId              smallint unsigned NOT NULL auto_increment,
  formPublicationDate date NOT NULL,                              # When the form was published
  formCategoryId      smallint unsigned NOT NULL,                 # The form category ID
  formTitle           varchar(255) NOT NULL,                      # Full title of the form
  formSummary         text NOT NULL,                              # A short summary of the form
  formContent         mediumtext NOT NULL,                        # The HTML content of the form
  formImageExtension  varchar(255) NOT NULL,                      # The filename extension of the form's full-size and thumbnail images
 
  PRIMARY KEY     (formId)
);

## 
# Create FormCategories Table
##

DROP TABLE IF EXISTS slugCategories;
CREATE TABLE slugCategories
(
  slugCategoryId              smallint unsigned NOT NULL auto_increment,
  slugCategoryParentId        smallint unsigned NOT NULL,
  slugCategoryName            varchar(255) NOT NULL,                      # Name of the slugCategory
  slugCategoryDescription     text NOT NULL,                              # A short description of the slugCategory
 
  PRIMARY KEY     (slugCategoryId)
);

INSERT INTO `slugCategories` (`slugCategoryId`, `slugCategoryParentId`,`slugCategoryName`, `slugCategoryDescription`) VALUES
(0, 0, 'Uncategorised', '<p>No Category assigned</p>\r\n');

 
DROP TABLE IF EXISTS slug;
CREATE TABLE slug
(
  slugId              smallint unsigned NOT NULL auto_increment,
  slugPublicationDate date NOT NULL,                              # When the slug was published
  slugCategoryId      smallint unsigned NOT NULL,                 # The slug category ID
  slugTitle           varchar(255) NOT NULL,                      # Full title of the slug
  slugSummary         text NOT NULL,                              # A short summary of the slug
  slugContent         mediumtext NOT NULL,                        # The HTML content of the slug
  slugImageExtension  varchar(255) NOT NULL,                      # The filename extension of the slug's full-size and thumbnail images
 
  PRIMARY KEY     (slugId)
);
