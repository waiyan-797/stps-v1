@extends('layouts.app')

@section('content')
	<div class="container py-4">
		<div class="col-md-12 mx-0 mt-3 shadow-sm p-3">
			<div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="d-flex justify-content-between px-5">
                            <label for="topup">Topup Summary</label>
                            <div class="">
                                <button class="btn btn-sm btn-secondary" id="topup_day" onclick="topupChart('day')">D</button>
                                <button class="btn btn-sm btn-secondary" id="topup_week" onclick="topupChart('week')">W</button>
                                <button class="btn btn-sm btn-secondary" id="topup_month" onclick="topupChart('month')">M</button>
                                <button class="btn btn-sm btn-secondary" id="topup_year" onclick="topupChart('year')">Y</button>
                            </div>
                        </div>
                        <div style="width: 100%;"><canvas id="topup"></canvas></div>
                    </div>
			    </div>
                <div class="col-md-3 text-center text-md-start">
                    <div class="">
                        <div class="mt-5">
                            <h1 class=" fw-bold">{{ $balence }} Ks</h1>
                            <p class="fw-bold text-muted">Income in this month</p>
                        </div>
                        <div class="">
                            <a class="btn btn-outline-dark py-1 fw-bold" href="{{ route('income.summary', 'day') }}"
                                style="border:1px solid #bbb; font-size:0.8rem;">All Income
                                Summary
                                <span class="ms-2 text-muted"><i class="fa-solid fa-chevron-right"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="">
                        <div class="mt-4">
                            <h2>{{ $users->where('status', 'pending')->count() }}</h2>
                            <p class="fw-bold text-muted small">Pending {{ $users->where('status', 'pending')->count() }} Drivers</p>
                        </div>
                        <div class="">
                            <a class="btn btn-outline-dark py-1 fw-bold" href="{{ route('users.pending') }}"
                                style="border:1px solid #bbb; font-size:0.8rem;">Pending
                                <span class="ms-2 text-muted"><i class="fa-solid fa-chevron-right"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="">
                        <div class="mt-4">
                            <h2>{{ $users->where('status', 'active')->count() }}</h2>
                            <p class="fw-bold text-muted small">Active {{ $users->where('status', 'active')->count() }} Drivers</p>
                        </div>
                        <div class="">
                            <a class="btn btn-outline-dark py-1 fw-bold" href="{{ route('users.active') }}"
                                style="border:1px solid #bbb; font-size:0.8rem;">Active
                                <span class="ms-2 text-muted"><i class="fa-solid fa-chevron-right"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
		</div>
        <div class="col-md-12 mx-0 mt-3 shadow-sm p-3">
                <div class="row mt-3 justify-content-between">
                    <div class="col-md-6 p-3">
                        <div class="d-flex justify-content-between px-5">
                            <label for="commission">Commission Fee Summmary</label>
                            <div class="">
                                <button class="btn btn-sm btn-secondary" id="comission_day" onclick="commissionChart('day')">D</button>
                                <button class="btn btn-sm btn-secondary" id="comission_week" onclick="commissionChart('week')">W</button>
                                <button class="btn btn-sm btn-secondary" id="comission_month" onclick="commissionChart('month')">M</button>
                                <button class="btn btn-sm btn-secondary" id="comission_year" onclick="commissionChart('year')">Y</button>
                            </div>
                        </div>
                        <div style="width: 100%;"><canvas id="commission"></canvas></div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="d-flex justify-content-between px-5">
                            <label for="topup">Trips Summary</label>
                            <div class="">
                                <button class="btn btn-sm btn-secondary" id="trip_day" onclick="tripChart('day')">D</button>
                                <button class="btn btn-sm btn-secondary" id="trip_week" onclick="tripChart('week')">W</button>
                                <button class="btn btn-sm btn-secondary" id="trip_month" onclick="tripChart('month')">M</button>
                                <button class="btn btn-sm btn-secondary" id="trip_year" onclick="tripChart('year')">Y</button>
                            </div>
                        </div>
                        <div style="width: 100%;"><canvas id="trip"></canvas></div>
                    </div>
                </div>
        </div>
	</div>
