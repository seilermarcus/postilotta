/**
 * postilotta browser app client
 *
 *   This file contains the main functionality of the browser-based web client.
 *   Further detailed description comming soon TODO.
 *
 * Author: Marcus Seiler
 *   info@uwezo-engineering.com
 *
 * Copyright (C) 2017 Uwezo Engineering GmbH
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with this program. If not, see http://www.gnu.org/licenses/
 *
 */

/**
* Load Attachment in sessionStorage
*/
 function upAttach(){
   var file = document.getElementById('attach').files[0];
   var reader = new FileReader();

   //Process after file is loaded
   reader.onload = function(e) {
     window.gAttachment = reader.result;
     var fname = document.getElementById('attach').value;
     fname = fname.substring(fname.lastIndexOf('\\') + 1, fname.length);
     window.gFilename = fname;
     document.getElementById('attach').style.visibility = 'hidden';
     document.getElementById('attReady').innerHTML = '<i>' + fname + '</i> ready for sending.';
   };

   // Start loading file
   var out = reader.readAsDataURL(file);
 }

 /**
  * Message sending step 1: Generate Return Key, etc. and trigger step 2.
  *
  */
function prepReply(to, c){

  window.document.body.style.cursor = "wait"; // sets the cursor shape to hour-glass.

  // replace submit button with waiting gif
  var tmpObj = document.createElement("div");
  tmpObj.innerHTML = '<img id="waitGIF" src="./pics/zahnrad.gif" alt="loading">';
  var butObj = document.getElementById('send');
  var objParent = butObj.parentNode;
  objParent.replaceChild(tmpObj,butObj);


  //Generate key pair with sjcl
  var pair = sjcl.ecc.elGamal.generateKeys(sjcl.ecc.curves.k256);
  var pub = pair.pub.get();
  var sec = pair.sec.get();

  //Serializing key elements
  var pub_s = sjcl.codec.base64.fromBits(pub.x.concat(pub.y));
  var sec_s = sjcl.codec.base64.fromBits(sec);

  //Mask for transmission
  pub_s = encodeURI(encodeURIComponent(pub_s));
  sec_s = encodeURI(encodeURIComponent(sec_s));

  //Writing sec key to local file
  var filename = 'reply-rsa.key';
  download(filename, sec_s);

  //Trigger message encryption
  mEncryption(to, c, pub_s);
}

/**
 * Message sending step 2: Encrypt and trigger transmission
 *
 */
function mEncryption(to, c, rpub, frm) {
  // Pack text and attachment into JSON
  var oc = {
    txt:c,
    attach:window.gAttachment,
    fname:window.gFilename,
    from:frm
  };
  window.gAttachment = null;
  window.gFilename = null;
  var jc = JSON.stringify(oc);
  sessionStorage.jc = jc;
  sessionStorage.to = to;
  sessionStorage.rpub = rpub;
  sessionStorage.frm = frm;

  con('get_pub.php', {to:to}, cbAfter_mEnc_pub, true);
} function cbAfter_mEnc_pub(th){

  //Unmask back from utf-8 to string
  spub = decodeURIComponent(decodeURI(th[0].PubKey));

  //Deserialize public key
  var pub = new sjcl.ecc.elGamal.publicKey(
    sjcl.ecc.curves.k256,
    sjcl.codec.base64.toBits(spub)
  );

  //Encode message
  var jc = sessionStorage.jc;
  sessionStorage.jc = null;
  var ec = sjcl.encrypt(pub, jc);

  //Trigger transmission to server
  mTransmit(sessionStorage.to, ec, sessionStorage.rpub, sessionStorage.frm);
}

 /**
  * Message sending step 3: Transmit encoded message to server
  *
  */
