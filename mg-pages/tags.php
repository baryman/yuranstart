<?php MG::enableTemplate(); ?>
  <div class="products-wrapper">   
    <?php
     $actionButton = MG::getSetting('actionInCatalog')==="true"?'actionBuy':'actionView';
     $currency = MG::getSetting('currency');
     $items = TagsCloud::getProductsByTag($_GET['tag']);    
     
     foreach ($items['catalogItems'] as  &$item) {      
      if ($item['activity']==1) {
      ?>
      <div class="product-wrapper">
        <div class="product-image">
      <?php
      echo $item['recommend'] ? '<span class="sticker-recommend"></span>' : '';
      echo $item['new'] ? '<span class="sticker-new"></span>' : '';
      ?> 
          <a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?><?php echo $item["product_url"] ?>">
            <img src="<?php echo $item["image_url"]?>" alt="">
          </a>
        </div>
        <div class="product-name">
          <a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?><?php echo $item["product_url"] ?>"><?php echo $item["title"] ?></a>
        </div>
       
      
     	<span class="product-price"><?php echo $item["price"] ?> <?php echo $currency; ?></span>
			<!--Кнопка, кототорая меняет свое значение с "В корзину" на "Подробнее"-->
		  <?php 
		  if (!$item['liteFormData']){
        if($item['count']==0){
          echo $item['actionView'];          
        }else{
          echo $item[$actionButton]; 
        }
		  } else{
			  echo $item['liteFormData'];
		  }
		  ?>

     </div>
       <?php
       }
     }
    ?>
    <div class="clear"></div> 
    <?php echo $items['pager'];?>
    <div class="clear"></div> 
</div>
