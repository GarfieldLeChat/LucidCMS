<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
            <script>
 
      // Prevents file upload hangs in Mac Safari
      // Inspired by http://airbladesoftware.com/notes/note-to-self-prevent-uploads-hanging-in-safari
 
      function closeKeepAlive() {
        if ( /AppleWebKit|MSIE/.test( navigator.userAgent) ) {
          var xhr = new XMLHttpRequest();
          xhr.open( "GET", "/ping/close", false );
          xhr.send();
        }
      }
 
      </script>
      <h1><?php echo $results['pageTitle']?></h1>
 
      <form action="admin.php?action=<?php echo $results['formAction']?>" method="post" enctype="multipart/form-data" onsubmit="closeKeepAlive()">
        <input type="hidden" name="articleId" value="<?php echo $results['article']->articleId ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
        <ul>
 
          <li>
            <label for="articleTitle">Article Title</label>
            <input type="text" name="articleTitle" id="articleTitle" placeholder="Name of the article" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['article']->articleTitle )?>" />
          </li>
 
          <li>
            <label for="articleSummary">Article Summary</label>
            <textarea name="articleSummary" id="articleSummary" placeholder="Brief description of the article" required maxlength="1000" style="height: 5em;" class="ckeditor"><?php echo htmlspecialchars( $results['article']->articleSummary )?></textarea>
          </li>
 
          <li>
            <label for="articleContent">Article Content</label>
            <textarea name="articleContent" id="articleContent" placeholder="The HTML articleContent of the article" required maxlength="100000" style="height: 30em;" class="ckeditor"><?php echo htmlspecialchars( $results['article']->articleContent )?></textarea>
          </li>
 <?php if ( $results['article'] && $articleImagePath = $results['article']->getImagePath() ) { ?>
          <li>
            <label>Current Image</label>
            <img id="articleImage" src="<?php echo $articleImagePath ?>" alt="<?php echo htmlspecialchars( $results['article']->articleTitle )?>" title="<?php echo htmlspecialchars( $results['article']->articleSummary )?>" />
          </li>
 
          <li>
            <label></label>
            <input type="checkbox" name="deleteImage" id="deleteImage" value="yes"/ > <label for="deleteImage">Delete</label>
          </li>
          <?php } ?>
 
          <li>
            <label for="image">New Image</label>
            <input type="file" name="image" id="image" placeholder="Choose an image to upload" maxlength="255" />
          </li>
 
         <li>
            <label for="articleCategoryId">Article Category</label>
            <select name="articleCategoryId" id="articleCategoryId" class="chosen-single chosen-default chosen-select-width">
            <?php foreach ( $results['categories'] as $category ) { ?>
              <option value="<?php echo $category->categoryId?>"<?php echo ( $category->categoryId == $results['article']->articleCategoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $category->categoryName )?></option>
            <?php } ?>
            </select>
          </li>
 
          <li>
            <label for="articlePublicationDate">Publication Date</label>
            <input type="date" name="articlePublicationDate" id="articlePublicationDate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->articlePublicationDate ? date( "Y-m-d", $results['article']->articlePublicationDate ) : "" ?>" />
          </li>
 
 
        </ul>
 
        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
 
      </form>
 
<?php if ( $results['article']->articleId ) { ?>
      <p><a href="admin.php?action=deleteArticle&amp;articleId=<?php echo $results['article']->articleId ?>" onclick="return confirm('Delete This Article?')" class="btn btn-default"><span class="glyphicon glyphicon-minus"></span> Delete This Article</a></p>
<?php } ?>
 
<?php include "templates/include/footer.php" ?>