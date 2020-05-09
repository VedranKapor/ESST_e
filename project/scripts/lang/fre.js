var fuel_obj = new Object();
 fuel_obj['electricity'] = 'Electricité';
 fuel_obj['coal'] = 'Charbon';
 fuel_obj['hydro'] ='Hydraulique';
 fuel_obj['oil'] = 'Pétrole';
 fuel_obj['gas'] = 'Gaz naturel';
 fuel_obj['biofuels'] = 'Biocarburants';
 fuel_obj['heat'] = 'Chaleur';
 fuel_obj['peat'] = 'Tourbe';
 fuel_obj['waste'] = 'Déchets';
 fuel_obj['oil_shale'] = 'Schiste bitumineux';
 fuel_obj['solar'] = 'Solaire';
 fuel_obj['wind'] = 'Eolienne';
 fuel_obj['geothermal'] = 'Géothermique';
 fuel_obj['nuclear'] = 'Nucléaire';
 fuel_obj['importexport'] = 'Importation/Exportation';


var electricity = 'Electricité';
var coal = 'Charbon';
var hydro ='Hydraulique';
var oil = 'Pétrole';
var gas = 'Gaz naturel';
var biofuels = 'Biocarburants';
var heat = 'Chaleur';
var peat = 'Tourbe';
var waste = 'Déchets';
var oil_shale = 'Schiste bitumineux';
var solar = 'Solaire';
var wind = 'Eolienne';
var geothermal = 'Géothermique';
var nuclear = 'Nucléaire';
var importexport = 'Importation/Exportation';

var Industry = "Industry";
var Transport = "Transport";
var Residential = "Households";
var Commercial = "Commercial";
var Agriculture = "Agriculture";
var Fishing = "Fishing";
var Non_energy_use = "Non-energy use";
var Other = "Other";

var sector_obj = {};
 sector_obj['Industry'] = "Industrie";
 sector_obj['Transport'] = "Transport";
 sector_obj['Residential'] = "Ménages";
 sector_obj['Commercial'] = "Commerce";
 sector_obj['Agriculture'] = "Agriculture";
 sector_obj['Fishing'] = "Pêche";
 sector_obj['Non_energy_use'] = "Usages non énergétiques";
 sector_obj['Other'] = "Autres usages";

//var Import/Export = 'Import/Export';
//var TPES ='TPES';
//var 'Electricity Plants';
//'Own Use';
//'Distribution Losses';
//'TFC';

var case_name = "Nom du cas";
var case_description = "Description du cas" 

var case_name_required = 'Le nom de cas est obligatoire!';
var industry_sector_is_required = 'Secteur Industrie est obligatoire!';
var transport_sector_is_required = 'Secteur Transport est obligatoire!';
var residential_sector_is_required = 'Secteur Ménages est obligatoire!';
var commercial_sector_is_required = 'Secteur commercial est obligatoire!';
var electricity_required = 'Électricité est un produit énergétique obligatoire!';
var oil_required = 'Pétrole est un produit énergétique obligatoire!';
var gas_required = 'Gaz naturel est un produit énergétique obligatoire!';
var coal_required = 'Charbon est un produit énergétique obligatoire!';
var biofuel_required = 'Biocarburants est un produit énergétique obligatoire!';
var hydro_required = 'Hydraulique est un produit énergétique obligatoire!';
var electricity_required = 'Électricité est un produit énergétique obligatoire!';
var select_one_year = 'Sélectionner au moins un an!'

var successfully_created_case = "Le cas a été créé avec succès!"
var successfully_updated_case = "Le cas a été mis à jour avec succès!"
var error_occured = "Une erreur est survenue!";
var update_failed_el_mix  = 'Mise à jour n\'a pas réussi! La somme des parts de combustible pour la production d\'électricité n\'est pas 100!';
var case_with_same_name_exists = "Le cas avec le même nom existe déjà! Changer le nom de cas."
var sum_should_be_100 = "Somme des parts devrait être 100%! Corriger l\'entrée s'il vous plaît!";
var sum_is_greater_then_100 = "Sum is greater than 100%! Update is unsuccessful!";
var sum_is_less_than_100 = "La somme est inférieure de 100%! Corriger l\'entrée s\'il vous plaît ! Ajouter "
var to_some_fuel = " à un produit énergétique!";
var value_should_be_positive = "La valeur doit être positive!";

var select_case_1 = "Sélectionner le cas 1";
var select_case_2 = "Sélectionner le cas 2";
var select_case_to_compare = 'Sélectionner des cas de comparer!';

var update_fed_by_sectors = 'Mettre à jour la consommation énergétique finale par les consommateurs d\'énergie!'

var select_year = 'Sélectionner une année';
var please_select_year = 'Sélectionner une année, s\'il vous plaît.';
var please_select_years = 'Sélectionnez années de scénario!';

