function computeActualAmount(target, a, b, precision) {
  a = a || 0;
  b = b || 0;
  $(target).text(Number(sum(a, b, precision)).toLocaleString('en-PH', {
    style: 'currency',
    currency: 'PHP',
    useGrouping: true,
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }));
}
function computeActualAmount_input(target, a, b, precision) {
  a = a || 0;
  b = b || 0;
  $(target).val(Number(sum(a, b, precision)).toLocaleString('en-PH', {
    style: 'currency',
    currency: 'PHP',
    useGrouping: true,
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }));
}
function currencyFormat(value) {
  return Number(value).toLocaleString('en-PH', {
    style: 'currency',
    currency: 'PHP',
    useGrouping: true,
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
}
function sum(a, b, precision) {
  precision = precision || 2;
  let sum = Number(a) + Number(b);
  return Number(sum);
}

function subtract(a, b, precision){
  precision = precision || 2;
  let sub = Number(a) - Number(b);
  return Number(sub);
}

function getSessionInfo() {
  return $.getJSON('../api/api_session_info.php');
}

function initBackPreviousButton() {
  $('[data-action="back-prev"]').on('click', function(e) {
    e.preventDefault();
    history.back();
    return false;
  })
}


function populateDistrictsDropdown(city, dest, updateCallback) {
  $.getJSON('../api/api_districts.php?city_id=' + encodeURIComponent(city)).then(function(response) {
    $(dest).empty();
    $('<option>', { text: '--Select District--', value: '' }).appendTo(dest);
    for(let district of response.data) {
      $('<option>', { text: district.district, value: district.district_id }).appendTo(dest);
    }
    if (response.data.length === 1) {
      $(dest).val('Lone');
    }
    if (updateCallback) {
      updateCallback();
    }
  });
  $(dest).prop('disabled', false);
}