function mTransmit(to, e_c, rpub, frm, mode) {

  // Generate MsgID for this message
  var id = Math.floor(Math.random() * 1000000000); //TODO ensure uniquness

  // Generate MsgID for response
  var rid = Math.floor(Math.random() * 1000000000); //TODO ensure uniquness

  // For anonymous sending use MsgID of response as link
  var link = rid;
  // But not link if send out of inbox
  if (frm !== null && frm !== 'undefined') {
    link = null;
  }

  e_c = encodeURI(encodeURIComponent(e_c));

  sessionStorage.mode = mode;

  con('write.php', {id:id, to:to, c:e_c, pub:rpub, link:link}, cbAfter_mT_write, true);
}
function cbAfter_mT_write(resp){
    if(resp.rcode == 0) {
      document.getElementById('inf').innerHTML = resp.msg;
        switch (window.location.pathname) {
          case '/send.php':
            // clear screen
            document.getElementById('err').innerHTML = '';
            document.getElementById('out').innerHTML = '';
            document.getElementById("theForm").reset();
            document.getElementById("theForm").style.visibility = "hidden";
            document.getElementById("theForm").style.height = "1px";
            // Display response link
            var lnkout = 'http://'+ window.location.host +'/reply.php?' + resp.lnk;
            document.getElementById('inf').innerHTML += '<br><br>A response will be available here:<br> '
                                                     + '<b><a href="' + lnkout + '">' + lnkout + '</a></b>';
            break;
          case '/inbox.php':
          document.getElementById('out').innerHTML = '';
          document.getElementById('err').innerHTML = '';
            // Change state of orig msg to REPLYED
            if (sessionStorage.mode == 'rpl') {
              setMsgState(sessionStorage.mid2dc, 'REPLYED');
            }
        }
        //document.getElementById('inf').focus();

      }else{
        document.getElementById('inf').innerHTML = '';
        // remove waiting gif
/*        var gifObj = document.getElementById('waitGIF');
        var parentObj = gifObj.parentNode;
        parentObj.removeChild(gifObj);
*/
        // display error message
        document.getElementById('err').innerHTML = 'mEncrypt > write.php: ' + resp.msg;
    }
    window.document.body.style.cursor = "auto"; // reset cursor after waiting.
    document.body.scrollTop = 0; // For Chrome, Safari and Opera
    document.documentElement.scrollTop = 0; // For IE and Firefox
}


/*
 * Get valid addresses for datalist 'adds'
 */
function getToList() {
  con('get_adrlist.php', {}, cbAfter_get_adrlist, true);
}
function cbAfter_get_adrlist(jadds){
  sessionStorage.toList = JSON.stringify(jadds);
  var options = '';
  for (i = 0; i < jadds.length; i++) {
    if(jadds[i].Visible == 1){
      options += '<option value="' + jadds[i].Address + '" />';
    }
  }
  document.getElementById('adds').innerHTML = options;
}

/*
*
*/
function signSubmit(adr, pwd, eml, vis, agb, pay, prc, typ){
  // Generate BoxID
  var id = Math.floor(Math.random() * 1000000000); //TODO ensure uniquness

  // Hash password
  var bitArray = sjcl.hash.sha256.hash(pwd);
  var pwd_h = sjcl.codec.hex.fromBits(bitArray);

  // Generate key pair with sjcl
  var pair = sjcl.ecc.elGamal.generateKeys(sjcl.ecc.curves.k256);
  var pub = pair.pub.get();
  var sec = pair.sec.get();

  // Serializing key elements
  var pub_s = sjcl.codec.base64.fromBits(pub.x.concat(pub.y));
  var sec_s = sjcl.codec.base64.fromBits(sec);

  // Mask for transmission
  pub_s = encodeURI(encodeURIComponent(pub_s));
  sec_s = encodeURI(encodeURIComponent(sec_s));

  // Writing sec key to local file
  var filename = adr + '-rsa.key';
  download(filename, sec_s);

  // Init PaidUntil
    var d = new Date();
    var s = d.toISOString();
    var unt = s.substring(0, 9); // YYYY-MM-DD only

  // Make bool to int
  vis = vis ? 1 : 0;
  agb = agb ? 1 : 0;

  con('write_signup.php', {id:id, adr:adr, pub:pub_s, pw:pwd_h, eml:eml, vis:vis, pay:pay, prc:prc, typ:typ, unt:unt}, cbAfter_write_signup, true);
} function cbAfter_write_signup(resp){

  document.body.scrollTop = 0; // For Chrome, Safari and Opera
  document.documentElement.scrollTop = 0; // For IE and Firefox

  if(resp.rcode == 0) {
    switch (resp.pay) {
      case 'paypal':
        document.getElementById('paypal_button').style.display = 'block';
//        break;
      default:
        document.getElementById('err').innerHTML = '';
        document.getElementById('inf').innerHTML = resp.msg;
        document.getElementById('theForm').style.visibility = 'hidden';
        document.getElementById('theForm').style.height = '1px';
    }
  }else{
    document.getElementById('inf').innerHTML = '';
    document.getElementById('err').innerHTML = resp.msg;
  }
}

