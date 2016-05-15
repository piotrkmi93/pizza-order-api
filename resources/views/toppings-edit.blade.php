@extends('layouts.app') @section('content')

<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
<script>


    
function loadImage(element){
    var input = $(element).parent().find('input[type="file"]');
    input.click();

    var interval = setInterval(function(){
        if (input.val() != ""){
            $(element).removeClass('btn-primary');
            $(element).addClass('btn-success');
            $(element).text("Zmień");
            clearInterval(interval);
        }
    }, 100);
}
    
function showEdit(element){
    if(!$(element).parent().parent().hasClass('topping-edit')){
        $(element).parent().parent().css('display', 'none');
        $(element).parent().parent().next().css('display', 'table-row');
    } else {
        $(element).parent().parent().css('display', 'none');
        $(element).parent().parent().prev().css('display', 'table-row');
    }   
}
</script>

<style>
    tr:nth-of-type(even) {

        display: none;
    }
</style>

<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Zarządzanie dodatkami</div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nazwa</th>
                                <th>Obrazek</th>
                                <th>Widok</th>
                                <th>Cena (mała)</th>
                                <th>Cena (średnia)</th>
                                <th>Cena (duża)</th>
                                <th>Cena (mega)</th>
                                <th>Edycja</th>
                                <th>Usuń</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($toppings as $toppingIndex => $topping)
                            <tr id="topping-show-{{$topping->id}}">
                                <td>{{$toppingIndex+1}}</td>
                                <td>{{$topping -> name}}</td>
                                <td><img style="height:34px;" src="{{$topping -> img}}"></td>
                                <td><img style="height:34px;" src="{{$topping -> img_pizza}}"></td>
                                @foreach($topping -> prices as $price)
                                <td>{{number_format($price, 2)}} zł</td>
                                @endforeach
                                <td><button class="btn btn-primary" onclick="showEdit(this)">Edytuj</button></td>

                                <form action="{{url('topping/' . $topping -> id)}}" method="POST">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <td><button type="submit" class="btn btn-danger">Usuń</button></td>
                                </form>
                            </tr>
                            
                            <tr class="topping-edit">
                                <form action="{{url('editTopping')}}" method="POST" enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    {!! method_field('POST') !!}

                                    <td>
                                    <td>{{$toppingIndex+1}}</td>
                                        <input type="hidden" name="id" value="{{$topping->id}}">
                                    </td>
                                    <td><input class="form-control" name="name" type="text" maxlength="40" value="{{$topping -> name}}"></td>

                                    <td>
                                        <input name="img" type="file" accept="image/*" style="display:none !important;">
                                        <button onclick="loadImage(this); return false;" name="img" class="btn btn-primary">Wybierz</button>
                                    </td>

                                    <td>
                                        <input name="img_pizza" type="file" accept="image/*" style="display:none !important;">
                                        <button class="btn btn-primary" name="img_pizza" onclick="loadImage(this); return false;">Wybierz</button>
                                    </td>
                                    @foreach($topping -> prices as $index => $price)
                                    <td><input class="form-control" type="number" step="any" min="0" max="25" name="price{{$index+1}}" value="$price" placeholder="{{number_format($price, 2)}} zł"></td>
                                    @endforeach

                                    <td><button class="btn btn-success">OK</button></td>
                                </form>
                                <td><button class="btn btn-danger" onclick="showEdit(this)">Anuluj</button></td>
                            </tr>
                            @endforeach
                            
                            <tr>
                                <form enctype="multipart/form-data" id="add-topping-form" class="form-horizontal" role="form" method="POST" action="{{ url('/add-topping') }}">
                                    {!! csrf_field() !!}

                                    <td>
                                        {{count($toppings)+1}}
                                    </td>

                                    <td>
                                        <div class="form-group{{ $errors->has('text') ? ' has-error' : '' }}">
                                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Nazwa">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                                            <input type="file" accept="image/*" style="display:none !important;" name="img">
                                            <button onclick="loadImage(this); return false;" class="btn btn-primary">Wybierz</button>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                                            <input type="file" accept="image/*" style="display:none !important;" name="img_pizza">
                                            <button onclick="loadImage(this); return false;" class="btn btn-primary">Wybierz</button>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                                            <input type="number" step="any" min="0" max="25" class="form-control" name="price1" value="{{ old('price1') }}" placeholder="Cena (mała)">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                                            <input type="number" step="any" min="0" max="25" class="form-control" name="price2" value="{{ old('price1') }}" placeholder="Cena (średnia)">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                                            <input type="number" step="any" min="0" max="25" class="form-control" name="price3" value="{{ old('price1') }}" placeholder="Cena (duża)">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                                            <input type="number" step="any" min="0" max="25" class="form-control" name="price4" value="{{ old('price1') }}" placeholder="Cena (mega)">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            <button class="btn btn-success" type="submit">Dodaj</button>
                                        </div>
                                    </td>
                                </form>


                                <td>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

<!--

<form action="add-topping" method="post" name="add-topping" enctype="multipart/form-data">
                                    <td>{{count($toppings)+1}}</td>
                                    <td><input class="form-control" type="text" maxlength="40" name="name"></td>

                                    <td>
                                        <input type="file" accept="image/*" style="display:none !important;">
                                        <button type="button" onclick="loadImage(this)" class="btn btn-primary">Wybierz</button>
                                    </td>

                                    <td>
                                        <input type="file" accept="image/*" style="display:none !important;">
                                        <button type="button" class="btn btn-primary" onclick="loadImage(this)">Wybierz</button>
                                    </td>
                                    <td><input class="form-control" type="text" maxlength="5" name="price1"></td>
                                    <td><input class="form-control" type="text" maxlength="5" name="price2"></td>
                                    <td><input class="form-control" type="text" maxlength="5" name="price3"></td>
                                    <td><input class="form-control" type="text" maxlength="5" name="price4"></td>
                                    <td><button class="btn btn-success" type="submit">Dodaj</button></td>
                                    <td><button type="button" class="btn btn-danger">Zeruj</button></td>
                                </form>

-->