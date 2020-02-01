<?php global $HOME; 
$ref = $HOME.'referrals/'.$_SESSION['user_fmo'];
?>
<script>
$(document).ready(function(){
   GetReferrals();
});
</script>

<div class="col-md-9 col-sm-7 push-sm col-xs-12">
    <div class="dashboard-item-box">
        <div class="alert alert-info alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <span class="glyphicon glyphicon-info-sign"></span> Each entry that your referral does in Dollars, you will get the 10%. 
                          </div>
		
       
    </div>
</div>       

<div class="col-md-9 col-sm-7 push-sm col-xs-12">
    <div class="dashboard-item-box">
        <h3 class="module-title text-center">My Affialite Link</h3>
		<div class="col-xs-12 referral_box">
			<small style="color:royalblue" class="ranking_score"><?= $ref; ?></small>
		</div>
        <h3 class="module-title text-center">My Referrals</h3>
        <div class="row container-fluid referrals_content">
            
        </div>
    </div>
</div>       