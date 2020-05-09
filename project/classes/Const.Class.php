<?php
class Constant {

        public const ReserveCapacityTechs = array("Coal","Oil","Gas");
        public const EtaTechs = array("Coal"=>36,"Oil"=>38,"Gas"=>45, "Biofuels"=>35, "Waste"=>35, "Nuclear"=>33);
        public const HourlyAnalysisTech = array("Hydro","Wind","Solar");
        public const MTS = array("Nuclear"=>1000,"Coal"=>400,"Gas"=>300,"Oil"=>200,"OilShale"=>200,"Peat"=>200,"Waste"=>100,"Biofuels"=>100,"Geothermal"=>100 );
        public const MTD = array("Nuclear"=>4, "Coal"=>4,"Gas"=>2,"Oil"=>2,"OilShale"=>2,"Peat"=>2, "Waste"=>2, "Biofuels"=>2,"Geothermal"=>2);  

        public const STG = array("INIT"=>25,"VOL"=>0, "CAP"=>0,"LOS"=>0,"Eff"=>100,"FCo"=>0);  

        //ener staticgija u energiju ok
        public const GWh_GWh = 1;
        public const PJ_GWh = 1000/3.6;
        public const ktoe_GWh = 11.63;
        public const Mtoe_GWh = 11630;

        public const GWh_TJ = 3.6;
        public const PJ_TJ = 1000;
        public const ktoe_TJ = 41.868;
        public const Mtoe_TJ = 41868;

        //snag statica u energiju ok
        public const MW_GWh =  365*24/1000;
        public const MW_Mtoe = 365*24/1000 *8.6/100000;
        public const MW_ktoe = 365*24/1000 *8.6/100;
        public const MW_PJ =   365*24/1000 *3.6/1000;

        public const PJ_MW =   1000/3.6 *1000/8760;
        public const GWh_MW =  1000/8760;
        public const ktoe_MW = 11.630 *1000/8760;
        public const Mtoe_MW = 11630 *1000/8760;


        
// const ENERGY_CONVERTER = {
//         PJ_ktoe: 23.8845897,
//         PJ_Mtoe:  0.0238845897,
//         PJ_PJ: 1,
//         PJ_GWh: 277.777778,
    
//         ktoe_ktoe: 1,
//         ktoe_Mtoe: 0.001,
//         ktoe_GWh: 11.63,
//         ktoe_PJ: 0.041868,
    
//         Mtoe_ktoe: 1000,
//         Mtoe_Mtoe: 1,
//         Mtoe_GWh: 11630,
//         Mtoe_PJ: 41.868,
    
//         GWh_ktoe: 0.0859845228,
//         GWh_Mtoe: 0.0000859845228,
//         GWh_GWh: 1,
//         GWh_PJ: 0.0036,
    
//     }

        //kg/TJ of Fuel
        public const IPCC2016_CO2EmissionFactors = array(
                'Hydro' => 0,
                'Coal' => 94600,
                'Oil' => 77400,
                'Gas' => 54300,
                'Biofuels' => 112000,
                'Heat' => 0,
                'Peat' => 106000,
                'Waste' => 91700,
                'OilShale' => 107000,
                'Solar' => 0,
                'Wind' => 0,
                'Geothermal' => 0,
                'Nuclear' => 0   
            );
        
        public const emissionType = array("CO2", "SO2", "NOX", "Other");

        public const financeType = array('Fuel_cost', 'Investment_cost', 'Operating_cost_fixed', 'Operating_cost_variable');

                //energija u sangu [MW] ??
        // public $PJ_MW =   365/24/3.6*100000000;
        // public $GWh_MW =  100/365/24*1000;
        // public $ktoe_MW = 365/24*11630*100;
        // public $Mtoe_MW = 365/24*11630*100*1000;
}
?>