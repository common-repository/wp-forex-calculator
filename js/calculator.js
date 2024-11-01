jQuery(document).ready(function($) {
    var showRisk = true;

    var currencyPairs = ['EUR/USD', 'GBP/USD', 'USD/CHF', 'USD/CAD', 'USD/JPY', 'NZD/USD', 'AUD/USD', 'EUR/AUD', 
                         'EUR/GBP', 'EUR/JPY', 'EUR/CAD', 'EUR/CHF', 'EUR/NZD', 'GBP/CAD', 'GBP/CHF', 'GBP/JPY', 
                         'GBP/AUD', 'GBP/NZD', 'AUD/CAD', 'AUD/JPY', 'AUD/CHF', 'AUD/NZD', 'CHF/JPY', 'CAD/CHF', 
                         'CAD/JPY', 'NZD/CHF', 'NZD/JPY', 'NZD/CAD'];

    function recalculate() {
        var inputExchangeRate = $("#exchange-rate").val(),
            inputBalance = parseInt($("#balance").val().replace(',', '')),
            inputStopLoss = $("#stoploss").val(),
            inputCurrency = $("#currency").val(),
            inputCurrencyPair = $("#currencypair").val(),
            baseCurrency = inputCurrencyPair.split("/")[0],
            quoteCurrency = inputCurrencyPair.split("/")[1];
        
        $("#balance").val(numeral(inputBalance).format('0,0'));
        
        if (inputCurrency == quoteCurrency) {
            inputExchangeRate = 1;
        }

        var currencyPair = inputCurrency + "/" + quoteCurrency;
        if (currencyPairs.indexOf(currencyPair) < 0) {
            inputExchangeRate = 1 / inputExchangeRate;
        }
        
        if (showRisk) {
            inputRisk = $("#risk").val();
            amountRisk = inputBalance * inputRisk / 100.0;
            $("#riskmoney").val(amountRisk);
        } else {
            amountRisk = $("#riskmoney").val();
            inputRisk = Math.round(amountRisk * 10000.0 / inputBalance) / 100.0;
            $("#risk").val(inputRisk);
        }
            
        var currencyPairUnits = (baseCurrency == 'JPY' || quoteCurrency == 'JPY' ) ? 100 : 10000,
            perPip = amountRisk * inputExchangeRate / inputStopLoss,
            positionSize = perPip * currencyPairUnits;
            
        if (positionSize > 0 && positionSize != Infinity) {
            $("#amount-risk").val(numeral(amountRisk).format('0,0.[0000]') + " " + inputCurrency);
            $("#percent-risk").val(inputRisk);
            $("#position-size").val(numeral(positionSize).format('0,0') + " units");
            $("#lots-standard").val(numeral(positionSize / 100000).format('0,0'));
            $("#lots-mini").val(numeral(positionSize / 10000).format('0,0'));
            $("#lots-micro").val(numeral(positionSize / 1000).format('0,0'));
        } else {
            $("#amount-risk").val("");
            $("#percent-risk").val("");
            $("#position-size").val("");
            $("#lots-standard").val("");
            $("#lots-mini").val("");
            $("#lots-micro").val("");
        }
    }

    function refresh() {
        var inputCurrency = $("#currency").val(),
            inputCurrencyPair = $("#currencypair").val(),
            baseCurrency = inputCurrencyPair.split("/")[0],
            quoteCurrency = inputCurrencyPair.split("/")[1];
            
        if (showRisk) {
            $("#risk-row").show();
            $("#riskmoney-row").hide();
            $("#result-risk-amount-row").show();
            $("#result-risk-row").hide();
        } else {
            $("#risk-row").hide();
            $("#riskmoney-row").show();
            $("#risk-currency").text(inputCurrency);
            $("#result-risk-amount-row").hide();
            $("#result-risk-row").show();
        }
            
        if (inputCurrency == quoteCurrency) {
            $("#exchange-rate").val("");
            $("#exchange-rate-row").hide();
        } else {
            var currencyPair = quoteCurrency + "/" + inputCurrency;
            if (currencyPairs.indexOf(currencyPair) >= 0) {
                $("#exchange-rate-labels").text(quoteCurrency + " / " + inputCurrency);
            } else {
                $("#exchange-rate-labels").text(inputCurrency + " / " + quoteCurrency);
            }
            $("#exchange-rate-row").show();
        }
    }

    function toggleRisk() {
        showRisk = !showRisk;
        refresh();
    }

    $("#currency").change(refresh);
    $("#currencypair").change(refresh);
    $("#btn-calculate").on("click", recalculate);
    $("#btn-swap-money").on("click", toggleRisk);
    $("#btn-swap-risk").on("click", toggleRisk);
    refresh();
})
