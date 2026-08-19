<div class="isp-billing-v2">
<?php include('billcollection.php') ?>
<link rel="stylesheet" href="assets/css/isp-billing-v2.css">
<style>
    .table-responsive {
        overflow-x: auto;
    }
</style>
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <!-- Form Wizard Start -->
            <div class="form-wizard">
                <fieldset class="wizard-fieldset show">
                    <div class="row gy-3">
                        <div class="col-sm-3">
                            <label for="zone-filter" class="form-label">Zone</label>
                            <div>
                                <select id="zone-filter" class="form-control">
                                    <option value="">All Zones</option>
                                    <?php foreach ($bzones as $zone): ?>
                                        <option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['zone_name']; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="billing-person-filter" class="form-label">Billing Person</label>
                            <div class="position-relative">
                                <select id="billing-person-filter" class="form-control">
                                    <option value="">All Billing Persons</option>
                                    <?php foreach ($bbillingperson as $bp): ?>
                                        <option value="<?php echo $bp['UserId']; ?>"><?php echo $bp['FullName']; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <label for="status-filter" class="form-label">Status</label>
                            <div class="position-relative">
                                <select id="status-filter" class="form-control">
                                    <option value="">All</option>
                                    <option value="0">UnPaid</option>
                                    <option value="1">Partial</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <label for="date-from" class="form-label">From Date</label>
                            <div class="position-relative">
                                <input type="date" id="date-from" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <label for="date-to" class="form-label">To Date</label>
                            <div class="position-relative">
                                <input type="date" id="date-to" class="form-control">
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <!-- Form Wizard End -->
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="col-md-12">
        <div class="card h-100 p-0 radius-12">
            <div class="card-body p-24">
                <div class="mt-24">
                    <div class="row mt-24 gy-0">
                        <div class="col-xxl-3 col-sm-6">
                            <div class="card p-3 shadow-none radius-8 border h-100 bg-gradient-end-1">
                                <div class="card-body p-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">

                                        <div class="d-flex align-items-center gap-2">
                                            <span class="mb-0 w-48-px h-48-px bg-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                                <iconify-icon icon="hugeicons:invoice-03" class="icon"></iconify-icon>
                                            </span>
                                            <div>
                                                <span class="mb-2 fw-medium text-secondary-light text-sm">Total Bill Collection</span>
                                                <h6 class="fw-semibold" id="totalbill">0</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="col-md-12">
    <div class="card basic-data-table">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
            <h5 class="card-title mb-0">Bill Collection List</h5>
        </div>
        <div class="card-body table-responsive">
            <table
                class="table bordered-table mb-0"
                id="customer-datatable"
                data-page-length="10">
                <thead>
                    <tr>
                        <th scope="col">SL.</th>
                        <?php if ($obj->userWorkPermission('edit')) { ?>
                            <th scope="col-md-2">Action</th>
                        <?php } ?>
                        <th scope="col">ID</th>
                        <th scope="col">IP</th>
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Mobile No</th>
                        <th scope="col">Package</th>
                        <th scope="col">Monthly Bill</th>
                        <th scope="col">Total Due</th>
                        <th scope="col">Bill Date</th>
                        <th scope="col">Zone</th>
                        <th scope="col">B.Person</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="paymentModalLabel">Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form id="paymentForm">
                    <input type="hidden" id="ag_id" name="ag_id" class="form-control">
                    <div class="form-group mt-3 mb-1">
                        <label for="amount">Due Amount:</label> <strong><span id="due-amount">0</span> BDT*</strong>
                        <label for="amount">Pay Amount:</label>
                        <input type="text" id="amount" name="amount" class="form-control" required>
                    </div>
                    <div class="form-group mb-1">
                        <label for="discount">Discount Amount:</label>
                        <input type="text" id="discount" name="discount" class="form-control" value="0">
                    </div>
                    <div class="form-group mb-1">
                        <label for="discription">Description:</label>
                        <textarea id="discription" name="discription" class="form-control"></textarea>
                    </div>
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary" id="submit-btn">Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-success btn-lg" style="padding: 10px;margin-bottom: 16px;font-size: 21px;" id="graphicalViewButton">Graphical View</button>
<!-- chart start -->
<!-- Loading Bar (hidden by default) -->

