$(document).ready(function (e) {

    $("#upload-file").change(function () {
        var file = document.getElementById('upload-file').files[0];
        var reader = new FileReader();
        reader.readAsText(file);
        reader.onload = function (e) {
            $('#errorMessage').hide();
            $('#jsonView').hide();
            $('#chart-container').hide();

            $('#contentPreview > textarea').text(e.target.result);
            $('#contentPreview').show();
        };
    });

    $("#form").on('submit', (function (e) {
        e.preventDefault();
        $.ajax({
            url: $('#form').attr('upload-url'),
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
            },
            success: function (response) {
                if (response.status === 'success') {
                    showDataOnSuccess(response.result);
                } else if (response.status === 'error') {
                    $('#errorMessage').text(response.message);
                    $('#errorMessage').show();
                }
            },
            error: function (err) {
                console.log('Failed to send the data to server');
                console.log(err);
            }
        });
    }));

    function showDataOnSuccess(result) {
        if (result.dataViewType === 'chart') {
            createEmployeeChart(result.data);

            $('#jsonView').hide();
            $('#jsonView > textarea').text('');
            $('#chart-container').show();
        } else {
            $('#chart-container').hide();
            $('#jsonView > textarea').text(JSON.stringify(result.data, null, 4));
            $('#jsonView').show();
        }

    }

    var employeeChart;

    function createEmployeeChart(employeeData) {
        if (employeeChart) {
            employeeChart.init({'data': employeeData});
        } else {
            employeeChart = $('#chart-container').orgchart({
                'data': employeeData
            });
        }
    }
})