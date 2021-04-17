@extends('adminlte::page')

@section('title', 'Futebol na TV')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-plus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de Usuários</span>
                    <span class="info-box-number">{{$countUsers}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-gradient-blue elevation-1"><i class="fas fa-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Avisos Automático Ativo</span>
                    <span class="info-box-number">{{$countUsers}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-ban"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Avisos Automático Desabilitado</span>
                    <span class="info-box-number">{{$usersNotificationOff}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{\Carbon\Carbon::now()->format('d/m/Y')}}</span>
                    <span class="info-box-number">
                </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>

    <!-- charts -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Representação das notificações</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartcountUsers" width="400" height="400"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Inscrições por mês</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartUsersMonth" width="400" height="400"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
    </div>
@stop
@section('js')
    <script>
        $(function () {
            let usersNotificationActive = {!!$usersNotificationActive!!};
            let usersNotificationOff = {!! $usersNotificationOff !!};

            let ctx = document.getElementById("chartcountUsers").getContext('2d');
            let chartcountUsers = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ["Usuários com notificações Ativas", "Usuários com Notificações desabilitadas"],
                    datasets: [{
                        backgroundColor: ["#3cba9f","#3e95cd"],
                        data: [usersNotificationActive,usersNotificationOff]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });

            let labelMonths = {!! json_encode($labelMonths) !!}
            let dataMonths = {!! json_encode($dataMonths) !!}
            console.log(labelMonths);
            let cty = document.getElementById("chartUsersMonth").getContext('2d');
            let chartUsersMonth = new Chart(cty, {
                type: 'bar',
                data: {
                    labels: labelMonths,
                    datasets: [
                        {
                            label: "Agrupamento de inscritos por mês",
                            backgroundColor: [
                                "#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9",
                                "#c45850","#3e95cd", "#8e5ea2","#3cba9f",
                                "#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9",
                            ],
                            data: dataMonths
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes:
                            [{ticks: {beginAtZero: true}}]
                    }
                }
            });
        });
    </script>
@endsection
