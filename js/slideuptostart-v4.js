//SET VARS FOR GAME
// var gameURL = 'https://www.metrognomedash.co.uk/'; //used forbusting out of iframe on client version
var gameLandscape = true;
var forcegameRotation = false;//used for social media browsers that are locked to portrait
//if (isMobileDevice() && isFacebookApp() && !checkIfInsideIframe() && gameLandscape==true) forcegameRotation = true;
//alert(window.orientation);

function isMobileDevice() {
    console.log('### '+ (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1)+' ###');
    return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
};
function isAndroid(){
  var ua = navigator.userAgent.toLowerCase();
  console.log('### ANDROID TEST ###');
  return ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
}

function newWin(url,target){
  var win = window.open(url,target);
  if (win) {
    //Browser has allowed it to be opened
    win.focus();
  } else {
    //Browser has blocked it
    alert('Please allow popups for this website');
  }
}


function checkIfInsideIframe() {
  //var breakOutOfFrame = false;
  console.log('### CHECK FOR IFRAME ###');
  if ((window.self !== window.top) && isMobileDevice()){
    console.log('### IFRAME DETECTED ###');
    $('#toosmallmsgdetect').show();
    $('#toosmallmsgdetect').on('click', function() {
        //alert('test');
        $('#toosmallmsg').show();
        $('#toosmallmsg').addClass("displaymsg");

        // $('#btn_fullscreen').on('click', function() {
        //   newWin(gameURL,'_blank');
        // });
        $('#btn_cancel').on('click', function() {
          $('#toosmallmsg').hide();
          $('#toosmallmsgdetect').hide();
        });
    });
    return true;
  }
};

function toggleFullScreen() {
  if (!document.fullscreenElement) {
      document.body.requestFullscreen();
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    }
  }
}

// $( window ).scroll(function() {
//   alert("Started scrolling!");
// });


function requireLandcape(){
  console.log('function - requireLandcape');
  var bodyHeight = document.body.offsetHeight;
  var windowHeight = window.innerHeight; //HEIGHT OF CONTENTS
  var clientHeight = document.documentElement.clientHeight;
  if(isAndroid()){clientHeight = screen.availHeight-50;}; //MODIFIED TO ALLOW FOR ANNOYING STATUS BAR - COUDL BE IMPROVED
  var isLandscape = 0;

  if((window.innerHeight < window.innerWidth)||(checkIfInsideIframe())){isLandscape=1};

  if(!isLandscape){
    //PORTRATE MODE
    gameDisplayed = false;
    $('#scrollupmsg').hide();
    console.log('### YOU ARE NOW IN PORTRAIT MODE ###');

        if (forcegameRotation) {
            console.log('### GAME ROTATED TO LANDSCAPE MODE ###');
            $("#gameView").addClass("rotateGame");
            $('#rotatemsg').hide();

        }else{
          console.log('### ROTATE DEVICE MESSAGE SHOWN ###');
          $('#rotatemsg').show(); //TELL USER TO ROTATE THEIR DEVICE

          if(isFacebookApp()){
            $('#rotateInstructionsIos').show();
          }

        }
  } else{
  console.log('### windowHeight ###' + windowHeight);
  console.log('### clientHeight ###' + clientHeight);
  console.log('### $(window).height()  ###' + $(window).height() );
  console.log('###   window.innerHeight; ###' +   window.innerHeight );

    //LANDSCAPE MODE
    //console.log('### DEV: RESIZE INTERVAL CLEARED ###');
    $('#rotatemsg').hide();
    $("#gameView").removeClass("rotateGame");

    //document.body.requestFullscreen();
    //CHECK IF BROWSER BAR IS ON DISPLAY

    var browserBarOnShow = (windowHeight < clientHeight);

    //alert(showMessage ? 'show' : 'hide');
    if (browserBarOnShow && !gameDisplayed && !isFacebookApp()) {
      console.log('### SHOW SLIDE TO START ###');
      $('#scrollupmsg').show();

      // if(isAndroid){
      //   console.log('### ANDROID DETECTED ###');
      //   $(document).on("scrollstart",function(){
      //     console.log('### SCROLLED ###');
      //     toggleFullScreen();
      //   });
      // };
      document.activeElement.blur(); //HIDE KEYBOARD
      //alert('test');
      //$('#gameView').css('transform','scale(0.8,0.8)');
    }
    else{
      $('#scrollupmsg').hide();
      gameDisplayed = true;
      console.log('### GAME DISPLAYED ###');
      //$('#gameView').css('transform','scale(1,1)');
    }
  }

  // $('#debugdata').html('bodyHeight:'+bodyHeight+
  //   ' clientHeight:'+clientHeight+
  //   ' windowHeight:'+windowHeight+
  //   ' window.innerHeight:'+window.innerHeight+
  //   ' isLandscape:'+isLandscape
  // );
};
// function updateDebugData(){
//   $('#debugdata').html('TEST');
//
// }

