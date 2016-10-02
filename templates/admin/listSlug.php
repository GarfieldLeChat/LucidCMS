<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
      <h1>All Slug Items</h1>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
 
<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>
 <p class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> <a href="admin.php?action=newSlug">Add a New Slug Item</a></p>
 <p>&nbsp;</p>
      <table>
        <tr>
          <th>Slug</th>
          <th>Category</th>
        </tr>
 
<?php foreach ( $results['slug'] as $slug ) { ?>
 
        <tr onclick="location='admin.php?action=editSlug&amp;slugId=<?php echo $slug->slugId?>'">
          <td>
            <?php echo $slug->slugTitle?>
          </td>
          <td><?php if ( $results['slugCategories'][$slug->slugCategoryId] ) {
			  echo $results['slugCategories'][$slug->slugCategoryId]->slugCategoryName;
		  } elseif ( $results['slugCategories'][$slug->slugCategoryId == 0] ) {
			  echo 'Uncategorised';}?>
          </td>
        </tr>
 
<?php } ?>
 
      </table>
 
      <p><?php echo $results['totalRows']?> Slug Item<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>
 
      
 
<?php include "templates/include/footer.php" ?>