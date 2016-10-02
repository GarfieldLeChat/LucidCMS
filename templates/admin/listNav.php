<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
      <h1>Site Navigation</h1>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
 
<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>
      <p class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> <a href="admin.php?action=newNavigation">Add a New Navigation</a></p>
      <p>&nbsp;</p>
      <table>
        <tr>        
          <th>Navigation Name</th>
          <th>Navigation ID</th>
          <th>Navigation Parent ID</th>
          <th>Link Type</th>
          <th>Link</th>
          <th>Article ID</th>
          <th>Cat ID</th>
          <th>Form ID</th>
          <th>Media ID</th>
          <th>Menu ID</th>
          <th>Slug ID</th>
        </tr>
 
<?php foreach ( $results['navigations'] as $navBar ) { ?>
 
        <tr onclick="location='admin.php?action=editNavigation&amp;navBarId=<?php echo $navBar->navBarId?>'">
          <td>
            <?php echo $navBar->navBarName?>
          </td>
          <td><?php echo $navBar->navBarId?></td>
          <td><?php if ($navBar->navBarId == $navBar->navBarParentId) {
			  echo $navBar->navBarId;
			   }
		  else
			  echo $navBar->navBarParentId;
		  ?></td>
          <td><?php echo $navBar->navBarLinkType?></td>
          <td><?php echo $navBar->navBarURL?></td>
          <td><?php echo $navBar->navBarArticleId?></td>
          <td><?php echo $navBar->navBarCatId?></td>
          <td><?php echo $navBar->navBarFormId?></td>
          <td><?php echo $navBar->navBarMediaId?></td>
          <td><?php echo $navBar->navBarMenuId?></td>
          <td><?php echo $navBar->navBarSlugId?></td>
        </tr>
 
<?php } ?>
 
      </table>
 
      <p><?php echo $results['totalRows']?> Menu<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>
 
<?php include "templates/include/footer.php" ?>