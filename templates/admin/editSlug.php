<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
      <h1><?php echo $results['pageTitle']?></h1>
 
      <form action="admin.php?action=<?php echo $results['formAction']?>" method="post" enctype="multipart/form-data" onsubmit="closeKeepAlive()">
        <input type="hidden" name="slugId" value="<?php echo $results['slug']->slugId ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
        <ul>
 
          <li>
            <label for="slugTitle">Slug Item Title</label>
            <input type="text" name="slugTitle" id="slugTitle" placeholder="Name of the Slug" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['slug']->slugTitle )?>" />
          </li>
          <li>
            <label for="slugContent">Slug Item Content</label>
            <input type="text" name="slugContent" id="slugContent" placeholder="The Slug" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['slug']->slugContent )?>" />
          </li>
         <li>
            <label for="slugCategoryId">Slug Item Category</label>
            <select name="slugCategoryId" id="slugCategoryId" class="chosen-single chosen-default chosen-select-width">
            <?php foreach ( $results['slugCategories'] as $slugCategory ) { ?>
              <option value="<?php echo $slugCategory->slugCategoryId?>"<?php echo ( $slugCategory->slugCategoryId == $results['slug']->slugCategoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $slugCategory->slugCategoryName )?></option>
            <?php } ?>
            </select>
          </li>
        </ul>
        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
      </form>
<?php if ( $results['slug']->slugId ) { ?>
      <p><a href="admin.php?action=deleteSlug&amp;slugId=<?php echo $results['slug']->slugId ?>" onclick="return confirm('Delete This Slug Item?')" class="btn btn-default"><span class="glyphicon glyphicon-minus"></span> Delete This Slug Item</a></p>
<?php } ?>
 
<?php include "templates/include/footer.php" ?>