$(document).ready(function () {
    var path_name = window.location.pathname;
    var new_path = "";
    if (path_name.includes("emd-transaction")) {
        new_path = "/admin/emd-transaction/date-filter/";
    } else if (path_name.includes("emd-web-user")) {
        new_path = "/admin/emd-web-user/date-filter/";
    } else if (path_name.includes("contact")) {
        new_path = "/admin/contact/date-filter/";
    } else if (path_name.includes("emd-feedback")) {
        new_path = "/admin/emd-feedback/date-filter/";
    }
    // Get the current date
    const currentDate = new Date();
    // Get the current month number
    currentMonthNumber = currentDate.getMonth() + 1; // Adding 1 to match human-readable month numbering
    // Get the current year number
    currentYearNumber = currentDate.getFullYear();

    months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    $(".date-filter-button").click(function () {
        $('.date-ranges').toggle();
        $(".date-ranges").removeClass("d-none");
        return false;
    });


    // Code For Presets --- start 
    presets = [
        ['Last 7 Days', dateInFormate(7) + '-' + dateInFormate(0), dateInFormate(7, true), dateInFormate(0, true)],
        ['Last 30 Days', dateInFormate(30) + '-' + dateInFormate(0), dateInFormate(30, true), dateInFormate(0, true)],
        ['Last 90 Days', dateInFormate(90) + '-' + dateInFormate(0), dateInFormate(90, true), dateInFormate(0, true)],
        ['Last Week', dateInFormate(10) + '-' + dateInFormate(5), dateInFormate(10, true), dateInFormate(5, true)],
        ['Last Month', getMonthData(currentMonthNumber - 1).monthDuration, getMonthData(currentMonthNumber - 1).first_full_Date, getMonthData(currentMonthNumber - 1).last_full_Date],
    ]

    presets_html = '';
    presets.forEach(function (value) {
        presets_html += `<div class="presets d-flex justify-space-between" data-value="` + value[1] + `" data-startDate="` + value[2] + `" data-endDate="` + value[3] + `">
                          <span>` + value[0] + `</span>
                          <span>` + value[1] + `</span>
                      </div>`;
    });

    $('#c1').html(presets_html);
    $('.presets').on('click', function () {
        var from_date = $(this).attr('data-startDate');
        var last_date = $(this).attr('data-endDate');
        // console.log('------------------------------------');
        // console.log($(this).attr('data-startDate'));
        // console.log($(this).attr('data-endDate'));
        // console.log('------------------------------------');
        window.location = new_path + from_date + "/" + last_date;
    });
    // Code For Presets --- End 



    // Code For Days & Weeks Section ------ Start 

    $('#c2 .header h1,#c3 .header h1').html(months[currentDate.getMonth()] + ' ' + currentYearNumber);

    $('.day-grid').html(calender(currentMonthNumber));


    $('#c2 .pre_navigator , #c3 .pre_navigator').on("click", function () {

        previousMonth = handleBackButtonClick();
        // console.log(previousMonth.month, previousMonth.year);
        $('#c2 .header h1,#c3 .header h1').html(months[previousMonth.month - 1] + ' ' + previousMonth.year);

        $('#c2 .day-grid, #c3 .day-grid').html(calender(previousMonth.month, previousMonth.year));

    });

    $('#c3 .post_navigator,#c2 .post_navigator').on("click", function () {

        nextMonth = handleNextButtonClick();
        // console.log(nextMonth.month, nextMonth.year);
        $('#c2 .header h1,#c3 .header h1').html(months[nextMonth.month - 1] + ' ' + nextMonth.year);

        $('#c2 .day-grid, #c3 .day-grid').html(calender(nextMonth.month, nextMonth.year));

    });
    // Code For Days & Weeks Section ------ End 


    $(document).on("click ", ".rowWrapper li", function () {

        sectionType = $(this).parent().parent().parent().parent();

        if (sectionType.attr('id') == 'c2') { //c2 means days and c3 means weeks
            var mm = months.indexOf(($('#c2 .header h1').html()).substring(0, 3)) + 1;
            if (mm < 10) {
                mm = "0" + mm;
            }
            var dd = parseInt($(this).html());
            if (dd < 10) {
                dd = "0" + dd;
            }
            var from_date = ($('#c2 .header h1').html()).substr(-4) + "-" + mm + "-" + dd;
            var last_date = ($('#c2 .header h1').html()).substr(-4) + "-" + mm + "-" + dd;
            // console.log('------------------------------------');
            // console.log(($('#c2 .header h1').html()).substr(-4) + "-" + mm + "-" + dd);
            // console.log(($('#c2 .header h1').html()).substr(-4) + "-" + mm + "-" + dd);
            // console.log('------------------------------------');
            window.location = new_path + from_date + "/" + last_date;
        }

        if (sectionType.attr('id') == 'c3') { //c2 means days and c3 means weeks
            startDate_Of_Week = $(this).parent().children('li:first').html() == '' ? 1 : $(this).parent().children('li:first').html();
            lastDate_Of_Week = $(this).parent().children('li:last').html();

            var mm = months.indexOf(($('#c3 .header h1').html()).substring(0, 3)) + 1;
            if (mm < 10) {
                mm = "0" + mm;
            }
            if (startDate_Of_Week < 10) {
                startDate_Of_Week = "0" + startDate_Of_Week;
            }
            if (lastDate_Of_Week < 10) {
                lastDate_Of_Week = "0" + lastDate_Of_Week;
            }
            var from_date = ($('#c3 .header h1').html()).substr(-4) + "-" + mm + "-" + startDate_Of_Week;
            var last_date = ($('#c3 .header h1').html()).substr(-4) + "-" + mm + "-" + lastDate_Of_Week;
            // console.log('------------------------------------');
            // console.log();
            // console.log();
            // console.log('------------------------------------');

            window.location = new_path + from_date + "/" + last_date;
        }


    });

    $('.month-span').on("click", function () {
        console.log('-------');
        var mm = $(this).attr('data-value');
        if (mm < 10) {
            mm = "0" + mm;
        }
        var from_date = $(".year_head").text() + "-" + mm + "-01";
        var last_date = $(".year_head").text() + "-" + mm + "-" + (getMonthData(parseInt(mm)).last_full_Date).substr(-2);
        // console.log();
        // console.log();
        // console.log('-------');
        window.location = new_path + from_date + "/" + last_date;
    });

    $('.year_pre_navigator').on("click", function () {

        y = $('#c4 .year_head').text();
        y = parseInt(y) - 1;
        $('#c4 .year_head').text(y);

    });

    $('.year_post_navigator').on("click", function () {

        y = $('#c4 .year_head').text();
        y = parseInt(y) + 1;
        $('#c4 .year_head').text(y);

    });

    $("#start_date, #end_date").on('change', function () {
        // console.log('-------');
        // console.log();
        // console.log();
        // console.log('-------');
        var from_date = $("#start_date").val();
        var last_date = $("#end_date").val();
        if (last_date != "" && from_date != "") {
            window.location = new_path + from_date + "/" + last_date;
        }

    });



    function calender(month, year = currentYearNumber) {
        firstDayName = getMonthData(month, year).firstDayName;
        totalDays = getMonthData(month, year).totalDays;

        offset_of_week = weekday.indexOf(firstDayName);

        calender_dates = '';

        for (let i = 1; i <= parseInt(totalDays) + offset_of_week; i++) {

            if (i == 1) {
                calender_dates += `<span class="rowWrapper">`;
            }

            if (i <= offset_of_week) {
                calender_dates += `<li class="month-prev visibility-hidden"></li>`;
            } else {
                calender_dates += `<li>` + (i - offset_of_week) + `</li>`;
            }

            if (isMultipleOf7(i)) {
                calender_dates += '</span>';
                if (i != parseInt(totalDays) + offset_of_week) {
                    calender_dates += '<span class="rowWrapper">';
                }
            }

        }
        return calender_dates;
    }

    // Function to get complete data for a specific month and year
    function getMonthData(month = currentMonthNumber, year = currentYearNumber) {
        // Check if the month number is valid
        if (month >= 1 && month <= 12) {
            // Create a new date object with the specified month and year
            const date = new Date(year, month - 1);

            // Get the month number
            const monthNumber = month;

            // Get the month name
            const monthName = date.toLocaleString('en-US', {
                month: 'short'
            });

            // Get the last day of the month
            const lastDay = new Date(year, month, 0).getDate();

            // Get the first day name
            const firstDayName = new Date(year, month - 1, 1).toLocaleString('en-US', {
                weekday: 'long'
            });

            // Get the last day name
            const lastDayName = new Date(year, month - 1, lastDay).toLocaleString('en-US', {
                weekday: 'long'
            });

            // Get the month duration (total number of days in the month)
            const monthDuration = lastDay;

            // Create an array to hold the data
            if (month < 10) {
                month = "0" + month;
            }
            const monthData = {
                monthNumber: monthNumber,
                monthName: monthName,
                totalDays: monthDuration,
                firstDayName: firstDayName,
                lastDayName: lastDayName,
                monthDuration: monthName + ' ' + '01' + '-' + monthName + ' ' + lastDay,
                // first_full_Date: '01' + ' ' + monthName + ' ' + year,
                first_full_Date: year + "-" + month + "-01",
                // last_full_Date: lastDay + ' ' + monthName + ' ' + year,
                last_full_Date: year + "-" + month + "-" + lastDay,
            };

            // Return the monthData object
            return monthData;
        } else {
            // If the month number is invalid, return an error message
            return "Invalid month number. Please enter a number between 1 and 12.";
        }
    }


    function handleBackButtonClick() {
        // Subtract 1 from the current month
        currentMonthNumber--;


        // If the current month becomes 0, set it to December (12) and decrement the current year
        if (currentMonthNumber === 0) {
            currentMonthNumber = 12;
            currentYearNumber--;
        }

        // Log the previous month and year
        // console.log("Previous Month: " + currentMonthNumber);
        // console.log("Previous Year: " + currentYearNumber);

        return {
            month: currentMonthNumber,
            year: currentYearNumber,
        }

        // You can perform any other actions with the previous month and year here
    }


    function handleNextButtonClick() {
        // Add 1 to the current month
        currentMonthNumber++;

        // If the current month becomes 13, set it to January (1) and increment the current year
        if (currentMonthNumber === 13) {
            currentMonthNumber = 1;
            currentYearNumber++;
        }

        // Log the next month and year
        // console.log("Next Month: " + currentMonthNumber);
        // console.log("Next Year: " + currentYearNumber);

        return {
            month: currentMonthNumber,
            year: currentYearNumber,
        }

        // You can perform any other actions with the next month and year here
    }


    // Function for geting Date in formate with giving previous days
    function dateInFormate(backDate, withYear) {
        var d = new Date();
        d.setDate(d.getDate() - backDate);
        if (withYear) {
            return formatDate(d, true);
        }
        return formatDate(d);
    }


    // Function for date format
    function formatDate(date, withYear) {
        var dd = date.getDate();
        var mmm = months[date.getMonth()];
        var mm = date.getMonth() + 1;
        var yyyy = date.getFullYear();

        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }

        if (withYear) {
            // date = mm + ' ' + dd + ' ' + yyyy;
            date = yyyy + '-' + mm + "-" + dd;
        } else {
            date = mmm + ' ' + dd;
        }

        return date
    }


    function isMultipleOf7(number) {
        return number % 7 === 0;
    }
});