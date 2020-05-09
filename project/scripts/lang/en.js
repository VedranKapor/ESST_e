var fuel_obj = new Object();
 fuel_obj['electricity'] = 'Electricity';
 fuel_obj['coal'] = 'Coal';
 fuel_obj['hydro'] ='Hydro';
 fuel_obj['oil'] = 'Oil';
 fuel_obj['gas'] = 'Gas';
 fuel_obj['biofuels'] = 'Biofuels';
 fuel_obj['heat'] = 'Heat';
 fuel_obj['peat'] = 'Peat';
 fuel_obj['waste'] = 'Waste';
 fuel_obj['oil_shale'] = 'Oil shale';
 fuel_obj['solar'] = 'Solar';
 fuel_obj['wind'] = 'Wind';
 fuel_obj['geothermal'] = 'Geothermal';
 fuel_obj['nuclear'] = 'Nuclear';
 fuel_obj['importexport'] = 'Import/Export';


var electricity = 'Electricity';
var coal = 'Coal';
var hydro ='Hydro';
var oil = 'Oil';
var gas = 'Gas';
var biofuels = 'Biofuels';
var heat = 'Heat';
var peat = 'Peat';
var waste = 'Waste';
var oil_shale = 'Oil shale';
var solar = 'Solar';
var wind = 'Wind';
var geothermal = 'Geothermal';
var nuclear = 'Nuclear';
var importexport = 'Import/Export';

var Industry = "Industry";
var Transport = "Transport";
var Residential = "Households";
var Commercial = "Commercial";
var Agriculture = "Agriculture";
var Fishing = "Fishing";
var Non_energy_use = "Non-energy use";
var Other = "Other";

var sector_obj = {};
 sector_obj['Industry'] = "Industry";
 sector_obj['Transport'] = "Transport";
 sector_obj['Residential'] = "Residential";
 sector_obj['Commercial'] = "Commercial";
 sector_obj['Agriculture'] = "Agriculture";
 sector_obj['Fishing'] = "Fishing";
 sector_obj['Non_energy_use'] = "Non-energy use";
 sector_obj['Other'] = "Other";

//var Import/Export = 'Import/Export';
//var TPES ='TPES';
//var 'Electricity Plants';
//'Own Use';
//'Distribution Losses';
//'TFC';

var case_name = "Case name";
var case_description = "Case description" 

var case_name_required = 'Case name is required!';
var industry_sector_is_required = 'Industry sector is required!';
var transport_sector_is_required = 'Transport sector is required!';
var residential_sector_is_required = 'Household sector is required!';
var commercial_sector_is_required = 'Commercial sector is required!';
var electricity_required = 'Electricity is a required energy product!';
var oil_required = 'Oil is a required energy product!';
var gas_required = 'Gas is a required energy product!';
var coal_required = 'Coal is a required energy product!';
var biofuel_required = 'Biofuel is a required energy product!';
var hydro_required = 'Hydro is is a required energy product!';
var electricity_required = 'Electricity is a required energy product!';
var select_one_year = 'Select at least one year!'

var successfully_created_case = "Case was succesfully created!"
var successfully_updated_case = "Case was succesfully updated!"
var error_occured = "Error occured!";
var update_failed_el_mix  = 'Update failed! The sum of fuel shares for electricity generation is not 100!';
var case_with_same_name_exists = "Case with the same name already exists! Change case name."
var sum_should_be_100 = "Sum of shares schould be 100%! Please correct the input!";
var sum_is_greater_then_100 = "Sum is greater than 100%! Update is unsuccessful!";
var sum_is_less_than_100 = "Sum is less than 100%! Please Correct the input! Add "
var to_some_fuel = " to some energy product!";
var value_should_be_positive = "Value should be positive!";

var select_case_1 = "Select case 1";
var select_case_2 = "Select case 2";
var select_case_to_compare = 'Select case to compare!';

var update_fed_by_sectors = 'Update final energy consumption by energy consumers!'

var select_year = 'Select year';
var please_select_year = 'Please select year!';
var please_select_years = 'Please select scenario years!';

var sum_of_fuel_shares_for = 'The sum of energy product shares for ';
var sector_is_not_100 = ' energy consumer is not 100!';

var update_failed_ind = 'Update failed! The sum of energy product shares for Industry is not 100%!';
var update_failed_tra = 'Update failed! The sum of energy product shares for Transport is not 100%!';
var update_failed_res = 'Update failed! The sum of energy product shares for Households is not 100%!';
var update_failed_com = 'Update failed! The sum of energy product shares for Commercial is not 100%!';
var update_failed_agr = 'Update failed! The sum of energy product shares for Agriculture is not 100%!';
var update_failed_fis = 'Update failed! The sum of energy product shares for Fishing is not 100%!';
var update_failed_neu = 'Update failed! The sum of energy product  hares for Non-energy uses is not 100%!';
var update_failed_oth = 'Update failed! The sum of energy product shares for Other uses is not 100%!';

var final_energy_demand = "Final energy consumption";
var secondary_energy_supplies = "Secondary energy uses";
var primary_energy_supplies = "Primary energy supply";
var tpes = "Total primary energy supply";
var final_energy_demand_bysectors = "Final energy consumption by energy consumers";
var final_energy_demand_fuel_shares = "Final energy consumption - energy product shares";
var td_losses = 'Losses';
var by_fuels = 'by energy product';
var by_years = 'by years';

var sum_of_el_generation = 'The sum of energy product shares for electricity generation is not 100%!';

var import_export_tool_tip ='Enter negative value for net export and positive value for net import!'
var fuel_shares_for_el_gen = "Energy product shares for electricity generation";
var el_gen = 'Electricity generation';
var fuel_shares_for_el_gen = 'Energy product shares for electricity generation';
var el_gen_from_each_fuel = "Electricity generated from each energy product";
var domestic_production_tool_tip = 'Update primary energy production!';
var domestic_production = 'Primary energy production';
var expected_domestic_production = 'Expected primary energy production';
var pe_for_el_gen = "Primary energy products used for electricity generation";

var capacity_factor_tool_tip = 'Capacity factor value is needed for calculation! Please enter value!';

var installed_capacities = "Installed capacities";
var by_technology = 'by energy product (technology)';
var specific_investment_cost = "Specific investment cost";

var energy_output_lbl = "Electricity output";
var energy_output_calc_from_installed_cap_and_cap_factor = "Electricity output calculated from installed capacities and capacity factors";

var energy_deficit = "Electricity deficit"
var energy_deficit_calc_from_inst_cf_fed = "Electricity deficit calculated from installed capacities, capacity factors and final energy consumption";
var new_capacity_needed = "Additional capacity needed";
var capacity_that_should_be_installed = "Capacity that should be installed to meet consumption";
var investment_lbl = 'Investment';
var investment_cost_for_added_cap = "Investment cost for new capacities";

var additional_capacity_investment = 'Additional capacity and investment';
var case_name_not_allowed = 'Special characters are not allowed in case name!';
var transformation = 'Transformation';
var create_new_or_edit_exisiting_case = "Create new or edit existing case!";

var save_chart = 'Save chart';
var sankey = 'Sankey diagram';
var energy_balance = 'Energy Balance';
var millions = 'millions of ';
var co2_emissions = 'CO2 emissions';        
var emissions_from_el_gen = 'Emissions from electricity generation';

var export_grid = 'Export grid';
var save_as_png = 'Save chart as PNG'

var blockUI = 'Please wait...Your request is being processed!!';

var planning_and_economic_studies_section = 'Planning and Economic Studies Section';

var view_el_deficit = 'View electricity deficit';
var el_deficit = 'Electricity deficit'







                    	