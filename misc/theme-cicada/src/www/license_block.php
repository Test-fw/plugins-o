<?php

$serviceCommands = [
    "IPS/IDS" => ["suricata", "opnsense-maltrailsensor", "crowdsec"],
    "AppFil"=>["zenarmor"],
    "Avirus" => ["clamav"],
    "ASpam" => ["postfix"],
    "Email" => ["rspamd", "redis"],
    "WAF" => ["nginx"],
    "DNS" => ["dnscrypt-proxy", "unbound", "bind"],
    "SLB" => ["haproxy", "relayd"]
];

$licenseOnlyCommand = "/usr/local/bin/python3 /usr/local/opnsense/service/service_status.py lic_only";
$new = shell_exec($licenseOnlyCommand);

$licenseInfo = eval("return " . $new . ";");

foreach ($licenseInfo as $element) {
    $real_names=$serviceCommands[$element];

    foreach($real_names as $service){
        
        $command= "service $service onestatus";
        echo shell_exec($command);
    }
}