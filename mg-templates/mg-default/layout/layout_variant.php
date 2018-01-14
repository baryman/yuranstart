<?php if(!empty($data['blockVariants'])){?>
<div class="clear"></div><div class="block-variants">
<span>Варианты товара:</span>
<table class="variants-table">
<script>
  $( document ).ready(function() {
    $('.variant-tr').click(function(){
      $('.variant-tr').removeClass( "active-var" );
      $(this).addClass( "active-var" );
    });
  });  
</script>

  <?php foreach ($data['blockVariants'] as $variant) :?>

      <tr class="variant-tr <?php echo !$j++ ? 'active-var' : ''?>">
        <td>  
            <label for="variant-<?php echo $variant['id']; ?>">
              <input type="radio" id="variant-<?php echo $variant['id']; ?>" data-count="<?php echo $variant['count']; ?>" name="variant" value = "<?php echo $variant['id']; ?>" <?php echo !$i++ ? 'checked=checked' : ''?>>
            </label>
        </td>
          <td>
              <label for="variant-<?php echo $variant['id']; ?>"  <?php echo !$j++ ? 'class="active"' : ''?>><?php echo $variant['title_variant'] ?></label>
          </td>
          <td style="padding-left:5px;" class="nowrap price">
           <label for="variant-<?php echo $variant['id']; ?>">
              <span>
                  <?php echo $variant['price'] ?> <?php echo MG::getSetting('currency')?>                  
              </span>
           </label>
          </td>     
            <td>
                 <?php if ($variant['activity'] === "0" || $variant['count'] == 0){ 
                  echo '<span class="nonav">Нет в наличии</span>';
                 }else{ 
                  echo '<span class="avail">Есть в наличии</span>';}?>
            </td>  
         </tr>
     
      

   <?php endforeach; ?>
    </table>
</div>
<?php }?>