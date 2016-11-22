function Message_Update() {
	$('#show_message').load('http://www.cs.ccu.edu.tw/~cys102u/chatroom/show_message.php');
}

function Message_Send() {
	var content = document.getElementById("content").value;
	var hashkey = document.getElementById("hashkey").value;
	
	if (content.length == 0) {
		alert("請輸入對話訊息");
		return false;
	} else if (content.length > 32) {
		location.reload();
		alert("對話訊息異常，頁面將重新載入");
		return false;
	} else if (hashkey.length < 1) {
		alert("請輸入加密金鑰");
		return false;
	}
	content = $('<div/>').text(content).html();
	
	var hashkeybin,tmp;
	if (/^[\d]+$/.test(hashkey)) {
		hashkeybin = 0;
		if (hashkey.length > 10) {
			hashkeybin = hashkey;
		} else {
			for (i=0;i<hashkey.length;i++) {
				tmp = hashkey.charCodeAt(i);
				hashkeybin = hashkeybin + tmp * 1146880 + tmp * 1234567 + tmp * tmp + tmp;
			}
		}
	} else if (/^[a-zA-Z]+$/.test(hashkey)) {
		hashkeybin = 0;
		for (i=0;i<hashkey.length;i++) {
			tmp = hashkey.charCodeAt(i);
			hashkeybin = hashkeybin + tmp * 65536 + tmp * tmp + tmp;
		}
	} else {
		hashkeybin = 0;
		for (i=0;i<hashkey.length;i++) {
			tmp = hashkey.charCodeAt(i);
			hashkeybin = hashkeybin + tmp * 32768 + tmp + 699;
		}
	}
	
	var result = "";
	for (i=0;i<content.length;++i) {
		result+=String.fromCharCode(hashkeybin^content.charCodeAt(i));
	}
	$.post('http://www.cs.ccu.edu.tw/~cys102u/chatroom/chat.php', {content:result});
	document.getElementById("content").value="";
	Message_Update();
	return false;
}

function XOR_Decrypt(quantity) {
	var hashkey = document.getElementById("hashkey").value;
	var content;
	
	var hashkeybin,tmp;
	if (/^[\d]+$/.test(hashkey)) {
		hashkeybin = 0;
		if (hashkey.length > 10) {
			hashkeybin = hashkey;
		} else {
			for (i=0;i<hashkey.length;i++) {
				tmp = hashkey.charCodeAt(i);
				hashkeybin = hashkeybin + tmp * 1146880 + tmp * 1234567 + tmp * tmp + tmp;
			}
		}
	} else if (/^[a-zA-Z]+$/.test(hashkey)) {
		hashkeybin = 0;
		for (i=0;i<hashkey.length;i++) {
			tmp = hashkey.charCodeAt(i);
			hashkeybin = hashkeybin + tmp * 65536 + tmp * tmp + tmp;
		}
	} else {
		hashkeybin = 0;
		for (i=0;i<hashkey.length;i++) {
			tmp = hashkey.charCodeAt(i);
			hashkeybin = hashkeybin + tmp * 32768 + tmp + 699;
		}
	}
	
	var result = "";
	for (c=0;c<quantity;c++) {
		content = document.getElementById("hashcontent"+c);
		for (i=0;i<content.innerHTML.length;i++) {
			result+=String.fromCharCode(hashkeybin^content.innerHTML.charCodeAt(i));
		}
		document.getElementById("hashcontent"+c).innerHTML = result;
		//document.getElementById("hashcontent"+c).innerHTML = $('<div/>').text(result).html();
		result = "";
	}
}

var title_time;
var i;
var nickname;
function Start_Msg_Notify(usernickname) {
	i = 0;
	nickname = usernickname;
	document.documentElement.onmousemove = Stop_Msg_Notify;
	New_Message_Notify();
	title_time = window.setInterval(New_Message_Notify, 1250);
}

function Stop_Msg_Notify() {
	window.clearInterval(title_time);
	document.title = "聊天室";
	document.documentElement.onmousemove = null;
}

function New_Message_Notify() {
	if (i % 2 == 1) {
		document.title = "聊天室";
	} else {
		document.title = "來自 " + nickname + " 的新訊息";
	}
	i++
}

function Message_Process_Check() {
	if(confirm("確定要執行此操作？") == true){
		return true;
	} else {
		return false;
	}
}