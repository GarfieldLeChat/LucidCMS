<?php include "templates/include/header.php" ?>
 
      <ul id="headlines">
 
<?php foreach ( $results['articles'] as $article ) { ?>
 
        <li>
          <h2>
            <span class="pubDate"><?php echo date('j F', $article->articlePublicationDate)?></span><a href=".?action=viewArticle&amp;articleId=<?php echo $article->articleId?>"><?php echo htmlspecialchars( $article->articleTitle )?></a>
            <?php if ( $article->articleCategoryId ) { ?>
            <span class="category">in <a href=".?action=archive&amp;categoryId=<?php echo $article->articleCategoryId?>">
			<?php echo htmlspecialchars( $results['categories'][$article->articleCategoryId]->categoryName )?></a></span>
            <?php } ?>
          </h2>
          <div class="summary">
            <?php if ( $imagePath = $article->getImagePath( IMG_TYPE_THUMB ) ) { ?>
              <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>"><img class="articleImageThumb" src="<?php echo $imagePath?>" alt="Article Thumbnail" /></a>
            <?php } ?>
          <?php echo $article->articleSummary?>
          </div>
        </li>
 
<?php } ?>
 
      </ul>
 
      <p><a href="./?action=archive">Article Archive</a></p>
 
<?php include "templates/include/footer.php" ?>