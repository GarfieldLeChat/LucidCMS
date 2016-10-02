<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
      <h1><?php echo $results['pageTitle']?></h1>
 
      <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="mediaCategoryId" value="<?php echo $results['mediaCategory']->mediaCategoryId ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
        <ul>
 
          <li>
            <label for="mediaCategoryName">Media Category Name</label>
            <input type="text" name="mediaCategoryName" id="mediaCategoryName" placeholder="Name of the mediaCategory" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['mediaCategory']->mediaCategoryName )?>" />
          </li>
          
           <li>
            <label for="mediaCategoryParentId">Media Category Parent</label>
            <select name="mediaCategoryParentId" id="mediaCategoryParentId" class="chosen-single chosen-default chosen-select-width">
            <?php foreach ( $results['mediaCategories'] as $mediaCategory ) { ?>
              <option value="<?php echo $mediaCategory->mediaCategoryId?>"<?php echo ( $mediaCategory->mediaCategoryId == $results['mediaCategory']->mediaCategoryParentId ) ? " selected" : ""?>><?php echo ( $mediaCategory->mediaCategoryName )?></option>
            <?php } ?>
            </select>
          </li>
          
          <li>
            <label for="mediaCategoryDescription">Description</label>
            <textarea name="mediaCategoryDescription" id="mediaCategoryDescription" placeholder="Brief description of the mediaCategory" required maxlength="1000" style="height: 5em;" class="ckeditor"><?php echo htmlspecialchars( $results['mediaCategory']->mediaCategoryDescription )?></textarea>
          </li>
 
        </ul>
        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
      </form>
 
<?php if ( $results['mediaCategory']->mediaCategoryId ) { ?>
      <p><a href="admin.php?action=deleteMediaCategory&amp;mediaCategoryId=<?php echo $results['mediaCategory']->mediaCategoryId ?>" onclick="return confirm('Delete This Media Category?')" class="btn btn-default"><span class="glyphicon glyphicon-minus"></span> Delete This Media Category</a></p>
<?php } ?>
 
<?php include "templates/include/footer.php" ?>