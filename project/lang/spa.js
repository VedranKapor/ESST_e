var fuel_obj = new Object();
 fuel_obj['electricity'] = 'Electricidad';
 fuel_obj['coal'] = 'Carbón';
 fuel_obj['hydro'] ='Hidroenergía';
 fuel_obj['oil'] = 'Productos de petróleo';
 fuel_obj['gas'] = 'Gas';
 fuel_obj['biofuels'] = 'Biocombustibles';
 fuel_obj['heat'] = 'Calor';
 fuel_obj['peat'] = 'Turba';
 fuel_obj['waste'] = 'Desechos';
 fuel_obj['oil_shale'] = 'Esquistos bituminosos (Oil shale)';
 fuel_obj['solar'] = 'Solar';
 fuel_obj['wind'] = 'Eólica';
 fuel_obj['geothermal'] = 'Geotérmica';
 fuel_obj['nuclear'] = 'Nuclear';
 fuel_obj['importexport'] = 'Importaciones/Exportaciones';

var electricity = 'Electricidad';
var coal = 'Carbón';
var hydro ='Hidroenergía';
var oil = 'Productos de petróleo';
var gas = 'Gas';
var biofuels = 'Biocombustibles';
var heat = 'Calor';
var peat = 'Turba';
var waste = 'Desechos';
var oil_shale = 'Esquistos bituminosos (Oil shale)';
var solar = 'Solar';
var wind = 'Eólica';
var geothermal = 'Geotérmica';
var nuclear = 'Nuclear';
var importexport = 'Importaciones/Exportaciones';
var ud = "La demanda no atendida";

var Industry = "Industria";
var Transport = "Transporte";
var Residential = "Residencial";
var Commercial = "Servicios";
var Agriculture = "Agricultura";
var Fishing = "Pesca";
var Non_energy_use = "Usos no energéticos";
var Other = "Otros";

var sector_obj = {};
 sector_obj['Industry'] = "Industria";
 sector_obj['Transport'] = "Transporte";
 sector_obj['Residential'] = "Residencial";
 sector_obj['Commercial'] = "Servicios";
 sector_obj['Agriculture'] = "Agricultura";
 sector_obj['Fishing'] = "Pesca";
 sector_obj['Non_energy_use'] = "Usos no energéticos";
 sector_obj['Other'] = "Otros";

//var Import/Export = 'Import/Export';
//var TPES ='TPES';
//var 'Electricity Plants';
//'Own Use';
//'Distribution Losses';
//'TFC';

var case_name = "Nombre del caso";
var case_description = "Descripción del caso" 

var case_name_required = 'Se requiere el nombre del caso!';
var industry_sector_is_required = 'Se requiere el sector Industria!';
var transport_sector_is_required = 'Se requiere el sector Transporte!';
var residential_sector_is_required = 'Se requiere el sector Residencial!';
var commercial_sector_is_required = 'Se requiere el sector Servicios!';
var electricity_required = 'La Electricidad es un producto energético requerido!';
var oil_required = 'Los Productos de petróleo son productos energéticos requeridos!';
var gas_required = 'El Gas es un producto energético requerido!';
var coal_required = 'El Carbón es un producto energético requerido!';
var biofuel_required = 'Los Biocombustibles son productos energéticos requeridos!';
var hydro_required = 'La Hidroenergía es un producto energético requerido!';
var electricity_required = 'La Electricidad es un producto energético requerido!';
var select_one_year = 'Seleccione al menos un año!'

var successfully_created_case = "El caso se creó exitosamente!"
var successfully_updated_case = "El caso se actualizó exitosamente!"
var error_occured = "Ocurrió un error!";
var update_failed_el_mix  = 'Actualización fallida! La suma de las participaciones de los combustibles en la generación de electricidad no es igual a 100!';
var case_with_same_name_exists = "Existe un caso con el mismo nombre! Cambie el nombre del caso."
var sum_should_be_100 = "La suma de las participaciones debe ser igual a 100%! Por favor revise los datos de entrada!";
var sum_is_greater_then_100 = "La suma es mayor que el 100%! No se pudo actualizar!";
var sum_is_less_than_100 = "La suma es menor que el 100%! Por favor revise los datos! Adicione "
var to_some_fuel = " a alguno de los productos energéticos!";
var value_should_be_positive = "El valor debe ser positivo!";

var select_case_1 = "Seleccione el caso 1";
var select_case_2 = "Seleccione el caso 2";
var select_case_to_compare = 'Seleccione el caso a comparar!';

var update_fed_by_sectors = 'Actualice el consumo final de energía por consumidores energéticos!'

