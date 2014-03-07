<form role="form" action="/wohoo" method="POST">
  <label>Stuff</label>
    <div class="multi-field-wrapper">
      <div class="multi-fields">
        <div class="multi-field">
            
            <div class="selector">
                <span>Full URL</span>
                <select class="u-select u-length-0 _uniformed" style="opacity: 1;">
                    <option value="13">13</option>
                </select>
              </div>

              <div class="selector">
                <span>Contains (case sensitive)</span>
                <select class="u-select u-length-0 _uniformed" style="opacity: 1;">
                    <option value="24">24</option>
                    <option value="25" selected="selected">25</option>
                </select>
              </div>  
              <div><input type="text" /></div>

        </div>
      </div>
    <!--<button type="button" class="add-field">Add field</button> -->
        <input type="button" class="add-field" value="+ add more rules" onclick="addInput('dynamicInput');" />
  </div>
</form>
          
          
<script>
$('.multi-field-wrapper').each(function() {
    var $wrapper = $('.multi-fields', this);
    $(".add-field", $(this)).click(function(e) {
        $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
    });
    $('.multi-field .remove-field', $wrapper).click(function() {
        if ($('.multi-field', $wrapper).length > 1)
            $(this).parent('.multi-field').remove();
    });
});

</script>
