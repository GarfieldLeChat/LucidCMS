<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
      <h1>Form Categories</h1>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
 
<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>
 <p class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> <a href="admin.php?action=newFormCategory">Add a New Form Category</a></p>
 <p>&nbsp;</p>
      <table>
        <tr>        
          <th>Form Category Name</th>
          <th>Form Category ID</th>
          <th>Form Category Parent ID</th>
        </tr>
 
<?php foreach ( $results['formCategories'] as $formCategory ) { ?>
 
        <tr onclick="location='admin.php?action=editFormCategory&amp;formCategoryId=<?php echo $formCategory->formCategoryId?>'">
          <td>
            <?php echo $formCategory->formCategoryName?>
          </td>
          <td><?php echo $formCategory->formCategoryId?></td>
          <td><?php if ($formCategory->formCategoryId == $formCategory->formCategoryParentId) {
			  echo $formCategory->formCategoryId;
			   }
		  else
			  echo $formCategory->formCategoryParentId;
		  ?></td>
        </tr>
 
<?php } ?>
 
      </table>
 
      <p><?php echo $results['totalRows']?> categor<?php echo ( $results['totalRows'] != 1 ) ? 'ies' : 'y' ?> in total.</p>
 
 
<?php include "templates/include/footer.php" ?>