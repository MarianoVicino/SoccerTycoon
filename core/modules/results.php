<div class="col-md-9 col-sm-7 push-sm col-xs-12">
    <div class="dashboard-item-box">
        <div class="row container-fluid ranking_content">
            <?php
				
				$round = (isset($_POST['round'])) ? $_POST['round'] : 0;
			
                $builder->GetResults($_SESSION['user_fmo'], $round);
            ?>
        </div>
    </div>
</div>   

