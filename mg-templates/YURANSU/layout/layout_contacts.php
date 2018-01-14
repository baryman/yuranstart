<div class="mg-contacts-block desktop" itemscope itemtype="http://schema.org/Organization">
  <div class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
      <span>Адрес:</span>
      <div class="bold" itemprop="streetAddress"><?php echo MG::getSetting('shopAddress') ?></div>
  </div>
  <div class="phone">
      <span>Телефон:</span>
    <div class="bold" itemprop="telephone"><?php echo MG::getSetting('shopPhone') ?></div>
      <?php if (class_exists('BackRing')): ?>
        [back-ring]
      <?php endif; ?>
  </div>
  <div class="clock-work">
    <span>Мы работаем:</span>
    <div class="dayWork" >Пн - Сб</div>
      <div class="clockWork">7:00 - 13:00</div>  
    
  </div>
</div>