var sum_of_fuel_shares_for = 'La somme des parts de produits énergétique pour ';
var sector_is_not_100 = ' consommateurs d\'énergie n\'est pas 100!';

var update_failed_ind = 'Mise à jour n\'a pas réussi! La somme des parts de produits énergétiques pour l\'industrie n\'est pas 100%!';
var update_failed_tra = 'Mise à jour n\'a pas réussi! La somme des parts de produits énergétique pour le transport n\'est pas 100%!';
var update_failed_res = 'Mise à jour n\'a pas réussi! La somme des parts de produits énergétique pour les ménages n\'est pas 100%!';
var update_failed_com = 'Mise à jour n\'a pas réussi! La somme des parts de produits énergétiques pour commerce n\'est pas 100%!';
var update_failed_agr = 'Mise à jour n\'a pas réussi! La somme des parts de produits énergétiques de l\'agriculture n\'est pas 100%!';
var update_failed_fis = 'Mise à jour n\'a pas réussi! La somme des parts de produits énergétiques pour la pêche n\'est pas 100%!';
var update_failed_neu = 'Mise à jour n\'a pas réussi! La somme des parts de produits énergétique pour des usages non énergétiques n\'est pas 100%!';
var update_failed_oth = 'Mise à jour n\'a pas réussi! La somme des parts de produits énergétique pour autres usages n\'est pas 100%!';

var final_energy_demand = "Consommation finale énergétique";
var secondary_energy_supplies = "Niveau d\'énergie secondaire";
var primary_energy_supplies = "L\'approvisionnement en énergie primaire";
var tpes = "Consommation intérieure brute";
var final_energy_demand_bysectors = "Consommation finale énergétique - par les consommateurs d\'énergie";
var final_energy_demand_fuel_shares = "Consommation finale énergétique - part des produits énergétiques";
var td_losses = 'Pertes';
var by_fuels = ' par des produits énergétiques';
var by_years = ' par ans';

var sum_of_el_generation = 'La somme des parts de produits énergétiques pour la production d\'électricité n\'est pas 100%!';

var import_export_tool_tip ='Entrez une valeur négative pour les exportations nettes et une valeur positive pour l\'importation net!'
var fuel_shares_for_el_gen = "Parts de produits énergétiques pour la production d\'électricité";
var el_gen = 'La production d\'électricité';
var fuel_shares_for_el_gen = 'Parts de produits énergétiques pour la production d\'électricité';
var el_gen_from_each_fuel = "L\'électricité produite à partir de chaque produit énergétique";
var domestic_production_tool_tip = 'Mettre à jour la production primaire!';
var domestic_production = 'Production primaire';
var expected_domestic_production = 'Prevision de la production primaire';
var pe_for_el_gen = "Energie primaire utilisé pour la production d'électricité";

var capacity_factor_tool_tip = 'Valeur du facteur de capacité est nécessaire pour le calcul! S\'il vous plaît entrer la valeur!';

var installed_capacities = "La capacité installée";
var by_technology = ' par produit énergétique (technologie)';
var specific_investment_cost = "Coût d\'investissement spécifique";

var energy_output_lbl = "La production d\'électricité";
var energy_output_calc_from_installed_cap_and_cap_factor = "La production d\'électricité calculée à partir de capacités installées et les facteurs de capacité";

var energy_deficit = "Déficit d'électricité"
var energy_deficit_calc_from_inst_cf_fed = "Déficit d\'électricité calculée à partir de capacités installées, des facteurs de capacité et de la consommation finale énergétique";
var new_capacity_needed = "La capacité additionnelle requis";
var capacity_that_should_be_installed = "Capacité à être installé pour satisfaire la consommation";
var investment_lbl = 'Investissement';
var investment_cost_for_added_cap = "Coût d\'investissement pour les nouvelles capacités";

var additional_capacity_investment = 'Capacité additionnelle et l\'investissement';
var case_name_not_allowed = 'SDes caractères spéciaux ne sont pas autorisés en nom du cas!';
var transformation = 'Transformations';
var create_new_or_edit_exisiting_case = "Créer un nouveau ou modifier le cas existant!";

var save_chart = 'Enregistrer graphique';
var sankey = 'Sankey schéma (flux d\'énergie)';
var energy_balance = 'Bilan énergétique pour';
var millions = 'millions de ';
var co2_emissions = 'les émissions de CO2';        
var emissions_from_el_gen = 'Émissions de la production d\'électricité';

var export_grid = 'Exporter le tableau';
var save_as_png = 'Enregistrer graphique PNG'

var blockUI = 'S\'il vous plaît patienter ... Votre demande est en cours!!';

var planning_and_economic_studies_section = 'Section pour la planification et des études économiques';







                    	