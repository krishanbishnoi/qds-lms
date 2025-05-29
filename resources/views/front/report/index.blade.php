@extends('front.layouts.trainee-default')
@section('content')
    <div class="dashboard-main">
        <div class="containter-fluid">
            <div class="d-flex flex-wrap justify-content-end">
                {{-- Include Sidebar --}}
                @include('front.layouts.trainee-sidebar')
                <div class="dashboard_content">
                    <div class="dashboard_head mb-md-5 d-lg-flex justify-content-between">
                        <div class="">
                            <h1 class="fs-2">My <span>Report</span></h1>
                        </div>
                        {{-- for ntifications file --}}
                        @include('front.layouts.notification')
                    </div>
                    <div class="box">
                        <div class="row">
                            <div class="col-xl-5 col-xxl-4 mb-3">
                                <div class="trainee-info innerBox h-100">
                                    <h2 class="fs-6 fw-semibold text-dark mb-3">Trainee Information</h2>
                                    <div class="profile d-flex align-items-center mb-2 mb-md-3">
                                        @if (!empty(Auth::user()->image))
                                            <i><img src="{{ USER_IMAGE_URL . Auth::user()->image }}" alt="img"></i>
                                        @else
                                            @php
                                                $first_letter = strtoupper(substr(Auth::user()->fullname, 0, 1));
                                                $space_index = strpos(Auth::user()->fullname, ' ');
                                                $second_letter = strtoupper(
                                                    substr(Auth::user()->fullname, $space_index + 1, 1),
                                                );
                                            @endphp
                                            <i class="employeeFirstLetter">
                                                {{ $first_letter . '' . $second_letter }}</i>
                                        @endif
                                        <div class="ms-3">
                                            <h3 class="blue-text fs-6 mb-0">
                                                {{ ucwords(Auth::user()->first_name . ' ' . Auth::user()->last_name) }}</h3>
                                            <p class="mb-0">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                    <div class="designation">
                                        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3">
                                            <div class="manager d-flex align-items-center">
                                                <img src="{{ asset('front/img/manager-icon.svg') }} " alt=""
                                                    width="30">
                                                <div class="ms-3">
                                                    <p class="blue-text mb-0">Manager Name</p>
                                                    <p class="mb-0">{{ $user->parentManager->fullname }}</p>
                                                </div>
                                            </div>
                                            <div class="manager d-flex align-items-center">
                                                <img src="{{ asset('front/img/trainee-icon.svg') }}" alt=""
                                                    width="25">
                                                <div class="ms-3">
                                                    <p class="blue-text mb-0">Trainee ID</p>
                                                    <p class="mb-0">{{ Auth::user()->employee_id }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="manager d-flex align-items-center">
                                                <img src="{{ asset('front/img/manager-icon.svg') }} " alt=""
                                                    width="30">
                                                <div class="ms-3">
                                                    <p class="blue-text mb-0">Mobile Number</p>
                                                    <p class="mb-0">{{ Auth::user()->mobile_number }}</p>
                                                </div>
                                            </div>
                                            <div class="manager d-flex align-items-center">
                                                <img src="{{ asset('front/img/trainee-icon.svg') }}" alt=""
                                                    width="25">
                                                <div class="ms-3">
                                                    <p class="blue-text mb-0">Center & Location</p>
                                                    <p class="mb-0">
                                                        {{ isset(Auth::user()->circle) && !empty(Auth::user()->circle) ? ucwords(Auth::user()->circle) : '' }}
                                                        /
                                                        {{ isset(Auth::user()->location) && !empty(Auth::user()->location) ? ucwords(Auth::user()->location) : '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Training Count And test Count --}}
                            @php
                                $totalTrainingCount = $userTrainings->count();
                                $totalTestCount = $userTests->count();
                                $tatalTestSubmitted = $completedTests->count();
                            @endphp
                            <div class="col-xl-7 col-xxl-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="complete-training">
                                            <h2 class="fs-6 fw-semibold text-dark">Trainings Completed</h2>
                                            <div class="row">
                                                <div class="col-8 pe-0">
                                                    <div class="d-flex align-items-end mt-4">
                                                        <img src="{{ asset('front/img/report-icon-1.svg') }}"
                                                            alt="" width="38">
                                                        <i class="bi bi-check-circle-fill greentxt ms-2"></i>
                                                        <span class="ms-2 fw-bold fs-7">{{ $totalTrainingCount }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-4 d-flex align-items-end justify-content-end">
                                                    <strong class="fw-bold fs-4 mb-0">{{ $totalTrainingCount }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="spent-hours">
                                            <h2 class="fs-6 fw-semibold text-dark">Test Completed</h2>
                                            <div class="row">
                                                <div class="col-8 pe-0">
                                                    <div class="d-flex align-items-end mt-4">
                                                        <img src="{{ asset('front/img/report-icon-1.svg') }}"
                                                            alt="" width="38">
                                                        <i class="bi bi-check-circle-fill greentxt ms-2"></i>
                                                        <span class="ms-2 fw-bold fs-7">{{ $tatalTestSubmitted }}
                                                            Attend</span>
                                                    </div>
                                                </div>
                                                <div class="col-4 d-flex align-items-end justify-content-end">
                                                    <strong class="fw-bold fs-4 mb-0">{{ $totalTestCount }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="ongoing-training">
                                            <div class="row">
                                                <div class="col-8">
                                                    <h2 class="fs-6 fw-semibold text-dark">Hours Spent</h2>
                                                    <div class="mt-4">
                                                        <img src="{{ asset('front/img/report-icon-3.svg') }}"
                                                            alt="" width="38">
                                                    </div>
                                                </div>
                                                <div class="col-4 d-flex align-items-end justify-content-end">
                                                    <strong class="fw-bold fs-4 mb-0">00</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="upcoming-training">
                                            <div class="row">
                                                <div class="col-8">
                                                    <h2 class="fs-6 fw-semibold text-dark">Hours Spent</h2>
                                                    <div class="mt-4">
                                                        <img src="{{ asset('front/img/report-icon-4.svg') }}"
                                                            alt="" width="38">
                                                    </div>
                                                </div>
                                                <div class="col-4 d-flex align-items-end justify-content-end">
                                                    <strong class="fw-bold fs-4 mb-0">00</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center justify-content-between selecthour mb-2">
                                    <h2 class="fs-6 fw-semibold text-dark">Hours Spent</h2>
                                    <select class="selectpicker cstmSelect">
                                        <option selected disabled>Duration</option>
                                        <option>Monthly</option>
                                        <option>Daily</option>
                                        <option>Weekly</option>
                                    </select>
                                </div>
                                <div class="duration-graph innerBox mt-3">
                                    <div id="hoursSpentChart"></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center justify-content-between selecthour mb-2">
                                    <h2 class="fs-6 fw-semibold text-dark">Trainee Performance</h2>
                                    <select class="selectpicker cstmSelect">
                                        <option selected disabled>Duration</option>
                                        <option>Monthly</option>
                                        <option>Daily</option>
                                        <option>Weekly</option>
                                    </select>
                                </div>
                                <div class="performance-scale innerBox mt-3">
                                    <span class="perfomLbel"><i></i> Performance</span>
                                    <div id="meterchart"></div>
                                    <span class="d-block text-center mt-3 fw-bold light-text">Trainee Points - <b
                                            class="text-black">556</b></span>
                                    <strong class="orangeTxt d-block text-center fw-semibold">15th on leader board <img
                                            src="../img/rocket-icon.svg" width="13" height="13"></strong>
                                </div>
                            </div>
                        </div> --}}
                        <div class="trainingTab nav-tabs d-sm-flex justify-content-between align-items-center my-3"
                            id="tab" role="tablist">
                            <h2 class="fs-5 fw-semibold blue-text">Completed Trainings And Tests</h2>
                            <div class="tab-menu d-flex mb-3 mb-md-0">
                                <button class="tabButton active" data-bs-toggle="tab"
                                    data-bs-target="#complatedTrainingsTab" type="button"
                                    aria-controls="complatedTrainingsTab" aria-selected="true" role="tab">
                                    Trainings</button>
                                <button class="tabButton" data-bs-toggle="tab" data-bs-target="#completedTestsTab"
                                    type="button" aria-controls="completedTestsTab" aria-selected="false"
                                    tabindex="-1" role="tab"> Tests</button>
                            </div>
                        </div>
                        <div class="trainingTabContent">
                            <div class="tab-content" id="tab-Content">
                                <div class="tab-pane fade show active" id="complatedTrainingsTab" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-center">Time Left</th>
                                                    <th class="text-center">Score</th>
                                                    <th class="text-center">Result</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (is_array($reportData) || is_object($reportData))
                                                    @foreach ($reportData as $report)
                                                        <tr>
                                                            <td>{{ $report['trainingName'] }}</td>
                                                            <td class="text-center">{{ $report['trainingType'] }}</td>
                                                            <td
                                                                class="text-center {{ $report['timeLeft'] === 'Expired' ? 'text-danger' : '' }}">
                                                                {{ $report['timeLeft'] === 'Expired' ? 'Expired' : $report['timeLeft'] }}
                                                            </td>

                                                            @if ($report['obtainedMarks'] > 0 && $report['obtainedMarks'] >= $report['passingMarks'])
                                                                <td class="text-center greentxt">
                                                                    {{ $report['obtainedMarks'] }}
                                                                </td>
                                                            @elseif ($report['obtainedMarks'] > 0 && $report['obtainedMarks'] <= $report['passingMarks'])
                                                                <td class="text-center text-danger">
                                                                    {{ $report['obtainedMarks'] }}
                                                                </td>
                                                            @else
                                                                <td class="text-center">
                                                                    --
                                                                </td>
                                                            @endif

                                                            @if ($report['obtainedMarks'] > 0 && $report['status'] == 'Passed')
                                                                <td class="text-center greentxt">
                                                                    {{ $report['status'] }}
                                                                </td>
                                                            @elseif ($report['obtainedMarks'] > 0 && $report['status'] == 'Failed')
                                                                <td class="text-center text-danger">
                                                                    {{ $report['status'] }}
                                                                </td>
                                                            @else
                                                                <td class="text-center">--</td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {{--  Complated Test --}}
                                <div class="tab-pane fade" id="completedTestsTab" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-center">Passing Score</th>
                                                    <th class="text-center">Obtained Score</th>
                                                    {{-- <th class="text-center">Hrs. Spent</th> --}}
                                                    <th class="text-center">Result</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (is_array($completedTests) || is_object($completedTests))
                                                    @foreach ($completedTests as $test)
                                                        {{--  Complated tests --}}
                                                        <tr>
                                                            <td>{{ ucFirst($test->title) }}</td>
                                                            @if ($test->type == 'regular_test')
                                                                <td class="text-center">Regular Test</td>
                                                            @elseif($test->type == 'feedback_test')
                                                                <td class="text-center">Feedback Test</td>
                                                            @else
                                                                <td class="text-center">Training Test</td>
                                                            @endif
                                                            <td class="text-center">{{ $test->minimum_marks }}</td>
                                                            <td class="text-center greentxt">{{ $test->score }}</td>
                                                            {{-- <td class="text-center">{{ $test->type }}</td> --}}
                                                            @if ($test->score >= $test->minimum_marks)
                                                                <td class="greentxt">Passed</td>
                                                            @else
                                                                <td class="danger-text">Failed</td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Taraining Detail Modal -->
    <div class="modal fade trainingDetailModal" id="trainingDetailModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Training Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <strong class="mb-2">Training Name</strong>
                    <p>Lorem Ipsum is simply dumm</p>
                    <strong class="mt-3  mb-2">About this Training</strong>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of type and scrambled it to make a type specimen book.</p>
                    <strong class="mt-3 mb-2">Other Details</strong>
                    <p>Training Type : Lorem Ipsum</p>
                    <p>Trainee : 50</p>
                    <p>Total Content : 4</p>
                    <p>Total Time to finish : 5 Days</p>
                    <strong class="mt-3 mb-2">Certificates</strong>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                    <p>Complete the training and test to achieve this certificate</p>
                    <span class="btn btn-light py-2 px-3 fs-7 mt-3">Certificate</span>
                </div>
            </div>
        </div>
    </div>
    <div class="overllayBg" id="overllayBg" style="display: none;"></div>
    <script>
        //Hours Spent Chart Js
        function dashWidgetMeter() {
            var el = d3.select('#meterchart');
            percent = 0.20;
            padRad = 0;
            chartInset = 0;
            // Calculate the available space for the widget
            var containerWidth = el.node().getBoundingClientRect().width;
            var containerHeight = containerWidth * 0.6;
            // Orientation of gauge:
            totalPercent = 0.75;
            width = containerWidth;
            height = containerHeight;
            radius = Math.min(width, height) / 2;
            barWidth = 18 * width / 360;
            var color = d3.scaleLinear()
                .domain([1, 0.2, 10])
                .range(['#FF7C00']);
            /*
             Utility methods
             */
            percToDeg = function(perc) {
                return perc * 360;
            };
            percToRad = function(perc) {
                return degToRad(percToDeg(perc));
            };
            degToRad = function(deg) {
                return deg * Math.PI / 180;
            };
            // Create SVG element with the calculated dimensions
            svg = el.append('svg')
                .attr('width', width)
                .attr('height', height / 1.7);
            // Add layer for the panel
            chart = svg.append('g').attr('transform', `translate(${width / 2}, ${height / 2})`);
            var arcFilled = chart.append('path').attr('class', 'arc chart-filled'),
                arcEmpty = chart.append('path').attr('class', 'arc chart-empty'),
                arc2 = d3.arc().outerRadius(radius - chartInset).innerRadius(radius - chartInset - barWidth);
            arc1 = d3.arc().outerRadius(radius - chartInset).innerRadius(radius - chartInset - barWidth);
            repaintGauge = function(perc) {
                var next_start = totalPercent,
                    arcStartRad1 = percToRad(next_start),
                    arcEndRad1 = arcStartRad1 + percToRad(perc / 2);
                arc1.startAngle(arcStartRad1).endAngle(arcEndRad1);
                next_start += perc / 2;
                arcStartRad2 = percToRad(next_start);
                arcEndRad2 = arcStartRad2 + percToRad((1 - perc) / 2);
                arc2.startAngle(arcStartRad2 + padRad).endAngle(arcEndRad2);
                arcFilled.attr('d', arc1)
                    .attr('fill', function() {
                        return color(perc);
                    });
                arcEmpty.attr('d', arc2);
            };
            var Needle = (function() {
                var recalcPointerPos = function(perc) {
                    var centerX,
                        centerY,
                        leftX,
                        leftY,
                        rightX,
                        rightY,
                        thetaRad,
                        topX,
                        topY;
                    thetaRad = percToRad(perc / 2);
                    centerX = 0;
                    centerY = 0;
                    topX = centerX - this.len * Math.cos(thetaRad);
                    topY = centerY - this.len * Math.sin(thetaRad);
                    leftX = centerX - this.radius * Math.cos(thetaRad - Math.PI / 2);
                    leftY = centerY - this.radius * Math.sin(thetaRad - Math.PI / 2);
                    rightX = centerX - this.radius * Math.cos(thetaRad + Math.PI / 2);
                    rightY = centerY - this.radius * Math.sin(thetaRad + Math.PI / 2);
                    return `M ${leftX} ${leftY} L ${topX} ${topY} L ${rightX} ${rightY}`;
                };

                function Needle(el) {
                    this.el = el;
                    this.len = width / 4.3;
                    this.radius = this.len / 10;
                }
                Needle.prototype.render = function() {
                    this.el.append('circle').attr('class', 'needle-center').attr('cx', 0).attr('cy', 0).attr(
                        'r', this.radius).attr('fill', '#FF7C00');
                    return this.el.append('path').attr('class', 'needle').attr('d', recalcPointerPos.call(this,
                        0)).attr('fill', '#FF7C00');
                };
                Needle.prototype.moveTo = function(perc) {
                    var self = this;
                    this.perc = perc;
                    this.el.transition().duration(1500).select('.needle').tween('progress', function() {
                        return function(percentOfPercent) {
                            var progress = percentOfPercent * perc;
                            repaintGauge(progress);
                            return d3.select(this).attr('d', recalcPointerPos.call(self, progress));
                        };
                    });
                };
                return Needle;
            })();
            needle = new Needle(chart);
            needle.render();
            needle.moveTo(percent);
        }
        // Call the function initially
        dashWidgetMeter();
        // Call the function on window resize
        window.addEventListener('resize', function() {
            // Remove the existing chart
            d3.select('#meterchart svg').remove();
            // Call the function again after resizing
            dashWidgetMeter();
        });
        //Trainee Performance Chart Js
        var dataset = [{
                day: "Mon",
                hours: 40
            },
            {
                day: "Tue",
                hours: 35
            },
            {
                day: "Wed",
                hours: 30
            },
            {
                day: "Thur",
                hours: 15
            },
            {
                day: "Fri",
                hours: 22
            },
            {
                day: "Sat",
                hours: 45
            }
        ];
        var margin = {
            top: 5,
            right: 0,
            bottom: 20,
            left: 24
        };
        var containerWidth = document.getElementById("hoursSpentChart").offsetWidth;
        var width = containerWidth - margin.left - margin.right;
        var height = 230 - margin.top - margin.bottom;
        var x = d3.scaleBand().range([0, width]).padding(0.1);
        var y = d3.scaleLinear().range([height, 0]);
        var svg = d3
            .select("#hoursSpentChart")
            .append("svg")
            .attr("width", containerWidth)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
        x.domain(dataset.map(function(d) {
            return d.day;
        }));
        y.domain([0, d3.max(dataset, function(d) {
            return d.hours;
        })]);
        svg.append("g")
            .attr("class", "axis axis--x")
            .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(x));
        svg.append("g")
            .attr("class", "axis axis--y")
            .call(d3.axisLeft(y));
        svg.selectAll(".bar")
            .data(dataset)
            .enter()
            .append("rect")
            .attr("class", "bar")
            .attr("x", function(d) {
                return x(d.day);
            })
            .attr("width", x.bandwidth())
            .attr("y", function(d) {
                return y(d.hours);
            })
            .attr("height", function(d) {
                return height - y(d.hours);
            })
            .style("fill", "#FC995A");

        function resizeChart() {
            var containerWidth = document.getElementById("hoursSpentChart").offsetWidth;
            width = containerWidth - margin.left - margin.right;
            svg.attr("width", containerWidth);
            x.range([0, width]);
            svg.select(".axis--x").call(d3.axisBottom(x));
            svg.selectAll(".bar")
                .attr("x", function(d) {
                    return x(d.day);
                })
                .attr("width", x.bandwidth());
        }
        window.addEventListener("resize", resizeChart);
    </script>
@stop