<div class="col-md-6 graphicalChart" style="display: none;">
    <div class="card h-100 p-0">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h6 class="card-title text-lg fw-semibold mb-0">Bill Collection by Month</h6>
        </div>
        <div class="card-body p-24">
            <div id="bil_collection_view_line_chart"></div>
        </div>
    </div>
</div>

<div class="col-md-6 graphicalChart" style="display: none;">
    <div class="card h-100 p-0">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h6 class="card-title text-lg fw-semibold mb-0">Income And Expenses Comparison</h6>
        </div>
        <div class="card-body p-24">
            <div id="cutomer_view_column_chart" class=""></div>
        </div>
    </div>
</div>


<?php $obj->start_script(); ?>
<script>
    $(document).ready(function() {
        var table = $('#customer-datatable').DataTable({
            dom: `<"row"<"col-sm-6"l><"col-sm-6 text-end"f>>` + // Show entries and search in one row
                `<"row"<"col-sm-12 text-end"B>>` + // Buttons in a separate row
                `<"row dt-layout-row"<"col-sm-12"tr>>` + // Table content
                `<"row"<"col-sm-5"i><"col-sm-7 text-end"p>>`, // Info left, pagination right
            keys: true,
            stateSave: true,
            lengthChange: true,
            responsive: false,
            pagingType: "full_numbers",
            processing: true, // Show the processing indicator
            serverSide: true, // Enable server-side processing
            ajax: {
                url: "./pages/billcollection/billcollection_ajax.php", // URL to your PHP file
                type: "GET",
                data: function(d) {
                    d.zone = $('#zone-filter').val(); // Get zone filter value
                    d.bid = $('#billing-person-filter').val(); // Get billing person filter
                    d.status = $('#status-filter').val(); // Get status filter
                    d.datefrom = $('#date-from').val(); // Get date from filter
                    d.dateto = $('#date-to').val(); // Get date to filter
                }
            },
            columns: [{
                    data: 'sl',
                    orderable: false
                },
                {
                    data: 'ag_id',
                    render: function(data, type, row) {
                        return `<a href="pages/pdf/invoice.php?token=` + data + `" target="_blank">
                    <button class="btn btn-secondary waves-effect waves-light btn-sm"><i class="fas fa-print"></i></button></a>
                        <button class='btn btn-success waves-effect waves-light btn-sm' data-name='` + row['ag_name'] + `'  data-id='` + row['ag_id'] + `' data-due='` + row['dueadvance'] + `' onclick="openPaymentModal(this)">Pay</button>
                        
`;
                    }
                },
                {
                    data: 'cus_id',
                    orderable: true,
                    render: function(data, type, row) {
                        return '<a class="btn btn-info waves-effect waves-light btn-sm" href="?page=customer_ledger&token=' + row['ag_id'] + '"> ' + data + ' </a> <br>' +
                            ' <button class="btn btn-secondary waves-effect waves-light btn-sm send-sms mt-1" data-customerid=' + row['ag_id'] + '><i class="fas fa-envelope"></i></button>'
                    }
                },
                {
                    data: 'ip',
                    orderable: true
                },
                {
                    data: 'ag_name',
                    orderable: true,
                    render: function(data, type, row) {
                        return "<span class='text-wrap'>" + data + "</span>";
                    }
                },
                {
                    data: 'ag_office_address',
                    orderable: true,
                    render: function(data, type, row) {
                        return (data != null) ? "<span class='text-wrap'>" + data + "</span>" : "N/A";
                    }
                },
                {
                    data: 'ag_mobile_no',
                    orderable: true,
                    defaultContent: 'N/A'
                },
                {
                    data: 'mb',
                    orderable: true
                },
                {
                    data: 'taka',
                    orderable: true
                }, // Bill Amount
                {
                    data: 'dueadvance',
                    orderable: true
                },
                {
                    data: 'mikrotik_disconnect',
                    orderable: true
                },
                {
                    data: 'zone_name',
                    orderable: true,
                    render: function(data, type, row) {
                        return (data != null) ? "<span class='text-wrap'>" + data + "</span>" : "N/A";
                    }
                },
                {
                    data: 'FullName',
                    orderable: true,
                    render: function(data, type, row) {
                        return (data != null) ? "<span class='text-wrap'>" + data + "</span>" : "N/A";
                    }
                },
                {
                    data: 'bill_status',
                    orderable: true,
                    render: function(data, type, row) {
                        if (data == 1) {
                            return "<span class='text-success'>Partial Paid</span>";
                        } else {
                            return "<span class='text-danger'>Unpaid</span>";
                        }
                    }
                },
            ],
            buttons: [{
                    extend: "copy",
                    className: "btn-light"
                },
                {
                    extend: "print",
                    className: "btn-light"
                },
                {
                    extend: "pdf",
                    className: "btn-light"
                }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search customers...",
                lengthMenu: "Show _MENU_ entries",
                emptyTable: "No data available in table"
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            },
            lengthMenu: [10, 25, 50, 100, 500],
            order: [
                [1, 'asc']
            ], // Order by 'cus_id', adjust as needed
            initComplete: function(settings, json) {
                // This function is called once the table has been fully initialized
                $('#totalbill').text(json.totalbill);
                // $('#totalconnectionfee').text(json.totalconnectionFee);
            },
            drawCallback: function(settings) {
                var json = settings.json; // Get the JSON response

                // Update total bill and total connection fee after each table draw
                $('#totalbill').text(json.totalbill);
                // $('#totalconnectionfee').text(json.totalconnectionFee);
            }
        });

        // Trigger table reload when filters are changed
        $('#zone-filter, #billing-person-filter, #status-filter, #date-from, #date-to').on('change', function() {
            table.ajax.reload();
        });

        // Handle SMS button click event
        $('#customer-datatable').on('click', '.send-sms', function() {
            $.get("./pages/customer/customer_sms_ajax.php", {
                token: $(this).data('customerid')
            }, function(result) {
                result = JSON.parse(result)
                if (result.response == true) {
                    Swal.fire({
                        icon: "success",
                        title: 'SMS was sent Successfull',
                        timer: 1500,
                        showConfirmButton: false,
                        position: "top-end"
                    });
                } else {
                    // alert(result.response.error_message);
                    Swal.fire({
                        icon: "error",
                        title: result.response.error_message,
                        timer: 1500,
                        showConfirmButton: false,
                        position: "top-end"
                    });
                }
            });
        });

    });