function calcTrueVH(){ //SCRIPT TO CALC TRUE VIEWPORT height
    // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
    let vh = window.innerHeight * 0.01;
    // Then we set the value in the --vh custom property to the root of the document
    document.documentElement.style.setProperty('--vh', `${vh}px`);
};

function scaleContent() {
  var $el = $('.scaleContent');
  var elWidth = $el.outerWidth();

  console.log('elWidth'+$el.outerWidth());
  var elHeight = $el.outerHeight();
    console.log('elHeight'+$el.outerHeight());

  var $wrapper = $('body');
  var starterData = {
    size: {
      width: $wrapper.width(),
      height: $wrapper.height()
    }
  }
  console.log('bodyWidth'+$wrapper.width());
  console.log('bodyH'+$wrapper.height());
  var scale, origin;
  scale = Math.min(
    starterData.size.width / elWidth,
    starterData.size.height / elHeight
  );
  $el.css({
    transform: "translate(-50%, -50%) " + "scale(" + scale + ")"
  });
  console.log('scale'+scale);

    // }
};

function getMobileOperatingSystem() {
  var userAgent = navigator.userAgent || navigator.vendor || window.opera;

      // Windows Phone must come first because its UA also contains "Android"
    if (/windows phone/i.test(userAgent)) {
        return "Windows Phone";
    }

    if (/android/i.test(userAgent)) {
        return "Android";
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "iOS";
    }

    return "unknown";
}

function showPrompt(formDescription,presetValue){
  var returnValue = prompt(formDescription,presetValue);
  if (returnValue == null || returnValue == "") {
    return "User cancelled the prompt.";
  } else {
    return returnValue;
  }
}

function isFacebookApp() {
    var ua = navigator.userAgent || navigator.vendor || window.opera;
    return (ua.indexOf("FBAN") > -1) || (ua.indexOf("FBAV") > -1) || (ua.indexOf("Instagram") > -1);
}

function showScrollMessage(){

}
function showRotateMessage(){

}

$(document).ready(function () {
   setTimeout(function(){ $(window).trigger('resize'); }, 500);
   //$(window).trigger('resize');
  gameDisplayed = 0;
  //alert(showPrompt('please enter your name',''));
});

$(window).on('resize', function () {
  console.log('resize');
  checkIfInsideIframe();
  //scaleContent();
  calcTrueVH();//used to calc true viewport height on mobile
	//if mobile start process of forcing them into landscape
  if (!checkIfInsideIframe() && isMobileDevice() && gameLandscape){
    //requireLandcape();
    console.log('checkforlandcape');
    requireLandcape();
    //requireLandcapeTimer = setInterval(function(){ requireLandcape() }, 1000);
    window.scrollTo(0, 0); //keeps things tidy whilst rotating back and forth
  } else{
    console.log('checkforlandcape');
    $('#scrollupmsg').hide(); //hide landcape messages if on desktop
    $('#rotatemsg').hide();   //hide landcape messages if on desktop
  };

  // CALC TARGET HEIGHT OF iframe
  var gameActualWidthPX =   $("#gameiFrame").width();
  var gameOriginalHeightPX =   $("#gameiFrame").height();
  console.log('### gameActualWidthPX ###' + gameActualWidthPX);
  console.log('### gameOriginalHeightPX ###' + gameOriginalHeightPX);
  //var gameForcedHeightPX = (gameActualWidthPX * 0.4285) + 'px'; //21:9 - 1680x720
  var gameForcedHeightPX = (gameActualWidthPX * 0.35) + 'px'; //21:9 - 1680x720
  $("#gameiFrame").css("min-height", gameForcedHeightPX);
  console.log('### gameForcedHeightPX ###' + gameForcedHeightPX);
  //alert(gameForcedHeightPX);
});

  //doResize(null, starterData);
