@extends('adminlte::page')

@section('title', 'Inscritos')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Inscritos no Bot ({{$subscribers->total()}})</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered" style="text-align: center">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Cadastrado em</th>
                                <th>Última mensagem em</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($subscribers as $key=>$subscriber)
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$subscriber->first_name}}</td>
                                <td>{{$subscriber->group?'Grupo':'Usuário'}}</td>
                                <td>{{$subscriber->created_at->format('d/m/Y')}}</td>
                                <td>{{$subscriber->updated_at->format('d/m/Y')}}</td>
                            </tr>
                        @empty
                            <tr>
                               <td colspan="5" style="text-align: center">Sem inscritos</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                       {{$subscribers->links()}}
                    </ul>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@stop
@section('js')
@endsection
