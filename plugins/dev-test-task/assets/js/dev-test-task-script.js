jQuery(document).ready(function($){
	// estate-objects-form submit
	$('#estate-objects-form').submit(function(e){
		e.preventDefault();
		let loader = $('#estate-objects-container .content .loader');
		let content = $('#estate-objects-container .content');
		content.children().length > 1 && content.children()[1].remove();
		loader.removeClass('d-none');
		$.post($(this).attr('action'), {'action': 'estate_objects_form_action', 'data': $(this).serializeArray()}, function(){
		})
		.done(function(response){
			loader.addClass('d-none');
			content.append(response);
		})
		.fail(function(){
			content.append('<h5 class="text-white">Не удалось отправить запрос на сервер</h5>');
			loader.addClass('d-none');
		});
	});
});
