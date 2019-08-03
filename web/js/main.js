(function($) {

	if(window.matchMedia('(min-width: 991px)').matches) {
		var btn = $('.js_burger');

		btn.click(function(event) {
			$(this).toggleClass('active');
			var bodyStyle = $(this).closest('body').toggleClass('js_active-sidebar');
			if ($(this).hasClass('active')) {
				$.cookie('sidebar', '1', { expires: 365, path: '/' });
			}
			if (!$(this).hasClass('active')) {
				$.cookie('sidebar', '0', { expires: 365, path: '/' });
			}
		});
	}

	var editMail = $('.js_mail-edit');
	var inputMail = $('.js_email-input-edit');

	editMail.click(function(event) {
		$(this).closest('.settings__mail').fadeOut();
		$(this).closest('.settings__mail').next().removeClass('settings__input');
	});
	inputMail.blur(function(event) {
		$(this).prev().fadeIn().find('.settings__mail-text').text($(this).val());
		$(this).addClass('settings__input');
	});

	if(window.matchMedia('(max-width: 991px)').matches) {
	var btn = $('.js_burger');
		btn.on('click', function(event) {
			event.preventDefault();

			$documenThis = $(this).closest(document);
			$(this).closest('body').addClass('js_active-sidebar');
			$(this).addClass('active');

			if($(this).hasClass('active')) {
				closeMenu($documenThis, $(this));
			}
		});

		function closeMenu($document, $this) {
		    var firstClick = true;
		    $document.bind('click.myEvent', function(e) {
		      console.log($(e.target).closest('.sidebar__wrapp').length);

		      if (!firstClick && $(e.target).closest('.sidebar__wrapp').length == 0) {
		        $document.find('body').removeClass('js_active-sidebar');
		        $this.removeClass('active');
		        $document.unbind('click.myEvent');
		      }

		      firstClick = false;
		    });
		}
	}

  	$(".clip-input").change(function() {
        var filename = $(this).val().replace(/.*\\/, "");
        $(".clip-input-txt").text(filename);
    });

    var $parentTab = $('.js_tab-parent');
    var $parentTabClick = $('.js_tab-parent .js__tab-item');
    var $parentTabContent = $('.js_tab-parent .js__content-item');
    var flag = true;
    $parentTabClick.on('click', function(event) {
    	event.preventDefault();

    	$parentTabClick.removeClass('active').find('.js__show-hide').text('Что входит в тариф?');
    	$(this).addClass('active').find('.js__show-hide').text('Скрыть');

    	if ($parentTabContent.eq($(this).index()).is(':visible')) {
    		$(this).removeClass('active').find('.js__show-hide').text('Что входит в тариф?');
    		$parentTabContent.eq($(this).index()).slideUp();
    		flag = true;
    	} else {
    		if (flag) {
    			flag = false;
    			$parentTabContent.eq($(this).index()).slideDown();
    		} else {
    			$parentTabContent.hide(0);
    			$parentTabContent.eq($(this).index()).fadeIn(0);
    		}
    	}
    });

	$(".accordeon_inner .accordeon_inner_open").hide().prev().click(function(e) {
		e.preventDefault();
		$(this).parents(".accordeon_inner").find(".accordeon_inner_open").not(this).slideUp().prev();
		$(this).next().not(":visible").slideDown().prev();
	});
})(jQuery);
