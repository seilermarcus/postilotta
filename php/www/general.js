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
* Load attachment in sessionStorage resp. window space
* File is stored in window.attachfile, file name in sessionStorage.gFilename ready for encoding
* @return none
*/
 function upAttach(){
   var ln = JSON.parse(sessionStorage.ln);          // prepare for language lables
   var file = document.getElementById('attach').files[0];
   var reader = new FileReader();

   //Process after file is loaded
   reader.onload = function(e) {

     window.attachfile = reader.result;

     if (window.attachfile.length < 7000000){  // less than 5MB
       var fname = document.getElementById('attach').value;
       fname = fname.substring(fname.lastIndexOf('\\') + 1, fname.length);
       sessionStorage.gFilename = fname;
       document.getElementById('attach').style.visibility = 'hidden';
       document.getElementById('attReady').innerHTML = '<i>' + fname + '</i> ready for sending.';
      } else {
       document.getElementById('inf').innerHTML = '';
       document.getElementById('attReady').innerHTML = '<div style="color:red;">'+ ln['att_oversize'] +'</div>';
       window.attachfile = null;
      }
   };

   // Start loading file
   var out = reader.readAsDataURL(file);
 }

 /**
  * Sending Step 1:
  * Generate Return Key, etc. and trigger mEncryption.
  *
  * @param {string} to - Recipients 'Address'. Just passed through.
  * @param {string} c - Content (message without attachment) to trasnsmit. Just passed through.
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
 * Sending Step 2:
 * Encryption and triggering of mTransmission.
 * Pulls attachment out of window.attachfile.
 * Requests recipients pub key from server.
 *
 * @param {string} to - Recipients 'Address'. Just passed through.
 * @param {string} c - Content (message without attachment) to trasnsmit. Just passed through.
 * @param {string} rpub - Pub Key to encrypt a reply with. Just passed through.
 * @param {string} [frm] - Sender 'Address'.
 */
function mEncryption(to, c, rpub, frm) {
  // Pack text and attachment into JSON
  var oc = {
    txt:c,
    attach:window.attachfile,
    fname:sessionStorage.gFilename,
    from:frm
  };
  sessionStorage.gFilename = null;
  var jc = JSON.stringify(oc);
  window.attachfile = jc;
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
  var jc = window.attachfile;
  window.attachfile = null;
  var ec = sjcl.encrypt(pub, jc);

  //Trigger transmission to server
  mTransmit(sessionStorage.to, ec, sessionStorage.rpub, sessionStorage.frm);
}

 /**
  * Sending Step 3:
  * Transmit encoded message to server.
  * Display success info or error message.
  *
  * @param {string} to - Recipients 'Address'.
  * @param {string} e_c - Encrypted content (message incl. attachment as cipher) to trasnsmit.
  * @param {string} rpub - Pub Key to encrypt a reply with.
  * @param {string} frm - Sender 'Address'.
  * @param {string} mode - Sending mode like 'rpl' for Reply, for msg state mgmt purpose.
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

  // Set msg expire period
  var exp ='';
  if ( (sessionStorage.getItem("myMsgLife") !== null) && (sessionStorage.typ == 'premium') ) {
    exp = sessionStorage.myMsgLife;
  }
  sessionStorage.mode = mode; // e.g. 'rpl', store tmp for cbAfter
  con('write.php', {id:id, to:to, c:e_c, pub:rpub, link:link, exp:exp}, cbAfter_mT_write, true);
} function cbAfter_mT_write(resp){
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
            // Add response link to display
            document.getElementById('inf').innerHTML += resp.lnktxt;
            var lnkout = 'http://'+ window.location.host +'/reply.php?' + resp.lnk;
            document.getElementById('inf').innerHTML += '<b><a href="' + lnkout + '">' + lnkout + '</a></b>';
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


/**
 * Get list of all valid, not-hidden addresses from server.
 * Populate datalist 'adds' with content.
 *
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

/**
 * Get list of all addresses from server.
 * Write to sessionStorage.
 *
 */
function getAdrNameList() {
  con('get_adrlist.php', {}, cbAfter_get_adrNamelist, true);
}
function cbAfter_get_adrNamelist(jadds){
  sessionStorage.toList = JSON.stringify(jadds);
}

/**
 * Generate new inbox.
 *
 * @param {string} adr - 'Address' of new inbox.
 * @param {string} pwd - 'Password'.
 * @param {string} eml - 'Email'
 * @param {bool} vis - 'Visible', checkbox value.
 * @param {bool} agb - accept the terms&conditions, checkbox value.
 * @param {string} pay - 'Payment', radiobox selection.
 * @param {int} prc - 'Price'. Monthly contribution.
 * @param {string} typ - 'Type', like 'premium'.
 */
