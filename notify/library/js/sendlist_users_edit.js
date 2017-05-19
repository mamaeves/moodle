/**
 * 
 */
//$("#add_button").css("border","1px solid red");

$("#add_button").click(add_button_click);
$("#remove_button").click(remove_button_click);

$("#search_text").bind('input',search_text_change($("#search_text").val()));

$("#save_button").click(save_button_click);

function add_button_click(e)
{
	
	var listid=$("#tablelist").data("listid");
	
	var vars=$("#form_list").serialize();
	
	$.ajax(
			{
				
				url:"/local/notify/ajax/save_userlist.php",
				type:"POST",
				data:vars,
				success:function(){
					$("#unlisted_users :selected").each(
							function(i,elem){
								$("#listed_users").append($(elem));
								//alert($(elem).val()+"-"+listid);
							}
					);
				}
			}
	);
	
	
	
	//alert(vars);
}

function remove_button_click(e)
{
	var listid=$("#tablelist").data("listid");
	
	var vars=$("#form_list").serialize();
	
	$.ajax(
			{
				url:"/local/notify/ajax/save_userlist.php",
				type:"POST",
				data:vars,
				success:function(){
					$("#listed_users :selected").each(
							function (i,elem)
							{
								$("#unlisted_users").append($(elem));
							}
					);
				}
			}
	);
	
	
}

function search_text_change(search_string)
{
	
	//alert(search_string);
	send_search_request(search_string);
}

function send_search_request(data)
{
	$.ajax(
			{
				url:"/local/notify/ajax/search_user.php",
				type:"POST",
				data:data,
				xhrFields: {
				      withCredentials: true
				   },
				success:function(msg)
				{
					//alert(msg);
					//console.log(msg);
					var ar=$.parseJSON(msg);
					$("#unlisted_users").empty();
					for(key in ar)
					{
						//alert(ar[key].lastname);
						$("<option/>").val(ar[key].id).html(ar[key].lastname+' '+ar[key].firstname).appendTo("#unlisted_users");
						//$("#unlisted_users").append();
					}
				}		
				
				
			}
	);
}


function save_button_click(e)
{
	var listed_users=$("#listed_users");
	$.ajax(
			{
				url:"/local/notify/ajax/save_userlist.php",
				type:"POST",
				data:{"listed_users":listed_users.serialize()},
				xhrFields: {
				      withCredentials: true
				   },
				complete:function(){
						alert("completed");
				}
						
			}
	);
}
