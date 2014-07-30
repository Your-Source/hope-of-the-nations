$(document).ready(function() {

  function hotn_ajax_request(data) {
    // Set ajax callback to refresh the data and set trobber to load.
    $('#hotn-child-list').html('<div class="throbber">....</div>');
    $.ajax({
      type: 'GET',
      url: document.URL,
      data: data,
      success: function (data) {
        // Get the content.
        var $html = $(data);
        $content = $html.find('#hotn-child-list');

        // Replace the content.
        $('#hotn-child-list').html($content);
      }
    });
  };

  $('#hotn-filter-form').find('select').bind('change', function() {
    var $select = $(this);
    var $form = $select.parents('form');
    var val = $select.val();
    var name = $select.attr('name');

    data = {};
    // Get all value and name of select in form and set the value to array.
    $('select', $form).each(function(){
        var $select = $(this);
        var val = $select.val();
        var name = $select.attr('name');
        data[name] = val;
    });

    hotn_ajax_request(data);

  });

  $('#hotn-filter-form').find('.hotn-filter-form-reset').bind('click', function() {
    var $form = $(this).parents('form');

    // Set all select value to null.
    $('select', $form).each(function(){
        var $select = $(this);
        var val = $select.val('');
    });

    // Refresh the childeren form.
    hotn_ajax_request({})
  });


});
