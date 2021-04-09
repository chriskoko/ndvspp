<?
	require 'share/inc_share.php'; // all share values set here
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"> -->
<meta name="viewport" content="width=maximum-scale=1.0, device-width, initial-scale=1, minimum-scale=1.0, user-scalable=no, shrink-to-fit=no, viewport-fit=cover">
<meta name="theme-color" content="#000000" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? require 'share/inc_metatags.php'; ?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<link rel="stylesheet" href="css/slideupstyle.css?v=5" />
<style>body, html, canvas, iframe, div, span {-webkit-touch-callout: none;-webkit-user-select: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;}</style>
</head>
<body class="noselect" style="background-color: black; color: white; height:100vh; height: calc(var(--vh, 1vh) * 100); width:100vw; xoverflow:hidden;" id="bodyid">
	<!-- <button id="goFS">Go fullscreen</button>
	<script>
	  var goFS = document.getElementById("goFS");
	  goFS.addEventListener("click", function() {
	      document.body.requestFullscreen();
	  }, false);
	</script> -->
<div style="position:relative;">

  <div id="gameContainer" style="display: table;" class="fullscreen">
    <div id="gameView" class="fullscreen" >
    <iframe id="gameiFrame" scrolling="no" class="fullscreen" src="game/v55/index.html?test=1" frameborder="0"></iframe>
    <!-- <h4 id="debugdata" style="">
    </h4> -->
  </div>

  <div id="scrollupmsg" class="dialog" style="display:none">
    <div class="dialogcontent scaleContent" style="padding-top:30vh; vertical-align: top;">
      <img src="css/graphics/vertical-scroll.svg" class="icon-white icon" />
      <span>SCROLL UP TO GO FULL SCREEN</span>
    </div>
  </div>

	<style>
	#rotateInstructionsIos.speech-bubble {
	position: absolute;
	background: grey;
	width: 90%;
			left: 0;
			right: 0;margin: 30px auto;border-radius: .4em;
			    padding: 8px 10px 10px 10px;
}

#rotateInstructionsIos.speech-bubble:after {
	content: '';
	position: absolute;
	top: 0;
left: 90%;
	width: 0;
	height: 0;
	border: 15px solid transparent;
	border-bottom-color: grey;
	border-top: 0;
	margin-left: -15px;
	margin-top: -15px;
}


	#rotateInstructionsIos .content, #rotateInstructionsIos span{
		line-height: 30px;
		color: white;
		font-size: 12px;
		text-align: center;

	}
	#rotateInstructionsIos .button{
		color:white;border: 1px solid white; border-radius: 5px;  padding: 4px 8px;
		white-space: nowrap;
	}



	</style>
  <div id="rotatemsg" class="dialog fullscreen" style="display:none">
		<div id="rotateInstructionsIos" class="small speech-bubble"  style="display:none" >
			<div class="content">
				Having problems? <br /> Using the menu above, press the <span class="dots button">&bull;&bull;&bull;</span> button and choose <span class="button">Open in Safari/Chrome</span>
			</div>
		</div>

    <div class="dialogcontent scaleContent">
      <img src="css/graphics/phone.svg" class="icon-white icon" />
      ROTATE YOUR DEVICE
			<!-- <div display="none" id="rotateInstructionsAndroid" class="small" style="color:grey; font-size:11px; padding:20px;">Having problems? For a better experience please visit <?=$campaignUrl?> from your main browser app.</div>
			<div display="none" id="rotateInstructionsGeneric" class="small" style="color:grey; font-size:11px; padding:20px;">Having problems? For a better experience please visit <?=$campaignUrl?> from your main browser app.</div> -->
		</div>
  </div>

  <div id="toosmallmsgdetect" class="dialog fullscreen" style="display:none"></div>

  <div id="toosmallmsg" class="dialog fullscreen" style="display:none">
    <div class="dialogcontent scaleContent">
    <img src="css/graphics/newwindow.svg" class="icon-white icon" />
    <p>THIS CONTENT IS BEST VIEWED FULL SCREEN</p>
    <a href="<?=$campaignUrl?>/?action=fullscreen" target="_blank"><button id="btn_fullscreen" class="btn btn-primary">CONTINUE TO FULL SCREEN</button></a>
    <button id="btn_cancel" class="btn btn-secondary">CANCEL</button>
    </div>
  </div>

</div>
</div>

</body>

<script>

function showPrompt(formDescription,presetValue){
  var returnValue = prompt(formDescription,presetValue);
  if (returnValue == null || returnValue == "") {
    return "User cancelled the prompt.";
  } else {
    return returnValue;
  }
}
$(document).ready(function () {
	//alert(showPrompt('please enter your name',''));





});


	// ADD CSS TO IFRAME
	$("#gameiFrame").on("load", function() {
  let head = $("#gameiFrame").contents().find("head");
  let css = '<style>body, html, canvas, iframe {-webkit-touch-callout: none;-webkit-user-select: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;}</style>';
  $(head).append(css);
	//document.documentElement.webkitRequestFullScreen();

});
</script>
<script src="js/slideuptostart-v4.js?v=6" crossorigin="anonymous"></script>

</html>