/*
  //Send request to server
  xhttp.open('POST', 'write_signup.php', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
  xhttp.send("id=" + id + "&adr=" + adr + "&pub=" + pub_s + "&pw=" + pwd_h + "&eml=" + eml);
*/

/**
*
*/
function loginSubmit(adr, pwd){
  sessionStorage.p_adr = adr;
  con('check_login.php', {adr:adr, pw:pwd}, cbAfter_check_login, true);
}
function cbAfter_check_login(resp){
  if (resp.rcode == 0){
    window.location.assign('inbox.php');
    sessionStorage.typ = resp.typ;
    console.log('sessionStorage.typ: ' + sessionStorage.typ);
  } else {
    document.getElementById('inf').innerHTML = '';
    document.getElementById('err').innerHTML = 'Login failed.';
  }
}

/**
*
*/
function getInboxData(){
  con('get_inbox.php', {to:sessionStorage.p_adr}, cbAfter_inbox, true);
}
function cbAfter_inbox(resp){
  sessionStorage.myPub = resp[0].PubKey;
  sessionStorage.myEmail = resp[0].Email;
  sessionStorage.myVisible = resp[0].Visible;
  sessionStorage.myPayment = resp[0].Payment;
  sessionStorage.myPayment = resp[0].Payment;
  sessionStorage.myPrice = resp[0].Price;
  sessionStorage.myPaidUntil = resp[0].PaidUntil;
  sessionStorage.myMsgLife = resp[0].MsgLife;
}

/**
*
*/
function fetchMsgs(adr){
  //Clear screen
  document.getElementById('fileup').innerHTML = '';
  document.getElementById('out').innerHTML = '';
  document.getElementById('err').innerHTML = '';
  document.getElementById('inf').innerHTML = '';

  con("get_msglist.php", {to:adr}, cbAfter_get_msglist, true);
}
function cbAfter_get_msglist(resp){

  if ( resp.length != 0 ){
    var msglist = '<table class="msglst" id="mtable"><thead><tr><th>MsgID</th><th>UTC +0000</th><th>State</th></tr></thead><tbody>';
    for (i = 0; i < resp.length; i++) {
      msglist += '<tr class="msglst"><td class="msglst">' + resp[i].MsgID + '</td><td class="msglst">' + resp[i].Date + '</td><td class="msglst">' + resp[i].State + '</td></tr>';
    }
    msglist += '</tbody></table>';
    document.getElementById('out').innerHTML = msglist;
    var mtable = document.getElementById('mtable');
    var mrows = mtable.getElementsByTagName("tr");
    for (i = 0; i < mrows.length; i++) {
      mrows[i].onclick = function(){
        loadMsg(this.cells[0].innerHTML);
      };
    }
  }else{
    //TODO consolidate in a 'clearScreen()'
    document.getElementById('err').innerHTML = '';
    document.getElementById('out').innerHTML = '';
    document.getElementById('inf').innerHTML = 'No messages.';
  }
  checkPremium();
}

