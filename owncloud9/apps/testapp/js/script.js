/**
 * ownCloud - testapp
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author susan <liangxueping2015@gmail.com>
 * @copyright susan 2016
 */

(function ($, OC) {

	$(document).ready(function () {
		
		var datastoreId = '1031';
		var username = 'xliang@tnstate.edu';
		var apiKey = 'vKOkrLsSlSfQZY0+YDNkYjjLV8h4RzQKNasQL2DChkk=';
		var tbl="<table class='table table-bordered'><tr><th>Record</th><th>Record ID</th><th>Date/Time</th></tr>";
		
		$('#hello').click(function () {
			alert('Hello from your script file');
		});

		$('#echo').click(function () {
			var url = OC.generateUrl('/apps/testapp/echo');
			var data = {
				echo: $('#echo-content').val()
			};

			$.post(url, data).success(function (response) {
				$('#echo-result').text(response.echo);
			});
			
		});	
		
		$('#loadRecord').click(function () {
			
			upload();
		});
		function upload()
		{					
			alert("user");
			$.ajax({
				   url: "https://api.tierion.com/v1/records?datastoreId="+datastoreId,
				   headers: { 'X-Username': username,
				   'X-Api-Key': apiKey,
				   'Content-Type': 'application/json' },
				   success: function(result){
					   alert(username);
						var getVal=result.records;
						for(var iCounter=0; iCounter < getVal.length; iCounter++)
						{
							tbl +='<tr><td>'+(iCounter+1)+'</td>';
							tbl +="<td>"+getVal[iCounter]['id']+"</td>";
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
			

	});

})(jQuery, OC);