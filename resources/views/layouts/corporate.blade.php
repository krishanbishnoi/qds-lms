<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ trans('panel.site_title') }}</title>
    @php $faviconicon = Helper::getWebSettings('favicon') @endphp
    <link href="{{ url('setting') . '/' . $faviconicon }}" rel="shortcut icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="{{ asset('corporate/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('corporate/css/bootstrap/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('corporate/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('corporate/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('corporate/css/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('corporate/css/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('corporate/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('corporate/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('corporate/css/fancybox.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('corporate/css/style.css') }}">
</head>
<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            @include('partials.corporate-menu')
            <!-- partial -->
            <div class="main-panel dashboardMain">
                <div class="dashboardContent">
                    @yield('content')
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('corporate/js/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('corporate/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('corporate/js/jquery.timepicker.min.js') }}"></script>
    <script src="{{ asset('corporate/js/fusion/fusioncharts.js') }}"></script>
    <script src="{{ asset('corporate/js/fusion/fusioncharts.theme.fusion.js') }}"></script>
    <script src="{{ asset('corporate/js/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('corporate/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('corporate/js/slick.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('corporate/js/lottie-player.js') }}" type="text/javascript"></script>
    <script src="{{ asset('corporate/js/data-tables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('corporate/js/data-tables/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('corporate/js/data-tables/dataTables.buttons.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('corporate/js/data-tables/dataTables.select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('corporate/js/chart.js') }}"></script>
    <script src="{{ asset('corporate/js/moment.min.js') }}"></script>
    <script src="{{ asset('corporate/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('corporate/js/fancybox.umd.js') }}"></script>
    <script src="{{ asset('corporate/js/custom.js') }}" type="text/javascript"></script>
    @yield('scripts')
    <script>
        // Price Range Slider Start
        window.onload = function() {
            slideOne();
            slideTwo();
        };

        let sliderOne = document.getElementById("slider-1");
        let sliderTwo = document.getElementById("slider-2");
        let displayValOne = document.getElementById("range1");
        let displayValTwo = document.getElementById("range2");
        let minGap = 0;
        let sliderTrack = document.querySelector(".slider-track");
        let sliderMaxValue = document.getElementById("slider-1").max;

        function slideOne() {
            if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
                sliderOne.value = parseInt(sliderTwo.value) - minGap;
            }
            displayValOne.innerHTML = `<i class="bi bi-currency-rupee"></i>${sliderOne.value}`;
            fillColor();
        }

        function slideTwo() {
            if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
                sliderTwo.value = parseInt(sliderOne.value) + minGap;
            }
            displayValTwo.innerHTML = `<i class="bi bi-currency-rupee"></i>${sliderTwo.value}`;
            fillColor();
        }

        function fillColor() {
            percent1 = (sliderOne.value / sliderMaxValue) * 100;
            percent2 = (sliderTwo.value / sliderMaxValue) * 100;
            sliderTrack.style.background =
                `linear-gradient(to right, #dadae5 ${percent1}% , #3A266B ${percent1}% , #3A266B ${percent2}%, #dadae5 ${percent2}%)`;
        }
        // Price Range Slider Start
    </script>
    <script>
        /* ENd make equal height */
        var data = {
            labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
            datasets: [{
                    label: "",
                    colorFill: "rgba(220,220,220,2)",
                    borderColor: "#3A266B",
                    pointBackgroundColor: "#3A266B",
                    // pointBorderColor: "#fff",
                    // pointHoverBackgroundColor: "#3A266B",
                    // pointHoverBorderColor: "#3A266B",
                    data: [15, 25, 35, 20, 30, 25, 40, 20, 25, 30, 20, 27],
                },
                {
                    label: "",
                    backgroundColor: "#6BCECE",
                    borderColor: "#6BCECE",
                    pointBackgroundColor: "#6BCECE",
                    pointBorderColor: "#fff",
                    data: [10, 20, 30, 15, 25, 20, 35, 15, 20, 25, 11, 17],
                },
            ],
        };

        var options = {
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: true,
                        lineWidth: 1,
                    },
                },
                y: {
                    display: true,
                    grid: {
                        display: true,
                        lineWidth: 1,
                    },
                },

            },
            elements: {
                point: {
                    radius: 4,
                    borderWidth: 0,
                    hitRadius: 20,
                    hoverRadius: 4,
                    hoverBorderWidth: 1,
                },
                line: {},
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    enabled: false,
                },
            },
        };

        var ctx = document.getElementById("myChart").getContext("2d");
        var myChart = new Chart(ctx, {
            type: "line",
            data: data,
            options: options,
        });

        var ctxx = document.getElementById("barChart").getContext("2d");

        var mybarChart = new Chart(ctxx, {
            type: 'bar',
            data: {
                labels: ['DEPT1', 'DEPT2', 'DEPT3', 'DEPT4', 'DEPT5', 'DEPT6', 'DEPT7'],
                datasets: [{
                    label: 'Candidate A Votes',
                    backgroundColor: "#6BCECE",
                    data: [20, 40, 60, 35, 25, 45, 75]
                }]
            },

            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: false,
                    },
                },
            }
        });

        $(function() {
            $("#datepicker").datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,

            });
            $("#timepicker").timepicker({
                timeFormat: "h:mm p",
                interval: 30,
                minTime: "06",
                maxTime: "23:55pm",
                defaultTime: "06",
                startTime: "01:00",
                dynamic: true,
                dropdown: true,
                scrollbar: false
            });
        });
        new FusionCharts({
            type: "radialbar",
            width: "100%",
            height: "100%",
            renderAt: "chart-container",

            dataSource: {
                chart: {
                    theme: "fusion",
                    //   caption: "Top 5 Android OS Market Share",
                    //   subCaption: "January 2021",
                    showLegend: 0,
                    innerRadius: 30,
                    outerRadius: 155,
                    showLabels: 1,
                    labelText: "<b>$label</b>"

                },

                data: [{
                        label: "Jaipur",
                        value: 95,
                        color: "#6BCECE"
                    },
                    {
                        label: "jodhpur",
                        value: 30,
                        color: "#3A266B"

                    },
                    {
                        label: "Bikaner",
                        value: 70,
                        color: "#6BCECE"

                    },
                    {
                        label: "Ajmer",
                        value: 90,
                        color: "#3A266B"

                    },
                    {
                        label: "Udaipur",
                        value: 75,
                        color: "#6BCECE"

                    }
                ]
            }
        }).render();
    </script>
    <!-- End custom js for this page -->
</body>

</html>
