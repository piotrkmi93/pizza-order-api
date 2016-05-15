@extends('layouts.app')

@section('content')

    <script>

        function changeStatus(orderId, element){
            $("#change-status").click();
        }

    </script>

    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Zarządzanie zamówieniami</div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Adres</th>
                                <th>Telefon</th>
                                <th>Dodatki</th>
                                <th>Wielkość</th>
                                <th>Cena</th>
                                <th>Status</th>
                                <th>Usuń</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $orderIndex => $order)
                            <tr>
                                <td style="background-color: {{$colors[$orderIndex]}}">{{$orderIndex+1}}</td>
                                <td>{{$order -> address}}</td>
                                <td>{{$order -> phone}}</td>
                                <td>
                                    <ul>
                                        @foreach($order -> toppings as $topping)
                                            <li>{{$topping}}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{$order -> size}}</td>
                                <td>{{number_format($order -> price, 2)}} zł</td>
                                <td>
                                    <form action="{{url('changeStatus')}}" method="GET">

                                        <input type="hidden" name="orderId" value="{{$order -> id}}">
                                        <select class="form-control" name="statusId" onchange="changeStatus({{$order -> id}}, this)">
                                            @foreach($statuses as $status)
                                                <option {{$status -> id === $order -> status ? "selected" : ""}} value="{{$status -> id}}">{{$status -> name}}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" style="display:none;" id="change-status"></button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{url('deleteOrder/' . $order -> id)}}" method="POST">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button type="submit" class="btn btn-danger">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
