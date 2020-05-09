var electricity = 'Električna energija';
var coal = 'Ugalj';
var hydro ='Hidroenergija';
var oil = 'Nafta';
var gas = 'Plin';
var biofuels = 'Biogoriva';
var heat = 'Toplota';
var peat = 'Treset';
var waste = 'Otpad';
var oil_shale = 'Uljni škriljci';
var solar = 'Solarna energija';
var wind = 'Vjetroenergija';
var geothermal = 'Geotermalna energija';
var nuclear = 'Nuklearna energija';
var importexport = 'Import/Export';

var fuel_obj = {};
 fuel_obj['electricity'] = 'Električna energija';
 fuel_obj['coal'] = 'Ugalj';
 fuel_obj['hydro'] ='Hidro';
 fuel_obj['oil'] = 'Nafta';
 fuel_obj['gas'] = 'Plin';
 fuel_obj['biofuels'] = 'Biogoriva';
 fuel_obj['heat'] = 'Toplota';
 fuel_obj['peat'] = 'Treset';
 fuel_obj['waste'] = 'Otpad';
 fuel_obj['oil_shale'] = 'Uljni skriljci';
 fuel_obj['solar'] = 'Solarna energija';
 fuel_obj['wind'] = 'Vjetroenergija';
 fuel_obj['geothermal'] = 'Geotermalna energija';
 fuel_obj['nuclear'] = 'Nuklearna energija';
 fuel_obj['importexport'] = 'Import/Export';


var Industry = "Industrija";
var Transport = "Transport";
var Residential = "Kućanstvo";
var Commercial = "Usluge";
var Agriculture = "Poljoprivreda";
var Fishing = "Ribarenje";
var Non_energy_use = "Neenergetska potrošnja";
var Other = "Ostalo";

var sector_obj = {};
 sector_obj['Industry'] = "Industrija";
 sector_obj['Transport'] = "Transport";
 sector_obj['Residential'] = "Kućanstvo";
 sector_obj['Commercial'] = "Usluge";
 sector_obj['Agriculture'] = "Poljoprivreda";
 sector_obj['Fishing'] = "Ribarenje";
 sector_obj['Non_energy_use'] = "Neenergetska potrošnja";
 sector_obj['Other'] = "Ostalo";


var case_name = "Ime scenarija";
var case_description = "Opis scenarija" 

var case_name_required = 'Ime scenarija je obavezno!';
var industry_sector_is_required = 'Sektor industrije je obavezan!';
var transport_sector_is_required = 'Sektor transporta je obavezan!';
var residential_sector_is_required = 'Sektor kućanstva je obavezan!';
var commercial_sector_is_required = 'Sektor usluga je obavezan!';
var electricity_required = 'Električna energija je obavezno polje';
var oil_required = 'Nafta je obavezno polje';
var gas_required = 'Prirodni plin je obavezno polje';
var coal_required = 'Ugalj je obavezno polje';
var biofuel_required = 'Biogoriva su obavezno polje';
var hydro_required = 'Hidroenergija je obavezno polje';

var select_one_year = 'Molimo odaberite bar jednu godinu!'

var successfully_created_case = "Uspješno ste kreirali scenarij!"
var successfully_updated_case = "Uspješno ste ažurirali scenarij!"
var error_occured = "Greška u procesu!";
var update_failed_el_mix  = 'Ažuriranje neuspješno! Suma udijela za proizvodnju električne energije nije 100!';
var case_with_same_name_exists = "Scenarij sa istim imenom postoji!"
var sum_should_be_100 = "Suma udjela mora biti 100! Molimo ispravite unos!";
var sum_is_greater_then_100 = "Suma je veca od 100! Ažuriranje neuspješno!";
var sum_is_less_than_100 = "Suma je manja od 100! Dodajte "
var to_some_fuel = " nekom od goriva!";
var value_should_be_positive = "Unesena vrijednost mora biti pozitivna!";

var select_case_1 = "Izaberite scenarij 1";
var select_case_2 = "Izaberite scenarij 2";
var select_case_to_compare = 'Izaberite slučaj za usporedbu';

var update_fed_by_sectors = 'Ažuriraj konačnu potrošnju energije po sektorima!'

