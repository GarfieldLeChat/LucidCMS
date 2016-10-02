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
        <input type="hidden" name="mediaId" value="<?php echo $results['media']->mediaId ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
        <ul>
 
          <li>
            <label for="mediaTitle">Media Item Title</label>
            <input type="text" name="mediaTitle" id="mediaTitle" placeholder="Name of the Media Item" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['media']->mediaTitle )?>" />
          </li>
 
          <li>
            <label for="mediaSummary">Media Item Summary</label>
            <textarea name="mediaSummary" id="mediaSummary" placeholder="Brief description of the Media Item" required maxlength="1000" style="height: 5em;" class="ckeditor"><?php echo htmlspecialchars( $results['media']->mediaSummary )?></textarea>
          </li>
 <?php if ( $results['media'] && $mediaImagePath = $results['media']->getImagePath() ) { ?>
          <li>
            <label>Current Image</label>
            <img id="mediaImage" src="<?php echo $mediaImagePath ?>" alt="<?php echo htmlspecialchars( $results['media']->mediaTitle )?>" title="<?php echo htmlspecialchars( $results['media']->mediaSummary )?>" />
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
            <label for="mediaCategoryId">Media Item Category</label>
            <select name="mediaCategoryId" id="mediaCategoryId" class="chosen-single chosen-default chosen-select-width">
            <?php foreach ( $results['mediaCategories'] as $mediaCategory ) { ?>
              <option value="<?php echo $mediaCategory->mediaCategoryId?>"<?php echo ( $mediaCategory->mediaCategoryId == $results['media']->mediaCategoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $mediaCategory->mediaCategoryName )?></option>
            <?php } ?>
            </select>
          </li>
        </ul>
        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
      </form>
 
<?php if ( $results['media']->mediaId ) { ?>
      <p><a href="admin.php?action=deleteMedia&amp;mediaId=<?php echo $results['media']->mediaId ?>" onclick="return confirm('Delete This Media Item?')" class="btn btn-default"><span class="glyphicon glyphicon-minus"></span> Delete This Media Item</a></p>
<?php } ?>
 
<?php include "templates/include/footer.php" ?>