
function opencalendar(params, form, field, type) {
	window.open("./calendar.php?" + params, "calendar", "width=400,height=200,status=yes");
	dateField = eval("document." + form + "." + field);
	dateType = type;
}

function initCalendar() {
	if (!year && !month && !day) {
		/* Called for first time */
		if (window.opener.dateField.value) {
			value = window.opener.dateField.value;
			if (window.opener.dateType == 'datetime' || window.opener.dateType == 'date') {
				if (window.opener.dateType == 'datetime') {
					parts   = value.split(' ');
					value   = parts[0];
					if (parts[1]) {
						time = parts[1].split(':');
						hour = parseInt(time[0],10);
						minute = parseInt(time[1],10);
						second = parseInt(time[2],10);
					}
				}
				date = value.split("-");
				day = parseInt(date[2],10);
				month = parseInt(date[1],10) - 1;
				year = parseInt(date[0],10);
			} else {
				year = parseInt(value.substr(0,4),10);
				month = parseInt(value.substr(4,2),10) - 1;
				day = parseInt(value.substr(6,2),10);
				hour = parseInt(value.substr(8,2),10);
				minute = parseInt(value.substr(10,2),10);
				second = parseInt(value.substr(12,2),10);
			}
		}
		if (isNaN(year) || isNaN(month) || isNaN(day) || day == 0) {
			dt = new Date();
			year = dt.getFullYear();
			month = dt.getMonth();
			day = dt.getDate();
		}
		if (isNaN(hour) || isNaN(minute) || isNaN(second)) {
			dt = new Date();
			hour = dt.getHours();
			minute = dt.getMinutes();
			second = dt.getSeconds();
		}
		} else {
		/* Moving in calendar */
		if (month > 11) {
			month = 0;
			year++;
		}
		if (month < 0) {
			month = 11;
			year--;
		}
	}

	if (document.getElementById) {
		cnt = document.getElementById("calendar_data");
	} else if (document.all) {
		cnt = document.all["calendar_data"];
	}

	cnt.innerHTML = "";

	str = ""

	//heading table
	str += '<table class="calendar"><tr><th width="50%">';
	str += '<a href="javascript:month--; initCalendar();">&laquo;</a> ';
	str += month_names[month];
	str += ' <a href="javascript:month++; initCalendar();">&raquo;</a>';
	str += '</th><th width="50%">';
	str += '<a href="javascript:year--; initCalendar();">&laquo;</a> ';
	str += year;
	str += ' <a href="javascript:year++; initCalendar();">&raquo;</a>';
	str += '</th></tr></table>';

	str += '<table class="calendar"><tr>';
	for (i = 0; i < 7; i++) {
		str += "<th>" + day_names[i] + "</th>";
	}
	str += "</tr>";

	var firstDay = new Date(year, month, 1).getDay();
	var lastDay = new Date(year, month + 1, 0).getDate();

	str += "<tr>";

	dayInWeek = 0;
	for (i = 0; i < firstDay; i++) {
		str += "<td>&nbsp;</td>";
		dayInWeek++;
	}
	for (i = 1; i <= lastDay; i++) {
		if (dayInWeek == 7) {
			str += "</tr><tr>";
			dayInWeek = 0;
		}

		dispmonth = 1 + month;

		if (window.opener.dateType == 'datetime' || window.opener.dateType == 'date') {
			actVal = formatNum4(year) + "-" + formatNum2(dispmonth, 'month') + "-" + formatNum2(i, 'day');
		} else {
			actVal = "" + formatNum4(year) + formatNum2(dispmonth, 'month') + formatNum2(i, 'day');
		}
		if (i == day) {
			style = ' class="selected"';
		} else {
			style = '';
		}
		str += "<td" + style + "><a href=\"javascript:returnDate('" + actVal + "');\">" + i + "</a></td>"
		dayInWeek++;
	}
    for (i = dayInWeek; i < 7; i++) {
        str += "<td>&nbsp;</td>";
    }

    str += "</tr></table>";

    cnt.innerHTML = str;

    // Should we handle time also?
    if (window.opener.dateType != 'date' && !clock_set) {

        if (document.getElementById) {
            cnt = document.getElementById("clock_data");
        } else if (document.all) {
            cnt = document.all["clock_data"];
        }

        str = '';
        str += '<form class="clock">';
        str += '<input id="hour"    type="text" size="2" maxlength="2" onblur="this.value=formatNum2(this.value, \'hour\')" value="' + formatNum2(hour, 'hour') + '" />:';
        str += '<input id="minute"  type="text" size="2" maxlength="2" onblur="this.value=formatNum2(this.value, \'minute\')" value="' + formatNum2(minute, 'minute') + '" />:';
        str += '<input id="second"  type="text" size="2" maxlength="2" onblur="this.value=formatNum2(this.value, \'second\')" value="' + formatNum2(second, 'second') + '" />';
        str += '</form>';

        cnt.innerHTML = str;
        clock_set = 1;
    }

}

/**
 * Returns date from calendar.
 *
 * @param   string     date text
 */
function returnDate(d) {
    txt = d;
    if (window.opener.dateType != 'date') {
        // need to get time
        h = parseInt(document.getElementById('hour').value,10);
        m = parseInt(document.getElementById('minute').value,10);
        s = parseInt(document.getElementById('second').value,10);
        if (window.opener.dateType == 'datetime') {
            txt += ' ' + formatNum2(h, 'hour') + ':' + formatNum2(m, 'minute') + ':' + formatNum2(s, 'second');
        } else {
            // timestamp
            txt += formatNum2(h, 'hour') + formatNum2(m, 'minute') + formatNum2(s, 'second');
        }
    }

    window.opener.dateField.value = txt;
    window.close();
}