/**
*
*/
function loadMsg(mid){
  window.document.body.style.cursor = "wait"; // sets the cursor shape to hour-glass.
  sessionStorage.mid2dc = mid;
  con("get_msg.php", {mid:mid}, cbAfter_get_msg, true);
} function cbAfter_get_msg(resp){
  var mid = sessionStorage.mid2dc;
  window.gmsg2dc = resp[0].Content;
  sessionStorage.state2dc = resp[0].State;
  //TODO pull ReturnPubKey and ReturnLink from below to sessionStorage

  var out = '<button type="button" class="button" onclick="delMsg(' + mid + ')">Delete Message</button><br>'
          + '<p><b>Message ID:</b> ' + mid + '</p>'
          + '<p><b>Received (UTC +0):</b> ' + resp[0].Date + '</p>'
          + '<p hidden id="ReturnPubKey">' + resp[0].ReturnPubKey + '</p>'
          + '<p hidden id="ReturnLink">' + resp[0].ReturnLink + '</p>';

  document.getElementById('fileup').innerHTML = '<br>Please select your key file to decode the message content:'
                                              + '<br><input type="file" class="button" id="keyFile" size="50" onchange="decodeMsg()">';
  //                                                  + '<button id="loadKey" type=“button” class="button" onclick="decodeMsg()">Decode</button>';
  document.getElementById('out').innerHTML = out;
  //document.getElementById('keyFile').focus();
  window.document.body.style.cursor = "auto"; // reset cursor after waiting.
  checkPremium();
}


/*
*
*/
function decodeMsg(){
  window.document.body.style.cursor = "wait"; // sets the cursor shape to hour-glass.
  var file = document.getElementById("keyFile").files[0];
  var reader = new FileReader();

  //Process after file is loaded
  reader.onload = function(e) {
    var msg2decode = window.gmsg2dc;
    var t_text = reader.result;
    t_text = decodeURIComponent(decodeURI(t_text));
    document.getElementById('inf').innerHTML = '';
    document.getElementById('err').innerHTML = '';

    try {
        //Unserialize secure key
        var sec = new sjcl.ecc.elGamal.secretKey(
            sjcl.ecc.curves.k256,
            sjcl.ecc.curves.k256.field.fromBits(sjcl.codec.base64.toBits(t_text))
        );

        // Unmask
        msg2decode = decodeURIComponent(decodeURI(msg2decode));

        // Decode message
        var dc = sjcl.decrypt(sec, msg2decode);

        // Pack text and attachment into JSON
        var oc = JSON.parse(dc);
        var from = oc.from;
        var msg = oc.txt;
        var attach = oc.attach;
        var fname = oc.fname;
        window.gAttachment = attach;
        window.gFilename = fname;

        // Make new lines HTML ready
        msg = msg.replace(/\n/gi,"<br>");

        // Display 'from' if not anonymous
        if (from !== null && from !== undefined) {
          document.getElementById('out').innerHTML += '<b>From:</b><br><div class="att">' + from + '#postilotta.org</div><br>';
          // Set 'from' as reply to
          document.getElementById('ReturnLink').innerHTML = from;
        }

        // Append decoded message to display
        document.getElementById('out').innerHTML += '<b>Message:</b><br><div class="msg">' + msg + '</div>';

        // Download option if attachment was included
        if (attach !== null && attach !== undefined) {
          document.getElementById('out').innerHTML += '<br><b>Attachment:</b><br><div class="att"><a href="javascript:downAttach();">' + fname + '</a></div>';
        }

        // Mark as read
        if (sessionStorage.state2dc === 'NEW'){
          setMsgState(sessionStorage.mid2dc, 'READ');
        }

        // Add reply button only within inboxes
        if (window.location.pathname === '/inbox.php' && sessionStorage.state2dc != 'REPLYED'){
          document.getElementById('out').innerHTML += '<br><p><button id="reButton" type="button" class="button" onclick="reply()">Reply</button><p>';
        }
    }
    catch(err) {
      document.getElementById('inf').innerHTML = '';
      document.getElementById('err').innerHTML = 'Decryption failed.<br>' + err.message;
    }
    finally {
      // Clear file upload section
      document.getElementById('fileup').innerHTML = '';
      window.document.body.style.cursor = "auto"; // reset cursor after waiting.
      checkPremium();
    }
  };

  // Start loading file
  var out = reader.readAsText(file);
}

