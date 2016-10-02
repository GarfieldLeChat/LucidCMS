<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
      <h1>Slug Categories</h1>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
 
<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>
 <p class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> <a href="admin.php?action=newSlugCategory">Add a New Slug Category</a></p>
 <p>&nbsp;</p>
      <table>
        <tr>        
          <th>Slug Category Name</th>
          <th>Slug Category ID</th>
          <th>Slug Category Parent ID</th>
        </tr>
 
<?php foreach ( $results['slugCategories'] as $slugCategory ) { ?>
 
        <tr onclick="location='admin.php?action=editSlugCategory&amp;slugCategoryId=<?php echo $slugCategory->slugCategoryId?>'">
          <td>
            <?php echo $slugCategory->slugCategoryName?>
          </td>
          <td><?php echo $slugCategory->slugCategoryId?></td>
          <td><?php if ($slugCategory->slugCategoryId == $slugCategory->slugCategoryParentId) {
			  echo $slugCategory->slugCategoryId;
			   }
		  else
			  echo $slugCategory->slugCategoryParentId;
		  ?></td>
        </tr>
 
<?php } ?>
 
      </table>
 
      <p><?php echo $results['totalRows']?> categor<?php echo ( $results['totalRows'] != 1 ) ? 'ies' : 'y' ?> in total.</p>
 
 
<?php include "templates/include/footer.php" ?>