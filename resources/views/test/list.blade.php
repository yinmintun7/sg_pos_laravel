<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

<h2>HTML Table</h2>

<table>
  <tr>
    <th>Company Name</th>
    <th>Company Phone</th>
    <th>Company Email</th>
    <th>Company Address</th>
    <th>Action</th>
  </tr>
  @foreach ($setting as $value )
  <tr>
    <td>{{$value->company_name}}</td>
    <td>{{$value->company_phone}}</td>
    <td>{{$value->company_email}}</td>
    <td>{{$value->company_adress}}</td>
    <td><a href="{{ URL::to('test/edit', ['id' => $value->id]) }}">Edit</a></td>
    <td><a href="{{ URL::to('test/delete', ['id' => $value->id]) }}">Delete</a></td>

  </tr>
  @endforeach


</table>

</body>
</html>

