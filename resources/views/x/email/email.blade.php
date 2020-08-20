<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
    <h3>Name - {{$name}} {{$surname}}</h3>
    
    <h3>E_mail - {{$email}}</h3>
    
    @if(isset($num))
    <h3>Phone number - {{$num}}</h3>
    @endif
    
    @if(isset($country))
    <h3>Country - {{$country}}</h3>
    @endif
    
    <p>{!!$msg!!}</p>
</body>
</html>