<?php include "templates/include/header.php" ?>
 
      <h1><?php echo htmlspecialchars( $results['pageHeading'] ) ?></h1>
<?php if ( $results['category'] ) { ?>
      <h3 class="categoryDescription"><?php echo $results['category']->categoryDescription?></h3>
<?php } ?>
 
      <ul id="headlines" class="archive">
 
<?php foreach ( $results['articles'] as $article ) { ?>
 
        <li>
          <h2>
            <span class="pubDate"><?php echo date('j F Y', $article->articlePublicationDate)?></span><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>"><?php echo htmlspecialchars( $article->articleTitle )?></a>
            <?php if ( !$results['category'] && $article->articleCategoryId ) { ?>
            <span class="category">in <a href=".?action=archive&amp;categoryId=<?php echo $article->articleCategoryId?>"><?php echo htmlspecialchars( $results['categories'][$article->articleCategoryId]->categoryName ) ?></a></span>
<?php } ?> 
          </h2>
          <p class="summary">
            <?php if ( $imagePath = $article->getImagePath( IMG_TYPE_THUMB ) ) { ?>
              <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>"><img class="articleImageThumb" src="<?php echo $imagePath?>" alt="Article Thumbnail" /></a>
            <?php } ?>
          <?php echo $article->articleSummary?>
          </p>
        </li>
 
<?php } ?>
 
      </ul>
 
      <p><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>
 
      <p><a href="./">Return to Homepage</a></p>
 
<?php include "templates/include/footer.php" ?>