var idusr=0;

$(document).ready(function() 
{
	$('#tcategoryRPO').DataTable({
		"ajax": 'query/getrpo.php?tp=0',
		"columns": 
		[
			{ "data": "Код" },
			{ "data": "Название" },
			{ "data": "Действие" }
		]
	});
	
	$('#ttyperpo').DataTable({
		"ajax": 'query/getrpo.php?tp=1',
		"columns": 
		[
			{ "data": "Код" },
			{ "data": "Название" },
			{ "data": "Действие" }
		]
	});
	
	$('#users').DataTable({
		"ajax": 'query/getlist.php',
		"columns": 
		[
			{ "data": "№" },
			{ "data": "Логин" },
			{ "data": "Дата создания" },
			{ "data": "Комментарий" },
			{ "data": "Хэш" },
			{ "data": "Статус" },
			{ "data": "Действия" }
		]
	});
		
	$("#form-russianpost").submit(function(e) {
		e.preventDefault();
				
		var formData = {
			'login': $('#login').val(),
			'password': $('#password').val(),
			'comment': $('#comment').val()
		};
		
		$.ajax(	{
			type: "POST",
			url:"script/newuser.php",
			dataType: "html",
			data: formData,
			cache: false,
			success:function(data)
			{
				$('#users').DataTable().ajax.reload();
				if (data=='ERROR')
				{
					alert('Что-то пошло не так. Возможно логин занят.');
				}
				else
				{
					$("#newuser").dialog( "close" );
				}
			}
		});
	});
		
	$("#form-russianpost1").submit(function(e) {
		e.preventDefault();
		
		if ($('#newpassword').val()==$('#password1').val())
		{
			var formData = {
				'id': idusr,
				'password': $('#newpassword').val()
			};
						
			$.ajax(	{
				type: "POST",
				url:"script/newpassword.php",
				dataType: "html",
				data: formData,
				cache: false,
				success:function(data)
				{
					alert(data);
					$('#users').DataTable().ajax.reload();
					if (data=='ERROR')
					{
						alert('Что-то пошло не так. Возможно логин занят.');
					}
					else
					{
						$("#newuser").dialog( "close" );
					}
				}
			});
		}
		else
		{
			alert('Введенные пароли не совпадают. Убедитесь что они одинаковые.');
		}
	});

	$("#newuser").hide();
	$("#changepsw").hide();
	$("#typerpo").hide();
	$("#categoryRPO").hide();
});

function newhash(id)
{
	$.ajax(	{
		type: "GET",
		url:"script/newhash.php?id="+id,
		dataType: "html",
		cache: false,
		success:function(data)
		{
			$('#users').DataTable().ajax.reload();
		}
	});
}
			
function blockuser(id)
{
	$.ajax(	{
		type: "GET",
		url:"script/block.php?id="+id,
		dataType: "html",
		cache: false,
		success:function(data)
		{
			$('#users').DataTable().ajax.reload();
		}
	});
}
			
function unblockuser(id)
{
	$.ajax(	{
		type: "GET",
		url:"script/unblock.php?id="+id,
		dataType: "html",
		cache: false,
		success:function(data)
		{
			$('#users').DataTable().ajax.reload();
		}
	});
}
			
function blockrpo(id)
{
	$.ajax(	{
		type: "GET",
		url:"script/blockrpo.php?id="+id,
		dataType: "html",
		cache: false,
		success:function(data)
		{
			$('#tcategoryRPO').DataTable().ajax.reload();
			$('#ttyperpo').DataTable().ajax.reload();
		}
	});
}
			
function unblockrpo(id)
{
	$.ajax(	{
		type: "GET",
		url:"script/unblockrpo.php?id="+id,
		dataType: "html",
		cache: false,
		success:function(data)
		{
			$('#tcategoryRPO').DataTable().ajax.reload();
			$('#ttyperpo').DataTable().ajax.reload();
		}
	});
}
			
function adduser()
{
	$("#newuser").dialog({
		width: 'auto',
		modal: true,
		resizable: false,
		position: { my: 'top', at: 'top+150' }
	});
}
			
function clclose()
{
	$("#newuser").dialog( "close" );
	$('#login').val('');
	$('#password').val('');
	$('#comment').val('');
}
			
function clclose1()
{
	$("#changepsw").dialog( "close" );
	$('#login').val('');
	$('#password').val('');
	$('#comment').val('');
}


function click_exit()
{
	$.removeCookie("id");
	$.removeCookie("hash");
	window.location.href = "index.php";
}

function newpassword(id)
{
	idusr=id;
	$("#changepsw").dialog({
		width: 'auto',
		modal: true,
		resizable: false,
		position: { my: 'top', at: 'top+150' }
	});
}

function categoryRPO()
{
	$("#categoryRPO").dialog({
		width: 'auto',
		modal: true,
		resizable: false,
		position: { my: 'top', at: 'top+150' }
	});
}

function typerpo()
{
	$("#typerpo").dialog({
		width: 'auto',
		modal: true,
		resizable: false,
		position: { my: 'top', at: 'top+150' }
	});
}