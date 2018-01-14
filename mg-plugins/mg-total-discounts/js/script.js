$('#buttom-ok').click(function(){
	if (confirm("Внимание! Все действующие цены товаров будут изменены безвозвратно.")) {
		return true;
	} else {
		return false;
	}
})
//buttomOk.click = confirmApply;
//.click(confirmApply(buttomOk));
