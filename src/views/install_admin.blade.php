@extends('whoisdoma_installer::layouts.master')

@section('page_title','Whoisdoma Installer - Configure Administrator User')

@section('content')
<form method="post">
    <label for="admin_username">Username</label>
    <input type="text" name="admin_username"/><br/>
    <label for="admin_email">Email</label>
    <input type="text" name="admin_email"/><br/>
    <label for="admin_pass">Password</label>
    <input type="password" name="admin_pass"/><br/>
    <input type="submit" value="Next Step"/>
    
</form>
@stop

