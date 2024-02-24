<button class="date-filter-button">Filter Date</button>

<div class="date-ranges d-none" style="position: absolute; right: 0px;">

    <input type="radio" id="tab1" name="tab" checked>
    <label for="tab1"><i class="fa fa-home"></i> Presets</label>

    <input type="radio" id="tab2" name="tab">
    <label for="tab2"><i class="fa fa-user"></i> Days</label>

    <input type="radio" id="tab3" name="tab">
    <label for="tab3"><i class="fa fa-pencil"></i> Weeks</label>

    <input type="radio" id="tab4" name="tab">
    <label for="tab4"><i class="fa fa-envelope"></i> Months</label>

    <input type="radio" id="tab5" name="tab">
    <label for="tab5"><i class="fa fa-envelope"></i> Range</label>


    <div class="content-container">
        <div class="content" id="c1">
        </div>

        <div class="content" id="c2">
            <div class="calendar">
                <div class="header">
                    <span class="angle"> <img class="pre_navigator"
                            src="{{ asset('web_assets/admin/images/date-filter/left_nav.svg') }}" alt="left_nav">
                    </span>
                    <h1>November 2017</h1> <span class="angle"> <img class="post_navigator"
                            src="{{ asset('web_assets/admin/images/date-filter/right_nav.svg') }}" alt="right_nav">
                    </span>
                </div>

                <ul class="weekdays">
                    <li>
                        <abbr title="S">Sunday</abbr>
                    </li>
                    <li>
                        <abbr title="M">Monday</abbr>
                    </li>
                    <li>
                        <abbr title="T">Tuesday</abbr>
                    </li>
                    <li>
                        <abbr title="W">Wednesday</abbr>
                    </li>
                    <li>
                        <abbr title="T">Thursday</abbr>
                    </li>
                    <li>
                        <abbr title="F">Friday</abbr>
                    </li>
                    <li>
                        <abbr title="S">Saturday</abbr>
                    </li>
                </ul>

                <ol class="day-grid">
                    <li>29</li>
                </ol>
            </div>
        </div>

        <div class="content" id="c3">
            <div class="calendar">
                <div class="header">
                    <span class="angle"> <img class="pre_navigator"
                            src="{{ asset('web_assets/admin/images/date-filter/left_nav.svg') }}" alt="left_nav">
                    </span>
                    <h1>November 2017</h1> <span class="angle"> <img class="post_navigator"
                            src="{{ asset('web_assets/admin/images/date-filter/right_nav.svg') }}" alt="right_nav">
                    </span>
                </div>

                <ul class="weekdays">
                    <li>
                        <abbr title="S">Sunday</abbr>
                    </li>
                    <li>
                        <abbr title="M">Monday</abbr>
                    </li>
                    <li>
                        <abbr title="T">Tuesday</abbr>
                    </li>
                    <li>
                        <abbr title="W">Wednesday</abbr>
                    </li>
                    <li>
                        <abbr title="T">Thursday</abbr>
                    </li>
                    <li>
                        <abbr title="F">Friday</abbr>
                    </li>
                    <li>
                        <abbr title="S">Saturday</abbr>
                    </li>
                </ul>

                <ol class="day-grid">
                    <li>29</li>
                </ol>
            </div>
        </div>

        <div class="content" id="c4">
            <div class="months-grid">
                <span class="angle"> <img class="year_pre_navigator"
                        src="{{ asset('web_assets/admin/images/date-filter/left_nav.svg') }}" alt="left_nav"> </span>
                <h1 class="year_head">2023</h1>
                <span class="angle"> <img class="year_post_navigator"
                        src="{{ asset('web_assets/admin/images/date-filter/right_nav.svg') }}" alt="right_nav">
                </span>

                <span class="month-span" data-value="1">
                    Jan
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>
                <span class="month-span" data-value="2">
                    Feb
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>
                <span class="month-span" data-value="3">
                    Mar
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>


                <span class="month-span" data-value="4">
                    Apr
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>
                <span class="month-span" data-value="5">
                    May
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>
                <span class="month-span" data-value="6">
                    Jun
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>

                <span class="month-span" data-value="7">
                    Jul
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>
                <span class="month-span" data-value="8">
                    Aug
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>
                <span class="month-span" data-value="9">
                    Sep
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>

                <span class="month-span" data-value="10">
                    Oct
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>
                <span class="month-span" data-value="11">
                    Nov
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>
                <span class="month-span" data-value="12">
                    Dec
                    <img class="calender-icon" src="{{ asset('web_assets/admin/images/date-filter/calendar.png') }}"
                        alt="calendar">
                </span>

            </div>
        </div>

        <div class="content" id="c5">

            <div class="range-inputs">
                <div>
                    <p><b>Start Date</b></p>
                    <input type="date" name="start_date" id="start_date">
                </div>
                <div>
                    <p><b>End Date</b></p>
                    <input type="date" name="end_date" id="end_date">
                </div>
            </div>

        </div>
    </div>

</div>
