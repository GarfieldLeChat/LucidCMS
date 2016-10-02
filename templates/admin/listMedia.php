<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
      <h1>All Media Items</h1>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
 
<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>
 <p class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> <a href="admin.php?action=newMedia">Add a New Media Item</a></p>
 <p>&nbsp;</p>
      <table>
        <tr>
          <th>Media Item</th>
          <th>Category</th>
        </tr>
 
<?php foreach ( $results['media'] as $media ) { ?>
 
        <tr onclick="location='admin.php?action=editMedia&amp;mediaId=<?php echo $media->mediaId?>'">
          <td>
            <?php echo $media->mediaTitle?>
          </td>
          <td><?php if ( $results['mediaCategories'][$media->mediaCategoryId] ) {
			  echo $results['mediaCategories'][$media->mediaCategoryId]->mediaCategoryName;
		  } elseif ( $results['mediaCategories'][$media->mediaCategoryId == 0] ) {
			  echo 'Uncategorised';}?>
          </td>
        </tr>
 
<?php } ?>
 
      </table>
 
      <p><?php echo $results['totalRows']?> Media Item<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>
 
      
 
<?php include "templates/include/footer.php" ?>