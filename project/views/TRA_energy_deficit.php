<?php
if (isset($_SESSION['case']))
{
?>


<script>
session = getSession();
var casename = session.case;  
var user = session.us;  
getXML(casename, user);
</script>
<script type="text/javascript" src="scripts/blockUI.js"></script>
<script type="text/javascript" src="scripts/generateColors.js"></script>
<script type="text/javascript" src="scripts/generateElMixFuelsSeriesWOImportExport.js"></script>
<script type="text/javascript" src="scripts/generateYearsSeries.js"></script>
<script type="text/javascript" src="scripts/generateYearsColumns.js"></script>


<script type="text/javascript">
$(document).ready(function () {
    session = getSession();
    var casename = session.case;  
    var user = session.us;  
    if (typeof(casename) != 'undefined') {

        var unit = getUnit();
        var currency = getCurrency();
    
    
        //definisi prvi data adapter za grid i chart po sektorima
        var theme='fresh';
        $("#jqxExpander1").jqxExpander({ width: '90%', theme: theme });
        var sector = getUrlVars()["sector"];
        $("#chartEnergy").jqxButton({width: '150px', theme: theme});
        $("#excelEnergy").jqxButton({width: '150px', theme: theme});
           //dataadapter za energy output by years
           var energy_output =
            {
            url: 'data/TRA_energy_deficit_data.php?action=energy_output&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
            datafields: [
                { name: 'year', type: 'string' },
        		{ name: 'Hydro', type: 'number' },
        		{ name: 'Coal', type: 'number' },
        		{ name: 'Oil', type: 'number' },
        		{ name: 'Gas', type: 'number' },
                { name: 'Biofuels', type: 'number' },
                { name: 'Peat', type: 'number' },
                { name: 'Waste', type: 'number' },
                { name: 'Oil_shale', type: 'number' },
                { name: 'Solar', type: 'number' },
                { name: 'Wind', type: 'number' },
                { name: 'Geothermal', type: 'number' },
                { name: 'Nuclear', type: 'number' },
                
        		]
        };   
        var dataAdapter_energy_output = new $.jqx.dataAdapter(energy_output);                     
    
        //drugi data adapter za transponirane vrijednosti, po godinama
        var energy_output_trans =
        {
            url: 'data/TRA_energy_deficit_data.php?action=energy_output&trans=1',
            root: 'data',
            datatype: 'json',
            cache: false,
            datafields: [
                { name: 'name', type: 'string' },
        		{ name: '1990', type: 'number' },
        		{ name: '2000', type: 'number' },
                { name: '2005', type: 'number' },
        		{ name: '2010', type: 'number' },
                { name: '2015', type: 'number' },
        		{ name: '2020', type: 'number' },
                { name: '2025', type: 'number' },
                { name: '2030', type: 'number' },
                { name: '2035', type: 'number' },
                { name: '2040', type: 'number' },
                { name: '2045', type: 'number' },
                { name: '2050', type: 'number' },
        		]
	       };  
            var dataAdapter_energy_output_trans = new $.jqx.dataAdapter(energy_output_trans); 
                          
  
      
    
      // prepare jqxChart settings
            var energy_output_chart = {
                title: energy_deficit + ' ' + by_years,
                description: '',
                //description: energy_deficit_calc_from_inst_cf_fed,
                enableAnimations: true,
                showLegend: true,
                theme: theme,
                padding: { left: 5, top: 5, right: 25, bottom: 5 },
                titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
                source: dataAdapter_energy_output,
                categoryAxis:
                    {
                        text: 'Category Axis ',
                        type: 'basic',
                        textRotationAngle: 0,
                        dataField: 'year',
                        showTickMarks: true,
                        tickMarksInterval: 1,
                        tickMarksColor: '#888888',
                        unitInterval: 1,
                        showGridLines: false,
                        gridLinesInterval: 1,
                        gridLinesColor: '#888888',
                        axisSize: 'auto'
                    },
                colorScheme: 'scheme01',
                seriesGroups:
                    [
                        {                                                                    
                            type: 'stackedsplinearea',
                            columnsGapPercent: 50,
                            seriesGapPercent: 5,
                            valueAxis:
                            {
                               
                                minValue: 0,
                                maxValue: 'auto',
                                displayValueAxis: true,
                                description: unit,
                                axisSize: 'auto',
                                tickMarksColor: '#888888'
                            },
                            series: series_elmix_fuels
                            //series: [
//                                    { dataField: 'Hydro', displayText: hydro, color: clHydro},
//                            		{ dataField: 'Coal', displayText: coal, color: clCoal},
//                            		{ dataField: 'Oil', displayText: oil, color: clOil},
//                            		{ dataField: 'Gas', displayText: gas, color: clGas},
//                                    { dataField: 'Biofuels', displayText: biofuels, color: clBiofuels},
//                                    { dataField: 'Peat', displayText: peat, color: clPeat},
//                                    { dataField: 'Waste', displayText: waste, color: clWaste},
//                                    { dataField: 'Oil_shale', displayText: oil_shale, color: clOil_shale},
//                                    { dataField: 'Solar', displayText: solar, color: clSolar},
//                                    { dataField: 'Wind', displayText: wind, color: clWind},
//                                    { dataField: 'Geothermal', displayText: geothermal, color: clGeothermal},
//                                    { dataField: 'Nuclear', displayText: nuclear, color: clNuclear},
//                                ]
                        }
                    ]
            }; //kraj settinga za chart
            
            // setup the chart
            $('#jqxChart_energy_output').jqxChart(energy_output_chart);
            
//         var dataAdapter_energy_output_trans = new $.jqx.dataAdapter(energy_output_trans, {
//            loadComplete: function() 
//            { //kada se zavrsi load svih odataka pozvati funkciju koja ce provjeriti da li ima praznih celija i sakriti kolone samo za opcione godine u ovom slucaju
//                for (var key in years)
//                {
//                    var test = $('#jqxgrid_energy_output_trans').jqxGrid('getcellvalue', 0, key);
//                    //console.log(test);
//                    if (test === null)
//                    {
//                        $("#jqxgrid_energy_output_trans").jqxGrid('hidecolumn',key);
//                    }
//                }                   
//            }
//            });            
    //setting i inicijalizacija za transponirani grid po godinamma
        $("#jqxgrid_energy_output_trans").jqxGrid(
            {
                autoheight: true,
                autorowheight: true,
                width: '90%',
                theme: theme,
                source: dataAdapter_energy_output_trans,
                editable: false,
                selectionmode: 'singlecell',
                columns: columns_years
                //columns: [
//                  { text: unit, datafield: 'name', width: 80, pinned: true },
//                  { text: '1990', datafield: '1990', width: 80,cellsalign: 'right',cellsformat: 'd2'},
//                  { text: '2000', datafield: '2000', width: 80,cellsalign: 'right',cellsformat: 'd2'},
//                  { text: '2005', datafield: '2005', width: 80,cellsalign: 'right',cellsformat: 'd2'},
//                  { text: '2010', datafield: '2010', width: 80,cellsalign: 'right',cellsformat: 'd2'},
//                  { text: '2015', datafield: '2015', width: 80,cellsalign: 'right',cellsformat: 'd2'},
//                  { text: '2020', datafield: '2020', width: 80, cellsalign: 'right',cellsformat: 'd2'},
//                  { text: '2025', datafield: '2025', width: 80,cellsalign: 'right',cellsformat: 'd2'},
//                 { text: '2030', datafield: '2030', width: 80, cellsalign: 'right',cellsformat: 'd2'},
//                 { text: '2035', datafield: '2035', width: 80,cellsalign: 'right',cellsformat: 'd2'},
//                 { text: '2040', datafield: '2040', width: 80,cellsalign: 'right',cellsformat: 'd2'},
//                 { text: '2045', datafield: '2045', width: 80,cellsalign: 'right',cellsformat: 'd2'},
//                { text: '2050', datafield: '2050', width: 80, cellsalign: 'right',cellsformat: 'd2'}
//                ]
                  
                }); //kraj inicijalizacije i settinga za trans grid
                
              $("#chartEnergy").click(function () {
                    // call the export server to create a PNG image
                    $('#jqxChart_energy_output').jqxChart('saveAsPNG', 'ElectricityDeficit.png');
                });
            $("#excelEnergy").click(function() {
                $("#jqxgrid_energy_output_trans").jqxGrid('exportdata', 'xls', 'Electricity deficit in the system');
            });
            }
            
                
  });
    </script>




<div id = "page_title">
    <p><?php echo $case_name.': '; ?>
    <?php if (isset($_SESSION['case']))
            {
                echo "<span style='color:#A80000 ;'>" .$_SESSION['case']."</span><br>";
            }   
         else
            {
                echo $create_new_or_edit_exisiting_case;
            }
        ?>                            
        <span style="color:#1e5799; font-size:14px; font-style: italic;"><?php echo $transformation.'->'.$additional_capacity_investment; ?></span></p>
    </div>

<?php
 
    if (isset($_GET["casename"]) or isset($_SESSION['case']))
    {  

    ?>
 <div id='jqxExpander1'>
        <div><?php echo $energy_deficit; ?></div>
        <div><div id='jqxChart_energy_output' style="width:100%; height:250px;"></div>
            
        </div>
</div>
<div id="jqxgrid_energy_output_trans"></div>
<table>
<tr>
   <td class="left_table_cell"><?php echo $save_as_png; ?>
   </td>
    <td >
    <input style='float: left;' type="button" id="chartEnergy" value="<?php echo $save_chart; ?>" >
   </td>
</tr>
<tr>
   <td class="left_table_cell"><?php echo $export_grid; ?>
   </td>
    <td >
     <input style='float: left;' type="button"  id="excelEnergy" value="Excel" >
   </td>
</tr>
</table>



<?php
    }
        else{
            echo "<div id='odgovor'>$create_new_or_edit_exisiting_case</div>";
        }
?>	
 <?php
    }
        else{
            echo "<div id='odgovor'>$create_new_or_edit_exisiting_case</div>";
        }
?>
