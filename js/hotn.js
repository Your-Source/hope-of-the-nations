$( document ).ready(function() {
  $('#hotn-filter-form').find('select').bind('change', function() {
    $('#hotn-child-list').html('<div class="throbber">throbber</div>');
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

    // Set ajax callback to refresh the data.
    $.ajax({
      type: 'GET',
      url: 'index.php',
      data: data,
      success: function (data) {
        // Get the content.
        var $html = $(data);
        $content = $html.find('#hotn-child-list');

        // Replace the content.
        $('#hotn-child-list').html($content);
      }
    });
})
});
