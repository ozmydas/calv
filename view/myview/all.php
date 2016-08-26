<h3>VIEW ALL AVAILABLE DATA</h3>

<div class="row">
<div class="col-xs-12 col-md-6">

<table id="datatabel">
<thead><tr>
<td>no</td>
<td>ID</td>
<td>Nama</td>
<td>Keterangan</td>
</tr></thead>

<tbody>
<? $i=1;
foreach($result as $key=>$value):?><tr id="klik" data-id="<?=$value['product_id']?>">

	<td><?=$i++?></td>
	<td><?=$value['product_id']?></td>
	<td><?=$value['product_name']?></td>
	<td><?=$value['product_price']?></td>

</tr><?endforeach?>
</tbody>

</table>

</div>


<div  class="col-xs-12 col-md-6 well"><div id="herewego">
<h4 id="title">Tambah Data</h4>
<hr>

<form method="POST" name="myform" action="<?=$site_url?>?route=myapp/save" class="col-xs-10 col-xs-offset-1">

<?\form::text("product_name", null, "Masukkan Nama", "class='form-control' id='frm-name' ")?>
<?\form::text("product_category", null, "Masukkan Kategori", "class='form-control'")?>
<?\form::number("product_price", null, "Masukkan Harga", "class='form-control'")?>
<?\form::textarea("product_info", null, "Masukkan Info", "class='form-control'")?>

<?\form::hidden("product_id", null)?>

<hr>

<?\form::submit("submit", "SAVE", "class='form-control btn btn-success' ")?>
<span id="anotherbtn"></span>

</form>
</div></div>

</div>

<script>
$('[id="klik"]').click(function(){
var id = $(this).attr("data-id");
//alert("i clicked : "+id);

$.ajax({
type:'POST',
url:'<?=$site_url;?>index.php?route=myapp/detail/'+id,
data:'',
success:function(response){
//alert('sending ajax ---> '+response);
var data = JSON.parse(response);

$('#title').html("Data ID : "+data.product_id+" [<span id='addnew' style='color:#06b'>tambah data baru</span>]");

$('input[name=product_id]').val(data.product_id);
$('input[name=product_name]').val(data.product_name);
$('input[name=product_category]').val(data.product_category);
$('input[name=product_price]').val(data.product_price);
$('textarea[name=product_info]').val(data.product_info);
$('input[name=submit]').val("UPDATE");

$('input[name=submit]').attr('class', 'form-control btn btn-primary');
$('span[id=anotherbtn]').html("<?\form::submit('submit', 'DELETE', 'class=\"form-control btn btn-danger\"')?>");
}
});

});

//WHEN USER KLIK BUTTON

$('input[type=submit]').click(function(){
var action = $(this).val();

if(action == "UPDATE"){
update();
}

if(action == "SAVE"){
save();
}

return true;
});


//BUTTON SAVE

function save(){
$('form').attr("action", "<?=$site_url?>?route=myapp/save");
}


//BUTTON UPDATE

function update(){
var id = $('input[name=product_id]').val();

$('form').attr("action", "<?=$site_url?>?route=myapp/update/"+id);
}


//WHEN USER MAU NAMBAH DATA

$('[id="addnew"]').click(function(){
alert("oteee");
});

</script>
