<?php
require_once '../config.php';
require_once '../classes/EsstCase.php';

if (isset($_SESSION['case'])){
   $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
   if(file_exists($filepath)){

        $esstCase = new EsstCase($_SESSION['case']);
        $sectors = $esstCase->getSectors();
        $elmix_fuels = $esstCase->getElMixFuels();
        $fuels = $esstCase->getFuels();
        $unit = $esstCase->getUnit();
        $currency = $esstCase->getCurrency();
        $years = $esstCase->getYears();
   }else{
    $sectors = '';
    $elmix_fuels = '';
    $fuels = '';
    $unit = '';
    $currency = '';
    $years = '';
}
}
else{
    $sectors = '';
    $elmix_fuels = '';
    $fuels = '';
    $unit = '';
    $currency = '';
    $years = '';
}
?>

<script type="text/javascript">
         var selector = '#Navi > li';
         var subselector = '#Navi > li> .submenu li';
         var subsubselector = '#Navi > li>.submenu li> .submenu li';
         var subsubsubselector = '#Navi > li>.submenu li> .submenu li > .submenu li';

  
        $(document).delegate(".hLight","click",function(e){
            e.stopPropagation(); 

            $('#Navi').find('.active').removeClass('active');
            $('#Navi').find('.open').removeClass('open');
            $(this).addClass('active');
            $(this).parentsUntil("#Navi", '.hLight').addClass('active open');

            $(this).parentsUntil("#Navi", '.active').siblings().find('ul').slideUp("slow");
            
        });

        $('.navbar-brand').click(function(e) {
            e.stopPropagation();
            $(selector).removeClass('active');
            $(selector).removeClass('open');
        });

</script> 
<div class="sidebar-shortcuts" id="sidebar-shortcuts">

<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
    <button id="en" class="btn btn-lang btn-sm active" onclick="changeLang('en'); return false;">
        En
    </button>
    <button id="es" class="btn btn-lang btn-sm" onclick="changeLang('es'); return false;">
        Es
    </button>
    <button id="fr" class="btn btn-lang btn-sm" onclick="changeLang('fr'); return false;">
        Fr
    </button>
    <button id="rs" class="btn btn-lang btn-sm" onclick="changeLang('rs'); return false;">
        Rs
    </button>
</div>

<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
    <span class="btn btn-nest"></span>
    <span class="btn btn-nest"></span>
    <span class="btn btn-nest"></span>
    <span class="btn btn-nest"></span>

</div>