function signSubmit(adr, pwd, eml, vis, agb, pay, prc, typ){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for multilanguage usage
  // form  validation
  document.getElementById('err').innerHTML = '';
  var valid = true;
  if (adr == ''){
    document.getElementById('err').innerHTML += '&#216; &#8594; ' + ln['mis-adr'] + '<br>';
    document.getElementById('p_name').style.borderColor = 'red';
    valid = false;
  }else{
      document.getElementById('p_name').style.borderColor = 'initial';
  }
  if (pwd == ''){
    document.getElementById('err').innerHTML += '&#216; &#8594; ' + ln['mis-pwd'] + '<br>';
    document.getElementById('p_pwd').style.borderColor = 'red';
    valid = false;
  }
  else{
      document.getElementById('p_pwd').style.borderColor = 'initial';
  }

  if (agb == ''){
    document.getElementById('err').innerHTML += '&#216; &#8594; ' + ln['mis-agb'] + '<br>';
    document.getElementById('l_agb').style.color = 'red';
    valid = false;
  }else{
      document.getElementById('l_agb').style.color = 'initial';
  }
  var alist = JSON.parse(sessionStorage.toList);
  var exist = alist.find(searchItem, document.getElementById('p_name').value)
  if(exist != undefined){
    document.getElementById('err').innerHTML += '&#216; &#8594; ' + ln['taken'] + '<br>';
    document.getElementById('p_name').style.borderColor = 'red';
    valid = false;
  }
  // premium form validation
  if (typ == 'premium'){
    if(prc == ''){
      document.getElementById('err').innerHTML += '&#216; &#8594; ' + ln['mis-price'] + '<br>';
      document.getElementById('p_price').style.borderColor = 'red';
      valid = false;
    }
    if(pay == ''){
      document.getElementById('err').innerHTML += '&#216; &#8594; ' + ln['mis-pay'] + '<br>';
      document.getElementById('paytype').style.color = 'red';
      valid = false;
    }
  }

  if (!valid) {return;}

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
  var unt = s.substring(0, 10); // YYYY-MM-DD only

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
        document.forms['paypal_button']['a3'].value = resp.prc + '.00';
        document.forms['paypal_button']['item_name'].value = resp.adr + '#postilotta.org';
        document.getElementById('paypal_button').style.display = 'block';
//        break;
      default:
        document.getElementById('err').innerHTML = '';
        document.getElementById('inf').innerHTML = resp.msg;
        document.getElementById('theForm').style.visibility = 'hidden';
        document.getElementById('theForm').style.height = '1px';
        var c2 = document.getElementById('container-2');
        if(c2 != null){
            c2.parentNode.removeChild(c2);
        }
        var c3 = document.getElementById('container-3');
        if(c3 != null){
          c3.parentNode.removeChild(c3);
        }
    }
  }else{
    document.getElementById('inf').innerHTML = '';
    document.getElementById('err').innerHTML = resp.msg;
  }
}

/**
 * Check address and password for login to inbox.
 * Forward to inbox if successfully verified.
 *
 * @param {string} adr - 'Address' of inbox to log into.
 * @param {string} pwd - Password, clear.
 */
function loginSubmit(adr, pwd){
  sessionStorage.p_adr = adr;
  // Hash password
  var bitArray = sjcl.hash.sha256.hash(pwd);
  var h_pwd = sjcl.codec.hex.fromBits(bitArray);
  sessionStorage.p_pwd = h_pwd;
  con('check_login.php', {adr:adr, pw:h_pwd}, cbAfter_check_login, true);
}
function cbAfter_check_login(resp){
  if (resp.rcode == 0){
    window.location.assign('inbox.php');
    sessionStorage.typ = resp.typ;
  } else {
    document.getElementById('inf').innerHTML = '';
    document.getElementById('err').innerHTML = 'Login failed.';
  }
}

/**
 * Fetch inbox profile date like ‘Email’ and ‘Visible’.
 * Write it to sessionStorage.
 *
 */
