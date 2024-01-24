{source}
<!-- You can place html anywhere within the source tags -->
<script>
$(document).ready(function() {
$('#tblId').DataTable({
dom: 'Bfrtip',
"buttons" :[{ extend: 'excel', text: 'Export to Excel' },'print'], 
"paging": false,
"ordering": false,
"info": false
});
});

function printIt(id) {
var printContents = document.getElementById(id).innerHTML;
var originalContents = document.body.innerHTML;

document.body.innerHTML = printContents;

window.print();

document.body.innerHTML = originalContents;
}
</script>



<?php

if($_REQUEST['category']){$cid=$_REQUEST['category'];}
else{$cid=0;}

if($_REQUEST['year']){$yr=$_REQUEST['year'];}
else{$yr=0;}

$catg = "http://mprojgar.gov.in/api/DistrictWiseCandidateCount/0";
$year = "http://mprojgar.gov.in/api/YearMaster/0";
//$participants = "http://mprojgar.gov.in/api/DistrictWiseCandidateDetail/".$cid;
$participants = "http://mprojgar.gov.in/api/DistrictWiseCandidateCount/".$cid;

function jasonDtls($path) {
$xmlfile = @file_get_contents($path);
//$con = simplexml_load_string($xmlfile);
$con =$xmlfile;
$con = json_decode($con, true);
return $con;
}



$catg_dtls = jasonDtls($catg)[0];
$year_dtls = jasonDtls($year)[0];
$participants_dtls = jasonDtls($participants)[0];



/*
<?php echo $participants_dtls[$x]['TotalCancellation']; ?>
echo '<pre>';
print_r($catg_dtls); echo "</pre><br/><br/><br/>";
echo '<pre>';
print_r($year_dtls); echo "</pre><br/><br/><br/>";
echo '<pre>';
print_r($participants_dtls); echo "</pre>";*/

?>
<h1> This is for testing publish.</h1>
<form method="get" name="search" >
<div class="row bg-light px-1 py-3 form-group mx-0">
<div class="col-md-5">
<span class="text-black-50">Select District Name</span>
<?php

echo '<select name="category" ><option value=""> All District </option>';
for ($x = 0; $x <= count($catg_dtls); $x++) {
$selected='';
if($catg_dtls[$x]['DistrictId']==$cid){$selected='selected';}

if($catg_dtls[$x]['DistrictId']!='' || $catg_dtls[$x]['DistrictId']=='0'){


$distName=str_replace("District Employment Exchange, ","",$catg_dtls[$x]['DistrictName']);

$distName=str_replace("Special Employement Exchange for PHP, ","",$distName);


?>
<option value="<?php echo $catg_dtls[$x]['DistrictId']; ?>" <?php echo $selected; ?> > <?php echo $distName; ?></option>

<?php
}
}
echo '</select>';
?>
</div>

<!--
<div class="col-md-5">
<span class="text-black-50">Select Year</span>
<?php

echo '<select name="year" ><option value=""> Select </option>';
for ($x = 0; $x <= count($year_dtls); $x++) {
$selected='';
if($year_dtls[$x]['YearId']==$yr){$selected='selected';}

if($year_dtls[$x]['YearId']!=''){
?>
<option value="<?php echo $year_dtls[$x]['YearId']; ?>" <?php echo $selected; ?> > <?php echo $year_dtls[$x]['Year']; ?></option>

<?php
}
}
echo '</select>';
?>
</div>
-->

<div class="col-md-2 align-self-end"><input type="submit" name="sbtnm" value="Search" class="btn btn-primary" /></div>
</div>
</form>

<table id="tblId" class="table table-striped table-bordered table-sm table-hover" align="center" style="width:100%">
<thead>
<tr>
<th>S.No</th>
<th>District Name</th>
<th>Exchange Name</th>
<th>Candidate Count</th>
</tr>
</thead>
<tbody>


<?php for ($x = 0; $x <= count($participants_dtls); $x++) {
if(@$participants_dtls[$x]['DistrictId']){


$districtName=str_replace("District Employment Exchange, ","",$participants_dtls[$x]['DistrictName']);

$districtName=str_replace("Special Employement Exchange for PHP, ","",$districtName);



?><tr>
<th><?php echo $x+1; ?></th>
<td><?php echo $districtName; ?></td>
<td><?php echo $participants_dtls[$x]['DistrictName']; ?></td>
<td><?php echo $participants_dtls[$x]['CandidateCount']; ?></td>



</tr>
<?php

$totalCnt+=$participants_dtls[$x]['CandidateCount'];
}
}
?>
<tr><td></td><td></td><th>Total </th><th><?php echo $totalCnt ?></th></tr>

</tbody>


</table>

<!-- Chart--->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
<div class="col-md-12">
<script type="text/javascript">
google.charts.load('current', {'packages':['bar']});
google.charts.setOnLoadCallback(drawBarChart);
function drawBarChart() {
var data = google.visualization.arrayToDataTable([
['Exchange Name','Total'], 
<?php 
for ($x = 0; $x <= count($participants_dtls); $x++) {
if(@$participants_dtls[$x]['DistrictName'])
{ 

$ProgramName = $participants_dtls[$x]['DistrictName'];

$total = $participants_dtls[$x]['CandidateCount'];


echo "['".$ProgramName."', ".$total."],"; 
} 
}
?> 
]);
var options = {
chart: {
title: 'Employment Exchange',
},
bars: 'vertical', // Required for Material Bar Charts.
};
var chart = new google.charts.Bar(document.getElementById('barchart_material'));
chart.draw(data, google.charts.Bar.convertOptions(options));
}
</script>

<div><input type="button" onclick="printIt('barchart_material')" value="Print Graph!" /></div>
<div id="barchart_material" style="width: 100%; height: 500px;"></div>

</div>


</div>
{/source}