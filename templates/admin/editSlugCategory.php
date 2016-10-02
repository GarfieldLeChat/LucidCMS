<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
      <h1><?php echo $results['pageTitle']?></h1>
 
      <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="slugCategoryId" value="<?php echo $results['slugCategory']->slugCategoryId ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
        <ul>
 
          <li>
            <label for="slugCategoryName">Slug Category Name</label>
            <input type="text" name="slugCategoryName" id="slugCategoryName" placeholder="Name of the slugCategory" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['slugCategory']->slugCategoryName )?>" />
          </li>
          
           <li>
            <label for="slugCategoryParentId">Slug Category Parent</label>
            <select name="slugCategoryParentId" id="slugCategoryParentId" class="chosen-single chosen-default chosen-select-width">
            <?php foreach ( $results['slugCategories'] as $slugCategory ) { ?>
              <option value="<?php echo $slugCategory->slugCategoryId?>"<?php echo ( $slugCategory->slugCategoryId == $results['slugCategory']->slugCategoryParentId ) ? " selected" : ""?>><?php echo ( $slugCategory->slugCategoryName )?></option>
            <?php } ?>
            </select>
          </li>
          
          <li>
            <label for="slugCategoryDescription">Description</label>
            <textarea name="slugCategoryDescription" id="slugCategoryDescription" placeholder="Brief description of the slugCategory" required maxlength="1000" style="height: 5em;" class="ckeditor"><?php echo htmlspecialchars( $results['slugCategory']->slugCategoryDescription )?></textarea>
          </li>
 
        </ul>
        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
      </form>
 
<?php if ( $results['slugCategory']->slugCategoryId ) { ?>
      <p><a href="admin.php?action=deleteSlugCategory&amp;slugCategoryId=<?php echo $results['slugCategory']->slugCategoryId ?>" onclick="return confirm('Delete This Slug Category?')" class="btn btn-default"><span class="glyphicon glyphicon-minus"></span> Delete This Slug Category</a></p>
<?php } ?>
 
<?php include "templates/include/footer.php" ?>