function getInboxData(){
  con('get_inbox.php', {to:sessionStorage.p_adr}, cbAfter_inbox, true);
} function cbAfter_inbox(resp){
  sessionStorage.myPub = resp[0].PubKey;
  sessionStorage.myEmail = resp[0].Email;
  sessionStorage.myVisible = resp[0].Visible;
  sessionStorage.myPayment = resp[0].Payment;
  sessionStorage.myPayment = resp[0].Payment;
  sessionStorage.myPrice = resp[0].Price;
  sessionStorage.myPaidUntil = resp[0].PaidUntil;
  sessionStorage.myMsgLife = resp[0].MsgLife;
  sessionStorage.myIdVerified = resp[0].IdVerified;
  checkPremium(); // color and logo
  checkVerified(); // logo
}

/**
 * Fetch headers of all messages sent to an Address from server.
 * Display list as clickable table in inbox.
 *
 * @param {string} adr - 'Address' of recipient.
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
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables // prepare for multilanguage usage
  if ( resp.length != 0 ){
    var msglist = '<table class="msglst" id="mtable"><thead><tr><th>MsgID</th><th>UTC+0</th><th>'+ ln['state'] +'</th></tr></thead><tbody>';
    for (i = 0; i < resp.length; i++) {
      msglist += '<tr class="msglst"><td class="msglst">' + resp[i].MsgID + '</td><td class="msglst">' + resp[i].Date + '</td><td class="msglst">' + ln.statevalue[resp[i].State] + '</td></tr>';
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
    document.getElementById('inf').innerHTML = ln['nomsgs'];
  }
  checkPremium();
}

/**
 * Fetch message incl. content from server.
 * Show header and input-element for key-file for decoding.
 *
 * @param {string} mid - 'MsgID'.
 */
function loadMsg(mid){
  window.document.body.style.cursor = "wait"; // sets the cursor shape to hour-glass.
  sessionStorage.mid2dc = mid;
  con("get_msg.php", {mid:mid}, cbAfter_get_msg, true);
} function cbAfter_get_msg(resp){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables // prepare for multilanguage usage
  var mid = sessionStorage.mid2dc;
  window.gmsg2dc = resp[0].Content;
  sessionStorage.state2dc = resp[0].State;
  //TODO pull ReturnPubKey and ReturnLink from below to sessionStorage

  var out = '<button type="button" class="button" onclick="delMsg(' + mid + ')">'+ ln['delmsg'] +'</button><br><br>'
          + '<table class="msghead">'
          + '<tr><td><b>'+ ln['msgid'] +':</b></td><td>' + mid + '</td></tr>'
          + '<tr><td><b>'+ ln['msgrec'] +':</b></td><td>' + resp[0].Date + '</td></tr>'
          + '<tr><td><b>'+ ln['msgexp'] +':</b></td><td>' + resp[0].Expire + '</td></tr>'
          + '</table>'
          + '<p hidden id="ReturnPubKey">' + resp[0].ReturnPubKey + '</p>'
          + '<p hidden id="ReturnLink">' + resp[0].ReturnLink + '</p>';

  document.getElementById('fileup').innerHTML = '<br>'+ ln['keyselect'] +':'
                                              + '<br><input type="file" class="button" id="keyFile" size="50" onchange="decodeMsg()">';
  //                                                  + '<button id="loadKey" type=“button” class="button" onclick="decodeMsg()">Decode</button>';
  document.getElementById('out').innerHTML = out;
  //document.getElementById('keyFile').focus();
  window.document.body.style.cursor = "auto"; // reset cursor after waiting.
  checkPremium();
}


/**
 * Decode message content and display.
 */
