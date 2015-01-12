<?php
/* $Id: year.php,v 1.67.2.4 2008/03/31 21:03:41 umcesrjones Exp $ */
include_once 'includes/init.php';
include_once 'includes/header.php';

//send_no_cache_header ();

$program_id=(isset($_REQUEST['program_id'])) ? ($_REQUEST['program_id']) : '';
$teacher_id=(isset($_REQUEST['teacher_id'])) ? ($_REQUEST['teacher_id']) : '';
$subject_id=(isset($_REQUEST['subject_id'])) ? ($_REQUEST['subject_id']) : '';
$room_id=(isset($_REQUEST['room_id'])) ? ($_REQUEST['room_id']) : '';
$area_id=(isset($_REQUEST['area_id'])) ? ($_REQUEST['area_id']) : '';
$teacher_type_id=(isset($_REQUEST['teacher_type_id'])) ? ($_REQUEST['teacher_type_id']) : '';
$cycle_id=(isset($_REQUEST['cycle_id'])) ? ($_REQUEST['cycle_id']) : '';
$room_filter_id = (isset($_REQUEST['room_avail_id']))?$_REQUEST['room_avail_id']:'';
$teacher_filter_id = (isset($_REQUEST['teacher_avail_id']))?$_REQUEST['teacher_avail_id']:'';
$program_filter_id = (isset($_REQUEST['program_avail_id']))?$_REQUEST['program_avail_id']:'';

//check UAC
if ( ! access_can_access_function ( ACCESS_YEAR ) || 
  ( ! empty ( $user ) && ! access_user_calendar ( 'view', $user ) )  )
  send_to_preferred_view ();
      
if ( ( $user != $login ) && $is_nonuser_admin )
  load_user_layers ( $user );
else
  load_user_layers ();

load_user_categories ();

if ( empty ( $year ) )
  $year = date ( 'Y' );

$thisyear = $year;
if ( $year != date ( 'Y' ) )
  $thismonth = 1;

// Set up global $today value for highlighting current date.
set_today ( $date );

$nextYear = $year + 1;
$prevYear = $year - ( $year > '1903' ? 1 : 0 );

$startdate = mktime ( 0, 0, 0, 1, 1, $year );
$enddate = mktime ( 23, 59, 59, 12, 31, $year );
$exception_dates=array();
if ( $ALLOW_VIEW_OTHER != 'Y' && ! $is_admin )
  $user = '';

$boldDays = false;
$catSelectStr = '';
if ( ! empty ( $BOLD_DAYS_IN_YEAR ) && $BOLD_DAYS_IN_YEAR == 'Y' ) {
  /* Pre-Load the repeated events for quckier access */
  $repeated_events = read_repeated_events (
    ( ! empty ( $user ) && strlen ( $user ) ? $user : $login ),
    $startdate, $enddate, $cat_id );

  /* Pre-load the non-repeating events for quicker access */
  
  	if($program_id!='' || $teacher_id!='' || $subject_id!='' || $room_id!='' || $area_id!='' || $teacher_type_id!='' || $cycle_id!=''){
		$events = read_events_filters ( ( ! empty ( $user ) && strlen ( $user ) )? $user : $login, $startdate, $enddate, '',$program_id,$teacher_id,$subject_id,$room_id,$area_id,$teacher_type_id,$cycle_id);
	}elseif($room_filter_id!='' || $teacher_filter_id!="" || $program_filter_id!=""){
		$events_detail = read_events_clsrm_teacher_availability ( ( ! empty ( $user ) && strlen ( $user ) ) ? $user : $login, $startdate, $enddate, $cat_id ,$room_filter_id,$teacher_filter_id,$program_filter_id);
		$events=$events_detail[0];
		$exception_dates=(isset($events_detail[1]) && $events_detail[1]!='')? $events_detail[1] : '';
	}else{
 		$events = read_events ( ( ! empty ( $user ) && strlen ( $user ) ? $user : $login ),$startdate, $enddate, $cat_id);
	}
  $boldDays = true;
  $catSelectStr = print_category_menu ( 'year', $thisyear, $cat_id );
  $navStr = display_navigation ( 'year' );
}

