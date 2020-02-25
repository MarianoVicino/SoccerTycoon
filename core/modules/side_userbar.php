<!-- TEAM INFO -->
        <div class="col-md-3 col-sm-5 col-xs-12">
            <div class="dashboard-item-box text-center">
                <?php 
                    $builder->GetTeamInfo($_SESSION['user_fmo']); 
                ?>
            </div>
            <div class="dashboard-item-box push text-center">
                <hgroup>
                    <h3 class="nom-nop">My Gold</h3>
                </hgroup>
                <?php 
                    $builder->GetGold2($_SESSION['user_fmo']); 
                ?>
                <hgroup>
                    <h3 class="nom-nop">My Coins</h3>
                </hgroup>
                <?php 
                    $builder->GetGold($_SESSION['user_fmo']); 
                ?>
            </div>
            <div class="dashboard-item-box push text-center">
                <?php 
                    $builder->GetTeamStadium($_SESSION['user_fmo']);
                ?>
            </div>
            <div class="dashboard-item-box push next-match text-center">
                <hgroup>
                    <?php
                        $builder->GetNextMatch($_SESSION['user_fmo']);
                    ?>
                </hgroup>
				<br>
            </div>
            <div class="dashboard-item-box push next-match text-center">
                <hgroup>
                    <?php
                        $builder->GetPrevMatch($_SESSION['user_fmo']);
                    ?>
                </hgroup>
                <br>
            </div>
        </div>

