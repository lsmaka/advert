ls.hook.add('ls_template_init_end',function() {
	var BlockFloatId = '#block_advert_sidebar_float';
	if($(BlockFloatId).length)
	{
		var top = $(BlockFloatId).offset().top;
		var width = $(BlockFloatId).width();
		
		$(window).scroll(function(){
			var y = $(this).scrollTop();
			if(y>=top)
			{
				$(BlockFloatId).addClass("advertfixed");
				$(BlockFloatId).css({'width' : width});
			}
			else
			{
				$(BlockFloatId).removeClass("advertfixed");
				$(BlockFloatId).css({'width' : 'none'});
			}
		});	
	}

});