var select_year = 'Seleccione el año';
var please_select_year = 'Por favor seleccione el año!';
var please_select_years = 'Seleccione año de escenarios';

var sum_of_fuel_shares_for = 'Suma de los productos energéticos The sum of energy product shares for ';
var sector_is_not_100 = ' consumidores energéticos no es igual a 100!';

var update_failed_ind = 'Actualización fallida! La suma de las participaciones de los productos energéticos en la Industria no es igual a 100%!';
var update_failed_tra = 'Actualización fallida! La suma de las participaciones de los productos energéticos en el Transporte no es igual a 100%!';
var update_failed_res = 'Actualización fallida! La suma de las participaciones de los productos energéticos en el sector Residencial no es igual a 100%!';
var update_failed_com = 'Actualización fallida! La suma de las participaciones de los productos energéticos en el sector Servicios no es igual a 100%!';
var update_failed_agr = 'Actualización fallida! La suma de las participaciones de los productos energéticos en la Agricultura no es igual a 100%!';
var update_failed_fis = 'Actualización fallida! La suma de las participaciones de los productos energéticos en la Pesca no es igual a 100%!';
var update_failed_neu = 'Actualización fallida! La suma de las participaciones de los productos energéticos para Usos no energéticos no es igual a 100%!';
var update_failed_oth = 'Actualización fallida! La suma de las participaciones de los productos energéticos para Otros usos no es igual a 100%!';

var final_energy_demand = "Consumo de Energía final";
var secondary_energy_supplies = "Uso de Energía Secundaria";
var primary_energy_supplies = "Suministro de Energía primaria";
var tpes = "Suministro total de energía primaria";
var final_energy_demand_bysectors = "Consumo de energía final por consumidor energético";
var final_energy_demand_fuel_shares = "Consumo de energía final - participación de los productos energéticos";
var td_losses = 'Pérdidas';
var by_fuels = 'por producto energético';
var by_years = 'por años';

var sum_of_el_generation = 'La suma de las participaciones de los productos energéticos en la generación de electricidad no es igual a 100%!';

var import_export_tool_tip ='Entre valores negativos para las exportaciones netas y valores positivos para las importaciones netas!'
var fuel_shares_for_el_gen = "Participación de los productos energéticos en la generación de electricidad";
var el_gen = 'Generación de electricidad';
var fuel_shares_for_el_gen = 'Participación de los productos energéticos en la generación de electricidad';
var el_gen_from_each_fuel = "Electricidad generada por cada uno de los productos energéticos";
var domestic_production_tool_tip = 'Producción de energía primaria actualizada!';
var domestic_production = 'Producción de energía primaria';
var expected_domestic_production = 'Producción de energía primaria esperada';
var pe_for_el_gen = "Productos energéticos primarios usados para la generación de electricidad";

var capacity_factor_tool_tip = 'Se necesita el valor del factor de capacidad para los cálculos! Por favor entre el valor!';

var installed_capacities = "Capacidades instaladas";
var by_technology = 'por producto energético (tecnología)';
var specific_investment_cost = "Costo de inversión específico";

var energy_output_lbl = "Electricity output";
var energy_output_calc_from_installed_cap_and_cap_factor = "Electricity output calculada a partir de las capacidades instaladas y los factores de capacidad";

var energy_deficit = "Déficit de electricidad"
var energy_deficit_calc_from_inst_cf_fed = "Déficit de electricidad calculado a partir de las capacidades instaladas, los factores de capacidad y el consumo final de energía";
var new_capacity_needed = "Capacidad nueva necesitada";
var capacity_that_should_be_installed = "Capacidad que se necesita instalar para cubrir la demanda";
var investment_lbl = 'Inversión';
var investment_cost_for_added_cap = "Costo de inversión para las nuevas capacidades";

var additional_capacity_investment = 'Capacidad adicional e inversión';
var case_name_not_allowed = 'No se permiten caracteres especiales en el nombre del caso!';
var transformation = 'Transformación';
var create_new_or_edit_exisiting_case = "Crear un nuevo caso o editar uno existente!";

var save_chart = 'Guardar el gráfico';
var sankey = 'Diagrama Sankey';
var energy_balance = 'Balance energético';
var millions = 'millones de ';
var co2_emissions = 'Emisiones de CO2';        
var emissions_from_el_gen = 'Emisiones provenientes de la generación de electricidad';

var export_grid = 'Exportar tabla';
var save_as_png = 'Guardar el gráfico as PNG';

var blockUI = 'Por favor, espere ... Se está procesando su petición!!!';

var hourly_loads = 'Carga Horaria';







                    	