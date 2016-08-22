<?
\Form::start('POST',"admin/login/process");
\Form::text('username',NULL,"Username","class='form-control'");
\Form::pass('password',NULL,"Password","class='form-control'");?>

<div style="text-align:center;color:red">
<?=$message;?>
</div>

<? \Form::submit('LOGIN',"class='btn btn-primary btn-block'");
\Form::end();
?>
