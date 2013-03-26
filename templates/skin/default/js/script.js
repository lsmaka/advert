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
//
jQuery(document).ready(function($){
ls.autocomplete.add($(".autocomplete-advert-users"), aRouter['advert']+'ajax/users/', true);
ls.autocomplete.add($(".autocomplete-advert-blogs"), aRouter['advert']+'ajax/blogs/', true);
ls.autocomplete.add($(".autocomplete-advert-topics"), aRouter['advert']+'ajax/topics/', true);

ls.autocomplete.add($(".autocomplete-advert-filter-advertid"), aRouter['advert']+'ajax/filter_advertid/', true);
ls.autocomplete.add($(".autocomplete-advert-filter-advertuser"), aRouter['advert']+'ajax/filter_advertuser/', true);

ls.autocomplete.add($(".autocomplete-advert-fields-style"), aRouter['advert']+'ajax/fields_style/', true);
});
// -------------------------------------------------------------------------------------------------------------------

// Подсчет кол-ва переходов
function advert_click(advertId)
{
	var param = {'advertId' : advertId};
	ls.ajax(aRouter['advert']+'ajax/click/',param);
}
// Подсчет выбор стилей в соответствии с типом блока
function advert_changeStyle(advertBlockType, advertUserOwner)
{
	var param = {'advertBlockType' : advertBlockType, 'advertUserOwner' : advertUserOwner};
	ls.ajax(aRouter['advert']+'ajax/blockTypeChange/',param ,function(result)
	{
		if (!result.bStateError)
		{
			var objBlockCssWrapper = document.getElementById('advert_block_css_wrapper');
			var objBlockCss = document.getElementById('advert_block_css');
			objBlockCssWrapper.removeChild(objBlockCss);

			var objBlockCss = document.createElement('div');
			objBlockCss.setAttribute("id", "advert_block_css");		
			
			var elSelect = document.createElement('select');
			elSelect.name = "advert_block_css";
			
			for (key in result.aItems)
			{
				//alert('key = ' +key+ ' , val = ' +result.aItems[key]);
				var opt = document.createElement("option");
				opt.value= key;
				opt.innerHTML = result.aItems[key];
				elSelect.appendChild(opt);				
			}
			
			objBlockCss.appendChild(elSelect);
			objBlockCssWrapper.appendChild(objBlockCss);
		}
		else
		{
			$('#advert_block_css').html('no data');
		}	

	});		
}

//Подтверждение выбора
function advert_conform_choise(todo, id)
{
	var srtInfo;
	switch (todo)
	{
		case 'edit':
		srtInfo="Отредактировать задание #"+id+" ? После редактирование задание автоматически будет остановлено и его нужно будет запустить повторно.";
		break;
		
		case 'start':
		srtInfo="Запустить задание #"+id+" в работу ?";
		break;
		
		case 'stop':
		srtInfo="Остановить задание #"+id+" ?";
		break;
		
		case 'del':
		srtInfo="Удаленное задание нельзя восстановить! Удалить задание #"+id+" ?";
		break;
	}
	
	if (confirm(srtInfo)) 
	{
		return true;
	} 
	else 
	{
		return false;
	}	
}
//
function advert_checkAddData(formName)
{
	var dtostop_year = $("#advert_date_tostop_year option:selected").val();
	var dtostop_month = $("#advert_date_tostop_month option:selected").val();
	var dtostop_day = $("#advert_date_tostop_day option:selected").val();
	var dtostop_hour = $("#advert_date_tostop_hour option:selected").val();
	var dtostop_min = $("#advert_date_tostop_min option:selected").val();
	var dtostop_sec = $("#advert_date_tostop_sec option:selected").val();
	
	var dtostop = new Date(dtostop_year, dtostop_month-1, dtostop_day, dtostop_hour, dtostop_min, dtostop_sec);
	var curdate = new Date();
	var errFlag = false;
	//
	$("#advert_date_tostart_hint").hide();
	$("#advert_date_tostop_hint").hide();	
	//
	if(curdate >= dtostop)
	{
		$("#advert_date_tostop_hint").show('slow');	
		errFlag = true;
	}
	
	if ($('#advert_date_tostart_flag').is(':checked'))
	{
		var dtostart_year = $("#advert_date_tostart_year option:selected").val();
		var dtostart_month = $("#advert_date_tostart_month option:selected").val();
		var dtostart_day = $("#advert_date_tostart_day option:selected").val();
		var dtostart_hour = $("#advert_date_tostart_hour option:selected").val();
		var dtostart_min = $("#advert_date_tostart_min option:selected").val();
		var dtostart_sec = $("#advert_date_tostart_sec option:selected").val();	
		
		var dtostart = new Date(dtostart_year, dtostart_month-1, dtostart_day, dtostart_hour, dtostart_min, dtostart_sec);

		if(curdate >= dtostart)
		{
			$("#advert_date_tostart_hint").show('slow');	
			errFlag = true;
		}
	}
	
	if(errFlag)
	{
		$('#advert_help_page_wrapper').fadeIn('slow');
		return false;
	}
	else
	{
		return true;
	}
}
//
function advert_uploadfile(input) 
{
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			var img = new Image;
			
			img.onload = function() {

				$("#advert_userfile_size_hint").text('Размер '+img.width+' x '+img.height+' Нажмите для показа preview !');
				$("#advert_userfile_size_hint").css({'background': '#336699', 'padding':'5px', 'color': 'white','cursor':'pointer'});

				$("#advert_userfile_preview").css("background-image", "url(" + e.target.result + ")");
				$("#advert_userfile_preview").css("background-repeat", 'no-repeat');
				
				//
				$("#advert_userfile_size_error").text('');
				$("#advert_userfile_size_error").css({'background': 'none'});
				$("#advert_userfile_preview_hint").text('');
				$("#advert_userfile_preview_hint").css({'background': 'none'});
				
				var widthNormal = 300;
				if(img.width > widthNormal)
				{
					$("#advert_userfile_preview").css("width", widthNormal);
					var widthDiffPersent = ( ( img.width - widthNormal ) * 100 ) / img.width;
					var heightNew = (img.height - (widthDiffPersent * img.height ) / 100 );
					$("#advert_userfile_preview").css("height", heightNew);
					
					$("#advert_userfile_preview").css("background-size", "100%");
					$("#advert_userfile_size_error").text('Ширина '+img.width+' !!!');
					$("#advert_userfile_size_error").css({'background': 'red', 'padding':'5px', 'color': 'white'});
					$("#advert_userfile_preview_hint").text('Есть большая вероятость что при встевке в sidebar изображение будет выходить за границы блока. Оптимальный размер изображения для sidebar по ширине 250-300 px Будте внимательны !');
					$("#advert_userfile_preview_hint").css({'background': 'red'});
				}
				else
				{
					$("#advert_userfile_preview").css("width", img.width);
					$("#advert_userfile_preview").css("height", img.height);
					//$("#advert_userfile_preview_hint").text('С изображением вроде все нормально :)');
				}		
				
				//$('#blah')
				//.attr('src', e.target.result)
				//.width(img.width)
				//.height(img.height);
			};
			img.src = reader.result;
		};

		reader.readAsDataURL(input.files[0]);
	}
}
//

