<script>
$(document).ready(function(){
   GetHistoric(0); 
   $('.historic_content').on('click','.show_more',function(e){
       e.preventDefault();
       $(this).hide();
       GetHistoric($(this).val());
   });
});
</script>
<div class="col-md-9 col-sm-7 push-sm col-xs-12">
    <div class="dashboard-item-box">
        <h3 class="module-title text-center">MY TRANSACTIONS</h3>
        <div class="row container-fluid historic_content">
            
        </div>
    </div>
</div>       