</div>
    <ul class="nav nav-list" id="Navi">

        <li class="hLight ">
            <a href="#" class="dropdown-toggle ">
                <i class="menu-icon fa fa-pencil-square-o"></i>
                <span class="menu-text" lang="en">
                    Case studies
                </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>

            <b class="arrow"></b>
            <ul class="submenu">
                <li class="hLight">
                    <a href="#/AddCase?action=new" lang="en">
                        <i class="menu-icon fa fa-caret-right "></i>
                        <i class="menu-icon fa fa-plus success"></i>
                            Create case
                    </a>
                    <b class="arrow"></b>
                </li>
                <li class="hLight">
                    <a href="#/ManageCases" lang="en">
                        <i class="menu-icon fa fa-caret-right red"></i>
                        <i class="menu-icon fa fa-pencil-square-o"></i>
                            Manage cases
                    </a>
                    <b class="arrow"></b>
                </li>
            </ul>      
        </li>

        <!--Final energy consumption-->
        <li class="hLight">
			<a href="#" class="dropdown-toggle">
				<!--<i class="menu-icon fa fa-desktop"></i>-->
                <i class="menu-icon fa-alph">F<small>e</small>C</i>
					<span class="menu-text" lang="en">
						Final energy consumption
					</span>
                <b class="arrow fa fa-angle-down"></b>
			</a>

            <b class="arrow"></b>
            <ul class="submenu">
                <!-- <li class=""> -->
                        <li class="hLight">
                            <a href="#/FED_bysectors" lang="en">
							<i class="menu-icon fa fa-caret-right red"></i>
                            <i class="fa fa-keyboard-o red" aria-hidden="true"></i>
								By energy consumers <span><?php echo  "[".$unit."]"; ?></span>
							</a>
							<b class="arrow"></b>
                        </li>


                        <li class="hLight">
                            <a href="#" class="dropdown-toggle" lang="en">
                                <i class="menu-icon fa fa-caret-right"></i>
                                    Energy products shares
                                <b class="arrow fa fa-angle-down"></b>
                            </a>
                            <b class="arrow"></b>
                            <ul class="submenu">
                                <li class="hLight">
                                    <a href="#" class="dropdown-toggle" >
                                        <i class="menu-icon fa fa-caret-right red"></i>
                                        <span class="menu-text" lang="en">
                                             Energy products shares <span>[%]</span>
                                        </span>
                                        <b class="arrow fa fa-angle-down"></b>
                                    </a>
									<b class="arrow"></b>		
                                    <ul class="submenu">
                                            <?php
                                            if (!isset($_SESSION['case']) && !isset($_GET['casename'])){
                                                echo '<li class="hLight">';
                                                echo	'<a  href="#/ManageCases" lang="en">';
                                                echo		'<i class="menu-icon fa fa-exclamation-triangle red"></i>';
                                                echo		'Please select a case!';
                                                echo	'</a>'; 
                                                echo	'<b class="arrow"></b>';
                                                echo '</li>';
                                            }
                                            else{
                                                foreach($sectors as $key=>$value){
                                                    if ($value =="1"){
                                                        echo '<li class="hLight">';
                                                        echo	"<a  href='#/FED_fuelshares.php?sector={$key}' lang='en'>";
                                                        echo		'<i class="fa fa-keyboard-o red" aria-hidden="true"></i>';
                                                        echo		  $key;
                                                        echo	'</a>'; 
                                                        echo	'<b class="arrow"></b>';
                                                        echo '</li>';
                                                    }
                                                }  
                                            }  
                                                ?>
                                    </ul>
                                </li>
                                <li class="hLight"> 
                                    <a href="#" class="dropdown-toggle" >
                                        <i class="menu-icon fa fa-caret-right red"></i>
                                        <span class="menu-text" lang="en">
                                             Energy products shares <span><?php echo ' ['.$unit.']'; ?></span>
                                        </span>

                                        <b class="arrow fa fa-angle-down"></b>
                                    </a>
                                    <b class="arrow"></b>
                                    <ul class="submenu">
                                        <?php
                                            if (!isset($_SESSION['case']) && !isset($_GET['casename'])){
                                                        echo '<li class="hLight">';
                                                        echo	"<a  href='#/ManageCases' lang='en'>";
                                                        echo		'<i class="menu-icon fa fa-exclamation-triangle red"></i>';
                                                        echo		  'Please select a case!';
                                                        echo	'</a>'; 
                                                        echo	'<b class="arrow"></b>';
                                                        echo '</li>';
                                            }
                                                //   echo "<li><a href='?page=cases/ManageCases.php'>$please_select_case_to_view</a></li>";
                                            else
                                            {
                                                foreach($sectors as $key=>$value){
                                                    if ($value =="1")
                                                    {
                                                        //echo "<li><a href='?page=FED_fuelshares_values.php&amp;sector={$key}'>" .$fuel_shares . "-" .$$key."</a></li>";
                                                        echo '<li class="hLight">';
                                                        echo	"<a  href='#/FED_fuelshares_values.php?sector={$key}' lang='en'>";
                                                        echo		'<i class="fa fa-bar-chart green" aria-hidden="true"></i>';
                                                        echo		  $key;
                                                        echo	'</a>'; 
                                                        echo	'<b class="arrow"></b>';
                                                        echo '</li>';
                                                    }
                                                }  
                                            }  
                                        ?>
                                    </ul>
                                </li>
                                <li class="hLight">
                                    <a href="#/FED_fuelshares_total" lang="en">
                                    <i class="menu-icon fa fa-bar-chart green"></i>
                                        Energy products shares total <span><?php echo  "[".$unit."]"; ?></span>
                                    </a>
                                    <b class="arrow"></b>
                                </li>
                        <!--</ul>-->
                    <!-- </li> -->
                     
                    </ul>
                </li>                       
            </ul>
        </li>


        <!--Secondary energy uses-->
		<li class="hLight">
			<a href="#" class="dropdown-toggle" lang="en">
				<i class="menu-icon fa-alph ">S<small>e</small>U</i>
				<span class="menu-text" lang="en">
					Secondary energy uses
				</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
            <b class="arrow"></b>

            <ul class="submenu">
                <!-- <li class=""> -->
                        <li class="hLight">
							<a href="#/FED_losses" lang="en">
								<i class="menu-icon fa fa-caret-right red"></i>
                                <i class="fa fa-keyboard-o red" aria-hidden="true"></i>
								    Losses <span><?php echo  ' [%]'; ?></span>
							</a>
							<b class="arrow"></b>
						</li>
                        <li class="hLight">
							<a href="#/SecondaryEnergySupplies" lang="en">
								<i class="menu-icon fa fa-caret-right red"></i>
                                <i class="fa fa-bar-chart green" aria-hidden="true"></i>
								    Secondary energy uses
							</a>
							<b class="arrow"></b>
						</li>

                        <li class="hLight">
							<a href="#/SES_elmix" lang="en">
								<i class="menu-icon fa fa-caret-right red"></i>
                                <i class="fa fa-keyboard-o red" aria-hidden="true"></i>
								    Electricity supply structure<span> <?php echo  ' [%]'; ?></span>
							</a>
							<b class="arrow"></b>
						</li>
                        <li class="hLight">
							<a href="#/SES_elmix_values" lang="en">
								<i class="menu-icon fa fa-caret-right red"></i>
                                <i class="fa fa-bar-chart green" aria-hidden="true"></i>
								    Electricity supply structure <span><?php echo ' ['. $unit .']'; ?></span>
							</a>
							<b class="arrow"></b>
						</li>
                    <!--</ul>-->
                <!-- </li> -->
            </ul>
        </li>

        <!--Primary energy supply-->
		<li class="hLight">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa-alph " >P<small>e</small>S</i>
				<span class="menu-text" lang="en">
					Primary energy supply
				</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="hLight">
					<a href="#/PES_domestic_production" lang="en">
						<i class="menu-icon fa fa-caret-right red"></i>
                        <i class="fa fa-keyboard-o red" aria-hidden="true"></i>
						    Primary energy production
					</a>
					<b class="arrow"></b>
                </li>
                <li class="hLight">
					<a href="#/PES_tpes" lang="en">
						<i class="menu-icon fa fa-caret-right red"></i>
                        <i class="fa fa-bar-chart green" aria-hidden="true"></i>
						    Total energy supply
					</a>
					<b class="arrow"></b>
                </li>
                <!-- </li> -->
            </ul>      
        </li>

        <!--Electricity supply planning-->
        <li class="hLight">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-line-chart"></i>
                <span class="menu-text" lang='en'>
                    Electricity supply
				</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="hLight">
					<a href="#/TechData" lang='en'>
						<i class="menu-icon fa fa-caret-right"></i>
                        <i class="fa fa-keyboard-o red" aria-hidden="true"></i>
                           Technical data input
					</a>
					<b class="arrow"></b>
                </li>
                <li class="hLight">
					<a href="#/TRA_report" lang='en'>
						<i class="menu-icon fa fa-caret-right"></i>
                        <i class="fa fa-bar-chart-o green" aria-hidden="true"></i>
						    Analysis results
					</a>
					<b class="arrow"></b>
                </li>  
            </ul>                        
        </li>

        <!--Environmental impact-->
        <li class="hLight">
            <a href="#" class="dropdown-toggle">
                <i class=" menu-icon fa fa-industry" aria-hidden="true"></i>
               
                <span class="menu-text" lang='en'>
					 Emissions
				</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="hLight">
					<a href="#/TRA_el_gen_emissions" lang='en'>
						<i class="menu-icon fa fa-caret-right"></i>
                        <i class="fa fa-bar-chart-o green" aria-hidden="true"></i>
						    Emissions from electricity generation
					</a>
					<b class="arrow"></b>
                </li>
                <li class="hLight">
					<a href="#/TRA_environment_co2" lang='en'>
						<i class="menu-icon fa fa-caret-right"></i>
                        <i class="fa fa-bar-chart-o green" aria-hidden="true"></i>
						    <span>CO<sub>2</sub></span> Emissions
					</a>
					<b class="arrow"></b>
                </li>  
            </ul>                        
        </li>

        <!--EB Sankey!-->
        <li class="hLight">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-balance-scale"></i>
                <span class="menu-text" lang='en'>
                    Energy Balance
				</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>

            <ul class="submenu">
                    <?php
                        if (!isset($_SESSION['case']) && !isset($_GET['casename'])){
                             //echo "<li><a href='?page=cases/ManageCases.php'>$please_select_case_to_view</a></li>";
                            echo '<li class="hLight">';
                            echo	"<a  href='#/ManageCases' lang='en'>";
                            echo		'<i class="menu-icon fa fa-exclamation-triangle red"></i>';
                            echo		  'Please select a case!';
                            echo	'</a>'; 
                            echo	'<b class="arrow"></b>';
                            echo '</li>';
                        }
                        else{
                            foreach($years as $key=>$value){
                                if ($value =="1"){
                                    $tmp = substr($key, 1);
                                    //echo "<li><a href='?page=EnergyBalance.php&amp;year={$tmp}'>$tmp</a></li>";
                                    echo '<li class="hLight">';
                                    echo	"<a href='#/EnergyBalance.php?year={$tmp}' lang='en'>";
                                    echo		'<i class="fa fa-table green" aria-hidden="true"></i>';
                                    echo		  $tmp;
                                    echo	'</a>'; 
                                    echo	'<b class="arrow"></b>';
                                    echo '</li>';
                                }
                            }  
                        }  
                        ?>
            </ul>                        
        </li>


        <!--EB Sankey!-->
        <!-- <li class="hLight">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-balance-scale "></i>
                <span class="menu-text" lang='en'>
					 Energy Balance
				</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="hLight">
                    <a href="#" class="dropdown-toggle" lang='en'>
                        <i class="menu-icon fa fa-caret-right"></i>
                            Aggregated balance
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
					<ul class="submenu">
                    <?php
                        if (!isset($_SESSION['case']) && !isset($_GET['casename'])){
                             //echo "<li><a href='?page=cases/ManageCases.php'>$please_select_case_to_view</a></li>";
                            echo '<li class="hLight">';
                            echo	"<a  href='#/ManageCases' lang='en'>";
                            echo		'<i class="menu-icon fa fa-exclamation-triangle red"></i>';
                            echo		  'Please select a case!';
                            echo	'</a>'; 
                            echo	'<b class="arrow"></b>';
                            echo '</li>';
                        }
                        else{
                            foreach($years as $key=>$value){
                                if ($value =="1"){
                                    $tmp = substr($key, 1);
                                    //echo "<li><a href='?page=EnergyBalance.php&amp;year={$tmp}'>$tmp</a></li>";
                                    echo '<li class="hLight">';
                                    echo	"<a href='#/EnergyBalance.php?year={$tmp}' lang='en'>";
                                    echo		'<i class="fa fa-table green" aria-hidden="true"></i>';
                                    echo		  $tmp;
                                    echo	'</a>'; 
                                    echo	'<b class="arrow"></b>';
                                    echo '</li>';
                                }
                            }  
                        }  
                        ?>
                    </ul>
                </li>
                <li class="hLight">
                    <a href="#" class="dropdown-toggle" lang='en'>
                        <i class="menu-icon fa fa-caret-right"></i>
                            Sankey diagram
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
					<ul class="submenu">
                        <?php
                        if (!isset($_SESSION['case']) && !isset($_GET['casename'])){
                             //echo "<li><a href='?page=cases/ManageCases.php'>$please_select_case_to_view</a></li>";
                                    echo '<li class="hLight">';
                                    echo	"<a href='#/ManageCases' lang='en'>";
                                    echo		'<i class="menu-icon fa fa-exclamation-triangle red"></i>';
                                    echo		  'Please select a case!';
                                    echo	'</a>'; 
                                    echo	'<b class="arrow"></b>';
                                    echo '</li>';
                        }
                        else{
                            foreach($years as $key=>$value){
                                if ($value =="1"){
                                    $tmp = substr($key, 1);
                                    //echo "<li><a href='?page=Sankey.php&amp;year={$tmp}'>$tmp</a></li>";
                                    echo '<li class="hLight">';
                                    echo	"<a href='#/Sankey.php?year={$tmp}' lang='en'>";
                                    echo		'<i class="fa fa-bar-chart-o green" aria-hidden="true"></i>';
                                    echo		  $tmp;
                                    echo	'</a>'; 
                                    echo	'<b class="arrow"></b>';
                                    echo '</li>';
                                }
                            }  
                        }  
                        ?>
                    </ul>
                </li>
            </ul> 
        </li>    -->
                   
        <li class="hLight">
            <a href="#/HourlyAnalysis">
                <i class="menu-icon fa fa-bar-chart "></i>
                <span class="menu-text" lang='en'>
                    Hourly analysis
                </span>
               
            </a>
            <b class="arrow"></b>
        </li>
        <!-- <li class="hLight" id="rptByYears" style="display:none">
            <a href="#/reportByYears">
                <i class=" menu-icon fa fa-file-text"></i>
                <span class="menu-text" lang='en'>
                    Report
                </span>
               
            </a>
            <b class="arrow"></b>
        </li> -->
        <li class="hLight" id="Stats">
            <a href="#/Stats">
                <i class=" menu-icon fa fa-database"></i>
                <span class="menu-text" lang='en'>
                    Reports
                </span>
               
            </a>
            <b class="arrow"></b>
        </li>
        <li class="hLight">
            <a href="#/Docs">
                <i class=" menu-icon fa fa-file-text-o"></i>
                <span class="menu-text" lang='en'>
                    Documentation
                </span>
               
            </a>
            <b class="arrow"></b>
        </li>
        <!-- <li>
            <a href="#/ResultsRaw">
                <i class="menu-icon fa fa-exclamation-triangle red"></i>
                <span class="menu-text">Results Raw</span>
            </a>
            <b class="arrow"></b>
        </li> -->
    </ul>
    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
		<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-right ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
	</div>






