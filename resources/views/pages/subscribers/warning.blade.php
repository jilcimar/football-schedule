@extends('adminlte::page')

@section('title', 'Inscritos')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Disparar Aviso</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{route('sendWarning')}}" role="form">
                    @csrf
                    <div class="card-body">
                        <div class="callout callout-danger">
                            <h5>Atenção!</h5>
                            <p>Ao enviar o formulário a mensagem é enviada instantaneamente para o(s) usuário(s)</p>
                        </div>
                        <div class="form-group">
                            <label for="tipe">Enviar para: <span class="text-red">*</span></label>
                            <select class="form-control" name="type" required>
                                <option value="0" selected>Todos usuários</option>
                                @forelse($subscribers as $subscriber)
                                    <option value="{{$subscriber->chat_id}}">{{$subscriber->first_name}}</option>
                                @empty
                                    Sem inscritos
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="text">Mensagem <span class="text-red">*</span></label>
                            <textarea class="form-control" rows="6" name="text" placeholder="Mensagem" required></textarea>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <a href="{{route('home')}}" class="btn btn-default">Cancelar</a>
                        <button type="submit" class="btn btn-primary float-right">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('js')
@endsection