/*
* Save sec key file to device
*/
function download(filename, text) {
  var os = getOS();
  switch (os) {
    case 'iOS':
      var uriContent = "data:application/octet-stream," + text;
      var newWindow = window.open(uriContent, filename);
      break;
    default:
      var element = document.createElement('a');
      element.setAttribute('href', 'data:text/plain;charset=utf-8,' + text);
      element.setAttribute('download', filename);
      element.style.display = 'none';
      document.body.appendChild(element);
      element.click();
      document.body.removeChild(element);
    }
}

/**
*
*/
function downAttach(){
  // Get Attachment out of sessionStorage
  var filename = window.gFilename;
  window.gFilename = null;
  var data = window.gAttachment;
  window.gAttachment = null;

  // Trigger Fie Download
  var os = getOS();
  switch (os) {
    case 'iOS':
      var uriContent = data;
      var newWindow = window.open(uriContent, filename);
      break;
    default:
      var element = document.createElement('a');
      element.setAttribute('href', data);
      element.setAttribute('download', filename);
      element.style.display = 'none';
      document.body.appendChild(element);
      element.click();
      document.body.removeChild(element);
    }
}


/**
*
*/
function reply(){
  var but = document.getElementById('reButton')
  but.parentNode.removeChild(but);
  var repl = '<form id="reForm">'
     + 'Reply:<br>'
     + '<textarea id="p_text" cols="40" rows="10"></textarea>'
     + '<br><br>'
     + '<button type="button" class="button" onclick="rEncryption()">Submit</button>'
  + '</form>';

  document.getElementById('out').innerHTML += repl;
  document.forms["reForm"]["p_text"].focus();
  checkPremium();
}

/**
 * Message sending step 2: Encrypt reply and trigger transmission
 *
 */
function rEncryption() {
  var ReturnLink = document.getElementById('ReturnLink').innerHTML;
  var ReturnPubKey = document.getElementById('ReturnPubKey').innerHTML;
  var c = document.forms["reForm"]["p_text"].value;

  // Mask back from utf-8 to string
  spub = decodeURIComponent(decodeURI(ReturnPubKey));

  // Deserialize public key
  var pub = new sjcl.ecc.elGamal.publicKey(
      sjcl.ecc.curves.k256,
      sjcl.codec.base64.toBits(spub)
  );

  // Pack text and attachment into JSON
  var oc = {
    from:sessionStorage.p_adr,
    txt:c,
    attach:null,
    fname:null
  };
  var jc = JSON.stringify(oc);

  //Encode message
  var ec = sjcl.encrypt(pub, jc);

  //Trigger transmission to server
  mTransmit(ReturnLink, ec, sessionStorage.myPub, sessionStorage.p_adr, 'rpl');
}


/**
* Get MsgID of reply and trigger loading
*/
function loadRpl(lnk){
con('get_msglist.php', {to:lnk}, cbAfter_repl_list, true);
} function cbAfter_repl_list(resp){

  if (resp[0]){
    loadMsg(resp[0].MsgID);
  }else{
    document.getElementById('out').innerHTML += '<br>No response available (yet).<br>'
  }
}

/**
*
*/
function loadSendForm(){
  // Clear inbox screen
  document.getElementById('err').innerHTML = '';
  document.getElementById('inf').innerHTML = '';
  document.getElementById('fileup').innerHTML = '';
  document.getElementById('out').innerHTML ='';

  var xhttp = new XMLHttpRequest();
  // prepare response and callback
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('out').innerHTML = this.responseText;
      getToList();
      checkPremium();
    }
  };
  //Send msg state change request to server
  xhttp.open('POST', 'module-send-form.htm', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send();
}


/**
*
*/
function sendNewMessage(){
  var i_to = document.forms["sendInbox"]["p_to"];
  var i_c = document.forms["sendInbox"]["p_text"];
  // Use own pub key. User own address as 'from'.
  var abs = sessionStorage.p_adr;
  var pub =  sessionStorage.myPub;
  mEncryption(i_to.value, i_c.value, pub, abs);
}


