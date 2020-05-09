function getChartSettings(title='', description='', dataAdapter, series, unit, type= 'stackedcolumn', datafield='year', legendLayout=''){
   
    var setting = {
        title:title,
        description: description,
        legendLayout: legendLayout,
        enableAnimations: true,
        enableCrosshairs: true,
        crosshairsDashStyle: '2,2',
        crosshairsLineWidth: 1.5,
        crosshairsColor: '#2f6483',
        borderLineColor: 'transparent',
        showLegend: true,
        theme: theme,
        padding: { left: 5, top: 5, right: 5, bottom: 5 },
        titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
        source: dataAdapter,
        categoryAxis:
            {
                text: 'Category Axis',
                type:'basic',
                textRotationAngle: 0,
                dataField: datafield,
                showTickMarks: true,
                tickMarksInterval: 1,
                tickMarksColor: '#888888',
                unitInterval: 1,
                showGridLines: true,
                gridLinesInterval: 1,
                gridLinesColor: '#888888',
                axisSize: 'auto',
            },
        colorScheme: 'scheme04',
        seriesGroups:
            [
                {                                                                    
                    type: type,
                    valuesOnTicks: false,
                    columnsGapPercent:10,
                    seriesGapPercent: 5,
                    columnsMaxWidth:100,
                    columnsMinWidth:5,
                    toolTipFormatFunction: function (value, index, data, d) {
                        return  data.displayText + ' ' + parseFloat(value).toFixed(2) + ' ' + unit;
                    },

                    formatSettings:
                    {
                        thousandsSeparator: ',',
                        decimalPlaces: 2,
                        sufix: ' ' +unit,
                    },

                    labels: {
                        visible: true,
                        verticalAlignment: 'center',
                        offset: { x: 0, y: 0 },
                        angle: 0,
                        formatSettings:
                        {
                            thousandsSeparator: ',',
                            decimalPlaces: 2,
                            sufix: ' ' +unit,
                        },
                    },
                    formatFunction: 
                        function (value, index, data) { 
                            if (value > 0) {
                                return  parseFloat(value).toFixed(2) + ' ' + unit;
                            }else{
                                return '';
                            }   
                        },
                    valueAxis:
                    {
                        //unitInterval: 0,
                        minValue: 'auto',
                        maxValue: 'auto',
                        displayValueAxis: true,
                        description: unit,
                        axisSize: 'auto',
                        tickMarksColor: '#888888',
                        //formatSettings: {decimalPlaces: 0},
                    },
                    series: series
                }
            ]
    }; 
    return setting;
}

function getPeriodChart(title='', description='', dataAdapter, series, unit, type= 'stackedcolumn', datafield='year', label, minValue, maxValue,unitInterval,legendLayout){
   
    var setting = {
        title:title,
        description: description,
        legendLayout: legendLayout,
        enableAnimations: true,
        enableCrosshairs: true,
        crosshairsDashStyle: '2,2',
        crosshairsLineWidth: 1.5,
        crosshairsColor: '#2f6483',
        borderLineColor: 'transparent',
        showLegend: true,
        theme: theme,
        padding: { left: 5, top: 5, right: 5, bottom: 5 },
        titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
        source: dataAdapter,
        categoryAxis:
            {
                text: 'Category Axis',
                type:'basic',
                textRotationAngle: 0,
                dataField: datafield,
                showTickMarks: true,
                tickMarksInterval: 1,
                tickMarksColor: '#888888',
                // unitInterval: 1,
                // showGridLines: true,
                gridLinesInterval: unitInterval,
                gridLinesColor: '#888888',
                axisSize: 'auto',
                minValue: minValue,
                maxValue: maxValue,
                rangeSelector: {
                    serieType: 'line',
                    unitInterval: unitInterval,
                    padding: { /*left: 0, right: 0,*/ top: 5, bottom: 0 },
                    size: 55,
                }
            },
        colorScheme: 'scheme04',
        seriesGroups:
            [
                {                                                                    
                    type: type,
                    valuesOnTicks: true,
                    columnsGapPercent:10,
                    seriesGapPercent: 5,
                    columnsMaxWidth:100,
                    columnsMinWidth:5,
                    toolTipFormatFunction: function (value, index, data, d) {
                        return  data.displayText + ' ' + parseFloat(value).toFixed(2) + ' ' + unit;
                    },

                    formatSettings:
                    {
                        thousandsSeparator: ',',
                        decimalPlaces: 2,
                        sufix: ' ' +unit,
                    },

                    labels: {
                        visible: label,
                        verticalAlignment: 'center',
                        offset: { x: 0, y: 0 },
                        angle: 0,
                        formatSettings:
                        {
                            thousandsSeparator: ',',
                            decimalPlaces: 2,
                            sufix: ' ' +unit,
                        },
                    },
                    formatFunction: 
                        function (value, index, data) { 
                            if (value > 10) {
                                return  parseFloat(value).toFixed(2) + ' ' + unit;
                            }else{
                                return '';
                            }   
                        },
                    valueAxis:
                    {
                        unitInterval: 0,
                        minValue: 'auto',
                        maxValue: 'auto',
                        displayValueAxis: true,
                        description: unit,
                        axisSize: 'auto',
                        tickMarksColor: '#888888',
                        //formatSettings: {decimalPlaces: 0},
                    },
                    series: series
                }
            ]
    }; 
    return setting;
}
