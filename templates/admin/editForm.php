<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

      <h1><?php echo $results['pageTitle']?></h1>
 
      <form action="admin.php?action=<?php echo $results['formAction']?>" method="post" enctype="multipart/form-data" onsubmit="closeKeepAlive()">
        <input type="hidden" name="formId" value="<?php echo $results['form']->formId ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
        <ul>
          <li>
            <label for="formTitle">Form Item Title</label>
            <input type="text" name="formTitle" id="formTitle" placeholder="Name of the Form Item" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['form']->formTitle )?>" />
          </li>
          <li>
            <label for="formSummary">Form Item Summary</label>
            <textarea name="formSummary" id="formSummary" placeholder="Brief description of the Form Item" required maxlength="1000" style="height: 5em;" class="ckeditor"><?php echo htmlspecialchars( $results['form']->formSummary )?></textarea>
          </li>
          <li>
            <label for="formContent">Form Item Content</label>
            <textarea name="formContent" id="formContent" placeholder="The HTML formContent of the Form Item" required maxlength="100000" style="height: 30em;" class="ckeditor"><?php echo htmlspecialchars( $results['form']->formContent )?></textarea>
          </li>
         <li>
            <label for="formCategoryId">Form Item Category</label>
            <select name="formCategoryId" id="formCategoryId" class="chosen-single chosen-default chosen-select-width">
            <?php foreach ( $results['formCategories'] as $formCategory ) { ?>
              <option value="<?php echo $formCategory->formCategoryId?>"<?php echo ( $formCategory->formCategoryId == $results['form']->formCategoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $formCategory->formCategoryName )?></option>
            <?php } ?>
            </select>
          </li>
        </ul>
        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
      </form>
 
<?php if ( $results['form']->formId ) { ?>
      <p><a href="admin.php?action=deleteForm&amp;formId=<?php echo $results['form']->formId ?>" onclick="return confirm('Delete This Form Item?')" class="btn btn-default"><span class="glyphicon glyphicon-minus"></span> Delete This Form Item</a></p>
<?php } ?>
 
<?php include "templates/include/footer.php" ?>