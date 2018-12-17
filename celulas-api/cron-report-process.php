<?php
/*
	Cron Report Process
*/

$link = mysqli_connect("localhost", "toolbox_celulapi", "&L#3Olef6)jS", "toolbox_celulas_api_final");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

// update reports is_complete 1
$report = "SELECT * FROM reports where is_complete = '0'";

if ($result_up = mysqli_query($link, $report)) {

    while ($record = mysqli_fetch_assoc($result_up)) {

        $report_id = $record["id"];
        $final_date = date("Y-m-d H:i:s");

        $sql = "UPDATE reports SET is_complete = '1', updated_at = '$final_date' WHERE id = $report_id";  

        mysqli_query($link, $sql) or die(mysqli_error($link));
    }

    /* free result_up set */
    mysqli_free_result($result_up);
}

/* Process to add steps to selected guests */

// add steps of salvation
$report_salvation = "SELECT member_id, DATE(updated_at) as final_date, updated_at FROM reports_details where salvations = '1' AND assigned = '1'";

if ($result_salvation = mysqli_query($link, $report_salvation)) {

    while ($f_salvation = mysqli_fetch_assoc($result_salvation)) {

        $member_id = $f_salvation["member_id"];
        $final_date = $f_salvation["final_date"];
        $updated_at = $f_salvation["updated_at"];
        $today = date("Y-m-d");

        // remove step if exist
        $remove_step = "DELETE FROM members_cells_steps WHERE member_id = $member_id AND step_id = '1'";

        mysqli_query($link, $remove_step) or die(mysqli_error($link));

        // adding step 
        $create_salvation = "INSERT INTO members_cells_steps (`member_id`, `name`, `step_date`,`step_id`,`created_at`, `updated_at`) VALUES ($member_id, 'Salvation','$final_date','1', '$updated_at', '$updated_at')";

        mysqli_query($link, $create_salvation) or die(mysqli_error($link));
    }

    /* free result_salvation set */
    mysqli_free_result($result_salvation);
}

// add steps of baptized
$report_bap = "SELECT member_id, DATE(updated_at) as final_date, updated_at FROM reports_details where baptized = '1' AND assigned = '1'";

if ($result_bap = mysqli_query($link, $report_bap)) {

    while ($f_bap = mysqli_fetch_assoc($result_bap)) {

        $member_id = $f_bap["member_id"];
        $final_date = $f_bap["final_date"];
        $updated_at = $f_bap["updated_at"];
        $today = date("Y-m-d");

        // remove step if exist
        $remove_step2 = "DELETE FROM members_cells_steps WHERE member_id = $member_id AND step_id = '2'";

        mysqli_query($link, $remove_step2) or die(mysqli_error($link));

        // adding step 
        $create_baptized = "INSERT INTO members_cells_steps (`member_id`, `name`, `step_date`,`step_id`,`created_at`, `updated_at`) VALUES ($member_id, 'Baptized','$final_date','2', '$updated_at', '$updated_at')";

        mysqli_query($link, $create_baptized) or die(mysqli_error($link));
    }

    /* free result_bap set */
    mysqli_free_result($result_bap);
}


// update the assigned field to the members
$report_assign = "SELECT * FROM reports_details where assigned = '1'";

if ($result_assign = mysqli_query($link, $report_assign)) {

    while ($fields = mysqli_fetch_assoc($result_assign)) {

        $detail_id = $fields["id"];
        $final_date = date("Y-m-d H:i:s");

        $update_assign = "UPDATE reports_details SET assigned = '0', updated_at = '$final_date' WHERE id = $detail_id";  

        mysqli_query($link, $update_assign) or die(mysqli_error($link));
    }

    /* free result_assign set */
    mysqli_free_result($result_assign);
}

// update the block field to the members
$report_block = "SELECT * FROM members_cells where block = '1'";

