<?
	require 'share/inc_share.php'; // all share values set here
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- <meta name="viewport" content="
            width=device-width,
            initial-scale=1.0,
            maximum-scale=5.0,
            user-scalable=yes"> -->
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->


  <title>CLIENT IFRAME EXAMPLE</title>

</head>

<body>
    CLIENT IFRAME EXAMPLE:

    <style>
    .iframe-container {
      overflow: hidden;
      /* Calculated from the aspect ration of the content (in case of 16:9 it is 9/16= 0.5625) */
      padding-top: 42.85%;
      position: relative;
    }
    .iframe-container iframe {
       border: 0;
       height: 100%;
       left: 0;
       position: absolute;
       top: 0;
       width: 100%;
    }
    </style>
    <div class="iframe-container"><iframe ID="gameiFrame" scrolling="no" src="<?=$campaignUrl?>/?action=iframe" frameborder="0"></iframe></div>

<br />
<div style="padding: 20px; border: 1px solid; overflow: hidden">
    <?php highlight_string('<!-- #### IFRAME HTML CODE ### -->
<!-- The following iframe will autoscale it\'s height
to always match the correct aspect ratio of the width -->

<style> .iframe-container {
  overflow: hidden; position: relative;
  padding-top: 42.85%;   /* Calculated from the aspect ratio of the canvas */
}
.iframe-container iframe {
   border: 0;height: 100%;left: 0;position: absolute;top: 0;width: 100%;
} </style>
<div class="iframe-container"><iframe id="gameiFrame" scrolling="no" src="'.$campaignUrl.'/?action=iframe" frameborder="0"></iframe></div>'); ?>
</div>

</body>

</html>
