
(function($) {


  $(function() {

    // Add no conflict to prevent conflict.
    var $j = jQuery.noConflict();

    /**
     * AJAX request to get children.
     * @param  array data All parameters for request to server.
     */
    function hotn_ajax_request(data) {
      // Set ajax callback to refresh the data and set throbber to load.
      $j('#hotn-child-list').html('<div class="throbber">....</div>');
      $j.ajax({
        type: 'GET',
        url: document.URL,
        data: data,
        success: function (data) {
          // Get the content.
          var $html = $j(data);
          $content = $html.find('#hotn-child-list').html();

          // Replace the content.
          $j('#hotn-child-list').html($content);

          // Load the pager functionality after ajax request.
          hotn_ajax_pager($hotn_filter_form);
        }
      });
    };

    var $hotn_filter_form = $j('#hotn-filter-form');

    // Find all select fields and bind on change.
    $hotn_filter_form.find('select').bind('change', function() {

      data = {};
      // Get all value and name of select in form and set the value to array.
      $j('select', $hotn_filter_form).each(function() {
          var $filter_select = $j(this);
          var hotn_filter_val = $filter_select.val();
          var hotn_filter_name = $filter_select.attr('name');
          data[hotn_filter_name] = hotn_filter_val;
      });

      hotn_ajax_request(data);

    });

    // Find reset button and bind on click.
    $hotn_filter_form.find('.hotn-filter-form-reset').click(function(){

      // Set all select values to null.
      $j('select', $hotn_filter_form).each(function(){
          var $filter_select = $(this);
          var hotn_filter_val = $filter_select.val('');
      });

      // Refresh the children form.
      hotn_ajax_request({})
    });

    // Call the function pager for functionality for paging.
    hotn_ajax_pager($hotn_filter_form);

    /**
     * Function for ajax pager by change on pager item change the page of children.
     */
    function hotn_ajax_pager($hotn_filter_form) {
      $j('#hotn-pager').find('.pager').bind('click', function() {
        var pager_id = $(this).data('pager');

        data = {};

        // Set the pager id to variable.
        data['hotnpager'] = pager_id;

        $j('select', $hotn_filter_form).each(function() {
            var $filter_select = $j(this);
            var hotn_filter_val = $filter_select.val();
            var hotn_filter_name = $filter_select.attr('name');

            // Set the value to array with as key field name.
            data[hotn_filter_name] = hotn_filter_val;
        });

        // Call ajax request with all data.
        hotn_ajax_request(data);
      });
    }

  });

})(jQuery);
