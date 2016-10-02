<nav>
  <ul>
  
  <?php foreach ( $results['navigation'] as $nav ) { ?>
	<?php if ( $nav->navBarParentId == $nav->navBarId ) { ?>
    <li>
      <a href=".?action=viewArticle&amp;articleId=<?php echo ( $results['navigation'][$nav->id])?>"><?php echo htmlspecialchars( $nav->title )?></a>
      <?php } elseif ( $nav->navBarParentId !== $nav->navBarId  ) {?>
      <li>
      <a href=".?action=viewArticle&amp;articleId=<?php echo ( $results['navigation'][$nav->id])?>"><?php echo htmlspecialchars( $results['parentItem'][$nav->parentItem]->name )?></a>
    <div>
      <ul>
        <li>
          <a href=".?action=viewArticle&amp;articleId=<?php echo ( $results['navigation'][$nav->id])?>"><?php echo htmlspecialchars( $nav->title )?></a>
        </li>
      </ul>
    </div>
	<?php } ?>
      <div class="navigation-text">
        <?php if ( $imagePath = $nav->getImagePath( IMG_TYPE_THUMB ) ) { ?>
          <a href=".?action=viewArticle&amp;articleId=<?php echo ( $results['navigation'][$nav->id])?>"><img class="navImageThumb" src="<?php echo $imagePath?>" alt="Nav Thumbnail" /></a>
        <?php } ?>
      <?php echo $nav->nav-text?>
      </div>
    </li>
  
  <?php } ?>
  
  </ul>
</nav>