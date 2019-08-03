/**
 * Разработка: bacs21of@mail.ru
 */
(function ($) {
  /**
   * Обработка da-data
   */
  function join(arr) {
    var separator = arguments.length > 1 ? arguments[1] : ", ";
    return arr.filter(function (n) {
      return n
    }).join(separator);
  }

  function typeDescription(type) {
    var TYPES = {
      'INDIVIDUAL': 'Индивидуальный предприниматель',
      'LEGAL': 'Организация'
    };

    return TYPES[type];
  }

  function showSuggestion(suggestion) {
    var data = suggestion.data;
    if (!data)
      return;

    $("#type").text(
      typeDescription(data.type) + " (" + data.type + ")"
    );

    if (data.name) {
      $("#name_short").val(data.name.short_with_opf || "");
      $("#name_full").val(data.name.full_with_opf || "");
    }

    /** ИНН */
    $("#inn").val(data.inn);

    /** КПП */
    $("#kpp").val(data.kpp);

    /** ОГРН */
    $("#ogrn").val(data.ogrn);

    /** Адрес */
    $("#address").val(data.address);

    if (data.address) {
      var address = "";
      if (data.address.data.qc == "0") {
        address = join([data.address.data.postal_code, data.address.value]);
      } else {
        address = data.address.data.source;
      }
      $("#address").val(address);
    }
  }

  $("#party").suggestions({
    token: "e15c2aa35300ea40760eb73d42befdb8d67cebe0",
    type: "PARTY",
    count: 5,
    onSelect: showSuggestion
  });

  $("#bank").suggestions({
    token: "e15c2aa35300ea40760eb73d42befdb8d67cebe0",
    type: "BANK",
    onSelect: function (suggestion) {
      var info = suggestion.data;

      $("#usersettingsform-kor_schet").val(info.correspondent_account);
      $("#usersettingsform-bank_bic").val(info.bic);
    }
  });
})(jQuery);
