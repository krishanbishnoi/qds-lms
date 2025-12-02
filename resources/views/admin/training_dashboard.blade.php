@extends('admin.layouts.default')

@section('content')
<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Training Dashboard</h3>
            <p class="text-muted">Cumulative training analytics and drill-downs</p>
        </div>
        <div>
            <a href="#" class="btn btn-sm btn-outline-secondary" id="refreshDashboard"><i class="mdi mdi-refresh"></i> Refresh</a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <form id="filtersForm" class="row g-2">
                <div class="col-md-2">
                    <label>Agency</label>
                    <select id="filterAgency" name="agency_id" class="form-control">
                        <option value="">All</option>
                        @foreach($agencies as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Region</label>
                    <select id="filterRegion" name="region_id" class="form-control">
                        <option value="">All</option>
                        @foreach($regions as $r)
                            <option value="{{ $r->id }}">{{ $r->region }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>State</label>
                    <select id="filterState" name="state_id" class="form-control">
                        <option value="">All</option>
                        @foreach($states as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Training</label>
                    <select id="filterTraining" name="training_id" class="form-control">
                        <option value="">All</option>
                        @foreach($trainings as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label>From</label>
                    <input type="date" id="filterFrom" name="from_date" class="form-control">
                </div>
                <div class="col-md-1">
                    <label>To</label>
                    <input type="date" id="filterTo" name="to_date" class="form-control">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" id="applyFilters" class="btn btn-primary w-100">Apply</button>
                </div>
            </form>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row" id="kpiRow">
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Total Trainings</h6>
                    <h3 id="kpiTotalTrainings">0</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Upcoming</h6>
                    <h3 id="kpiUpcoming">0</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Ongoing</h6>
                    <h3 id="kpiOngoing">0</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Completed Trainings</h6>
                    <h3 id="kpiCompletedTrainings">0</h3>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Total Assigned Users</h6>
                    <h3 id="kpiTotalAssigned">0</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Completed Users</h6>
                    <h3 id="kpiCompletedUsers">0</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>In-progress Users</h6>
                    <h3 id="kpiInprogress">0</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Not Started Users</h6>
                    <h3 id="kpiNotStarted">0</h3>
                </div>
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Global Completion Rate</h6>
                    <div class="d-flex align-items-center">
                        <h2 id="kpiCompletionRate" class="me-4">0%</h2>
                        <canvas id="completionDonut" width="120" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Monthly Training Trend</h6>
                    <canvas id="monthlyTrendChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Region-wise Trainings (Heat)</h6>
                    <canvas id="regionHeatChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Agency Breakdown</h6>
                    <div id="agencyBreakdownContainer"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Top 5 Trainings (by completion)</h6>
                    <ul id="topTrainings" class="list-group list-group-flush"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed trainings table -->
    <div class="card">
        <div class="card-body">
            <h5>Trainings</h5>
            <div class="table-responsive">
                <table class="table table-striped" id="trainingsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Agency</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Assigned</th>
                            <th>Completed</th>
                            <th>In-progress</th>
                            <th>Not Started</th>
                            <th>Completion %</th>
                            <th>Status</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody id="trainingsTbody"></tbody>
                </table>
            </div>
            <nav>
                <ul class="pagination" id="trainingsPagination"></ul>
            </nav>
        </div>
    </div>

</div>

<!-- Training detail modal -->
<div class="modal fade" id="trainingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trainingModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="trainingSummary" class="mb-3"></div>
                <div class="row mb-3">
                    <div class="col-md-6"><h6>Completion Donut</h6><canvas id="detailDonut"></canvas></div>
                    <div class="col-md-6"><h6>Daily Completion</h6><canvas id="detailDaily"></canvas></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><h6>Region Distribution</h6><canvas id="detailRegion"></canvas></div>
                    <div class="col-md-6"><h6>Agency/Dept Completion</h6><canvas id="detailAgency"></canvas></div>
                </div>

                <hr>
                <h5>Participants</h5>
                <div class="table-responsive">
                    <table class="table table-sm" id="trainingUsersTable">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Completion Date</th>
                                <th>Certificate</th>
                            </tr>
                        </thead>
                        <tbody id="trainingUsersTbody"></tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination" id="trainingUsersPagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<script>
    let monthlyChart, regionChart, completionDonut, detailDonut, detailDaily, detailRegion, detailAgency;

    $(function() {
        // initial load
        fetchDashboard();
        fetchTrainingList();

        $('#applyFilters, #refreshDashboard').on('click', function(){
            const $btn = $(this);
            $btn.prop('disabled', true).addClass('disabled');
            // run both requests in parallel and re-enable button when done
            const statsReq = fetchDashboard();
            const listReq = fetchTrainingList();
            $.when(statsReq, listReq).always(function(){
                $btn.prop('disabled', false).removeClass('disabled');
            });
        });

        // keep selects from auto-refreshing — use Apply button to control refresh
    });

    function getFilters() {
        return {
            agency_id: $('#filterAgency').val(),
            region_id: $('#filterRegion').val(),
            state_id: $('#filterState').val(),
            training_id: $('#filterTraining').val(),
            from_date: $('#filterFrom').val(),
            to_date: $('#filterTo').val()
        };
    }

    function fetchDashboard(){
        const filters = getFilters();
        // return the jqXHR so callers may wait
        return $.getJSON("{{ route('Admin.TrainingDashboard.stats') }}", filters)
            .done(function(res){
                if (!res.ok) return;
                const d = res.data;
                $('#kpiTotalTrainings').text(d.total_trainings);
                $('#kpiUpcoming').text(d.upcoming);
                $('#kpiOngoing').text(d.ongoing);
                $('#kpiCompletedTrainings').text(d.completed_trainings);
                $('#kpiTotalAssigned').text(d.total_assigned);
                $('#kpiCompletedUsers').text(d.completed_users);
                $('#kpiInprogress').text(d.inprogress_users);
                $('#kpiNotStarted').text(d.not_started_users);
                $('#kpiCompletionRate').text(d.global_completion_rate + '%');

                renderCompletionDonut(d.global_completion_rate);
                renderMonthlyTrend(d.monthly_trend || []);
                renderRegionHeat(d.region_heat || []);
                renderAgencyBreakdown(d.agency_breakdown || []);
                renderTopTrainings(d.top_trainings || []);
            })
            .fail(function(){
                console.error('Failed to fetch dashboard stats');
            });
    }

    function fetchTrainingList(page=1){
        const filters = getFilters();
        filters.page = page;
        filters.per_page = 10;
        // return jqXHR
        return $.getJSON("{{ route('Admin.TrainingDashboard.list') }}", filters)
            .done(function(res){
                buildTrainingTable(res);
            })
            .fail(function(){
                console.error('Failed to fetch training list');
            });
    }

    function buildTrainingTable(res){
        const tbl = $('#trainingsTbody'); tbl.empty();
        let items = [];
        if (res && Array.isArray(res.data)) {
            items = res.data;
        } else if (Array.isArray(res)) {
            items = res;
        }

        if (items.length === 0) {
            tbl.append('<tr><td colspan="12" class="text-center">No trainings found</td></tr>');
        }

        items.forEach(function(row){
            const tr = `<tr>
                <td>${row.id}</td>
                <td>${row.name}</td>
                <td>${row.agency || ''}</td>
                <td>${row.start_date || ''}</td>
                <td>${row.end_date || ''}</td>
                <td>${row.total_assigned || 0}</td>
                <td>${row.completed || 0}</td>
                <td>${row.inprogress || 0}</td>
                <td>${row.not_started || 0}</td>
                <td>${row.completion_pct || 0}%</td>
                <td>${row.status || ''}</td>
                <td><button class="btn btn-sm btn-primary" onclick="openTraining(${row.id}, '${escapeHtml(row.name)}')">View</button></td>
            </tr>`;
            tbl.append(tr);
        });

        // Pagination
        buildPagination(res);
    }

    function buildPagination(res){
        const pager = $('#trainingsPagination'); pager.empty();
        if (!res) return;
        const current = res.current_page || 1;
        const last = res.last_page || 1;
        if (last <= 1) return;
        for (let p = 1; p <= last; p++){
            const active = p === current ? 'active' : '';
            pager.append(`<li class="page-item ${active}"><a class="page-link" href="#" onclick="fetchTrainingList(${p});return false;">${p}</a></li>`);
        }
    }

    function renderCompletionDonut(percent){
        const ctx = document.getElementById('completionDonut').getContext('2d');
        const used = percent; const rest = Math.max(0, 100 - used);
        if (completionDonut) completionDonut.destroy();
        completionDonut = new Chart(ctx, {
            type: 'doughnut',
            data: { labels: ['Completed','Remaining'], datasets: [{ data: [used, rest], backgroundColor: ['#28a745','#e9ecef'] }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%' }
        });
    }

    function renderMonthlyTrend(data){
        const labels = data.map(d => d.month);
        const vals = data.map(d => d.total);
        const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
        if (monthlyChart) monthlyChart.destroy();
        monthlyChart = new Chart(ctx, {
            type: 'line',
            data: { labels: labels, datasets: [{ label: 'Trainings', data: vals, borderColor: '#007bff', backgroundColor: 'rgba(0,123,255,0.1)'}] },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    function renderRegionHeat(data){
        const labels = data.map(d => d.name);
        const vals = data.map(d => d.trainings_count);
        const ctx = document.getElementById('regionHeatChart').getContext('2d');
        if (regionChart) regionChart.destroy();
        regionChart = new Chart(ctx, {
            type: 'bar',
            data: { labels: labels, datasets: [{ label: 'Trainings', data: vals, backgroundColor: '#ffc107' }] },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    function renderAgencyBreakdown(list){
        const container = $('#agencyBreakdownContainer'); container.empty();
        (list || []).forEach(function(a){
            const el = `<div class="d-flex justify-content-between align-items-center mb-2">
                <div><strong>${a.name}</strong> <small class="text-muted">(${a.trainings_count} trainings)</small></div>
                <div><small>${a.completion_rate}%</small></div>
            </div>`;
            container.append(el);
        });
    }

    function renderTopTrainings(list){
        const ul = $('#topTrainings'); ul.empty();
        (list || []).forEach(function(t){
            ul.append(`<li class="list-group-item d-flex justify-content-between align-items-center">${t.name} <span class="badge bg-success">${t.completion_rate}%</span></li>`);
        });
    }

    function openTraining(id, name){
        $('#trainingModalTitle').text(name);
        $('#trainingModal').modal('show');
        loadTrainingDetail(id);
        loadTrainingUsers(id,1);
    }

    function loadTrainingDetail(id){
        $.getJSON("{{ url('admin/training-dashboard/view/') }}" + '/' + id, function(res){
            if (!res.ok) return;
            const d = res.data;
            let html = `<p><strong>Agency:</strong> ${d.training.agency || ''} <strong>Region:</strong> ${d.training.region || ''} <strong>State:</strong> ${d.training.state || ''}</p>`;
            html += `<p><strong>Dates:</strong> ${d.training.start_date || ''} to ${d.training.end_date || ''}</p>`;
            html += `<p><strong>Assigned:</strong> ${d.total_assigned} &nbsp; <strong>Completed:</strong> ${d.completed} &nbsp; <strong>In-progress:</strong> ${d.inprogress} &nbsp; <strong>Not Started:</strong> ${d.not_started}</p>`;
            $('#trainingSummary').html(html);

            renderDetailDonut(d.completed, d.inprogress, d.not_started);
            renderDetailDaily(d.daily);
            renderDetailRegion(d.region_dist);
            renderDetailAgency(d.agency_dist);
        });
    }

    function renderDetailDonut(completed, inprogress, notstarted){
        const ctx = document.getElementById('detailDonut').getContext('2d');
        const data = [completed, inprogress, notstarted];
        if (detailDonut) detailDonut.destroy();
        detailDonut = new Chart(ctx, { type: 'doughnut', data: { labels: ['Completed','In-progress','Not Started'], datasets: [{ data: data, backgroundColor: ['#28a745','#17a2b8','#6c757d'] }] }, options: {responsive:true, maintainAspectRatio:false}});
    }

    function renderDetailDaily(daily){
        const labels = (daily || []).map(d => d.day);
        const vals = (daily || []).map(d => d.completed);
        const ctx = document.getElementById('detailDaily').getContext('2d');
        if (detailDaily) detailDaily.destroy();
        detailDaily = new Chart(ctx, { type: 'line', data: { labels: labels, datasets: [{ label: 'Daily completions', data: vals, borderColor: '#28a745', backgroundColor: 'rgba(40,167,69,0.1)' }] }, options:{responsive:true, maintainAspectRatio:false}});
    }

    function renderDetailRegion(list){
        const labels = (list || []).map(i => i.name);
        const vals = (list || []).map(i => i.users_count);
        const ctx = document.getElementById('detailRegion').getContext('2d');
        if (detailRegion) detailRegion.destroy();
        detailRegion = new Chart(ctx, { type:'bar', data: { labels: labels, datasets:[{ label:'Users', data: vals, backgroundColor:'#007bff' }] }, options:{responsive:true, maintainAspectRatio:false}});
    }

    function renderDetailAgency(list){
        const labels = (list || []).map(i => i.name);
        const vals = (list || []).map(i => i.completion_rate);
        const ctx = document.getElementById('detailAgency').getContext('2d');
        if (detailAgency) detailAgency.destroy();
        detailAgency = new Chart(ctx, { type:'bar', data:{ labels: labels, datasets:[{ label:'Completion %', data: vals, backgroundColor:'#17a2b8' }] }, options:{responsive:true, maintainAspectRatio:false}});
    }

    function loadTrainingUsers(training_id, page=1){
        $.getJSON("{{ url('admin/training-dashboard') }}" + '/' + training_id + '/users', {page:page, per_page:15}, function(res){
            const tbody = $('#trainingUsersTbody'); tbody.empty();
            (res.data || []).forEach(function(u){
                const cert = u.certificate_path ? `<a href="${u.certificate_path}" target="_blank">Download</a>` : '';
                tbody.append(`<tr><td>${u.id}</td><td>${u.first_name} ${u.last_name}</td><td>${u.mobile || ''}</td><td>${u.email}</td><td>${u.status}</td><td>${u.completion_date || ''}</td><td>${cert}</td></tr>`);
            });
            // build pagination (simple)
            const pager = $('#trainingUsersPagination'); pager.empty();
            if (res.last_page){
                for (let p=1;p<=res.last_page;p++){
                    pager.append(`<li class="page-item"><a class="page-link" href="#" onclick="loadTrainingUsers(${training_id},${p});return false;">${p}</a></li>`);
                }
            }
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text).replace(/'/g, "\\'").replace(/\"/g, '\\"');
    }

</script>
@endsection
