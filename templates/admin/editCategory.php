<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
      <h1><?php echo $results['pageTitle']?></h1>
 
      <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="categoryId" value="<?php echo $results['category']->categoryId ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
        <ul>
 
          <li>
            <label for="categoryName">Category Name</label>
            <input type="text" name="categoryName" id="categoryName" placeholder="Name of the category" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['category']->categoryName )?>" />
          </li>
          
           <li>
            <label for="categoryParentId">Category Parent</label>
            <select name="categoryParentId" id="categoryParentId" class="chosen-single chosen-default chosen-select-width">
            <?php foreach ( $results['categories'] as $category ) { ?>
              <option value="<?php echo $category->categoryId?>"<?php echo ( $category->categoryId == $results['category']->categoryParentId ) ? " selected" : ""?>><?php echo ( $category->categoryName )?></option>
            <?php } ?>
            </select>
          </li>
          
          <li>
            <label for="categoryDescription">Description</label>
            <textarea name="categoryDescription" id="categoryDescription" placeholder="Brief description of the category" required maxlength="1000" style="height: 5em;" class="ckeditor"><?php echo htmlspecialchars( $results['category']->categoryDescription )?></textarea>
          </li>
 
        </ul>
 
        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
 
      </form>
 
<?php if ( $results['category']->categoryId ) { ?>
      <p><a href="admin.php?action=deleteCategory&amp;categoryId=<?php echo $results['category']->categoryId ?>" onclick="return confirm('Delete This Category?')" class="btn btn-default"><span class="glyphicon glyphicon-minus"></span> Delete This Category</a></p>
<?php } ?>
 
<?php include "templates/include/footer.php" ?>