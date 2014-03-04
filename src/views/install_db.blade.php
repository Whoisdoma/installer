@extends('whoisdoma_installer::layouts.master')

@section('page_title','Whoisdoma Installer - Configure Database')

@section('content')
<form method="post">
    <label for="db_type">Database Type</label>
    <select name="db_type">
        <option value="mysqli">MySQL Improved</option>
        <option value="mysql">MySQL Standard</option>
    </select><br/>
    <label for="db_host">Database Host</label>
    <input type="text" name="db_host"/><br/>
    <label for="db_name">Database Name</label>
    <input type="text" name="db_name"/><br/>
    <label for="db_user">Database User</label>
    <input type="text" name="db_user"/><br/>
    <label for="db_pass">Database Password</label>
    <input type="password" name="db_pass"/><br/>
    <label for="db_prefix">Table Prefix</label>
    <input type="text" name="db_prefix"/><br/>
    <input type="submit" value="Next Step"/>
    
</form>
@stop

