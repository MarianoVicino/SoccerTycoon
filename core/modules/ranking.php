<?php
    require_once("core/models/class.Builder.php");
    $builder=new Builder();
 
?>
<script>
$(document).ready(function(){
	
	GetRankingIndex(); 
      
       
    });
</script>
<div class="col-md-9 col-sm-7 push-sm col-xs-12">
    <div class="dashboard-item-box">
        <h3 class="module-title text-center">TOP 10 GLOBAL USERS</h3>
  
                    <div class="row container-fluid ranking_content">
        </div>
    </div>
</div>       