function decodeMsg(){
  window.document.body.style.cursor = "wait"; // sets the cursor shape to hour-glass.
  var file = document.getElementById("keyFile").files[0];
  var reader = new FileReader();

  //Process after file is loaded
  reader.onload = function(e) {
    var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables // prepare for multilanguage usage
    var msg2decode = window.gmsg2dc;
    window.gmsg2dc = null; // save storage space
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
        window.gAttachment = oc.attach;
        sessionStorage.gFilename = oc.fname;
        oc = null;  // save storage space

        // Make new lines in message body HTML ready
        msg = msg.replace(/\n/gi,"<br>");

        // Display 'from' if not anonymous
        if (from !== null && from !== undefined) {
          document.getElementById('out').innerHTML += '<b>'+ ln['from'] +':</b><br><div class="att">' + from + '#postilotta.org</div><br>';
          // Set 'from' as reply to
          document.getElementById('ReturnLink').innerHTML = from;
        }

        // Append decoded message to display
        document.getElementById('out').innerHTML += '<b>'+ ln['msg'] +':</b><br><div class="msg">' + msg + '</div>';

        // Download option if attachment was included
        if (attach != null && attach != undefined) {
          document.getElementById('out').innerHTML += '<br><b>'+ ln['attach'] +':</b><br><div class="att"><a href="javascript:downAttach();">' + fname + '</a></div>';
        }

        // Mark as read
        if (sessionStorage.state2dc === 'NEW'){
          setMsgState(sessionStorage.mid2dc, 'READ');
        }

        // Add reply button only within inboxes
        if (window.location.pathname === '/inbox.php' && sessionStorage.state2dc != 'REPLYED'){
          document.getElementById('out').innerHTML += '<br><p><button id="reButton" type="button" class="button" onclick="reply()">'+ ln['reply'] +'</button><p>';
        }
    }
    catch(err) {
      document.getElementById('inf').innerHTML = '';
      document.getElementById('err').innerHTML = ''+ ln['decfailed'] +'<br>' + err.message;
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

/**
 * Save sec key file to device.
 *
 * @param {string} filename - Proposed name of file to store.
 * @param {string} text - File data to store.
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
 * Download message attachment.
 */
function downAttach(){
  var filename = sessionStorage.gFilename;
  var data = window.gAttachment;

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
//      document.body.removeChild(element);
    }
    sessionStorage.gFilename = null;
    window.gAttachment = null;
}


/**
 * Display input textbox and reply-button.
 */
function reply(){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables // prepare for multilanguage usage
  var but = document.getElementById('reButton')
  but.parentNode.removeChild(but);
  var repl = '<form id="reForm">'
     + ln['reply'] + ':<br>'
     + '<textarea id="p_text" cols="40" rows="10"></textarea>'
     + '<br><br>'
     + '<button type="button" class="button" onclick="rEncryption()">'+ ln['send'] +'</button>'
  + '</form>';

  document.getElementById('out').innerHTML += repl;
  document.forms["reForm"]["p_text"].focus();
  checkPremium();
}

/**
 * Reply Step 2:
 * Encrypt reply and trigger mTransmission
 * Same like mEncryption, but for reply.
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
 *
 * @param {string} lnk - 'Address'. Recipient of the message (reply) to display.
 */
function loadRpl(lnk){
  con('get_msglist.php', {to:lnk}, cbAfter_repl_list, true);
} function cbAfter_repl_list(resp){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables  // prepare for language lables

  if (resp[0]){
    loadMsg(resp[0].MsgID);
  }else{
    document.getElementById('out').innerHTML += '<br>'+ ln['notyet'] +'<br>'
  }
}

/**
 * Load module-send-form into inbox UI, for message sending.
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
  xhttp.open('POST', 'module-send-form.php', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send("lang="+sessionStorage.selectedLang);
}


/**
 * Trigger message sending out of inbox UI.
 * Calls mEncryption.
 */
function sendNewMessage(to, c){
//  var i_to = document.forms["sendInbox"]["p_to"];
//  var i_c = document.forms["sendInbox"]["p_text"];
  // Use own pub key. User own address as 'from'.
  var abs = sessionStorage.p_adr;
  var pub =  sessionStorage.myPub;
//  mEncryption(i_to.value, i_c.value, pub, abs);
  mEncryption(to, c, pub, abs);
}


/**
 * Delete message from server.
 *
 * @param {string} mid - 'MsgID' of message to delete.
 */
function delMsg(mid){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables
  //Clear file upload section
  document.getElementById('fileup').innerHTML = '';
  con('del_msg.php', {mid:mid}, cbAfter_delM, true);
} function cbAfter_delM(resp){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables
  document.body.scrollTop = 0; // For Chrome, Safari and Opera
  document.documentElement.scrollTop = 0; // For IE and Firefox
  if (resp.rcode == 0){
    document.getElementById('out').innerHTML = '';
    document.getElementById('inf').innerHTML = ln['delsuccess'];
  } else {
    document.getElementById('inf').innerHTML = '';
    document.getElementById('err').innerHTML = ln['delfail'] + resp.msg;
  }
}


/**
 * Log out of inbox UI.
 * Incl. php session and sessionStorage cleanup.
 * Redirect to landing page afterwards.
 */
function logOut(){
  //TODO Proper cleanup at Logout
  sessionStorage.clear();
  con('logout.php', {}, null, true);
  location.replace("index.php");
}

/**
 * Prompt password request for inbox destruction.
 * Trigger destruction if password check passed.
 *
 * @param {string} adr - 'Address' of related inbox.
 */
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
 * Delete inbox from server.
 *
 * @param {string} adr - 'Address' of inbox to delete.
 * @param {string} pwd - 'Password' hashed.
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
 * Change state of message 'mid' to 'state'.
 *
 * @param {string} mid - 'MsgID' of message to change state.
 * @param {string} state - New 'State' to change to.
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
 * Detect Operating System of user client.
 * Big 5 only.
 *
 * @return {string} os - Short name of OS.
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
 *
 * @param {string} target - String target address to post to
 * @param {object} params - object containing the parameters to pass
 * @param {string} callback - function name to call after response, passing response 'this'
 * @param {bool} enc - false for raw response (no json, or paranoia)
 * @param {string} [ctype] - Content-type, default is application/x-www-form-urlencoded
 */
function con(target, params, callback, enc, ctype){
  var xhttp = new XMLHttpRequest();
  var paramStr = '';
  if (sessionStorage.selectedLang != undefined){
    params['lang'] = sessionStorage.selectedLang;
  }
  if (!ctype){
    ctype = 'application/x-www-form-urlencoded';
  }

  // prepare response and callback
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (callback){
        var resp = this.responseText;
//        console.log('RESPONSE (raw): ' + JSON.stringify(resp));

        //decode if paranoia
        if((sessionStorage.paranoiaLink != undefined) && (sessionStorage.paranoiaLink != 'undefined')){
          resp = decrytParaResp(resp);
//          console.log('RESPONSE (dec): ' + JSON.stringify(resp));
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

//  console.log('REQUEST:' + target + '?' + paramStr);

  //Send msg state change request to server
  xhttp.open("POST", target, true);
  xhttp.setRequestHeader("Content-type", ctype);
  xhttp.send(paramStr);
}

/*---------- ExtraSecure Mode ----------------------*/

/**
 * Prepare ExtraSecure session by sending secrets to server.
 * Display Link and QRCode where to access the session.
 *
 * @param {string} pf - 'Passphrase', to encrypt whole data transfer with.
 * @param {string} ww - 'Watchword' for authenticity proof.
 */
function prepareParanoia(pf, ww){
  con('write_para.php', {pf:pf, ww:ww}, cbAfter_write_para, true);
} function cbAfter_write_para(resp){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables

  if(resp.rcode == 0) {
      // Clear screen
      var form = document.getElementById('theForm');
      form.parentNode.removeChild(form);
      document.getElementById('err').innerHTML = '';

      // Display Backdoor-Link
      document.getElementById('inf').style.color = '#6495ED';
      document.getElementById('inf').innerHTML = resp.msg;
      var lnkout = 'http://'+ window.location.host +'/backdoor.php?' + resp.plink;
      document.getElementById('inf').innerHTML += '<br>' + ln['access'] + '<br>'
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

/**
 * Enable ExtraSecure session.
 *
 * @param {string} pf - 'Passphrase'.
 */
function activateParanoia(pf){
    var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables
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
    document.getElementById('out').innerHTML = '<b>' + ln['watchword'] + ':</b><br><div class="att" style="color:black">' + decrypted + '</div><br>'
                                             + '<br><div style="color:#ecd201">' + ln['aesactive'] + '</div>';
  }catch(e){
    document.getElementById('inf').innerHTML = '';
    document.getElementById('out').innerHTML = '';
    document.getElementById('err').innerHTML = ln['noaes'];
  }
  checkParaOn();
}

/**
 * Check whether ExtraSecure session is enabled and change style accordingly.
 */
function checkParaOn(){
  // on initial visit of e.g. index.php, async calls can produce hazard and sessionStorage.ln is not ready in time.
  try {
    var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables
  }catch(err){
    return;
  }
  var pp = sessionStorage.paranoiaPWD;
//  console.log(JSON.stringify(sessionStorage));

  if (pp !== null && pp != "undefined" && pp !== undefined){
    document.body.classList.add('para');
    document.getElementById('logo').src = 'pics/schwarzerumschlag_p_96.jpg';
    document.getElementById('tn-li-para').innerHTML = ln['extraoff'];
    document.getElementById('tn-li-para').className = 'paraoff';
    document.getElementById('tn-li-para').href = 'index.php';
    document.getElementById('tn-li-para').parentNode.onclick = logOut;
  }
}

/**
 * Decrpyt server response in ExtraSecure mode.
 *
 * @param {string} resp - Server response to decrypt.
 */
function decrytParaResp(resp){
  // paranoia Decryption
  resp = JSON.parse(CryptoJS.AES.decrypt(resp, sessionStorage.paranoiaPWD, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));
  return resp;
}

/**
 * Encrpyt server request before sending in ExtraSecure mode.
 *
 * @param {string} req - Request to encrypt.
 */
function encryParaReq(req){
  req  = CryptoJS.AES.encrypt(JSON.stringify(req), sessionStorage.paranoiaPWD, {format: CryptoJSAesJson}).toString();
  return req;
}

/**
 * Clear sessionStorage except ExtraSecure related variables.
 */
function clearSessionSoft(){
  var tmp = sessionStorage.paranoiaPWD;
  var tmpLink = sessionStorage.paranoiaLink;
  var lang = sessionStorage.selectedLang;
  var ln = sessionStorage.ln;
  sessionStorage.clear();
  sessionStorage.paranoiaPWD = tmp;
  sessionStorage.paranoiaLink = tmpLink;
  sessionStorage.selectedLang = lang;
  sessionStorage.ln = ln;
}


/*--------------- Display Functions--------------------*/

/**
 * Replace TopNav for large displays by mobile version.
 */
function replaceNav(){
  var x = document.getElementById("monnav");
  if (x.className.indexOf("show") == -1) {
    x.className += " show";
  } else {
    x.className = x.className.replace(" show", "");
  }
}

//--- Password Strength -------
function pwdStrength(){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables
  var strength = {
          0: ln['bad'],
          1: ln['weak'],
          2: ln['weak'],
          3: ln['good'],
          4: ln['strong']
  }

  var password = document.getElementById('p_pwd');
  var text = document.getElementById('password-strength-text');

  password.addEventListener('input', function() {
      var val = password.value;
      var result = zxcvbn(val);
      // Update the text indicator
      if(val !== "") {
        document.getElementById('p_pwd').style.borderColor = 'initial';
          text.innerHTML = strength[result.score];
          switch (result.score) {
            case 4:
              document.getElementById('password-strength-text').style.color = 'green';
              break;
            case 3:
              document.getElementById('password-strength-text').style.color = 'orange';
              break;
            default:
              document.getElementById('password-strength-text').style.color = 'red';
          }
      }
      else {
          text.innerHTML = "";
      }
  });
}

/**
 *
 */
function checkPWDConf() {
var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables
  if (document.getElementById('p_pwd').value ==
  document.getElementById('p_pwd2').value) {
    document.getElementById('notConf').style.color = 'green';
    document.getElementById('notConf').innerHTML = '';
  } else {
    document.getElementById('notConf').style.color = 'red';
    document.getElementById('notConf').innerHTML = ln['no_match'];
  }
}

/**
 *
 */
function checkAdrExist(th){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables
  var alist = JSON.parse(sessionStorage.toList);
  var exist = alist.find(searchItem, th.value)
  if(exist != undefined){
    document.getElementById('adrtaken').innerHTML = ln['taken'];;
    document.getElementById('adrtaken').style.color = 'red';
    document.getElementById('p_name').style.borderColor = 'red';
  }else{
    document.getElementById('adrtaken').innerHTML = "";
    document.getElementById('p_name').style.borderColor = 'initial';
//    document.getElementById('p_name').style.textAlign = 'right';
  }
}

/**
 *
 */
function agbChanged(th) {
  if (th.checked == true){
    document.getElementById('l_agb').style.color = 'initial';
  }
}
/**
 *
 */
function priceChanged(th){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables
  if(th.value < 5){
    document.getElementById('noprice').innerHTML = ln['noprice'];
    document.getElementById('noprice').style.color = 'red';
    th.style.borderColor = 'red';
  } else {
    th.style.borderColor = 'initial';
    document.getElementById('noprice').innerHTML = '';
  }
}


/**
 * Send form validate.
 * Calls prepReply if successfull.
 *
 * @param {string} to - Recipients 'Address'. Just passed through.
 * @param {string} c - Content (message without attachment) to trasnsmit. Just passed through.
 */
function checkSend(caller, to, c){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables // prepare for multilanguage usage
  if (caller == 'inbox'){
    to = document.forms["sendInbox"]["p_to"].value;
    c = document.forms["sendInbox"]["p_text"].value;
  }
  document.getElementById('err').innerHTML = '';
  var valid = true;

  if (to == ''){
    document.getElementById('err').innerHTML += '&#216; &#8594; ' + ln['mis-adr'] + '<br>';
    document.getElementById('p_to').style.borderColor = 'red';
    valid = false;
  }else{
    document.getElementById('p_to').style.borderColor = 'initial';
    var alist = JSON.parse(sessionStorage.toList);
    var exist = alist.find(searchItem, document.getElementById('p_to').value)
    if(exist == undefined){
      document.getElementById('err').innerHTML += '&#216; &#8594; ' + ln['adr-noexist'] + '<br>';
      document.getElementById('p_to').style.borderColor = 'red';
      valid = false;
    }
  }
  if (c == ''){
    document.getElementById('err').innerHTML += '&#216; &#8594; ' + ln['mis-text'] + '<br>';
    document.getElementById('p_text').style.borderColor = 'red';
    valid = false;
  }
  else{
      document.getElementById('p_text').style.borderColor = 'initial';
  }

  if (valid) {
    switch (caller) {
      case 'anonym':
        prepReply(to, c);
        break;
      case 'inbox':
        sendNewMessage(to, c);
    }

  }
}

/**
 *
 */
function changedMsgtxt(th){
  th.style.borderColor = 'initial';
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

/**
 * Checks whether loged in inbox is premium and changes style accordingly
 */
function checkPremium() {
  if(sessionStorage.typ =='premium'){
    var elements = document.getElementsByClassName('button');
    for (var i = 0; i < elements.length; i++) {
        elements[i].style.backgroundColor='#A9BCF5';
    }
    document.getElementById('tn-li-login').style.backgroundColor='#A9BCF5';

    elements = document.getElementsByClassName('tbitem');
    for (var i = 0; i < elements.length; i++) {
        elements[i].style.color='#A9BCF5';
    }
    document.getElementById('typ').src = './pics/premium.png';
  }
}

/**
 * Supporting function for adrSelect, returning Address property
 *
 * @param {object} item - current search item
 * @return {object} this
 */
function searchItem(item){
  return item.Address == this;
}

/**
 * Displays inbox-type and id-veryfied logos of selected Address
 *
 * @param {object} th - selection DOM input object
 */
function adrSelect(th){
  var ln = JSON.parse(sessionStorage.ln);  // prepare for language lables
  var a_toList = JSON.parse(sessionStorage.toList);
  var selected = a_toList.find(searchItem, th.value);

  if(selected == undefined){
    document.getElementById('adr-noex').innerHTML = ln['adr-noex'];;
    document.getElementById('adr-noex').style.color = 'red';
    document.getElementById('p_to').style.borderColor = 'red';
    document.getElementById('adr-typ').src = '';
  }else{
    document.getElementById('adr-noex').innerHTML = "";
    document.getElementById('p_to').style.borderColor = 'initial';

    switch (selected.Type) {
      case 'premium':
        document.getElementById('adr-typ').src = './pics/premium_25.png';
        break;
      case 'basic':
        document.getElementById('adr-typ').src = './pics/basic_25.png';
      break;
      default:
    }
    if (selected.IdVerified) {
      document.getElementById('adr-idv').src = './pics/id-verified_yellow_30.png';
    } else {
      document.getElementById('adr-idv').src = '';
    }
  }
}

/*-------------- Settings ----------------------*/

/**
 * Loads module-inbox-settings into inbox UI.
 */
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

      // Badic settings
      document.forms["settings"]["p_mail"].value = sessionStorage.myEmail;
      if (sessionStorage.myVisible == 1) {document.forms["settings"]["p_visible"].checked = true;}
      document.getElementById('p_typ').innerHTML = sessionStorage.typ;

      // Premium settings
      if (sessionStorage.myPayment!= 0) {
        document.getElementById('o_' + sessionStorage.myPayment).selected = true;
      }else {
        document.getElementById('o_none').selected = true;
      }
      document.forms["settings"]["p_price"].value = sessionStorage.myPrice;
      document.forms["settings"]["p_msglife"].value = parseInt(sessionStorage.myMsgLife);

      // make premium settins editable
      if (sessionStorage.typ === 'premium'){
        document.forms["settings"]["p_msglife"].readOnly = false;
        document.getElementById('p_until').innerHTML = 'settled until: ' + sessionStorage.myPaidUntil;
        document.forms["settings"]["p_price"].readOnly = false;
        document.getElementById('p_pay').disabled = false;
        document.getElementById('p_color').disabled = false;
        // remove signup button
        var but = document.getElementById('b_sign');
        but.parentNode.removeChild(but);
        // replace verify-id button if already verified
        if (sessionStorage.myIdVerified == 1) {
          var but = document.getElementById('b_very');
          but.parentNode.removeChild(but);
          document.getElementById('d_very').innerHTML = 'Verification completed &#10003;';
        }
      } else {
        document.getElementById('b_very').onclick = '';     // Disable ID verification
        // Disable premium submit update button
        document.getElementById('sub2').className = 'buttonfrm';
        document.getElementById('sub2').onclick = '';
        document.getElementById('d_quit').innerHTML = 'Go premium';
        // remove back to basic button
        var but = document.getElementById('b_quit');
        but.parentNode.removeChild(but);
      }
    }
  };
  //Send msg state change request to server
  xhttp.open('POST', 'module-inbox-settings.php', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send("lang="+sessionStorage.selectedLang);
}

/**
 * Writes changes inbox settings back to server.
 * TODO: update changes only.
 */
function submitSettingsUpdate(){
  var adr = sessionStorage.p_adr;
  var npwd = document.forms['settings']['p_newpas'].value;
  if (npwd != ''){
    // Hash new password
    var bitArray = sjcl.hash.sha256.hash(npwd);
    npwd = sjcl.codec.hex.fromBits(bitArray);
  }else{
    npwd = sessionStorage.p_pwd;
  }
  var mail = document.forms["settings"]["p_mail"].value;
  var vis = document.forms["settings"]["p_visible"].checked;
  vis = vis ? 1 : 0;
  var mlf = document.forms["settings"]["p_msglife"].value;
  var pay = document.getElementById('p_pay').value;
  var price = document.getElementById('p_price').value;

  con('write_settings.php', {adr:adr, mail:mail, vis:vis, mlf:mlf, pay:pay, price:price, pw:npwd}, cbAfter_write_set, true);
} function cbAfter_write_set(resp){
  if(resp.rcode == 0) {
    document.getElementById('err').innerHTML = '';
    document.getElementById('out').innerHTML = '';
    document.getElementById('inf').innerHTML = resp.msg;
  }else{
    document.getElementById('inf').innerHTML = '';
    document.getElementById('err').innerHTML = resp.msg;
  }

}

/*-------------- ID Verification ----------------------*/

/**
 * Loads module-verify-id into inbox UI.
 * TODO: Use more sophisticated id-verification process...
 */
function loadVerify(){
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
    }
  };
  //Send msg state change request to server
  xhttp.open('POST', 'module-verify-id.php', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send("lang="+sessionStorage.selectedLang);
}

/**
 * Sets id-verification status of current inbox to true.
 */
function verifyID(){
  window.open('https://calendly.com/postilotta/id-verification','_blank')
}
/*
 con('write_verifyid.php', {adr:sessionStorage.p_adr}, cbAfter_write_verifyid, true);
} function cbAfter_write_verifyid(resp){

  document.body.scrollTop = 0; // For Chrome, Safari and Opera
  document.documentElement.scrollTop = 0; // For IE and Firefox

  if(resp.rcode == 0) {
    document.getElementById('err').innerHTML = '';
    document.getElementById('out').innerHTML = '';
    document.getElementById('inf').innerHTML = resp.msg;
  }else{
    document.getElementById('inf').innerHTML = '';
    document.getElementById('err').innerHTML = resp.msg;
  }
}
*/

/**
 * Checks wheter the current loged in inbox has id-verification and display logo accordingly.
 */
function checkVerified() {
  if(sessionStorage.myIdVerified == 1){
    document.getElementById('idv').src = './pics/id-verified_yellow_40.png';
  }
}

/*-------------- Multilanguage ----------------------*/

/**
 * Prepare clientside use of localised lables.
 */
function checkLang(){
  // Set language to selected or default
//  var lang = sessionStorage.selectedLang;
  var lang = document.getElementById('p_lang').value;
  if(lang == 'undefined'){
    lang = navigator.language;
//    sessionStorage.selectedLang = lang;
  }
  //document.getElementById('o_ln_' + lang).selected = true;

  // Load language file if not already here
  if (sessionStorage.ln == 'undefined') {
    setLang(lang);
  }
}

/**
 * Change language on new selection
 */
function changeLang(th){
  var lang = th.value;
  setLang(lang);
  var target = window.location.href.replace(/\?lang=.{2}/gi, '');
  target += '?lang=' + lang;
  window.location.assign(target);
}

/**
 * Fetches general_<lang>.json if not already in session.
 */
function setLang(lang, cb){
  var xhttp = new XMLHttpRequest();
  // prepare response and callback
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4){
      if (this.status == 200){
        sessionStorage.ln = this.responseText;
      }else{
        setLang('en');
       }
    }
  };
  //Send msg state change request to server
  xhttp.open('POST', 'language/general_' + lang + '.json', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send();
}
