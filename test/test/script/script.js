var records, key;
var datastoreId = '1031';
var username = 'xliang@tnstate.edu';
var apiKey = 'vKOkrLsSlSfQZY0+YDNkYjjLV8h4RzQKNasQL2DChkk=';

$(document).ready(function() {
   			
		$('#loadRecord').click(function () {
			upload();
		});

		$('.RecordEntry').click(function () {
			showDetails(this.html);
		});
});

function upload()
{
	var tbl="<table class='table table-bordered'><tr><th>Record</th><th>Record ID</th><th>Date/Time</th></tr>";
			$.ajax({
				   url: "https://api.tierion.com/v1/records?datastoreId="+datastoreId,
				   headers: { 'X-Username': username,
				   'X-Api-Key': apiKey,
				   'Content-Type': 'application/json' },
				   success: function(result){
						var getVal=result.records;
						for(var iCounter=0; iCounter < getVal.length; iCounter++)
						{
							tbl +='<tr><td><a onclick="showDetails(\''+getVal[iCounter]['id']+'\');">Record '+(iCounter+1)+'</a></td>';
							tbl +="<td class='RecordEntry'>"+getVal[iCounter]['id']+"</td>";
							tbl +="<td>"+timeConverter(getVal[iCounter]['timestamp'])+"</td></tr>";
						}
						tbl+="</table>";	
						$("#divloadData").html(tbl);
					},
					error: function(xhr, textStatus, errorThrown){
							$("#divloadData").html("<p> Sorry no records found. </p>" + textStatus + errorThrown);
					}
					
				});
}
function showDetails(recordID)
{
	var tbl="<table class='table table-bordered'><tr><th>File Name</th><th>user</th><th>operation</th><th>File Location</th><th>affecteduser</th><th>Blockchain receipt</th><th>Bitcoin transaction</th></tr><tbody>";
	$.ajax({
		   url: "https://api.tierion.com/v1/records/"+recordID,
		   headers: { 'X-Username': username,
		   'X-Api-Key': apiKey,
		   'Content-Type': 'application/json' },
		   success: function(resultRecord){
				var getVal=resultRecord.json;
				getVal=getVal.replaceAll('\'','"');
				var jsonVal=JSON.parse(getVal);
				tbl += "<tr><td>" + jsonVal.object_name + "</td><td>" + jsonVal.user + "</td><td>" + jsonVal.type + "</td><td>";
				tbl += jsonVal.link + "</td><td>" + jsonVal.affecteduser + "</td><td>Target Hash:&nbsp&nbsp&nbsp";
				tbl += resultRecord.blockchain_receipt.targetHash + "<br />Merkle Root:&nbsp&nbsp&nbsp&nbsp";
				tbl += resultRecord.blockchain_receipt.merkleRoot + "</td><td><a href='https://www.blocktrail.com/BTC/tx/";
				tbl += resultRecord.blockchain_receipt.anchors[0].sourceId +"' target='_blank'>";
				tbl += resultRecord.blockchain_receipt.anchors[0].sourceId + "</a></td></tr></tbody></table>";
				$("#divRecord").html(tbl);
			},
			error: function(xhr, textStatus, errorThrown){
					$("#divRecord").html("<p> Sorry no records found. </p>");
			}
			
		});
}

function timeConverter(UNIX_timestamp){
  var a = new Date(UNIX_timestamp * 1000);
  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var year = a.getFullYear();
  var month = months[a.getMonth()];
  var date = a.getDate();
  var hour = a.getHours();
  var min = a.getMinutes();
  var sec = a.getSeconds();
  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
  return time;
}

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

function showRecord()
{
	var recordID=$("#txtUserRecordID").val();
	var tbl="<table class='table table-bordered'><tr><th>Device Type</th><th>Date/Time</th><th>Measurement/Dosage</th><th>Units</th><th>Drug</th></tr><tbody>";
    
	if(recordID)
	{
		var dataOld="<p>Loading in progress ... <span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span></p> <div class='divImg'><img src='img/datastream0.gif' width='500px'/></div> ";
		$('#divRecordData').html(dataOld);
	
		$.ajax({
			   url: "https://api.tierion.com/v1/records/"+recordID,
			   headers: { 'X-Username': username,
			   'X-Api-Key': apiKey,
			   'Content-Type': 'application/json' },
			   success: function(result){
					var getVal=JSON.parse(result.json).recorddata;
					getVal=getVal.replaceAll('\'','"');
					var jsonVal=JSON.parse(getVal);
					for (var i = 0; i < jsonVal["Device_Type"].length; i++) {
						tbl+="<tr><td>";
						tbl+=jsonVal["Device_Type"][i];
						tbl+="</td>";
						tbl+="<td>";
						tbl+=jsonVal["Date_Time"][i];
						tbl+="</td>";
						tbl+="<td>";
						tbl+=jsonVal["Measurment_Dosage"][i];
						tbl+="</td>";
						tbl+="<td>" ;
						tbl+=jsonVal["Units"][i];
						tbl+="</td>";
						tbl+="<td>";
						tbl+=jsonVal["Drug"][i];
						tbl+="</td></tr>";
					}
					tbl+="</tbody></table>";
					$("#divRecordData").html(tbl);
				},
				error: function(xhr, textStatus, errorThrown){
					$("#divRecordData").html("<p> Sorry no records found. </p>");
				}
				
			});
	}
}
