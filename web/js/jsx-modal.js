	// PopUp

$(document).ready(function() {
  var jsxModalClick = $('[data-jsx-modal-target]');
  var jsxModal    = $('.jsx-modal');
  var jsxModalClose = $('.jsx-modal__close');

  var jsxBody = $('body');

  // Open popup 
  jsxModalClick.click(function(event) {
    event.preventDefault();
    $(this).addClass('jsx-active');
    var dataJsxModal = $(this).data('jsx-modal-target');
    $("[data-jsx-modal-id='" + dataJsxModal + "']").fadeIn();
    jsxBody.addClass('jsx-hidden'); 
  });

  // close popup
  jsxBody.click(function(e) {
    var jsxTargetBlock = $(e.target);
    var clickModal;
    
    if(jsxTargetBlock.has('[data-jsx-modal-target]').length || jsxTargetBlock.closest('[data-jsx-modal-target]').length) {
      clickModal = true;
    }
    if(jsxModalClick.hasClass('jsx-active')) {
      if(jsxTargetBlock.closest('.jsx-modal__block').length == 0 && !clickModal) {
        console.log ('close tel__me');
        jsxModal.fadeOut();
        jsxBody.removeClass('jsx-hidden');
      }
    }
  });

  jsxModalClose.click(function(event) {
    var jsxModal = $(this).closest('.jsx-modal');
    jsxModal.fadeOut();
    jsxBody.removeClass('jsx-hidden');
  });

}); 