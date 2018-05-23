var ajaxLoaderSrc = 'system/modules/wem-contao-locations/assets/backend/ajax-loader_16_blue.gif';
var objStatusClasses = {'success':'tl_confirm', 'error':'tl_error'};
$(document).ready(function(){
	$('.geocode').bind('click',function(e){
		e.preventDefault();
		var objIcon = $(this);
		var objRow = objIcon.closest('.tl_content').find('.ajax-results').first();
		var originalImg = objIcon.find('img').first().attr('src');
		objIcon.find('img').first().attr('src', ajaxLoaderSrc);
		$.get(objIcon.attr('href')+'&src=ajax', function(data){
			objRow.addClass(objStatusClasses[data.status]).html(data.response).slideDown(200);
		}).always(function(){
			setTimeout(function(){ objRow.slideUp(200, function() { objRow.attr('class', 'ajax-results').html(''); }); }, 5000);
			objIcon.find('img').first().attr('src', originalImg);
		});
	});

	$('.header_geocodeAll').bind('click',function(e){
		e.preventDefault();
		var objIcon = $(this);
		if(confirm(objIcon.data('confirm'))){
			$('.tl_content .geocode').each(function(i){
				var objButton = $(this);
				setTimeout(function(){ objButton.trigger('click'); }, i * 1000);
			});
		}
	});
});