if ($result_block = mysqli_query($link, $report_block)) {

    while ($field = mysqli_fetch_assoc($result_block)) {

        $member_id = $field["id"];
        $final_date = date("Y-m-d H:i:s");

        $update_block = "UPDATE members_cells SET block = '0', updated_at = '$final_date' WHERE id = $member_id";  

        mysqli_query($link, $update_block) or die(mysqli_error($link));
    }

    /* free result_assign set */
    mysqli_free_result($result_block);
}


/***** NEW REPORTS *****/

$cells = "SELECT * FROM groups_cells WHERE active = 1";

if ($result = mysqli_query($link, $cells)) {

    /* fetch associative array */
    while ($row = mysqli_fetch_assoc($result)) {

        $final_d = date("Y-m-d H:i:s");
        $today = date("Y-m-d");

        $district_code = $row["district_code"];
        $zone_code = $row["zone_code"];
        $sector_code = $row["sector_code"];
        $cell_code = $row["cell_code"];
        $cell_id = $row["id"];
        $leader = $row["leader"];

        /*Name of report*/
        $report_y = date('Y', strtotime($today. ' + 1 days'));

        $month = date('n', strtotime($today. ' + 1 days'));
        $quarter = null;

        if($month < 4){
          $quarter = "1";
        } elseif($month > 3 && $month <7){
          $quarter = "2";
        } elseif($month >6 && $month < 10){
          $quarter = "3";
        } elseif($month >9){
          $quarter = "4";
        }

        $currentWeekNumber = date('W', strtotime($today. ' + 1 days'));

        $fweek = '0';
        $fweek = $currentWeekNumber;

        if($currentWeekNumber > '13'){
          if ($currentWeekNumber == '14') {
            $fweek = '1';
          } else if ($currentWeekNumber == '15') {
            $fweek = '2';
          } else if ($currentWeekNumber == '16') {
            $fweek = '3';
          } else if ($currentWeekNumber == '17') {
            $fweek = '4';
          } else if ($currentWeekNumber == '18') {
            $fweek = '5';
          } else if ($currentWeekNumber == '19') {
            $fweek = '6';
          } else if ($currentWeekNumber == '20') {
            $fweek = '6';
          } else if ($currentWeekNumber == '21') {
            $fweek = '7';
          } else if ($currentWeekNumber == '22') {
            $fweek = '8';
          }else if ($currentWeekNumber == '23') {
            $fweek = '9';
          } else if ($currentWeekNumber == '24') {
            $fweek = '10';
          } else if ($currentWeekNumber == '25') {
            $fweek = '11';
          } else if ($currentWeekNumber == '26') {
            $fweek = '12';
          } else if ($currentWeekNumber == '27') {
            $fweek = '1';
          } else if ($currentWeekNumber == '28') {
            $fweek = '2';
          } else if ($currentWeekNumber == '29') {
            $fweek = '3';
          } else if ($currentWeekNumber == '30') {
            $fweek = '4';
          }else if ($currentWeekNumber == '31') {
            $fweek = '5';
          }else if ($currentWeekNumber == '32') {
            $fweek = '6';
          }else if ($currentWeekNumber == '33') {
            $fweek = '7';
          }else if ($currentWeekNumber == '34') {
            $fweek = '8';
          }else if ($currentWeekNumber == '35') {
            $fweek = '9';
          }else if ($currentWeekNumber == '36') {
            $fweek = '10';
          }else if ($currentWeekNumber == '37') {
            $fweek = '11';
          }else if ($currentWeekNumber == '38') {
            $fweek = '12';
          }else if ($currentWeekNumber == '39') {
            $fweek = '13';
          }else if ($currentWeekNumber == '40') {
            $fweek = '1';
          }else if ($currentWeekNumber == '41') {
            $fweek = '2';
          }else if ($currentWeekNumber == '42') {
            $fweek = '3';
          }else if ($currentWeekNumber == '43') {
            $fweek = '4';
          }else if ($currentWeekNumber == '44') {
            $fweek = '5';
          }else if ($currentWeekNumber == '45') {
            $fweek = '6';
          }else if ($currentWeekNumber == '46') {
            $fweek = '7';
          }else if ($currentWeekNumber == '47') {
            $fweek = '8';
          }else if ($currentWeekNumber == '48') {
            $fweek = '9';
          }else if ($currentWeekNumber == '49') {
            $fweek = '10';
          }else if ($currentWeekNumber == '50') {
            $fweek = '11';
          }else if ($currentWeekNumber == '51') {
            $fweek = '12';
          }else if ($currentWeekNumber == '52') {
            $fweek = '13';
          }

        }

        $report_name = $district_code.$zone_code.$sector_code.$cell_code."-".$report_y."-".$quarter."-".$fweek;

        $end_date = date('Y-m-d', strtotime($today. ' + 6 days'));


        if (empty($leader)) {
            $create_report = "INSERT INTO reports (`name`, `creation_date`,`end_date`, `donations_offering`,`donations_events`,`donations_transport`,`total_member_assistance`,`total_kids_assistance`,`total_guest_assistance`,`total_doctrine`,`total_celebration`,`total_salvation`,`total_baptized`,`total_schedule_visits`,`cell_id`,`district_code`,`zone_code`,`sector_code`,`cell_code`,`is_complete`,`created_by`,`year`,`quarter`,`week`,`created_at`, `updated_at`) VALUES ('$report_name', '$today','$end_date', '0.00','0.00','0.00','0','0','0','0','0','0','0','0',$cell_id,$district_code,$zone_code,$sector_code,$cell_code,'0',NULL,$report_y,$quarter,$currentWeekNumber,'$final_d', '$final_d')";
        }else{

            $create_report = "INSERT INTO reports (`name`, `creation_date`,`end_date`, `donations_offering`,`donations_events`,`donations_transport`,`total_member_assistance`,`total_kids_assistance`,`total_guest_assistance`,`total_doctrine`,`total_celebration`,`total_salvation`,`total_baptized`,`total_schedule_visits`,`cell_id`,`district_code`,`zone_code`,`sector_code`,`cell_code`,`is_complete`,`created_by`,`year`,`quarter`,`week`,`created_at`, `updated_at`) VALUES ('$report_name', '$today','$end_date', '0.00','0.00','0.00','0','0','0','0','0','0','0','0',$cell_id,$district_code,$zone_code,$sector_code,$cell_code,'0','$leader',$report_y,$quarter,$currentWeekNumber,'$final_d', '$final_d')";
        }

        mysqli_query($link, $create_report) or die(mysqli_error($link));

        $report_id = mysqli_insert_id($link);

        // add leaders to reports details
        if (!empty($leader)) {
            $create_detail_lead = "INSERT INTO reports_details (`cell_group`, `doctrine`, `celebration`,`salvations`,`baptized`,`scheduled_visits`,`report_id`,`member_id`,`user_id`,`assigned`,`created_at`, `updated_at`) VALUES ('0', '0','0','0','0','0',$report_id,NULL,$leader,'0', '$final_d', '$final_d')";
        
            mysqli_query($link, $create_detail_lead) or die(mysqli_error($link));
        }

        // add reports details (members)
        $members_cells = "SELECT * FROM members_cells WHERE cell_id = $cell_id AND (role = 1 OR role = 2) AND active = 1";

        if ($result2 = mysqli_query($link, $members_cells)) {

            while ($row2 = mysqli_fetch_assoc($result2)) {

                $member_id = $row2["id"];

                $create_detail = "INSERT INTO reports_details (`cell_group`, `doctrine`, `celebration`,`salvations`,`baptized`,`scheduled_visits`,`report_id`,`member_id`,`user_id`,`assigned`,`created_at`, `updated_at`) VALUES ('0', '0','0','0','0','0',$report_id,$member_id,NULL,'0', '$final_d', '$final_d')";

                mysqli_query($link, $create_detail) or die(mysqli_error($link));
            }

            // /* free result2 set */
            // mysqli_free_result($result2);
        }  

    }

    /* free result set */
    mysqli_free_result($result);
}

/* close connection */
mysqli_close($link);


?>