@endsection
@push('script')
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script>
        const domain = window.location.href;
        let commissionChartInstance = null;
        let topupChartInstance = null;
        let tripChartInstance = null;



        async function topupChart(range) {
            await axios.get(`${domain}topup-chart/${range}`)
                .then((response) => {
                    const data = response.data;

                    let arr = Object.keys(data).map((key) => {
                        return {
                            date: data[key].date,
                            income: data[key].income,
                        };
                    });

                    if (!topupChartInstance) {
                        // Create the chart instance if it doesn't exist
                        const chartElement = document.getElementById("topup");
                        topupChartInstance = new Chart(chartElement, {
                            type: 'line',
                            data: {
                                labels: arr.map((row) => row.date),
                                datasets: [
                                    {
                                        label: `Topup per ${range}`,
                                        data: arr.map((row) => row.income),
                                        borderColor: '#ffc107',
                                        backgroundColor: '#ffc107',
                                        tension: 0.2,
                                    },
                                ],
                            },
                        });
                    } else {
                        // Update the existing chart instance with new data
                        topupChartInstance.data.labels = arr.map((row) => row.date);
                        topupChartInstance.data.datasets[0].label = `Topup per ${range}`;
                        topupChartInstance.data.datasets[0].data = arr.map((row) => row.income);

                        topupChartInstance.update();
                    }


                })
            .catch((error) => {
                    console.log(error);
                });
            const ranges = ['day', 'week', 'month', 'year'];
                    ranges.forEach((rag) => {
                        document.querySelector(`#topup_${rag}`).classList.remove('btn-dark');
                        document.querySelector(`#topup_${rag}`).classList.add('btn-secondary');
                    });

                    document.querySelector(`#topup_${range}`).classList.add('btn-dark');
                    document.querySelector(`#topup_${range}`).classList.remove('btn-secondary');
        }

        async function commissionChart(range) {
            await axios.get(`${domain}commission-chart/${range}`)
                .then((response) => {
                    const data = response.data;

                    let arr = Object.keys(data).map((key) => {
                        return {
                            date: data[key].date,
                            income: data[key].income,
                        };
                    });
                    if (!commissionChartInstance) {
                        // Create the chart instance if it doesn't exist
                        const chartElement = document.getElementById("commission");
                        commissionChartInstance = new Chart(chartElement, {
                            type: 'line',
                            data: {
                                labels: arr.map((row) => row.date),
                                datasets: [
                                    {
                                        label: `Commission per ${range}`,
                                        data: arr.map((row) => row.income),
                                        borderColor: '#9CFF2E',
                                        backgroundColor: '#38E54D',
                                        tension: 0.2,
                                    },
                                ],
                            },
                        });
                    } else {
                        // Update the existing chart instance with new data
                        commissionChartInstance.data.labels = arr.map((row) => row.date);
                        commissionChartInstance.data.datasets[0].label = `Commission per ${range}`;
                        commissionChartInstance.data.datasets[0].data = arr.map((row) => row.income);

                        commissionChartInstance.update();
                    }

                })
                .catch((error) => {
                    console.log(error);
                });

            const ranges = ['day', 'week', 'month', 'year'];
                    ranges.forEach((rag) => {
                        document.querySelector(`#comission_${rag}`).classList.remove('btn-dark');
                        document.querySelector(`#comission_${rag}`).classList.add('btn-secondary');
                    });

                    document.querySelector(`#comission_${range}`).classList.add('btn-dark');
                    document.querySelector(`#comission_${range}`).classList.remove('btn-secondary');
        }

        async function tripChart(range) {
            await axios.get(`${domain}trip-chart/${range}`)
                .then((response) => {
                    const data = response.data;
                    let arr = Object.keys(data).map((key) => {
                        return {
                            date: data[key].date,
                            tripCount: data[key].tripCount,
                        };
                    });
                    console.log(arr);

                    if (!tripChartInstance) {
                        // Create the chart instance if it doesn't exist
                        const chartElement = document.getElementById("trip");
                        tripChartInstance = new Chart(chartElement, {
                            type: 'line',
                            data: {
                                labels: arr.map((row) => row.date),
                                datasets: [
                                    {
                                        label: `Trip per ${range}`,
                                        data: arr.map((row) => row.tripCount),
                                        borderColor: '#00C4FF',
                                        backgroundColor: '#30A2FF',
                                        tension: 0.2,
                                    },
                                ],
                            },
                        });
                    } else {
                        // Update the existing chart instance with new data
                        tripChartInstance.data.labels = arr.map((row) => row.date);
                        tripChartInstance.data.datasets[0].data = `Trip per ${range}`;
                        tripChartInstance.data.datasets[0].data = arr.map((row) => row.tripCount);
                        tripChartInstance.update();
                    }


                })
            .catch((error) => {
                    console.log(error);
                });

            const ranges = ['day', 'week', 'month', 'year'];
                    ranges.forEach((rag) => {
                        document.querySelector(`#trip_${rag}`).classList.remove('btn-dark');
                        document.querySelector(`#trip_${rag}`).classList.add('btn-secondary');
                    });

                    document.querySelector(`#trip_${range}`).classList.add('btn-dark');
                    document.querySelector(`#trip_${range}`).classList.remove('btn-secondary');
        }

         window.onload = function () {
            commissionChart('month');
            topupChart('month');
            tripChart('month');
        };
    </script>
@endpush
