<?php

add_shortcode( 'imok_commands', 'imok_commands_func' );
function imok_commands_func(){
	$user = wp_get_current_user();

	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$response = $_POST['command'];
	}
	else{
		$response = $_GET['command'];
	}

	if($response == 'imok'){
		return imok_pushed();
		//return 'IMOK your OK';
	}
	if($response == 'imnotok'){
		return 'I am not OK';
	}

	}

function imok_pushed(){
	//get date / time
	$current_unix_time = current_time("timestamp" , 0); //in unix time no gmt

	//get users date time
	$user = wp_get_current_user();
	$imok_start_date =  get_user_meta( $user->ID, 'imok_start_date', true );
	$imok_start_time = get_user_meta( $user->ID, 'imok_start_time', true );
	$start_date_time_string = $imok_start_date . ' ' . $imok_start_time;
	$start_unix_time = strtotime( $start_date_time_string ); //convert to unix time

	return $start_unix_time;

	//compare


/*
 if( $current_time_stamp <= $now ){#alarm was/is triggered
  until( $new_time_stamp  > $now ){#we do this loop as we are basing our repeating Alert date/times based on our initial setting, not based on when we click the imok button
   $new_time_stamp = $new_time_stamp + $user->{'timeout_sec'};
  }
 $message = "$message Alarm was likely triggered. Please email your contacts and tell them you are OK.";
 #send out IMOK email. Member has checked in...
}
elsif( ($current_time_stamp - $user->{'timeout_sec'}) <= $now ){ #we are clicking just before alarm is triggered
 until( $new_time_stamp  > ($now + $user->{'timeout_sec'}) ){
   $new_time_stamp = $new_time_stamp + $user->{'timeout_sec'};
  }
}
elsif( ($current_time_stamp - $user->{'timeout_sec'}) > $now  ){# we are a full timeout before the time stamp. do nothing
  return 1;
}#do nothing
*/

	//process

	//give feedback

}

/*
sub imok(){
my $logged_in = $AuthorizeMeObj->AmILoggedIn();
my $user = $AuthorizeMeObj->{'user'};
if( ! $logged_in ){return 0;}
my $filename = "$AuthorizeMeObj->{'settings'}->{'path_to_users'}$user->{'user_id'}";
my ($dev,$ino,$mode,$nlink,$uid,$gid,$rdev,$size,$atime,$current_time_stamp,$ctime,$blksize,$blocks) = stat($filename);
#my $new_time_stamp = $current_time_stamp; #will ALWAYS jump to next start_time (after now) + timeout

#my $new_time_stamp = $user->{'start_unix_time'}; #will ALWAYS jump to next start_time (after now) + timeout
my $new_time_stamp = $current_time_stamp; #will ALWAYS jump to next start_time (after now) + timeout

my $now = time();

if( $current_time_stamp <= $now ){#alarm was/is triggered
  until( $new_time_stamp  > $now ){#we do this loop as we are basing our repeating Alert date/times based on our initial setting, not based on when we click the imok button
   $new_time_stamp = $new_time_stamp + $user->{'timeout_sec'};
  }
 $message = "$message Alarm was likely triggered. Please email your contacts and tell them you are OK.";
 #send out IMOK email. Member has checked in...
}
elsif( ($current_time_stamp - $user->{'timeout_sec'}) <= $now ){ #we are clicking just before alarm is triggered
 until( $new_time_stamp  > ($now + $user->{'timeout_sec'}) ){
   $new_time_stamp = $new_time_stamp + $user->{'timeout_sec'};
  }
}
elsif( ($current_time_stamp - $user->{'timeout_sec'}) > $now  ){# we are a full timeout before the time stamp. do nothing
  return 1;
}#do nothing

#trigger time on users computer
my $new_time_stamp_user_tz = $new_time_stamp + ($user->{'tz_offset_hours' } * 60 * 60);
my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = gmtime($new_time_stamp_user_tz);
$mon = $mon + 1;
$year = 1900 + $year;
my $trigger_time_string = sprintf("%d-%.2d-%.2d  %d:%.2d", $year , $mon , $mday , $hour , $min);

my $result = &set_time_stamp($new_time_stamp , $filename);
if($result == 1){
 $message = "$message Trigger time updated.";
}
else{
 $message = "$message IMOK trigger time failed. Please try again.";
}
 &write_to_log("$user->{'email'} checked in.");
return $result;
}
*/


?>
