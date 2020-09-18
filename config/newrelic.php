<?php
if (extension_loaded('newrelic')) {
  $domain = $_SERVER['HTTP_HOST'];
  newrelic_set_appname($domain);
  // ### Newrelic.com PHP Performance tracking on domain name
}
?>