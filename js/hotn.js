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
        $content = $html.find('#hotn-child-list').html();

        // Replace the content.
        $('#hotn-child-list').html($content);

        // Load the pager functionality after ajax request.
        ajax_pager();
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
    $('select', $form).each(function() {
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

  // Load the function pager by load.
  ajax_pager();
  // Function for pager.
  function ajax_pager() {
    $('#hotn-pager').find('.pager').bind('click', function() {
      var pager_id = $(this).data('pager');

      data = {};

      // Set the pager id to variable.
      data['hotnpager'] = pager_id;

      // Get all value from select in the form.
      $('#hotn-filter-form').each(function() {
        var $form = $(this);

        $('select', $form).each(function() {
            var $select = $(this);
            var val = $select.val();
            var name = $select.attr('name');

            // Set the value to array with as key field name.
            data[name] = val;
        });

      });

      // Call ajax request with all data.
      hotn_ajax_request(data);
    });
  }

});