</script>
<script>
    var clickbtn;

    function openPaymentModal(button) {
        clickbtn = button;
        button.disabled = true;
        // Retrieve data-id and data-due values from the button
        let paymentId = button.getAttribute('data-id');
        let paymentName = button.getAttribute('data-name');
        let dueAmount = button.getAttribute('data-due');

        // Set modal input values
        document.getElementById('ag_id').value = paymentId;
        document.getElementById('due-amount').innerText = dueAmount;
        document.getElementById('amount').value = dueAmount;

        const date = new Date();
        document.getElementById('discription').innerText = `Bill collection for ${date.toLocaleString('default', { month: 'long' })}-${date.getFullYear()} From Customer ${paymentName}`;


        // Reset submit button state
        const submitButton = document.getElementById('submit-btn');
        submitButton.disabled = false;
        submitButton.innerText = 'Submit Payment';

        // Show the modal
        $('#paymentModal').modal('show');
    }


    $('#paymentForm').on('submit', function(e) {

        const submitButton = document.getElementById('submit-btn');
        submitButton.disabled = true;
        submitButton.innerText = 'Processing...';
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: './pages/billcollection/billpay_ajax.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                // console.log(response);

                if (response.success) {
                    document.getElementById('submit-btn').innerText = 'Completed';
                    //   alert(response.message);
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: response.message || "Pay Operations processed successfully!",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    $('#customer-datatable').DataTable().ajax.reload();
                    $('#paymentModal').modal('hide');
                }
            },
            error: function() {
                alert('Error processing payment.');
                clickbtn.disabled = false;
            }
        });
    });
</script>
<script>
    // Get the current date
    const today = new Date();

    // Get the first day of the current month (1st)
    const startDate = new Date(today.getFullYear(), today.getMonth(), 2);

    // Get the last day of the current month (30th or 31st based on the month)
    const endDate = new Date(today.getFullYear(), today.getMonth() + 1, 1);
    // Format the dates to 'YYYY-MM-DD'
    const formatDate = (date) => {
        return date.toISOString().split('T')[0];
    };

    // Set the min and max attributes of the date inputs
    document.getElementById('date-from').setAttribute('min', formatDate(startDate));
    document.getElementById('date-from').setAttribute('max', formatDate(endDate));
    document.getElementById('date-to').setAttribute('min', formatDate(startDate));
    document.getElementById('date-to').setAttribute('max', formatDate(endDate));

    // Set default values to the first day and last day of the current month
    document.getElementById('date-from').value = formatDate(startDate);
    document.getElementById('date-to').value = formatDate(endDate);
