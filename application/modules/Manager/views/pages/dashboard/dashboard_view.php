<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Dashboard</h1>
            <?php echo $this->session->flashdata('dashboard_msg'); ?>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Reporting Rates
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="reporting_rates_chart"></div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Patients by Regimen
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="patients_by_regimen_chart"></div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-clock-o fa-fw"></i> Drug Consumption and Allocation Trend
                            <div class="nav navbar-right">
                                <button data-toggle="modal" data-target="#drug_consumption_allocation_trend_chart_filter_modal" class="btn btn-warning btn-xs">
                                    <span class="glyphicon glyphicon-filter"></span>
                                </button>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="drug_consumption_allocation_trend_chart"></div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <span class="drug_consumption_allocation_trend_chart_heading heading"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Facility ADT Version Distribution
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="facility_adt_version_distribution_chart"></div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Facility Internet Access
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="facility_internet_access_chart"></div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
<!--modal(s)-->
<div class="modal fade" id="drug_consumption_allocation_trend_chart_filter_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><strong>COMMODITY/ALLOCATION CONSUMPTION FILTER</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-9">
                        <select id="drug_consumption_allocation_trend_chart_filter" data-filter_type="drug" size="2"></select>
                    </div>
                    <div class="col-sm-3">
                        <button id="drug_consumption_allocation_trend_chart_filter_clear_btn" class="btn btn-danger btn-xs clear_btn"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
                        <button id="drug_consumption_allocation_trend_chart_filter_btn" class="btn btn-warning btn-xs filter_btn"><span class="glyphicon glyphicon-filter"></span> Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var chartURL = '../Manager/get_chart'
    var drugListURL = '../API/drug/list'
    var filters = {}
    $(document).ready(function () {
        var charts = ['reporting_rates_chart', 'patients_by_regimen_chart', 'facility_adt_version_distribution_chart', 'facility_internet_access_chart']

        //Add filter to chart then load chart
        setChartFilter('drug_consumption_allocation_trend_chart', drugListURL)

        //Load Charts
        $.each(charts, function(key, chartName) {
            LoadChart('#'+chartName, chartURL, chartName, {})
        });

        //Show dashboard sidemenu
        $(".dashboard").closest('ul').addClass("in");
        $(".dashboard").addClass("active active-page");

        //Filter click Event
        $(".filter_btn").on("click", FilterBtnHandler);

        //Clear click Event
        $(".clear_btn").on("click", ClearBtnHandler);
    });

    function setChartFilter(chartName, filterURL){
        $.ajax({
            url: filterURL,
            datatype: 'JSON',
            success: function(data){
                filterID = '#'+chartName+'_filter'
                //Create multiselect box
                CreateSelectBox(filterID, '100%', 10)
                //Add data to selectbox
                $(filterID+ " option").remove();
                $.each(data, function(i, v) {
                    $(filterID).append($("<option value='" + v.name + "'>" + v.name.toUpperCase() + "</option>"));
                });
                $(filterID).multiselect('rebuild');
                $(filterID).data('filter_type', 'drug');
            },
            complete: function(){
                LoadChart('#'+chartName, chartURL, chartName, {})
            }
        });
    }

    function CreateSelectBox(elementID, width, limit){
        $(elementID).val('').multiselect({
            enableCaseInsensitiveFiltering: true,
            enableFiltering: true,
            disableIfEmpty: true,
            maxHeight: 300,
            buttonWidth: width,
            nonSelectedText: 'None selected',
            includeSelectAllOption: false,
            selectAll: false, 
            onChange: function(option, checked) {
                //Get selected options.
                var selectedOptions = $(elementID + ' option:selected');
                if (selectedOptions.length >= limit) {
                    //Disable all other checkboxes.
                    var nonSelectedOptions = $(elementID + ' option').filter(function() {
                        return !$(this).is(':selected');
                    });
                    nonSelectedOptions.each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', true);
                        input.parent('li').addClass('disabled');
                    });
                }
                else {
                    //Enable all checkboxes.
                    $(elementID + ' option').each(function() {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', false);
                        input.parent('li').addClass('disabled');
                    });
                }
            }
        });
    }

    function LoadSpinner(divID){
        var spinner = new Spinner().spin()
        $(divID).empty('')
        $(divID).height('auto')
        $(divID).append(spinner.el)
    }

    function LoadChart(divID, chartURL, chartName, selectedfilters){
        //Load Spinner
        LoadSpinner(divID)
        //Load Chart*
        $(divID).load(chartURL, {'name':chartName, 'selectedfilters': selectedfilters}, function(){
            //Pre-select filters for charts
            $.each($(divID + '_filters').data('filters'), function(key, data){
                if($.inArray(key, ['data_year', 'data_month', 'data_date', 'county', 'subcounty']) == -1){
                    $(divID + "_filter").val(data).multiselect('refresh');
                    //Output filters
                    var filtermsg = '<b><u>'+key.toUpperCase()+':</u></b><br/>'
                    if($.isArray(data)){
                        filtermsg += data.join('<br/>')
                    }else{
                        filtermsg += data
                    }
                    $("."+chartName+"_heading").html(filtermsg) 
                }
            });
        });
    }

    function FilterBtnHandler(e){
        var filterName = String($(e.target).attr("id")).replace('_btn', '')
        var filterID = "#"+filterName
        var filterType = $(filterID).data('filter_type')
        var chartName = filterName.replace('_filter', '')
        var chartID = "#"+chartName


        if($(filterID).val() != null){
            filters[filterType] = $(filterID).val()
        }

        LoadChart(chartID, chartURL, chartName, filters)

        //Hide Modal
        $(filterID+'_modal').modal('hide')
    }

    function ClearBtnHandler(e){
        var filterName = String($(e.target).attr("id")).replace('_clear_btn', '')
        var filterID = "#"+filterName
        var filterType = $(filterID).data('filter_type')

        //Clear filterType
        filters[filterType] = {}

        //Filter multiple multiselect
        $(filterID).multiselect('deselectAll', false);
        $(filterID).multiselect('updateButtonText');
        $(filterID).multiselect('refresh');
        
        //Trigger filter event
        $(filterID+'_btn').trigger('click');
    }
</script>