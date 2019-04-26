<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" ></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<style>
.todoinpt{
    width:100%;
    border: none;
}
.todoinpt:focus{   
    outline: none;
}
.card{
    top: 10em;  
}
.edit-input{
    background: #e6e6e645;
}
.span-desc{
    cursor: pointer;
}
</style>
</head>
    <body>
    
        <div id="app">
            <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="card">
                  
                        <ul class="list-group list-group-flush" id="todo-list">
                            <div class="list-group-item">
                            <form method="post" action="{{ route('todos.store') }}" id="add_todo_form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-10">
                                        <input type="text" id="todo_input" placeholder="Add my Todos" class="todoinpt" name="tododesc" autocomplete="off"> 
                                    </div>
                                    <div class="col-md-2">
                                        <button id="add_todo" type="button" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                             </form>
                            </div>
                            @foreach($todos as $todo)
                            <div class="list-group-item" id="todo-item-{{$todo->id}}">
                                <div class="row">
                                    <div class="col-md-10">
                                    
                                        <span title="click to update" class="span-desc" id="span-todo-{{$todo->id}}" data-id="{{$todo->id}}">{{$todo->description}}</span>
                                        <input type="text" id="edit-todo-{{$todo->id}}" value="{{$todo->description}}" data-id="{{$todo->id}}" placeholder="Add my Todos" class="edit-input todoinpt" name="tododesc" autocomplete="off" style="display:none;">
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-default remove-todo" data-id="{{$todo->id}}"><i class="fa fa-times" aria-hidden="true"></i></a>                                 
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </ul>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
      
            </div>
        </div>

    </body>

</html>

<script>
    $('#add_todo').on('click', function(){
        $.ajax({
            url: "{{route('todos.store')}}",
            type: 'post',
            data: $('#add_todo_form').serialize()
        }).then(function(data){
            if(data){
                $('#todo_input').val('');
                var html =' <div class="list-group-item" id="todo-item-'+data.id+'">' +
                                '<div class="row">' +
                                    '<div class="col-md-10"> '+
                                    '<span title="click to update" class="span-desc" id="span-todo-'+data.id+'" data-id="'+data.id+'">'+data.description+'</span>'+
                                       ' <input type="text" id="edit-todo-'+data.id+'" value="'+data.description+'" data-id="'+data.id+'" class="edit-input todoinpt" name="tododesc"  style="display:none;">'+
                                ' </div>'+
                                    '<div class="col-md-2">'+
                                            '<a class="btn-default btn remove-todo" data-id="'+data.id+'"><i class="fa fa-times" aria-hidden="true"></i></a>'+
                                        '</form>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
                $('#todo-list').append(html);

            }
        })
    });

    $(document).on('click','.remove-todo' , function(){
        var id = $(this).data('id');
        $.ajax({
            url: "/todos/" + id,
            type: "delete",
            data: {_token: '{{csrf_token()}}'}
        }).then(function(data){
            if(data.success == true){
                $('#todo-item-'+id).remove();
            }
        });
    });


    $('.span-desc').on('click',function(){
        $(this).hide();
        $('#edit-todo-' + $(this).data('id')).css('display','block');
    })

    $('.edit-input').keypress(function (e) {
        var key = e.which;
        var ei = $(this);
        var id = $(this).data('id');
        var new_todo = $('#edit-todo-'+id);
        var span_todo = $('#span-todo-'+id);
        if(key == 13){

            
            $.ajax({
                url: "/todos/" + id,
                type: 'put',
                data: {
                    _token: '{{csrf_token()}}',
                    tododesc:  new_todo.val()
                }
            }).then(function(data){
                if(data.success == true) {
                    span_todo.text(data.todo.description);
                    ei.hide();
                    $('#span-todo-'+id).css('display','block');
                }
            })
        
            
    
        }

    })
</script>