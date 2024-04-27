<?php

class LinuxMonitoring {

    private function getServerHostname() {
        $serverHostname = gethostname();
        return $serverHostname;
    }

    private function execServerAction($command) {
        $filteredOutput = escapeshellarg($command);
        $commandResult = shell_exec($filteredOutput);

        if ($commandResult === null) {
            throw new Exception("Error Executing: {$filteredOutput}");
        }

        set_time_limit(30);

        return $commandResult;
    }

    public function getLoadAverage() {
        $loadAverageValues = sys_getloadavg();
        $loadAverageMinutes = array(1, 5, 15);
        $loadAverageMessage = "Load Average on Sever: {$this->getServerHostname()} ";
        $counter = 0;

        foreach ($loadAverageValues as $singleLoadValue) {
            $loadAverageMessage .= "for {$loadAverageMinutes[$counter]} minute/s: {$singleLoadValue} ";
            $counter++;
        }

        return $loadAverageMessage;
    }

    public function getRamInfo() {
        $command = 'free -h';
        $ramInfo = $this->execServerAction($command);
        $ramInfoMessage = "Ram Usage on Server: {$this->getServerHostname()}";
        $ramInfoMessage .= $ramInfo;

        return $ramInfoMessage;
    }

    public function getDiskInfo() {
        $command = 'df -h';
        $diskInfo = $this->execServerAction($command);
        $diskInfoMessage = "Disk Usage on Server: {$this->getServerHostname()}";
        $diskInfoMessage .= $diskInfo;

        return $diskInfoMessage;
    }
}