var select_year = 'Izaberite godinu';
var please_select_year = 'Molimo izaberite godinu!';
var please_select_years = 'Izaberite godine za scenarij!';

var sum_of_fuel_shares_for = 'Suma udjela za ';
var sector_is_not_100 = ' sektor nije 100!';

var update_failed_ind = 'Ažuriranje bezuspješno! Suma udijela goriva za industriju nije 100!';
var update_failed_tra = 'Ažuriranje bezuspješno! Suma udijela goriva za transport nije 100!';
var update_failed_res = 'Ažuriranje bezuspješno! Suma udijela goriva za kućanstvo nije 100!';
var update_failed_com = 'Ažuriranje bezuspješno! Suma udijela goriva za usluge nije 100!';
var update_failed_agr = 'Ažuriranje bezuspješno! Suma udijela goriva za poljoprivredu nije 100!';
var update_failed_fis = 'Ažuriranje bezuspješno! Suma udijela goriva za ribarstvo nije 100!';
var update_failed_neu = 'Ažuriranje bezuspješno! Suma udijela goriva za ne energetsku potrošnju nije 100!';
var update_failed_oth = 'Ažuriranje bezuspješno! Suma udijela goriva za ostalo nije 100!';

var final_energy_demand = "Fianlna enegetska potrošnja";
var secondary_energy_supplies = "Sekndarni energetski izvori";
var primary_energy_supplies = "Primarni energetski izvori";
var tpes = "Ukupni primarni energetski izvori";
var final_energy_demand_bysectors = "Finalna energetska potrošnja po sektorima";
var final_energy_demand_fuel_shares = "Finalna energetska potrošnja-udjeli goriva";
var td_losses = 'T&D gubici';
var by_fuels = 'po gorivima';
var by_years = 'po godinama';

var sum_of_el_generation = 'Suma udjela goriva za proizvodnju električne energije nije 100!';

var import_export_tool_tip ='Unesite negativnu vrijednost za export i pozitivnu za impoprt!'
var fuel_shares_for_el_gen = "Udjeli goriva za proizvodnju električne energije";
var el_gen = 'Proizvodnja električne enerije';
var el_gen_from_each_fuel = "Količina električne energije proizvedena iz svakog goriva";
var domestic_production_tool_tip = 'Ažuriraj domaću proizvodnju!';
var domestic_production = 'Domaća proizvodnja';
var expected_domestic_production = 'Očekivana domaća proizvodnja';
var pe_for_el_gen = "Primarna energija koja se koristi za proizvodnju električne energije";

var capacity_factor_tool_tip = 'Faktor kapaciteta se treba unijeti radi izračuna!!';

var installed_capacities = "Instalirani kapacitet";
var by_technology = 'po tehnologiji';
var specific_investment_cost = "Specifični investicijski trošak";

var energy_output_lbl = "Energija";
var energy_output_calc_from_installed_cap_and_cap_factor = "Energija izračunata iz instaliranih kapaciteta i faktora kapaciteta";

var energy_deficit = "Energetski deficit"
var energy_deficit_calc_from_inst_cf_fed = "Energetski deficit izračunat iz instaliranih kapaciteta, faktora kapaciteta i finalne energetske potrošnje";
var new_capacity_needed = "Potrebi kapaciteti";
var capacity_that_should_be_installed = "Kapaciteti koji bi se trebali instalirati da bi se zadovoljila potreba";
var investment = 'Investicije';
var investment_cost_for_added_cap = "Investicijski trošak za novo dodane kapacitete";

var additional_capacity_investment = 'Dodatni kapaciteti i investicije';
var case_name_not_allowed = 'Specijalni karakteri nisu dozvoljeni u imenu scenarija!';
var transformation = 'Transformacije';
var save_chart = 'Spasi graf';
var investment_lbl = 'Investicjia';
var Snakey = 'Sankey;'
var sankey = 'Sankey diagram';
var energy_balance = 'Energetska bilanca';
var millions = 'milioni ';
var co2_emissions = 'CO2 emisije';
var emissions_from_el_gen = 'Emisije nastale proizvodnjom električne energije';

var export_grid = 'Izvezi tablicu';
var save_as_png = 'Spasi graf kao PNG'

var blockUI = 'Molim sačekajte...Vaš zahtjev se procesira!!!';





                    	