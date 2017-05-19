<?php
defined('MOODLE_INTERNAL') || die();
?>
<form id="form_list">
<input type="hidden" name="id" value="<?php echo $id;?>">
<table width="90%" id="tablelist" data-listid="<?php echo $id; ?>">
<tr>
<td width="33%">
<select id="listed_users" name="listed_users[]" multiple="multiple" size="20">

<?php 
foreach($listed_users as $lu)
{
	echo '<option value="'.$lu->id.'">'.$lu->lastname.' '.$lu->firstname.'</option>';
}
?>

</select>

</td>

<td>
	<input type="submit" id="add_button" name="add_button" value="Добавить"> <br>
	<input type="submit" id="remove_button" name="remove_button" value="Удалить">
</td>




<td width="33%">

<select id="unlisted_users" name="unlisted_users[]" multiple="multiple" size="20">

<?php 
foreach($unlisted_users as $ul)
{
	echo '<option value="'.$ul->id.'">'.$ul->lastname.' '.$ul->firstname.'</option>';
}
?>

</select>

<input type="text" id="search_text"></input><button id="search_button">Найти</button>
<script>
	$("#search_button").bind('click',function(){
		var val=$("#search_text").val();
		search_text_change("lastname="+encodeURI(val));
		});
	$("#form_list").submit(function(e){
		e.preventDefault();
		
		});
</script>
</td>

</tr>

</table>

</form>