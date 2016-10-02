<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

 
      <h1><?php echo $results['pageTitle']?></h1>
 
      <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="navBarId" value="<?php echo $results['navigation']->navBarId ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
        <ul>
 
          <li>
          <!-- class="chosen-single chosen-default chosen-select-width" -->
            <label for="navBarName">Menu Name</label>
            <input type="text" name="navBarName" id="navBarName" placeholder="Name of the navigation" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['navigation']->navBarName )?>" />
          </li>
          
           <li>
            <label for="navBarParentId">Menu Parent</label>
            <select name="navBarParentId" id="navBarParentId" class="chosen-single chosen-default chosen-select-width">
              <option value=""></option>
            <?php foreach ( $results['navigations'] as $navigation ) { ?>
<option value="<?php echo $navigation->navBarId?>"<?php echo ($navigation->navBarId == $results['navigation']->navBarParentId ) ? " selected" : ""?>><?php echo ( $navigation->navBarName )?></option>
            <?php } ?>
            </select>
<div class="chosen-container chosen-container-single chosen-with-drop chosen-container-active">
</div>
          </li>
          
          <li>
           <label for="navBarLinkType">Menu Type</label>
            <select name="navBarLinkType" id="navBarLinkType" class="chosen-single chosen-default chosen-select-width">
                <option value=""></option>
            <?php foreach ( $results['linktype'] as $linkType ) { ?>
<option value="<?php echo $linkType->linkTypeId?>"<?php echo ($linkType->linkTypeId == $results['navigation']->navBarLinkType ) ? " selected" : ""?>><?php echo ( $linkType->linkTypeName )?></option>
            <?php } ?>
            </select>
<div class="chosen-container chosen-container-single chosen-with-drop chosen-container-active">
</div>
            <p>Please chose the corresponding item below to link to the menu.</p>

            <div id="linkDiv" class="displayNone">
            <label for="navBarURL">Enter a Link</label><textarea name="navBarURL" id="navBarURL" placeholder="http://, Youtube, Vimeo etc" maxlength="1000" style="height: 2em;"><?php htmlspecialchars( $results['navigation']->navBarURL ) ?></textarea>
            </div>
            <div id="articleDiv" class="displayNone">
            <label for="navBarArticleId">Choose an Article</label>
                        <select name="navBarArticleId" id="navBarArticleId" class="chosen-single chosen-default chosen-select-width">
                <option value=""></option>
						  <?php foreach ( $results['articles'] as $article ) { ?>
<option value="<?php echo $article->articleId?>"<?php echo ($article->articleId == $results['navigation']->navBarArticleId ) ? " selected" : ""?>><?php echo ( $article->articleTitle )?></option>
                          <?php } ?>
                        </select>
<div class="chosen-container chosen-container-single chosen-with-drop chosen-container-active">
</div>
            </div>
            <div id="categoryDiv" class="displayNone"> 
            <label for="navBarCatId">Choose a Category</label>
              <select name="navBarCatId" id="navBarCatId" class="chosen-single chosen-default chosen-select-width">
                <option value=""></option>
              <?php foreach ( $results['categories'] as $category ) { ?>
<option value="<?php echo $category->categoryId?>"<?php echo ( $category->categoryId == $results['navigation']->navBarCatId ) ? " selected" : ""?>><?php echo ( $category->categoryName )?></option>
              <?php } ?>
              </select>
<div class="chosen-container chosen-container-single chosen-with-drop chosen-container-active">
</div>
            </div>
            <div id="formDiv" class="displayNone">
            <label for="navBarFormId">Choose a Form</label>
              <select name="navBarFormId" id="navBarFormId" class="chosen-single chosen-default chosen-select-width">
                <option value=""></option>
              <?php foreach ( $results['form'] as $form ) { ?>
<option value="<?php echo $form->formId?>"<?php echo ( $form->formId == $results['navigation']->navBarFormId ) ? " selected" : ""?>><?php echo ( $form->formName )?></option>
              <?php } ?>
              </select>
<div class="chosen-container chosen-container-single chosen-with-drop chosen-container-active">
</div>
            </div>
            <div id="mediaDiv" class="displayNone">
            <label for="navBarMediaId">Choose a Media Item</label>
              <select name="navBarMediaId" id="navBarMediaId" class="chosen-single chosen-default chosen-select-width">
                <option value=""></option>
              <?php foreach ( $results['media'] as $media ) { ?>
              <option value="<?php echo $media->mediaId?>"<?php echo ( $media->mediaId == $results['navigation']->navBarMediaId ) ? " selected" : ""?>><?php echo ( $media->mediaName )?></option>
              <?php } ?>
              </select>
<div class="chosen-container chosen-container-single chosen-with-drop chosen-container-active">
</div>
            </div>
            <div id="menuDiv" class="displayNone">
            <label for="navBarMenuId">Choose a Menu</label>
              <select name="navBarMenuId" id="navBarMenuId" class="chosen-single chosen-default chosen-select-width">
                <option value=""></option>
              <?php foreach ( $results['navigations'] as $menu ) { ?>
<option value="<?php echo $menu->navBarId?>"<?php echo ( $menu->navBarId == $results['navigation']->navBarMenuId ) ? " selected" : ""?>><?php echo ( $menu->navBarName )?></option>
              <?php } ?>
              </select>
<div class="chosen-container chosen-container-single chosen-with-drop chosen-container-active">
</div>
            </div>
            <div id="slugDiv" class="displayNone">
            <label for="navBarSlugId">Choose a Slug</label>
              <select name="navBarSlugId" id="navBarSlugId" class="chosen-single chosen-default chosen-select-width">
                <option value=""></option>
              <?php foreach ( $results['slug'] as $slug ) { ?>
<option value="<?php echo $slug->slugId?>"<?php echo ( $slug->slugId == $results['navigation']->navBarSlugId ) ? " selected" : ""?>><?php echo ( $slug->slugName )?></option>
              <?php } ?>
              </select>
<div class="chosen-container chosen-container-single chosen-with-drop chosen-container-active">
</div>
            </div>
          </li> 
        </ul>
 <div class="buttons">
          <input type="submit" name="saveChanges" value="Save &amp; Close" class="btn btn-default glyphicon glyphicon-plus" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" class="btn btn-default glyphicon glyphicon-plus" />
        </div>
      </form>
		<?php if ( $results['navigation']->navBarId ) { ?>
      <p class="delete-align"><a href="admin.php?action=deleteNavBar&amp;navBarId=<?php echo $results['navigation']->navBarId ?>" onclick="return confirm('Delete This Navigation?')" class="btn btn-default"><span class="glyphicon glyphicon-minus"></span> Delete This Menu</a></p>
<?php } ?>
 
<?php include "templates/include/footer.php" ?>