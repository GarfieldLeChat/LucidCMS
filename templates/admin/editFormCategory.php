<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
      <h1><?php echo $results['pageTitle']?></h1>
 
      <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="formCategoryId" value="<?php echo $results['formCategory']->formCategoryId ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
        <ul>
 
          <li>
            <label for="formCategoryName">Form Category Name</label>
            <input type="text" name="formCategoryName" id="formCategoryName" placeholder="Name of the formCategory" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['formCategory']->formCategoryName )?>" />
          </li>
          
           <li>
            <label for="formCategoryParentId">Form Category Parent</label>
            <select name="formCategoryParentId" id="formCategoryParentId" class="chosen-single chosen-default chosen-select-width">
            <?php foreach ( $results['formCategories'] as $formCategory ) { ?>
              <option value="<?php echo $formCategory->formCategoryId?>"<?php echo ( $formCategory->formCategoryId == $results['formCategory']->formCategoryParentId ) ? " selected" : ""?>><?php echo ( $formCategory->formCategoryName )?></option>
            <?php } ?>
            </select>
          </li>
          
          <li>
            <label for="formCategoryDescription">Description</label>
            <textarea name="formCategoryDescription" id="formCategoryDescription" placeholder="Brief description of the formCategory" required maxlength="1000" style="height: 5em;" class="ckeditor"><?php echo htmlspecialchars( $results['formCategory']->formCategoryDescription )?></textarea>
          </li>
 
        </ul>
 
        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
 
      </form>
 
<?php if ( $results['formCategory']->formCategoryId ) { ?>
      <p><a href="admin.php?action=deleteFormCategory&amp;formCategoryId=<?php echo $results['formCategory']->formCategoryId ?>" onclick="return confirm('Delete This Form Category?')" class="btn btn-default"><span class="glyphicon glyphicon-minus"></span> Delete This Form Category</a></p>
<?php } ?>
 
<?php include "templates/include/footer.php" ?>