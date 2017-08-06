$(document).ready(function()
{              
    $("#form-russianpost").submit(function(e) {
		e.preventDefault();
				
		var cart = 	{
						"courier": $("#courier:checked").is(':checked'),
						"declared-value": Math.floor($('#declared-value').val()*100),
						"dimension": {
							"height": Math.floor($('#height').val()*100),
							"length": Math.floor($('#length ').val()*100),
							"width": Math.floor($('#width').val()*100)
						},
						"fragile": $("#fragile:checked").is(':checked'),
						"index-from": $('#index-from').val(),
						"index-to": $('#index-to').val(),
						"mail-category": $('#mail-category').val(),
						"mail-type": $('#mail-type').val(),
						"mass": $('#mass').val(),
						"payment-method": $('#payment-method').val(),
						"with-order-of-notice": $("#with-order-of-notice:checked").is(':checked'),
						"with-simple-notice": $("#with-simple-notice:checked").is(':checked')
					};
		
			var json_string = JSON.stringify(cart);
			console.log(json_string);
			$.ajax({
				type: "POST",
				url: "tariff.php",
				data: json_string,
				dataType: "text",
				contentType : "application/json",
				success: function(data)
				{
					console.log(data);
					var result = JSON.parse(data);
					if (result.code)
					{
						$('#error').html("Код ошибки: " + result.code + "<br/> Подкатегория ошибки: " + result['sub-code'] + "<br/>" + result.desc);
						$('#error').show();
					}
					else
					{
						var allsum=0;
						$.each(result, function(key, value) 
						{
							switch(key) 
							{
								case 'delivery-time':  	$('#delivery').show();
														var mindays = 0;
														if (value['min-days'])
															mindays = value['min-days'];
														$('#delivery-min-days').html(mindays + GetDaysString(mindays));
														$('#delivery-max-days').html(value['max-days'] + GetDaysString(value['max-days']));
														break;
								case 'notice-rate':  	$('#notice').show();
														$('#notice-rate-rate').html(value.rate/100 + " руб. ");
														var vat = 0;
														if (value.vat)
															vat = value.vat;
														$('#notice-rate-vat').html(vat/100 + " руб. ");
														$('#notice-rate-all').html((vat+value.rate)/100 + " руб.");
														break;
								case 'avia-rate':  		$('#avia').show();
														$('#avia-rate-rate').html(value.rate/100 + " руб. ");
														var vat = 0;
														if (value.vat)
															vat = value.vat;
														$('#avia-rate-vat').html(vat/100 + " руб. ");
														$('#avia-rate-all').html((vat+value.rate)/100 + " руб.");
														break;
								case 'fragile-rate':  	$('#fragile').show();
														$('#fragile-rate-rate').html(value.rate/100 + " руб. ");
														var vat = 0;
														if (value.vat)
															vat = value.vat;
														$('#fragile-rate-vat').html(vat/100 + " руб. ");
														$('#fragile-rate-all').html((vat+value.rate)/100 + " руб.");
														break;
								case 'ground-rate':  	$('#ground').show();
														$('#ground-rate-rate').html(value.rate/100 + " руб. ");
														var vat = 0;
														if (value.vat)
															vat = value.vat;
														$('#ground-rate-vat').html(vat/100 + " руб. ");
														$('#ground-rate-all').html((vat+value.rate)/100 + " руб.");
														break;
								case 'insurance-rate':  $('#insurance').show();
														$('#insurance-rate-rate').html(value.rate/100 + " руб. ");
														var vat = 0;
														if (value.vat)
															vat = value.vat;
														$('#insurance-rate-vat').html(vat/100 + " руб. ");
														$('#insurance-rate-all').html((vat+value.rate)/100 + " руб.");
														break;
								case 'oversize-rate':  	$('#oversize').show();
														$('#oversize-rate-rate').html(value.rate/100 + " руб. ");
														var vat = 0;
														if (value.vat)
															vat = value.vat;
														$('#oversize-rate-vat').html(vat/100 + " руб. ");
														$('#oversize-rate-all').html((vat+value.rate)/100 + " руб.");
														break;
								case 'total-rate':		$('#total-rate-rate').html(value/100 + " руб.");
														allsum+=value;
														break;
								case 'total-vat':		$('#total-rate-vat').html(value/100 + " руб.");
														allsum+=value;
														break;
							}
						});
						
						$('#total-rate-all').html(allsum/100 + " руб.");
						$('#postcalc').hide();
						$('#postresult').show();
					}
				}
			}); 
	});
	
	$.ajax({
		type: "POST",
		url: "query/getrpo.php?tp=0",
		dataType: "application/json",
		contentType : "application/json",
		success: function(dt)
		{
			var obj = jQuery.parseJSON(dt);
			var options = '';
			for (var i = 0; i < obj.data.length; i++) 
			{
				options += '<option value="'+obj.data[i].code+'">' + obj.data[i].name + '</option>';
			}
			$("select#mail-category").html(options);
		}
	}); 
	
	$.ajax({
		type: "POST",
		url: "query/getrpo.php?tp=1",
		dataType: "application/json",
		contentType : "application/json",
		success: function(dt)
		{
			var obj = jQuery.parseJSON(dt);
			var options = '';
			for (var i = 0; i < obj.data.length; i++) 
			{
				options += '<option value="'+obj.data[i].code+'">' + obj.data[i].name + '</option>';
			}
			$("select#mail-type").html(options);
		}
	}); 
}); 

function returnCalc()
{
	$('#delivery').hide();
	$('#notice').hide();
	$('#avia').hide();
	$('#fragile').hide();
	$('#ground').hide();
	$('#insurance').hide();
	$('#oversize').hide();
	$('#total-rate-rate').val('');
	$('#total-rate-vat').html('');
	$('#declared-value').val('0.00');
	$('#height').val('0.00');
	$('#length').val('0.00');
	$('#width').val('0.00');
	$('#index-to').val('');
	$('#mass').val(0);
	$('#postcalc').show();
	$('#postresult').hide();
}

function GetDaysString(days)
{
	var mindays = ' день. ';
	switch (days%10) 
	{
		case 0: 	mindays = ' дней. ';
					break;
		case 1: 	mindays = ' день. ';
					break;
		case 2: 	mindays = ' дня. ';
					break;
		case 3: 	mindays = ' дня. ';
					break;
		case 4: 	mindays = ' дня. ';
					break;
		case 5: 	mindays = ' дней. ';
					break;
		case 6: 	mindays = ' дней. ';
					break;
		case 7: 	mindays = ' дней. ';
					break;
		case 8: 	mindays = ' дней. ';
					break;
		case 9: 	mindays = ' дней. ';
					break;
	}
	
	return mindays;
}