/*
*
*/
function delMsg(mid){
  //Clear file upload section
  document.getElementById('fileup').innerHTML = '';
  con('del_msg.php', {mid:mid}, cbAfter_delM, true);
} function cbAfter_delM(resp){
  document.body.scrollTop = 0; // For Chrome, Safari and Opera
  document.documentElement.scrollTop = 0; // For IE and Firefox
  if (resp.rcode == 0){
    document.getElementById('out').innerHTML = '';
    document.getElementById('inf').innerHTML = 'Message deleted.';
  } else {
    document.getElementById('inf').innerHTML = '';
    document.getElementById('err').innerHTML = 'Action failed:' + resp.msg;
  }
}


/**
*
*/
function logOut(){
  //TODO Proper cleanup at Logout
  sessionStorage.clear();
  con('logout.php', {}, null, true);
  location.replace("index.php");
}

function confirmBlow(adr){
  var pass = prompt('This will delete the whole inbox incl. all related messages of \
                    \n' + adr  + '#postilotta.org and jump ringt to postilotta home. \
                    \nPlease enter your password again to confirm:');
  if (pass != null){
    sessionStorage.clear();
    // Hash password
    var bitArray = sjcl.hash.sha256.hash(pass);
    var pwd_h = sjcl.codec.hex.fromBits(bitArray);
    blowUp(adr, pwd_h);
  }
}

/**
* Delete inbox currently logged on to
*/
function blowUp(adr, pwd){
  con('del_inbox.php', {adr:adr, pw:pwd}, cbAfter_blow, true);
} function cbAfter_blow(resp){

  if (resp.rcode == 0){
    location.replace("index.php");
  } else{
    document.getElementById('inf').innerHTML = '';
    document.getElementById('err').innerHTML = 'Destruction failed.';
  }
}

/**
*
*/
function setMsgState(mid, state){
  con('set_msgstate.php', {state:state, mid:mid}, cbAfter_msgstate, true);
}
function cbAfter_msgstate(resp) {
  if (resp.rcode != 0){
    document.getElementById('inf').innerHTML = '';
    document.getElementById('err').innerHTML = resp.msg;

  }
}

/**
*
*/
function getOS() {
  var userAgent = window.navigator.userAgent,
      platform = window.navigator.platform,
      macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
      windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
      iosPlatforms = ['iPhone', 'iPad', 'iPod'],
      os = null;

  if (macosPlatforms.indexOf(platform) !== -1) {
    os = 'Mac OS';
  } else if (iosPlatforms.indexOf(platform) !== -1) {
    os = 'iOS';
  } else if (windowsPlatforms.indexOf(platform) !== -1) {
    os = 'Windows';
  } else if (/Android/.test(userAgent)) {
    os = 'Android';
  } else if (!os && /Linux/.test(platform)) {
    os = 'Linux';
  }

  return os;
}
/*---------- AJAX con ----------------------*/
/**
* Wrapper for AJAX post calls
* @param target: String target address to post to
* @param params: object containing the parameters to pass
* @param callback: function name to call after response, passing response 'this'
* @param enc: false for raw response (no json, or paranoia)
* @param ctype: string [optional] Content-type, default is application/x-www-form-urlencoded
* Example call see below
* ParanoiaLink incl.
*/
function con(target, params, callback, enc, ctype){
  var xhttp = new XMLHttpRequest();
  var paramStr = '';
  if (!ctype){
    ctype = 'application/x-www-form-urlencoded';
  }

  // prepare response and callback
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (callback){
        var resp = this.responseText;
        console.log('RESPONSE (raw): ' + JSON.stringify(resp));

        //decode if paranoia
        if((sessionStorage.paranoiaLink != undefined) && (sessionStorage.paranoiaLink != 'undefined')){
          resp = decrytParaResp(resp);
          console.log('RESPONSE (dec): ' + JSON.stringify(resp));
        }

        resp = JSON.parse(resp);
        callback(resp);
      }
    }
  };

  if((sessionStorage.paranoiaLink != undefined) && (sessionStorage.paranoiaLink != 'undefined')){
    var lnkout = 'https://' + window.location.host + '/'+ target;
    params['target'] = lnkout;
    target = 'dontknow.php';

    for (var key in params) {
      if (params.hasOwnProperty(key)) {
        params[key] = encryParaReq(params[key]);
      }
    }

    //params = encryParaReq(params);            // paranoia encryption
    paramStr = JSON.stringify(params);
    ctype = 'Content-type:application/json';
  }else{
    // generate parameter string to post
    for (var key in params) {
      if (params.hasOwnProperty(key)) {
        paramStr += key + '=' + params[key] + '&';
      }
    }
  // remove last &
  paramStr = paramStr.slice(0,paramStr.length -1);
  }

  console.log('REQUEST:' + target + '?' + paramStr);

  //Send msg state change request to server
  xhttp.open("POST", target, true);
  xhttp.setRequestHeader("Content-type", ctype);
  xhttp.send(paramStr);
}
/*
// Excample call of con
con('get_msglist.php', {to:'9'}, cbAfter_Go);
function cbAfter_Go(th){
  console.log(th.responseText);
}
*/

