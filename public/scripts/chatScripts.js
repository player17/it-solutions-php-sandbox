"use strict";

$(document).ready(function(){
  //setInterval(refreshMessages, 1000);
});

function refreshMessages(){
  var lstIdMsg = $('span#page-chart__col-left__msg__wrap_msg_id').last().text();
  console.log(lstIdMsg);
}
refreshMessages();

