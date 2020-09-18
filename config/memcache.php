<?
    # Connect to memcache:
    global $memcache,$mememarker;
	if (class_exists('Memcache')) {
			$memcache = new Memcache;
			$memcache->connect('localhost', 11211);
			$memcache_stat = $memcache->getExtendedStats();
			$memcache_stat = $memcache_stat['localhost:11211'];
			$mememarker = 'climatecrisis';

			# Gets key / value pair into memcache
			function getCache($key) {
				global $memcache,$mememarker;
				return ($memcache) ? $memcache->get($mememarker.$key) : false;
			}

			# Puts key / value pair into memcache
			// default caching 12 hour
			function setCache($key,$object,$timeout = 7200) {
				global $memcache,$mememarker;
				return ($memcache) ? $memcache->set($mememarker.$key,$object,MEMCACHE_COMPRESSED,$timeout) : false;
			}

			function replaceCache($key,$object,$timeout = 7200) {
				global $memcache,$mememarker;
				return ($memcache) ? $memcache->replace($mememarker.$key,$object,MEMCACHE_COMPRESSED,$timeout) : false;
			}

      function deleteCache($key){
    	 	global $memcache,$mememarker;
    		$memcache->delete($mememarker.$key);
    	}

	}

?>
