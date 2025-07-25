@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
            {{-- <h6 class="op-7 mb-2">Admin Dashboard</h6> --}}
        </div>
        {{-- <div class="ms-md-auto py-2 py-md-0">
            <a href="#" class="btn btn-label-info btn-round me-2">Refresh</a>
            <a href="#" class="btn btn-primary btn-round">Add Tesk</a>
        </div> --}}
    </div>


    <div class="row">

        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="far fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Completed</p>
                                <h4 class="card-title">{{ $totalCompleted }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-spinner"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">In Progress</p>
                                <h4 class="card-title">{{ $totalProgress }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                <i class="fas fa-not-equal"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Incompleted</p>
                                <h4 class="card-title">{{ $totalIncompleted }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-luggage-cart"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Sales</p>
                                <h4 class="card-title">$ 1,345</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>




    {{-- =========================== User Column Chart ======================================== --}}

    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Multiple Bar Chart</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="multipleBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- =========================== User Column Chart ======================================== --}}





    {{-- =========================== User Chart ======================================== --}}

    <div class="row g-2">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Pie Chart</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="pieChart" style="width: 50%; height: 50%"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- =========================== User Chart ======================================== --}}






    {{-- =========================== User Statistics & Daily Sales ======================================== --}}
    {{-- <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">User Statistics</div>
                        <div class="card-tools">
                            <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                <span class="btn-label">
                                    <i class="fa fa-pencil"></i>
                                </span>
                                Export
                            </a>
                            <a href="#" class="btn btn-label-info btn-round btn-sm">
                                <span class="btn-label">
                                    <i class="fa fa-print"></i>
                                </span>
                                Print
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                        <canvas id="statisticsChart"></canvas>
                    </div>
                    <div id="myChartLegend"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Daily Sales</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-label-light dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Export
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-category">March 25 - April 02</div>
                </div>
                <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                        <h1>$4,578.58</h1>
                    </div>
                    <div class="pull-in">
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card card-round">
                <div class="card-body pb-0">
                    <div class="h1 fw-bold float-end text-primary">+5%</div>
                    <h2 class="mb-2">17</h2>
                    <p class="text-muted">Users online</p>
                    <div class="pull-in sparkline-fix">
                        <div id="lineChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- =========================== User Statistics & Daily Sales ======================================== --}}


    {{-- =========================== Users location ======================================== --}}
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <h4 class="card-title">Users Geolocation</h4>
                        <div class="card-tools">
                            <button class="btn btn-icon btn-link btn-primary btn-xs">
                                <span class="fa fa-angle-down"></span>
                            </button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card">
                                <span class="fa fa-sync-alt"></span>
                            </button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs">
                                <span class="fa fa-times"></span>
                            </button>
                        </div>
                    </div>
                    <p class="card-category">
                        Map of the distribution of users around the world
                    </p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive table-hover table-sales">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="flag">
                                                    <img src="assets/img/flags/id.png" alt="indonesia" />
                                                </div>
                                            </td>
                                            <td>Indonesia</td>
                                            <td class="text-end">2.320</td>
                                            <td class="text-end">42.18%</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flag">
                                                    <img src="assets/img/flags/us.png" alt="united states" />
                                                </div>
                                            </td>
                                            <td>USA</td>
                                            <td class="text-end">240</td>
                                            <td class="text-end">4.36%</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flag">
                                                    <img src="assets/img/flags/au.png" alt="australia" />
                                                </div>
                                            </td>
                                            <td>Australia</td>
                                            <td class="text-end">119</td>
                                            <td class="text-end">2.16%</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flag">
                                                    <img src="assets/img/flags/ru.png" alt="russia" />
                                                </div>
                                            </td>
                                            <td>Russia</td>
                                            <td class="text-end">1.081</td>
                                            <td class="text-end">19.65%</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flag">
                                                    <img src="assets/img/flags/cn.png" alt="china" />
                                                </div>
                                            </td>
                                            <td>China</td>
                                            <td class="text-end">1.100</td>
                                            <td class="text-end">20%</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flag">
                                                    <img src="assets/img/flags/br.png" alt="brazil" />
                                                </div>
                                            </td>
                                            <td>Brasil</td>
                                            <td class="text-end">640</td>
                                            <td class="text-end">11.63%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mapcontainer">
                                <div id="world-map" class="w-100" style="height: 300px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- =========================== Users location ======================================== --}}



    {{-- =========================== transaction section ======================================== --}}
    {{-- <div class="row">
        <div class="col-md-4">
            <div class="card card-round">
                <div class="card-body">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">New Customers</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-list py-4">
                        <div class="item-list">
                            <div class="avatar">
                                <img src="assets/img/jm_denis.jpg" alt="..." class="avatar-img rounded-circle" />
                            </div>
                            <div class="info-user ms-3">
                                <div class="username">Jimmy Denis</div>
                                <div class="status">Graphic Designer</div>
                            </div>
                            <button class="btn btn-icon btn-link op-8 me-1">
                                <i class="far fa-envelope"></i>
                            </button>
                            <button class="btn btn-icon btn-link btn-danger op-8">
                                <i class="fas fa-ban"></i>
                            </button>
                        </div>
                        <div class="item-list">
                            <div class="avatar">
                                <span class="avatar-title rounded-circle border border-white">CF</span>
                            </div>
                            <div class="info-user ms-3">
                                <div class="username">Chandra Felix</div>
                                <div class="status">Sales Promotion</div>
                            </div>
                            <button class="btn btn-icon btn-link op-8 me-1">
                                <i class="far fa-envelope"></i>
                            </button>
                            <button class="btn btn-icon btn-link btn-danger op-8">
                                <i class="fas fa-ban"></i>
                            </button>
                        </div>
                        <div class="item-list">
                            <div class="avatar">
                                <img src="assets/img/talha.jpg" alt="..." class="avatar-img rounded-circle" />
                            </div>
                            <div class="info-user ms-3">
                                <div class="username">Talha</div>
                                <div class="status">Front End Designer</div>
                            </div>
                            <button class="btn btn-icon btn-link op-8 me-1">
                                <i class="far fa-envelope"></i>
                            </button>
                            <button class="btn btn-icon btn-link btn-danger op-8">
                                <i class="fas fa-ban"></i>
                            </button>
                        </div>
                        <div class="item-list">
                            <div class="avatar">
                                <img src="assets/img/chadengle.jpg" alt="..." class="avatar-img rounded-circle" />
                            </div>
                            <div class="info-user ms-3">
                                <div class="username">Chad</div>
                                <div class="status">CEO Zeleaf</div>
                            </div>
                            <button class="btn btn-icon btn-link op-8 me-1">
                                <i class="far fa-envelope"></i>
                            </button>
                            <button class="btn btn-icon btn-link btn-danger op-8">
                                <i class="fas fa-ban"></i>
                            </button>
                        </div>
                        <div class="item-list">
                            <div class="avatar">
                                <span class="avatar-title rounded-circle border border-white bg-primary">H</span>
                            </div>
                            <div class="info-user ms-3">
                                <div class="username">Hizrian</div>
                                <div class="status">Web Designer</div>
                            </div>
                            <button class="btn btn-icon btn-link op-8 me-1">
                                <i class="far fa-envelope"></i>
                            </button>
                            <button class="btn btn-icon btn-link btn-danger op-8">
                                <i class="fas fa-ban"></i>
                            </button>
                        </div>
                        <div class="item-list">
                            <div class="avatar">
                                <span class="avatar-title rounded-circle border border-white bg-secondary">F</span>
                            </div>
                            <div class="info-user ms-3">
                                <div class="username">Farrah</div>
                                <div class="status">Marketing</div>
                            </div>
                            <button class="btn btn-icon btn-link op-8 me-1">
                                <i class="far fa-envelope"></i>
                            </button>
                            <button class="btn btn-icon btn-link btn-danger op-8">
                                <i class="fas fa-ban"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Transaction History</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Payment Number</th>
                                    <th scope="col" class="text-end">Date & Time</th>
                                    <th scope="col" class="text-end">Amount</th>
                                    <th scope="col" class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        Payment from #10231
                                    </th>
                                    <td class="text-end">Mar 19, 2020, 2.45pm</td>
                                    <td class="text-end">$250.00</td>
                                    <td class="text-end">
                                        <span class="badge badge-success">Completed</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        Payment from #10231
                                    </th>
                                    <td class="text-end">Mar 19, 2020, 2.45pm</td>
                                    <td class="text-end">$250.00</td>
                                    <td class="text-end">
                                        <span class="badge badge-success">Completed</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        Payment from #10231
                                    </th>
                                    <td class="text-end">Mar 19, 2020, 2.45pm</td>
                                    <td class="text-end">$250.00</td>
                                    <td class="text-end">
                                        <span class="badge badge-success">Completed</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        Payment from #10231
                                    </th>
                                    <td class="text-end">Mar 19, 2020, 2.45pm</td>
                                    <td class="text-end">$250.00</td>
                                    <td class="text-end">
                                        <span class="badge badge-success">Completed</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        Payment from #10231
                                    </th>
                                    <td class="text-end">Mar 19, 2020, 2.45pm</td>
                                    <td class="text-end">$250.00</td>
                                    <td class="text-end">
                                        <span class="badge badge-success">Completed</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        Payment from #10231
                                    </th>
                                    <td class="text-end">Mar 19, 2020, 2.45pm</td>
                                    <td class="text-end">$250.00</td>
                                    <td class="text-end">
                                        <span class="badge badge-success">Completed</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        Payment from #10231
                                    </th>
                                    <td class="text-end">Mar 19, 2020, 2.45pm</td>
                                    <td class="text-end">$250.00</td>
                                    <td class="text-end">
                                        <span class="badge badge-success">Completed</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- =========================== transaction section ======================================== --}}

    </div>
@endsection

@push('scripts')
    <script>
        // PIE CHART START
        var pieChart = document.getElementById("pieChart").getContext("2d");
        var myPieChart = new Chart(pieChart, {
            type: "pie",
            data: {
                datasets: [{
                    data: [{{ $totalCompleted }}, {{ $totalProgress }}, {{ $totalIncompleted }}],
                    backgroundColor: ["#1d7af3","#fdaf4b", "#f3545d" ],
                    borderWidth: 0,
                }, ],
                labels: ["Total Completed", "In Progress", "In Completed"],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "bottom",
                    labels: {
                        fontColor: "rgb(154, 154, 154)",
                        fontSize: 11,
                        usePointStyle: true,
                        padding: 20,
                    },
                },
                pieceLabel: {
                    render: "percentage",
                    fontColor: "white",
                    fontSize: 14,
                },
                tooltips: false,
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20,
                    },
                },
            },
        });
        //   PIE CHART END


        //  multiple Bar CHART START
    //     var multipleBarChart = document.getElementById("multipleBarChart").getContext("2d");

    //     var myMultipleBarChart = new Chart(multipleBarChart, {
    //     type: "bar",
    //     data: {
    //       labels: [
    //         "Jan",
    //         "Feb",
    //         "Mar",
    //         "Apr",
    //         "May",
    //         "Jun",
    //         "Jul",
    //         "Aug",
    //         "Sep",
    //         "Oct",
    //         "Nov",
    //         "Dec",
    //       ],
    //       datasets: [
    //         {
    //           label: "First time visitors",
    //           backgroundColor: "#59d05d",
    //           borderColor: "#59d05d",
    //           data: [95, 100, 112, 101, 144, 159, 178, 156, 188, 190, 210, 245],
    //         },
    //         {
    //           label: "Visitors",
    //           backgroundColor: "#fdaf4b",
    //           borderColor: "#fdaf4b",
    //           data: [
    //             145, 256, 244, 233, 210, 279, 287, 253, 287, 299, 312, 356,
    //           ],
    //         },
    //         {
    //           label: "Pageview",
    //           backgroundColor: "#177dff",
    //           borderColor: "#177dff",
    //           data: [
    //             185, 279, 273, 287, 234, 312, 322, 286, 301, 320, 346, 399,
    //           ],
    //         },
    //       ],
    //     },
    //     options: {
    //       responsive: true,
    //       maintainAspectRatio: false,
    //       legend: {
    //         position: "bottom",
    //       },
    //       title: {
    //         display: true,
    //         text: "Traffic Stats",
    //       },
    //       tooltips: {
    //         mode: "index",
    //         intersect: false,
    //       },
    //       responsive: true,
    //       scales: {
    //         xAxes: [
    //           {
    //             stacked: true,
    //           },
    //         ],
    //         yAxes: [
    //           {
    //             stacked: true,
    //           },
    //         ],
    //       },
    //     },
    //   });
    //  multiple Bar CHART END


    </script>
@endpush
