<?
class FileLogWriter{
	public function FileLogWriter($app) {
		$this->app = $app;
		$this->filesizerecycled = 20; // how many mb before log file is recycled
	}

	public function write($message){
	 // ### delete log file when creation date over 2 days
	 if(file_exists('koko.log')){
		$filesize = number_format(filesize('koko.log')/ 1048576, 2);
        if ($filesize > $this->filesizerecycled){
			//chown('koko.log', 666);
			rename('koko.log', 'koko-prev20mb.log');
			unlink('koko.log');
       }
	 }

	 // ### write custom log file
	$time = date('Y-m-d H:i:s');
	$request = $this->app->request();
	$message .= ' - '.sprintf("%s [%s] \"%s %s\"",
				$request->getIp(),
				$time,
				$request->getMethod(),
				$request->getPathInfo()
				).chr(13);

	// ### collect info to write to the log

	file_put_contents('koko.log',$message . PHP_EOL, FILE_APPEND);
	// ### write log to text file in the root
	}
}
?>