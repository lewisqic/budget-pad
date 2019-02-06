/******************************************************
 * Charting class used on account pages
 ******************************************************/
class Charting {

    /**
     * Class constructor, called when instantiating new class object
     */
    constructor() {
        // declare our class properties
        this.oChart = null;
        this.tCharts = [];
        // call init
        this.init();
    }


    /**
     * We run init when our class is first instantiated
     */
    init() {
        // bind events
        this.bindEvents();
        this.dateRangePicker();
        this.overviewChart();
        this.tagsChart();
    }

    /**
     * bind all necessary events
     */
    bindEvents() {
        let self = this;


        $('.change-date').on('click', function(e) {
            e.preventDefault();
            self.changeDate($(this));
        });

        $('.expandable h4').on('click', function(e) {
            e.preventDefault();
            $(this).parent().find('.content').slideToggle('fast');
        });

    }

    dateRangePicker() {
        let self = this;

        let $table = $('.dataTable:first');
        function cb(start, end, initial_load) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('.start-date').val(start.format('YYYY-MM-DD'));
            $('.end-date').val(end.format('YYYY-MM-DD'));
            if ( initial_load !== true ) {
                $('input[name="start_date"]').val(start.format('YYYY-MM-DD'));
                $('input[name="end_date"]').val(end.format('YYYY-MM-DD'));
                $('#change_dates_form').submit();
            }
        }

        let start = moment($('.start-date').val());
        let end = moment($('.end-date').val());
        $('#reportrange').daterangepicker({
            opens: 'left',
            startDate: start,
            endDate: end,
            ranges: {
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Year to Date': [moment().startOf('year'), moment()],
            }
        }, cb);
        cb(start, end, true);

    }

    overviewChart() {
        let self = this;

        let labels = [];
        let days = [];
        let avg = [];
        $.each(chart_data, function(index, row) {
            labels.push(row.label);
            days.push(row.day);
            avg.push(row.avg);
        });

        let ctx = document.getElementById("data_chart");
        this.oChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Avg. Daily ' + chart_name,
                    data: avg,
                    type: 'line',
                    fill: false,
                    borderColor: '#299fd6'
                }, {
                    label: chart_name,
                    data: days,
                    backgroundColor: '#595959'
                }]
            },
            options: {
                legend: { display: false },
                scales: { yAxes: [{ ticks: { beginAtZero: true } }] },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let label = data.datasets[tooltipItem.datasetIndex].label;
                            let dataLabel = data.labels[tooltipItem.index];
                            return label + ': $' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();
                        }
                    }
                }
            }
        });

    }

    tagsChart() {
        let self = this;

        $('.tags_chart').each(function(index, el) {

            let incomes = $(this).attr('data-incomes');
            let expenses = $(this).attr('data-expenses');
            let ctx = $(this)[0];
            let tChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: [
                        '$' + expenses + ' Expenses',
                        '$' + incomes + ' Income',
                    ],
                    datasets: [{
                        label: '',
                        data: [expenses, incomes],
                        backgroundColor: ['#cc0200', '#77b302']
                    }]
                },
                options: {
                    legend: { display: false },
                    scales: { xAxes: [{ ticks: { beginAtZero: true } }] },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                let label = data.datasets[tooltipItem.datasetIndex].label;
                                let dataLabel = data.labels[tooltipItem.index];
                                return label + ': $' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();
                            }
                        }
                    }
                }
            });
            self.tCharts.push(tChart);

        });

    }



}


/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    window.Charting = new Charting();
});