// Disable $DISPLAY_ALL_DAYS_IN_MONTH.
$DISPLAY_ALL_DAYS_IN_MONTH = 'N';

//Enable empty weekend days to be visible
$SHOW_EMPTY_WEEKENDS = true;

// Include unapproved events?
$get_unapproved = ( $DISPLAY_UNAPPROVED == 'Y' );

$nextStr = translate ( 'Next' );
$prevStr = translate ( 'Previous' );
$userStr = ( empty ( $user ) ? '' : '&amp;user=' . $user );

$fullnameStr='';
if ( $single_user == 'N' ) {
  if ( ! empty ( $user ) ) {
    user_load_variables ( $user, 'user_' );
    $fullnameStr = $user_fullname;
  } else
    $fullnameStr = $fullname;
}
$asstModeStr = ( $is_assistant
  ? '      <span class="asstmode">-- '
   . translate ( 'Assistant mode' ) . ' --</span>' : '' );

if ( empty ( $friendly ) ) {
  $unapprovedStr = display_unapproved_events ( ( $is_assistant || $is_nonuser_admin
      ? $user : $login ) );
  $printerStr = generate_printer_friendly ( 'year.php' );
} else
  $unapprovedStr = $printerStr = '';
$yr_rows = 3;
/* TODO: Move $yr_rows = 3 to webcal_config as default.
 * Add to webcal_user_prefs for each user.
 */
$yr_cols = intval ( 12 / $yr_rows );
$m = 1;

$gridOmonths = '';

$COUNT=0;
for ( $r = 1; $r <= $yr_rows; $r++ ) {
  $gridOmonths .= '        <tr>';
 
  for( $c = 1; $c <= $yr_cols; $c++, $m++ ) {
  $COUNT=$COUNT+1;
    $gridOmonths .= '
          <td>' . display_small_month ( $m, $year, false,'','','month.php?',$COUNT,$room_filter_id,$teacher_filter_id,$program_filter_id,$exception_dates) . '</td>';
  }
  $gridOmonths .= '
        </tr>';
}
$trailerStr = print_trailer ();
print_header ();
echo <<<EOT
    <table id="filters-table" border="0" width="70%" cellpadding="1" style=" padding-left:98px">
      <tr>
        <td id="filters-td" valign="top" width="70%" rowspan="2" style="padding-top:0px;">
			<fieldset>
  				<legend class="legend-calender">Filters:</legend>
 				{$navStr}
			</fieldset>
		 </td>
       </tr>
    </table>
EOT;
echo <<<EOT
    <table id="filters-table" border="0" width="70%" cellpadding="1" style="padding-top:10px;padding-left:92px;padding-bottom:30px;">
      <tr>
        <td id="filters-td" valign="top" width="70%" rowspan="2">
		  		<p>
					<div>
					   <div class="legend-only" ><strong >Legend:</strong></div>
					   <div class="legend-color-avail" > </div><label class="legend-label">Available</label>
					   <div class="legend-color-use" > </div><label class="legend-label">In Use</label>
					   <div class="legend-color-holiday" > </div><label class="legend-label">Holiday</label>
					   <div class="legend-color-excp" > </div><label >Exception</label>
				   </div>
				</p>
		</td>	
       </tr>
    </table>
EOT;

echo <<<EOT
    <div class="title">
      <a title="{$prevStr}" class="prev" href="year.php?year={$prevYear}{$userStr}" >
        <img src="images/leftarrow.gif" alt="{$prevStr}" style="padding-left:92px;"/></a>
      <a title="{$nextStr}" class="next" href="year.php?year={$nextYear}{$userStr}">
        <img src="images/rightarrow.gif" alt="{$nextStr}"  style="padding-right:74px;"/></a>
      <span class="date">{$thisyear}</span><br />
      <span class="user">{$fullnameStr}</span><br />
      {$asstModeStr}
      {$catSelectStr}
    </div><br />
    <div align="center">
      <table id="monthgrid" width="100%" style="padding-left:92px;">
        {$gridOmonths}
      </table>
    </div><br />
    {$unapprovedStr}<br />
    {$printerStr}
    {$trailerStr}
EOT;

include_once 'footer.php';
?>


