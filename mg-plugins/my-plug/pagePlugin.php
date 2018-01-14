<TABLE>
	<TR>
		<TH>
			<?=$lang['NAME_OF_TD'];?>
		</TH>
		<TH>
			<?=$lang['PRICE'];?>
		</TH>
		<TH>
			<?=$lang['COUNT_VIEWS'];?>
		</TH>
		<TH>
			<?=$lang['COUNT_VIEWS_TODAY'];?>
		</TH>

	</TR>


	
	
	
	<?php foreach ($myPlug as $data):?>
		<TR>
			<TD>
				<?=$data['title']?>
			</TD>
			<TD>
				<?=$data['price']?>
			</TD>
			<TD>
				<?echo $data['count_views'];?>
			</TD>
			
			<TD>
				+ <? if($data['count_views_today']>0){?> <span style="color:green;"><b><?=$data['count_views_today'];?></b></span><?}else{ echo $data['count_views_today'];}?>
			</TD>
		</TR>
	<?php endforeach;?>
	
	<?=$pagination;?>
	
	</TABLE>