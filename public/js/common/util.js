let MONTH_NAMES = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

function readGET(name) {
  var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
  if (results==null){
    return null;
  } else {
    return decodeURI(results[1]);
  }
}

function unixTimeToDateString(unixTime) {
  var MONTHS = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var date = new Date(unixTime * 1000);

  var str = date.getUTCDate() + ' ';
  str += MONTHS[date.getUTCMonth()] + ' ';
  str += date.getUTCFullYear();

  return str;
}

function unixTimeToDateTimeString(unixTime) {
  var date = new Date(unixTime * 1000);

  var hr = ('0' + date.getHours()).substr(-2);
  var min = ('0' + date.getMinutes()).substr(-2);
  var sec = ('0' + date.getSeconds()).substr(-2);
  var day = date.getDate();
  var mth = date.getMonth() + 1;
  var yr = date.getFullYear();

  return hr+':'+min+':'+sec+' '+day+'/'+mth+'/'+yr;
}

function unixTimeToISOString(unixTime) {
  var date = new Date(unixTime * 1000);

  var str = date.getUTCFullYear() + '-';
  str += ('0'+(1+date.getUTCMonth())).slice(-2) + '-';
  str += ('0'+date.getUTCDate()).slice(-2);

  return str;
}

function formatMoney(value, options = {symbol:'$', negativeBrackets:false}) {
  var string = value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

  if (options.negativeBrackets) {
    if (value < 0) {
      string = (-value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      string = '(' + string + ')';
    }
  }

  if (options.symbol) {
    string = options.symbol + ' ' + string;
  }
  return string;
}