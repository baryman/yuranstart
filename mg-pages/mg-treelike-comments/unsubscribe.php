<!-- если отписка прошла успешно, уведомляем об этом пользователя -->
<?php if($deleteResult) { ?>

	<h3>Вы успешно отписались!</h3>
	<p>Вы отписались от уведомлений о новых комментариях с этой <a href="<?php echo SITE.$url; ?>">страницы</a></p>

<?php } else { ?> 
<!-- если ошибка, то тоже говорим об этом -->
	<h3>Произошла ошибка!</h3>
	<p>Возможно вы уже отписались от уведомлений или ссылка являеться некоректной</p>
<?php } ?>