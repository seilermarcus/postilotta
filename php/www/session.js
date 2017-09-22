

if (sessionStorage.sid === null){
  con ('session.php', {sid:0}, cbAfter_session);
}
function cbAfter_session(th){
  console.log(th.responseText);
  sessionStorage.sid = th.responseText
}
