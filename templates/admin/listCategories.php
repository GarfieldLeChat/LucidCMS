<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
      <h1>Article Categories</h1>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
 
<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>
 <p class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> <a href="admin.php?action=newCategory">Add a New Category</a></p>
 <p>&nbsp;</p>
      <table>
        <tr>        
          <th>Category Name</th>
          <th>Category ID</th>
          <th>Category Parent ID</th>
        </tr>
 
<?php foreach ( $results['categories'] as $category ) { ?>
 
        <tr onclick="location='admin.php?action=editCategory&amp;categoryId=<?php echo $category->categoryId?>'">
          <td>
            <?php echo $category->categoryName?>
          </td>
          <td><?php echo $category->categoryId?></td>
          <td><?php if ($category->categoryId == $category->categoryParentId) {
			  echo $category->categoryId;
			   }
		  else
			  echo $category->categoryParentId;
		  ?></td>
        </tr>
 
<?php } ?>
 
      </table>
 
      <p><?php echo $results['totalRows']?> categor<?php echo ( $results['totalRows'] != 1 ) ? 'ies' : 'y' ?> in total.</p>
 
 
<?php include "templates/include/footer.php" ?>