</script>

<script>
    $(document).ready(function() {
        // Add event listener for the button click
        $('#graphicalViewButton').click(function() {
            $('.graphicalChart').show()
            // Perform AJAX request when the button is clicked
            $.ajax({
                type: "GET",
                url: "./pages/billcollection/bill_collection_charts_ajax.php",
                dataType: "json",
                success: function(response) {
                    $('#loading-spinner').hide();
                    // Call the function to render the line chart with the data from the server
                    bill_collection_view_line_chart(response.previousData, response.previousYear, response.currentData, response.currentYear, response.maxData);
                    console.log(response.previousData); // To check the response
                    console.log(response); // To check the full response
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
            $.ajax({
                type: "GET",
                url: "./pages/billcollection/bill_collection_column_chart_ajax.php",
                dataType: "json",
                success: function(res) {
                    $('#loading-spinner').hide();
                    // Call the function to render the line chart with the data from the server
                    // cutomer_view_column_chart(res.pakageCountData,res.mbbsCountData);
                    bill_collection_view_column_chart(res.collectionData, res.expenseData, res.collection, res.expense, res.maxData);
                    // console.log(res.pakageCountData);  // To check the response
                    // console.log(res);  // To check the full response
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        });
    });



    function bill_collection_view_line_chart(previousData, previousYear, currentData, currentYear, maxData) {
        // Define colors
        let colors = ["#6658dd", "#1abc9c"];
        const dataColors = $("#apex-line-test").data("colors");
        if (dataColors) {
            colors = dataColors.split(",");
        }

        // Static month names for both years
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        // Convert the comma-separated strings to arrays of numbers
        const previousDataArray = previousData.split(',').map(Number);
        const currentDataArray = currentData.split(',').map(Number);


        // Calculate the tick amount for y-axis dynamically
        const tickAmount = Math.ceil(maxData / 1000); // Adjust this based on your maxData

        // Chart options
        const options = {
            chart: {
                height: 380,
                type: "line",
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: colors,
            dataLabels: {
                enabled: true
            },
            stroke: {
                width: [3, 3],
                curve: "smooth"
            },
            series: [{
                name: `Previous - ${previousYear}`,
                data: previousDataArray // Use the actual array of numbers
            }, {
                name: `Current - ${currentYear}`,
                data: currentDataArray // Use the actual array of numbers
            }],
            grid: {
                row: {
                    colors: ["transparent", "transparent"],
                    opacity: 0.2
                },
                borderColor: "#f1f3fa"
            },
            markers: {
                style: "inverted",
                size: 6
            },
            xaxis: {
                categories: months, // Use static month names
                title: {
                    text: "Month"
                }
            },
            yaxis: {
                title: {
                    text: "Bill Count"
                },
                min: 0, // Start y-axis from 0 for better readability
                max: maxData, // Use maxData to dynamically set the maximum value of the y-axis
                tickAmount: 10 // Calculate dynamic ticks based on maxData
            },
            legend: {
                position: "top",
                horizontalAlign: "right",
                floating: true,
                offsetY: -25,
                offsetX: -25
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        toolbar: {
                            show: false
                        }
                    },
                    legend: {
                        show: false
                    }
                }
            }]
        };

        // Render the chart
        const chart = new ApexCharts(document.querySelector("#bil_collection_view_line_chart"), options);
        chart.render();
    }
    //end line chart

    // Column Charts Start
    function bill_collection_view_column_chart(collectionData, expenseData, collection, expense, maxData) {
        // Static month names for both years
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        // Convert the comma-separated strings to arrays of numbers
        const collectionDataArray = collectionData.split(',').map(Number);
        const expenseDataArray = expenseData.split(',').map(Number);
        var options = {
            series: [{
                name: collection,
                data: collectionDataArray
            }, {
                name: expense,
                data: expenseDataArray
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            colors: ['#008000', '#FF0000'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: months,
            },
            yaxis: {
                title: {
                    text: "Collection of Bill for Current Year"
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "৳ " + val + " BDT"
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#cutomer_view_column_chart"), options);
        chart.render();
    }
</script>



<?php $obj->end_script(); ?>