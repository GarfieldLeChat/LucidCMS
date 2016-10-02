<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
      <h1>All Articles</h1>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
 
<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>
 <p class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> <a href="admin.php?action=newArticle">Add a New Article</a></p>
 <p>&nbsp;</p>
      <table>
        <tr>
          <th>Publication Date</th>
          <th>Article</th>
          <th>Category</th>
        </tr>
 
<?php foreach ( $results['articles'] as $article ) { ?>
 
        <tr onclick="location='admin.php?action=editArticle&amp;articleId=<?php echo $article->articleId?>'">
          <td><?php echo date('j M Y', $article->articlePublicationDate)?></td>
          <td>
            <?php echo $article->articleTitle?>
          </td>
          <td><?php if ( $results['categories'][$article->articleCategoryId] ) {
			  echo $results['categories'][$article->articleCategoryId]->categoryName;
		  } elseif ( $results['categories'][$article->articleCategoryId == 0] ) {
			  echo 'Uncategorised';}?>
          </td>
        </tr>
 
<?php } ?>
 
      </table>
 
      <p><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>
 
      
 
<?php include "templates/include/footer.php" ?>