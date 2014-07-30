$( document ).ready(function() {
  $('#hotn-filter-form').find('select').bind('change', function() {
    $('#hotn-child-list').html('<div class="throbber">throbber</div>');
    var $select = $(this);
    var val = $select.val();
    var name = $select.attr('name');

    data = {};
    data[name] = val;

    $.ajax({
      type: 'GET',
      url: 'index.php',
      data: data,
      success: function (data) {
        var $html = $(data);
        $content = $html.find('#hotn-child-list');
        console.log($html);
        $('#hotn-child-list').html($content);
      }
    });
})
});
