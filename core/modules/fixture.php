<script>
$(document).ready(function(){
     $.ajax({
        beforeSend: function()
        {
            $('.ranking_content').html('<img src="libs/images/loading.gif" class="center-block" width="40" height="40">');
        },
        url: 'core/modules/ajax/get_fixtureAjax.php',
        type: 'POST',
        async: true,
        success: function(resp)
        {
            $('.ranking_content').html(resp);
        }
    });
});
</script>
<div class="col-md-9 col-sm-7 push-sm col-xs-12">
    <div class="dashboard-item-box">
        <div class="container-fluid ranking_content">

        </div>
    </div>
</div>   
