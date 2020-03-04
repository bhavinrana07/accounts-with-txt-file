const API_URL = "http://localhost/symfony4_rest/public/api/";
const FIELDS = "fields";

initFundTran();
initLoadAccounts();
initResetBalance();

function initResetBalance() {
  $("#reset").click(function() {
    $.ajax({
      url: API_URL + "resetAccounts",
      type: "get",
      dataType: "json",
      contentType: "application/json",
      success: function(data) {
        accountsPopulate(data);
      }
    });
  });
}

function initLoadAccounts() {
  $.ajax({
    url: API_URL + "accounts",
    type: "get",
    dataType: "json",
    contentType: "application/json",
    success: function(data) {
      accountsPopulate(data);
    }
  });
}

function accountsPopulate(data) {
  var accounts = "<ul>";
  $.each(data, function(index, value) {
    accounts +=
      "<li>" +
      value.name.replace(".txt", "") +
      "<span class='amt'>" +
      value.balance +
      "</span></li>";
  });
  accounts += "</ul>";
  $("#show_accounts").html(accounts);
}

function initFundTran() {
  $("#transfer").click(function() {
    from = $("#form_tran #from").val();
    to = $("#form_tran #to").val();
    amount = $("#form_tran #amount").val();
    postFields(from, to, amount);
  });
}

function postFields(from, to, amount) {
  $.ajax({
    url: API_URL + "transfer",
    type: "post",
    dataType: "json",
    contentType: "application/json",
    success: function(data) {
      transferResult(data);
    },
    data: JSON.stringify({
      transfer_from: from,
      transfer_to: to,
      amount: amount
    })
  });
}

function transferResult(data) {
  if (typeof data.status !== "undefined" && data.status == 0) {
    $("#show_message").html(data.message);
    return false;
  }
  accountsPopulate(data);
}
