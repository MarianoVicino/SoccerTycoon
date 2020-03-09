        <!-- LEAGUE TABLE -->
       
        <?php
        //reviso que no exista un usuario con ese id ni mail
        $sql = mysqli_query($db, "SELECT * FROM Equipos WHERE usuario='".$_SESSION['user_fmo']."'");
        $re = mysqli_fetch_array($sql);

        if($re['asignado'] == 0){ ?>
        <div class="col-md-9 col-sm-7 push-sm col-xs-12">
          
        <div class="dashboard-item-box text-center">
    <h2 class="text-center nom-nop" style="color:orange">Titulo</h2>
    <p>Texto</style></p>
           </div>  <br/>
        </div>
        <?php }else{ ?>

        

          <div class="col-md-9 col-sm-7 push-sm col-xs-12">
          
        <div class="dashboard-item-box text-center">
    <h2 class="text-center nom-nop" style="color:orange">Promotion!</h2>
    <p>By buying the 20dls package for the first time, you will receive the St & a gift of <span style="color:#70ff4d">1 player with 3000sc</style>.</p>
    <p>By buying the 50dls package for the first time, you will receive the St & a gift of  <span style="color:#70ff4d">1 player with 10000sc</style>.</p>
    <p>By buying the 100dls package for the first time, you will receive the St & a gift of  <span style="color:#70ff4d">2 player with 10000sc & a bonus of 100k St</style>.</p>

           </div>  <br/>
        </div>
      

        
           <div class="col-md-9 col-sm-7 push-sm col-xs-12">
            <div class="dashboard-item-box text-center">
            
         
                <hgroup>
                    <h2 class="text-center nom-nop">League Table</h2><br>


                </hgroup>
                <table class="table table-responsive table-positions">
                    <tr class="first">
                        <td>#</td>
                        <td>Team</td>
                        <td>Played</td>
                        <td class="hidden-sm hidden-xs">Won</td>
                        <td class="hidden-sm hidden-xs">Tied</td>
                        <td class="hidden-sm hidden-xs">Lost</td>
                        <td class="hidden-sm hidden-xs">GF</td>
                        <td class="hidden-sm hidden-xs">GA</td>
                        <td class="hidden-sm hidden-xs">GD</td>
                        <td>Points</td>
                    </tr>
                    <?php
                        $builder->GetLeagueTable($_SESSION['user_fmo']);
                    ?>
                </table>
            </div>
        </div>
<?php } ?>