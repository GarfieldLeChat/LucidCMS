<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
      <h1>All Forms</h1>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
 
<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>
 <p class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> <a href="admin.php?action=newForm">Add a New Form</a></p>
 <p>&nbsp;</p>
      <table>
        <tr>
          <th>Form</th>
          <th>Category</th>
        </tr>
 
<?php foreach ( $results['form'] as $form ) { ?>
 
        <tr onclick="location='admin.php?action=editForm&amp;formId=<?php echo $form->formId?>'">
          <td>
            <?php echo $form->formTitle?>
          </td>
          <td><?php if ( $results['formCategories'][$form->formCategoryId] ) {
			  echo $results['formCategories'][$form->formCategoryId]->formCategoryName;
		  } elseif ( $results['formCategories'][$form->formCategoryId == 0] ) {
			  echo 'Uncategorised';}?>
          </td>
        </tr>
 
<?php } ?>
 
      </table>
 
      <p><?php echo $results['totalRows']?> form<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>
 
      
 
<?php include "templates/include/footer.php" ?>