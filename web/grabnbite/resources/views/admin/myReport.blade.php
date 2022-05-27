<!DOCTYPE html>
<html>
<head>
<style>

body {
	font-family: Arial, Helvetica, sans-serif;
}

#customers {
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #292b2c;
  color: white;
}
</style>
</head>
<body>

<h4 style="text-align: center;">List of Earnings Between {{$report['start']}} and {{$report['end']}}</h4>

<table id="customers">
  <tr>
    <th>Date</th>
    <th>Order ID</th>
    <th>Customer</th>
    <th>Total</th>
  </tr>
  @foreach($report['results'] as $row)
  <tr>
	<td>{{$row['date']}}</td>
	<td>{{$row['id']}}</td>
	<td>{{$row['fname'].' '.$row['lname']}}</td>
	<td>{{$row['total']}}</td>
  </tr>
  @endforeach
</table>
	<p style="margin-top: 16px;text-align: right;"><b>Total Earnings:</b> {{$report['total']}}</p>
</body>
</html>