/*---------- Paranoia Mode ----------------------*/

/**
*
*/
function prepareParanoia(pf, ww){
//  var encrypted = CryptoJS.AES.encrypt(JSON.stringify(ww), pf, {format: CryptoJSAesJson}).toString();
//  document.getElementById('out').innerHTML += encrypted;
  con('write_para.php', {pf:pf, ww:ww}, cbAfter_write_para, true);
} function cbAfter_write_para(resp){

  document.getElementById("out").innerHTML = resp.msg;
  if(resp.rcode == 0) {
      // Clear screen
      document.getElementById("theForm").style.visibility = "hidden";
      document.getElementById("theForm").style.height = "1px";
      document.getElementById('inf').innerHTML = '';
      document.getElementById('err').innerHTML = '';
      // Display Backdoor-Link
      document.getElementById("out").style.color = '#6495ED';
      var lnkout = 'http://'+ window.location.host +'/backdoor.php?' + resp.plink;
      document.getElementById("out").innerHTML += '<br><br>Access via Backdoor-Link: <br>'
                                              + '<a href="' + lnkout + '">' + lnkout + '</a>';

      // Generate QR-Code
      var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: lnkout,
        width: 120,
        height: 120,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
      });
      // Make QR-Code element visible
      document.getElementById('qrcode').style.display = 'inline-block';
      document.getElementById('qrcode-href').href = lnkout;

    }else{
      document.getElementById('inf').innerHTML = '';
      document.getElementById('err').innerHTML = 'Paranoia preparation failed.';
    }
}

function activateParanoia(pf){
  try{
    // Decode watchword with given passphrase
    var encrypted = document.getElementById('hash').innerHTML;
    var passphrase = document.getElementById('p_pf').value;
    var decrypted = JSON.parse(CryptoJS.AES.decrypt(encrypted, passphrase, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));

    // Set passphrase and backdoor-link to session, for communication encryption
    sessionStorage.paranoiaPWD = passphrase;
    // Set backdoor-link without leading ? to session
    sessionStorage.paranoiaLink = location.search.slice(1,location.search.length);

    // clear screen
    document.getElementById('err').innerHTML = '';
    document.getElementById('inf').innerHTML = '';
    document.getElementById('theForm').reset();
    document.getElementById('theForm').style.visibility = 'hidden';
    document.getElementById('theForm').style.height = '1px';
    // Display watchword
    document.getElementById('out').innerHTML = '<b>Watchword:</b><br><div class="att" style="color:black">' + decrypted + '</div><br>'
                                             + '<br><div style="color:#ecd201">AES session encryption activated.<br>'
                                             + 'Use navigation bar above or replace url by reply-link to proceed.</div>';
  }catch(e){
    document.getElementById('inf').innerHTML = '';
    document.getElementById('out').innerHTML = '';
    document.getElementById('err').innerHTML = 'Unable to decrypt watchword package.<br> Activating paranoia mode failed.<br>'
                                             + 'Are you sure about the passphrase?<br>Consider starting over.';
  }
  checkParaOn();
}

function checkParaOn(){
  var pp = sessionStorage.paranoiaPWD;
  console.log(JSON.stringify(sessionStorage));

  if (pp !== null && pp != "undefined" && pp !== undefined){
    document.body.classList.add('para');
    document.getElementById('logo').src = 'pics/schwarzerumschlag_p_96.jpg';
    document.getElementById('tn-li-para').innerHTML = ' Turn ExtraSecure Off';
    document.getElementById('tn-li-para').className = 'paraoff';
    document.getElementById('tn-li-para').href = 'index.php';
    document.getElementById('tn-li-para').parentNode.onclick = logOut;
  }
}

