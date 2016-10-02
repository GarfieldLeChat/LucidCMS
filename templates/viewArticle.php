<?php include "templates/include/header.php" ?>
 
      <h1 style="width: 75%;"><?php echo htmlspecialchars( $results['article']->articleTitle )?></h1>
      <div style="width: 75%; font-style: italic;"><?php echo $results['article']->articleSummary?></div>
      <div style="width: 75%; min-height: 300px;">
      <?php if ( $imagePath = $results['article']->getImagePath() ) { ?>
        <img id="articleImageFullsize" src="<?php echo $imagePath?>" alt="Article Image" />
      <?php } ?>
      <?php echo $results['article']->articleContent?>
      </div>
      <p class="pubDate">Published on <?php echo date('j F Y', $results['article']->articlePublicationDate)?>
<?php if ( $results['category'] ) { ?>
        in <a href="./?action=archive&amp;categoryId=<?php echo $results['category']->categoryId?>"><?php echo htmlspecialchars( $results['category']->categoryName ) ?></a>
<?php } ?>
      </p>
 
      <p><a href="./">Return to Homepage</a></p>
 
<?php include "templates/include/footer.php" ?>