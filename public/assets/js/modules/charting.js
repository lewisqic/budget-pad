var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/******************************************************
 * Charting class used on account pages
 ******************************************************/
var Charting = function () {

    /**
     * Class constructor, called when instantiating new class object
     */
    function Charting() {
        _classCallCheck(this, Charting);

        // declare our class properties
        this.oChart = null;
        this.tCharts = [];
        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */


    _createClass(Charting, [{
        key: 'init',
        value: function init() {
            // bind events
            this.bindEvents();
            this.dateRangePicker();
            this.overviewChart();
            this.tagsChart();
        }

        /**
         * bind all necessary events
         */

    }, {
        key: 'bindEvents',
        value: function bindEvents() {
            var self = this;

            $('.change-date').on('click', function (e) {
                e.preventDefault();
                self.changeDate($(this));
            });

            $('.expandable h4').on('click', function (e) {
                e.preventDefault();
                $(this).parent().find('.content').slideToggle('fast');
            });
        }
    }, {
        key: 'dateRangePicker',
        value: function dateRangePicker() {
            var self = this;

            var $table = $('.dataTable:first');
            function cb(start, end, initial_load) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('.start-date').val(start.format('YYYY-MM-DD'));
                $('.end-date').val(end.format('YYYY-MM-DD'));
                if (initial_load !== true) {
                    $('input[name="start_date"]').val(start.format('YYYY-MM-DD'));
                    $('input[name="end_date"]').val(end.format('YYYY-MM-DD'));
                    $('#change_dates_form').submit();
                }
            }

            var start = moment($('.start-date').val());
            var end = moment($('.end-date').val());
            $('#reportrange').daterangepicker({
                opens: 'left',
                startDate: start,
                endDate: end,
                ranges: {
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Year to Date': [moment().startOf('year'), moment()]
                }
            }, cb);
            cb(start, end, true);
        }
    }, {
        key: 'overviewChart',
        value: function overviewChart() {
            var self = this;

            var labels = [];
            var days = [];
            var avg = [];
            $.each(chart_data, function (index, row) {
                labels.push(row.label);
                days.push(row.day);
                avg.push(row.avg);
            });

            var ctx = document.getElementById("data_chart");
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
                            label: function label(tooltipItem, data) {
                                var label = data.datasets[tooltipItem.datasetIndex].label;
                                var dataLabel = data.labels[tooltipItem.index];
                                return label + ': $' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();
                            }
                        }
                    }
                }
            });
        }
    }, {
        key: 'tagsChart',
        value: function tagsChart() {
            var self = this;

            $('.tags_chart').each(function (index, el) {

                var incomes = $(this).attr('data-incomes');
                var expenses = $(this).attr('data-expenses');
                var ctx = $(this)[0];
                var tChart = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: ['$' + expenses + ' Expenses', '$' + incomes + ' Income'],
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
                                label: function label(tooltipItem, data) {
                                    var label = data.datasets[tooltipItem.datasetIndex].label;
                                    var dataLabel = data.labels[tooltipItem.index];
                                    return label + ': $' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();
                                }
                            }
                        }
                    }
                });
                self.tCharts.push(tChart);
            });
        }
    }]);

    return Charting;
}();

/******************************************************
 * Instantiate new class
 ******************************************************/


$(function () {
    window.Charting = new Charting();
});