/**
*
*/
function decrytParaResp(resp){
  // paranoia Decryption
  resp = JSON.parse(CryptoJS.AES.decrypt(resp, sessionStorage.paranoiaPWD, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));
  return resp;
}

/**
*
*/
function encryParaReq(req){
  req  = CryptoJS.AES.encrypt(JSON.stringify(req), sessionStorage.paranoiaPWD, {format: CryptoJSAesJson}).toString();
  return req;
}

/**
*
*/
function clearSessionSoft(){
  var tmp = sessionStorage.paranoiaPWD;
  var tmpLink = sessionStorage.paranoiaLink;
  sessionStorage.clear();
  sessionStorage.paranoiaPWD = tmp;
  sessionStorage.paranoiaLink = tmpLink;
}


/*--------------- Display Functions--------------------*/
function replaceNav(){
  var x = document.getElementById("monnav");
  if (x.className.indexOf("show") == -1) {
    x.className += " show";
  } else {
    x.className = x.className.replace(" show", "");
  }
}




/*---------- cryptojs specifics ----------------------*/

var CryptoJSAesJson = {
    stringify: function (cipherParams) {
        var j = {ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)};
        if (cipherParams.iv) j.iv = cipherParams.iv.toString();
        if (cipherParams.salt) j.s = cipherParams.salt.toString();
        return JSON.stringify(j);
    },
    parse: function (jsonStr) {
        var j = JSON.parse(jsonStr);
        var cipherParams = CryptoJS.lib.CipherParams.create({ciphertext: CryptoJS.enc.Base64.parse(j.ct)});
        if (j.iv) cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv)
        if (j.s) cipherParams.salt = CryptoJS.enc.Hex.parse(j.s)
        return cipherParams;
    }
}

/*---------- Premium ----------------------*/

function checkPremium() {
  if(sessionStorage.typ =='premium'){
    var elements = document.getElementsByClassName('button');
    for (var i = 0; i < elements.length; i++) {
        elements[i].style.backgroundColor='#A9BCF5';
    }
    document.getElementById('tn-li-login').style.backgroundColor='#A9BCF5';
    document.getElementById('typ').src = './pics/premium.png';

  }
}

function searchItem(item){
  return item.Address == this;
}
function adrSelect(th){
  var a_toList = JSON.parse(sessionStorage.toList);
  var selected = a_toList.find(searchItem, th.value);
  switch (selected.Type) {
    case 'premium':
      document.getElementById('adr-typ').src = './pics/premium_25.png';
      break;
    case 'basic':
      document.getElementById('adr-typ').src = './pics/basic_25.png';
    break;
    default:
  }
}

/*-------------- Settings ----------------------*/

function loadSettings(){
  // Clear inbox screen
  document.getElementById('err').innerHTML = '';
  document.getElementById('inf').innerHTML = '';
  document.getElementById('fileup').innerHTML = '';
  document.getElementById('out').innerHTML ='';

  var xhttp = new XMLHttpRequest();
  // prepare response and callback
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('out').innerHTML = this.responseText;
      checkPremium();
      document.forms["settings"]["p_mail"].value = sessionStorage.myEmail;
      if (sessionStorage.myVisible) {document.forms["settings"]["p_visible"].checked = true;}
      document.getElementById('p_typ').innerHTML = sessionStorage.typ;
      document.getElementById('p_pay').innerHTML = sessionStorage.myPayment;
      document.getElementById('p_price').innerHTML = sessionStorage.myPrice + ' EUR per Month';
      document.getElementById('p_until').innerHTML = 'settled until: ' + sessionStorage.myPaidUntil;
      document.forms["settings"]["p_msglife"].value = parseInt(sessionStorage.myMsgLife);

    }
  };
  //Send msg state change request to server
  xhttp.open('POST', 'module-inbox-settings.htm', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send();
}
