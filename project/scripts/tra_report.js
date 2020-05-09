// function get_specific_investment (fuel,year)
// {
//     path = (sprintf('//tra_finance[@year="%s" and @technology="%s"]/Investment_cost', year, fuel));
    
//     nodes = xmlDoc.evaluate(path, xmlDoc, null,XPathResult.ANY_TYPE, null);
    
//     var result=nodes.iterateNext();
//     //console.log(result);
//     specific_investment = result.childNodes[0].nodeValue; 
    
//     return specific_investment;
// } 

// var power_output =
//         {
//         url: 'data/TRA_report_data.php?action=power_output&trans=0',
//         root: 'data',
//         datatype: 'json',
//         cache: false,
//         async: true,
//      //   datafields: [
// //            { name: 'year', type: 'string' },
// //    		{ name: 'Hydro', type: 'number' },
// //    		{ name: 'Coal', type: 'number' },
// //    		{ name: 'Oil', type: 'number' },
// //    		{ name: 'Gas', type: 'number' },
// //            { name: 'Biofuels', type: 'number' },
// //            { name: 'Peat', type: 'number' },
// //            { name: 'Waste', type: 'number' },
// //            { name: 'Oil_shale', type: 'number' },
// //            { name: 'Solar', type: 'number' },
// //            { name: 'Wind', type: 'number' },
// //            { name: 'Geothermal', type: 'number' },
// //            { name: 'Nuclear', type: 'number' },
// //            
// //    		]
//         };   
// var dataAdapter_power_output = new $.jqx.dataAdapter(power_output);        
        
//  var power_output_trans =
//         {
//             url: 'data/TRA_report_data.php?action=power_output&trans=1',
//             root: 'data',
//             datatype: 'json',
//             cache: false,
//             //datafields: [
// //                { name: 'name', type: 'string' },
// //        		{ name: '1990', type: 'number' },
// //        		{ name: '2000', type: 'number' },
// //                { name: '2005', type: 'number' },
// //        		{ name: '2010', type: 'number' },
// //                { name: '2015', type: 'number' },
// //        		{ name: '2020', type: 'number' },
// //                { name: '2025', type: 'number' },
// //                { name: '2030', type: 'number' },
// //                { name: '2035', type: 'number' },
// //                { name: '2040', type: 'number' },
// //                { name: '2045', type: 'number' },
// //                { name: '2050', type: 'number' },
// //        		]
// 	       };
           
// var dataAdapter_power_output_trans = new $.jqx.dataAdapter(power_output_trans);      
       



// var investment =
//             {
//             url: 'data/TRA_report_data.php?action=investment&trans=0',
//             root: 'data',
//             datatype: 'json',
//             cache: false,
//            // datafields: [
// //                { name: 'year', type: 'string' },
// //        		{ name: 'Hydro', type: 'number' },
// //        		{ name: 'Coal', type: 'number' },
// //        		{ name: 'Oil', type: 'number' },
// //        		{ name: 'Gas', type: 'number' },
// //                { name: 'Biofuels', type: 'number' },
// //                { name: 'Peat', type: 'number' },
// //                { name: 'Waste', type: 'number' },
// //                { name: 'Oil_shale', type: 'number' },
// //                { name: 'Solar', type: 'number' },
// //                { name: 'Wind', type: 'number' },
// //                { name: 'Geothermal', type: 'number' },
// //                { name: 'Nuclear', type: 'number' },
// //                
// //        		]
//         };  
// var dataAdapter_investment = new $.jqx.dataAdapter(investment);    
 
// var investment_trans =
//         {
//             url: 'data/TRA_report_data.php?action=investment&trans=1',
//             root: 'data',
//             datatype: 'json',
//             cache: false,
//             //datafields: [
// //                { name: 'name', type: 'string' },
// //        		{ name: '1990', type: 'number' },
// //        		{ name: '2000', type: 'number' },
// //                { name: '2005', type: 'number' },
// //        		{ name: '2010', type: 'number' },
// //                { name: '2015', type: 'number' },
// //        		{ name: '2020', type: 'number' },
// //                { name: '2025', type: 'number' },
// //                { name: '2030', type: 'number' },
// //                { name: '2035', type: 'number' },
// //                { name: '2040', type: 'number' },
// //                { name: '2045', type: 'number' },
// //                { name: '2050', type: 'number' },
// //             
// //        		]
// 	       }; 
// var dataAdapter_investment_trans = new $.jqx.dataAdapter(investment_trans);    






// var TIC_output =
//         {
//         url: 'data/TRA_report_data.php?action=TIC_output&trans=0',
//         root: 'data',
//         datatype: 'json',
//         cache: false,
//         async: true,
//         };   
// var dataAdapter_TIC_output = new $.jqx.dataAdapter(TIC_output);        
        
//  var TIC_output_trans =
//         {
//             url: 'data/TRA_report_data.php?action=TIC_output&trans=1',
//             root: 'data',
//             datatype: 'json',
//             cache: false,
// 	};
           
// var dataAdapter_TIC_output_trans = new $.jqx.dataAdapter(TIC_output_trans);  

// var ENS_output =
//         {
//         url: 'data/TRA_report_data.php?action=ENS&trans=0',
//         root: 'data',
//         datatype: 'json',
//         cache: false,
//         async: true,
//         };   
// var dataAdapter_ENS_output = new $.jqx.dataAdapter(ENS_output);        
        
//  var ENS_output_trans =
//         {
//             url: 'data/TRA_report_data.php?action=ENS&trans=1',
//             root: 'data',
//             datatype: 'json',
//             cache: false,
//             autoBind: true,
//             datafields: [
//                 { name: 'name', type: 'string' },
//        		    { name: '1990', type: 'number' },
//        		    { name: '2000', type: 'number' },
//                 { name: '2005', type: 'number' },
//        		    { name: '2010', type: 'number' },
//                 { name: '2015', type: 'number' },
//        		    { name: '2020', type: 'number' },
//                 { name: '2025', type: 'number' },
//                 { name: '2030', type: 'number' },
//                 { name: '2035', type: 'number' },
//                 { name: '2040', type: 'number' },
//                 { name: '2045', type: 'number' },
//                 { name: '2050', type: 'number' },
            
//        		]
// 	};
           
// var dataAdapter_ENS_output_trans = new $.jqx.dataAdapter(ENS_output_trans); 
          