<?php
/*
Plugin Name: RashiMantra
Plugin URI: https://rashimantra.com/
Description: This is a simple extension of wp-avd-quiz that displays each registered user score at the bottom of their avdfile. 
Version: 1.0
Author: Souvik
Author URI: https://rashimantra.com/
*/



add_action('show_user_avdfile', 'nijjwal_wp_avd_quiz_result_function');

function nijjwal_wp_avd_quiz_result_function()
{
	global $wpdb;

	$tableA = $wpdb->prefix.'wp_avd_quiz_statistic_ref';
	$tableB = $wpdb->prefix.'wp_avd_quiz_statistic';
	$tableC = $wpdb->prefix.'wp_avd_quiz_master';
	$currentUserId = get_current_user_id();


	$result = $wpdb->get_results( "SELECT  a.statistic_ref_id,
										   a.quiz_id,
										   a.user_id,
										   a.create_time,
									       c.name,
									       count(a.statistic_ref_id) as totalnumofquestions,
									       sum(b.points) as yourscore
									FROM   $tableA a,
									       $tableB b,
									       $tableC c
									WHERE  a.statistic_ref_id = b.statistic_ref_id
									AND    a.user_id = $currentUserId
									AND    c.id = a.quiz_id
									GROUP BY a.statistic_ref_id;");



	echo "<hr/><h2>Your Test Scores:</h2>";
	if(empty($result))
	{
		echo 'You have not taken any quiz!';
		return;
	}


	echo "<table>";
	?>
	  <tr>
	  	<th>Quiz Time</th>
	    <th>Quiz Name</th>
	    <th>Total number of questions</th>
	    <th>Total number of correct answers</th>
	    <th>Result</th>
	  </tr>
	<?php

		foreach($result as $row)
		{
			echo "<tr>";
				echo "<td style='border:1px solid black; text-align:center;'>".date('Y-m-d h:i:s',$row->create_time)."</td>";
				echo "<td style='border:1px solid black; text-align:center;'>".$row->name."</td>";
				echo "<td style='border:1px solid black; text-align:center;'>".$row->totalnumofquestions."</td>";
				echo "<td style='border:1px solid black; text-align:center;'>".$row->yourscore."</td>";
				echo "<td style='border:1px solid black; text-align:center;'>".number_format((($row->yourscore)*100)/($row->totalnumofquestions),2)." %"."</td>";
			echo "</tr>";

		}
	echo "</table>";
}
?>
