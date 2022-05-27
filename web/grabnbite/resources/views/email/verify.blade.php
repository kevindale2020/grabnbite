@extends('layouts.email')

@section('content')
<div style="font-size: 16px;">
	<p>Hi,</p>
	<p>Thanks for signing up! Your account is not verified yet, please verify it by clicking the link below:<br/><a href="/verify/{{$data['vkey']}}"></a>http://192.168.137.1:8000/verify/{{$data['vkey']}}</a></p>
</div>
@stop