$(document).ready(function() {
	var selectClick = $('.jsx-select');
	var selectSelected = $('.jsx-select__selected'); 
	var selectList = $('.jsx-select__list');
	var selectItem = $('.jsx-select__list li');

	var documents = $(document);

	selectClick.click(function(event) {
		var windheight =  $(window).height() - 170;
		var offsetFromScreenTop = $(this).offset().top - $(window).scrollTop();
		
		$(this).toggleClass('active');
		$(this).find('.jsx-select__list').toggle();


		var documnt = $(this).closest(document);
		if (windheight > offsetFromScreenTop) {
			selectList.addClass('top');
			selectList.removeClass('bottom');
		}
		else {
			selectList.addClass('bottom');
			selectList.removeClass('top');
		}

		var firstClick = true;
		if ($(this).hasClass('active')) {
			// Скрипт который будет закрывать sidebar при клике на любое место

			documnt.bind('click.myEvent', function(e) {
				// console.log($(e.target).closest('.jsx-select__list').length);
				if (!firstClick && $(e.target).closest('.jsx-select__list').length == 0) {
					selectClick.removeClass('active');
					selectList.hide(0);
					documnt.unbind('click.myEvent');
				}
				
				if ($(e.target).closest('.jsx-select__list').length == 1) {
					documnt.unbind('click.myEvent');
				}
				firstClick = false;
			});
		}
		
		

	});
	

	selectItem.click(function(event) {
		var $this = $(this).closest('.jsx-select');
		selectItem.removeClass('selected');
		$(this).addClass('selected');
		var	textHtml = $(this).html();
		var	text = $(this).text();

		$this.prev().val(text);
		if (selectItem.hasClass('selected')) 
			$this.find('.jsx-select__selected').html(textHtml).addClass('active');
	});

});