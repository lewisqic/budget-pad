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
        this.chart = null;
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
                if ($table.length) {
                    var params = $.parseJSON($table.attr('data-params'));
                    params['start_date'] = start.format('YYYY-MM-DD');
                    params['end_date'] = end.format('YYYY-MM-DD');
                    $table.attr('data-params', JSON.stringify(params));
                    $table.closest('.dataTables_wrapper').find('.dataTables_refresh a').click();
                }
                if (initial_load !== true) {
                    self.updateData();
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
            this.chart = new Chart(ctx, {
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
        key: 'updateData',
        value: function updateData() {
            var self = this;

            function currency(value) {
                return '$' + parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            $('.chart-wrapper').fadeTo(0, 0.5);

            $.ajax({
                url: Core.url($.url().segment(1) + ($.url().segment(2) ? '/' + $.url().segment(2) : '') + '/overview-data'),
                data: {
                    category_id: $('.category-id').val(),
                    start_date: $('.start-date').val(),
                    end_date: $('.end-date').val()
                },
                method: 'POST',
                dataType: 'json',
                success: function success(data) {

                    // update chart
                    var labels = [];
                    var days = [];
                    var avg = [];
                    $.each(data.chart, function (index, row) {
                        labels.push(row.label);
                        days.push(row.day);
                        avg.push(row.avg);
                    });
                    self.chart.data.labels = labels, self.chart.data.datasets[0].data = avg;
                    self.chart.data.datasets[1].data = days;
                    self.chart.update();

                    // update tiles
                    $('.tile-data .incomes').text(currency(data.tiles.incomes));
                    $('.tile-data .expenses').text(currency(data.tiles.expenses));
                    $('.tile-data .net').text(currency(data.tiles.net));
                    $('.tile-data .savings').text(data.tiles.savings + '%');
                    $('.tile-data .transactions').text(data.tiles.transactions);
                    $('.tile-data .avg').text(currency(data.tiles.avg));

                    $('.tile-data .incomes, .tile-data .net, .tile-data .savings').removeClass('text-success text-danger');
                    $('.tile-data .incomes').addClass('text-' + (data.tiles.incomes > 0 ? 'success' : 'danger'));
                    $('.tile-data .net').addClass('text-' + (data.tiles.net > 0 ? 'success' : 'danger'));
                    $('.tile-data .savings').addClass('text-' + (data.tiles.savings > 0 ? 'success' : 'danger'));

                    $('.chart-wrapper').fadeTo(0, 1);
                }
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
