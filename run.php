<?php 
require 'cmir.php';
$class = new coinmaster;
popen('cls', 'w');
echo "------------------ PRO Activate Invite Link ------------------\n";
$link = readline("Enter Invite Link:");
$count = readline("Enter Amount:");
popen('cls', 'w');
for ($i=0; $i < $count; $i++) {
	$number = $i+1;
	echo "============== EMULATOR STATUS [ ".$number."/".$count." ] ==============\n";
	if(!$class->addspin($link)){
		$i--;
		sleep(3);
	}
}
echo "ALL COMPLETE";
?>