<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
      <h1>Media Categories</h1>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
 
<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>
 <p class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> <a href="admin.php?action=newMediaCategory">Add a New Media Category</a></p>
 <p>&nbsp;</p>
      <table>
        <tr>        
          <th>Media Category Name</th>
          <th>Media Category ID</th>
          <th>Media Category Parent ID</th>
        </tr>
 
<?php foreach ( $results['mediaCategories'] as $mediaCategory ) { ?>
 
        <tr onclick="location='admin.php?action=editMediaCategory&amp;mediaCategoryId=<?php echo $mediaCategory->mediaCategoryId?>'">
          <td>
            <?php echo $mediaCategory->mediaCategoryName?>
          </td>
          <td><?php echo $mediaCategory->mediaCategoryId?></td>
          <td><?php if ($mediaCategory->mediaCategoryId == $mediaCategory->mediaCategoryParentId) {
			  echo $mediaCategory->mediaCategoryId;
			   }
		  else
			  echo $mediaCategory->mediaCategoryParentId;
		  ?></td>
        </tr>
 
<?php } ?>
 
      </table>
 
      <p><?php echo $results['totalRows']?> categor<?php echo ( $results['totalRows'] != 1 ) ? 'ies' : 'y' ?> in total.</p>
 
 
<?php include "templates/include/footer.php" ?>