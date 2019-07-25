(function ($) {
  if (window.matchMedia('(min-width: 991px)').matches) {
    var btn = $('.js_burger');

    btn.click(function (event) {
      $(this).toggleClass('active');
      var bodyStyle = $(this).closest('body').toggleClass('js_active-sidebar');
      if ($(this).hasClass('active')) {

        $.cookie('sidebar', '1', {expires: 365, path: '/'});
        console.log($.cookie());
      }
      if (!$(this).hasClass('active')) {
        $.cookie('sidebar', '0', {expires: 365, path: '/'});

      }

    });
  }

// Edit Email
  var editMail = $('.js_mail-edit');
  var inputMail = $('.js_email-input-edit');

  editMail.click(function (event) {
    $(this).closest('.settings__mail').fadeOut();
    $(this).closest('.settings__mail').next().removeClass('settings__input');
  });
  inputMail.blur(function (event) {
    $(this).prev().fadeIn().find('.settings__mail-text').text($(this).val());
    $(this).addClass('settings__input');
  });

// Mobile Menu
  if (window.matchMedia('(max-width: 991px)').matches) {
    var btn = $('.js_burger');

    btn.on('click', function (event) {
      event.preventDefault();

      $documenThis = $(this).closest(document);
      $(this).closest('body').addClass('js_active-sidebar');
      $(this).addClass('active');

      if ($(this).hasClass('active')) {
        closeMenu($documenThis, $(this));
      }
    });

    function closeMenu($document, $this) {
      var firstClick = true;
      $document.bind('click.myEvent', function (e) {
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
})(jQuery);