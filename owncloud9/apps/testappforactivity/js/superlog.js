function SuperLog(){
	
}
SuperLog.prototype={
	get:function(){
		var datas = {debug:true};
		$('#superlog_filter input,#superlog_filter select').each(function(){
			if($(this).val()!=''){
				datas[$(this).attr('id').replace('superlog_','')]=$(this).val();
			}
		});
		$.ajax({
			type: 'GET',
			url:OC.linkTo('superlog', 'ajax/list.php'),
			dataType: 'json',
			data:datas,
			async: true,
			success: function (logs) {
				var n=0;
				for(var i in logs['data']){
					n++;
					the_item=logs['data'][i];
					console.log(the_item);
					var line='<tr>';
					line+='<td>'+the_item['user']+'</td>';
					line+='<td>'+the_item['activity']+'</td>';
					line+='<td>'+the_item['date']+'</td>';
					line+='</tr>';
					$('#superlogs_results').append(line);
				}
				if(n==0){
					$('#superlog_more').fadeOut(500);
				}
				$('#superlog_start').val(parseInt($('#superlog_start').val())+n);
			}				
		});
	}
};

$(document).ready(function(){
	if($('#superlog').length>0){
		superlogs=new SuperLog();
		
		$('#superlog_filter label').hide();
		$('#superlog_filters').click(function(){
			$('#superlog_filter label').toggle();
		});
		$('#superlog_filter input,#superlog_filter select').change(function(){
			$('#superlog_more').fadeIn(500);
			$('#superlogs_results tr').remove();
			$('#superlog_start').val(0);
		});
		$('#superlog_more').click(function(){
			superlogs.get();
		});
		superlogs.get();
	}
});
