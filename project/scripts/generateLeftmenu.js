var years = getYears();
var sectors = getSectors();
var fuels = getFuels();
var elmix_fuels = getElMixFuels();
var unit = getUnit();

// napraviti serije za grafikone po gorivima finalnim
series_fuels = [];
for (var key in fuels)
{    
    if(fuels[key] == 1 )
    {
        var key1 = key.toLowerCase();        
        var  serija ={
                dataField:key,
                displayText:fuel_obj[key1],
                color:color_obj[key]
        };
        series_fuels.push(serija);
    }
}  